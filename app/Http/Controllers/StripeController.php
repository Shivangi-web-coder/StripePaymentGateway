<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan;
use App\Models\StripeUser;
use App\Helpers\StripeHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\UserSubscription;
use App\Http\Requests\StripeSubscriptionRequest;
use Carbon\Carbon;
use Stripe;
use Exception;

class StripeController extends Controller
{
    public function plan()
    {
        try {
            if(Auth::user()->id==''){
                return redirect()->route('login');
            }else{
            $planDetails = Plan::select('id','plan_name','uuid','plan_price')->get();
            return view('payment.plan',compact('planDetails'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with([
                'alert-type' => 'error',
                'message'    => $e->getMessage()
            ]);
        }
    }
    
     public function checkOut($planId){
        try{
            $planDetails = Plan::select('uuid','plan_name','plan_price')->where(['uuid'=>$planId])->active()->first();
            if($planDetails){
                return view ('payment.payout',['plan'=>$planDetails]);
            }else{
                return redirect()->route('plan')->with([
                    'alert-type' => 'error',
                    'message'    => 'This plan not valid'
                ]);
            }
        }catch (Exception $e){
            return redirect()->back()->with([
                'alert-type' => 'error',
                'message'    => $e->getMessage()
            ]);
        }
    }

    public function pay(StripeSubscriptionRequest $request){
        try{
             $planId       = $request->plan_id;
             $stripeToken  = $request->stripe_token;
             $user= auth()->user();
            DB::beginTransaction();
            $planDetails  = Plan::select('*')->where('uuid',$planId)->first();
            if($planDetails){
                $couponCode =$request->coupon_code;
                if($couponCode!=''){
                    $couponResult = StripeHelper::retrieveCoupon($couponCode);
                    if ($couponResult['status']== false || $couponResult['status']=='') {
                        return response()->json(['status'=>false,'message' => 'Invalid coupon code.']);
                    }
                }
                $stripePlanId = $planDetails->stripe_plan_id;
                $request->request->add(['plan_id' => $planDetails->id]);
                // check stripe customer in db;
                if($user->stripeUser){
                    $customerId =$user->stripeUser->stripe_customer_id;
                    $request->request->add(['stripe_user_id' => $user->stripeUser->id]);
                }else{
                    $clientObject = array(
                        'name'   => $user->name,
                        'email'  => $user->email,
                        "source" => $stripeToken,
                    );
                    // Create customer in stripe 
                     log::info('StripeHelper ');
                    $customerRes=StripeHelper::createCustomer($clientObject,$stripeToken);
                    if($customerRes['status'] == false) {   
                        return response()->json(['status'=>false,'message' =>$customerRes['error']]);
                    }
                    $customerId =$customerRes['data']->id;
                    $stripeUser=StripeUser::create([
                        'user_id'=>$user->id,
                        'stripe_customer_id'=>$customerId
                    ]);
                    $request->request->add(['stripe_user_id' => $stripeUser->id]);
                }

                // Free Trial Add 7 days to the current date
                $trialEndTime = strtotime(Carbon::now()->addDays(3)->setTime(12, 0, 0));

                // Create subscription in stripe
                 $subscriptionResult = StripeHelper::createSubscription($customerId,$stripePlanId,$couponCode,$trialEndTime);
                if($subscriptionResult['status']==false){
                    return response()->json(['status'=>false,'message' =>$subscriptionResult['error']]);
                }
                // Insert subscription details in db
                $this->userSubscription($subscriptionResult,$request);
                DB::commit();
                return response()->json(['status'=>true,'message'=>'Your subscription created successfully']);
            }else{
                return response()->json(['status'=>false,'message'=>'Invalid Plan Id']);
            }
        }catch (Exception $e) {
            DB::rollback();
            return response()->json(['status'=>false,'message' => $e->getMessage()]);
        }
    }

    public function userSubscription($data,$request=NULL){
        $subscriptionResult =$data;
        $startDate = date('Y-m-d H:i:s',$subscriptionResult['data']->current_period_start);
        $endDate   = date('Y-m-d H:i:s',$subscriptionResult['data']->current_period_end);
        $createSubscrArray = array(
            'user_name'       => $request->user_name,
            'email'           => $request->email,
            'phone_number'    => $request->phone_number,
            'plan_id'         => $request->plan_id,
            'subscription_id' => $subscriptionResult['data']->id,
            'stripe_user_id'  => $request->stripe_user_id,
            'start_date'      => $startDate,
            'end_date'        => $endDate,
            'plan_amount'     => $subscriptionResult['data']->plan->amount/100,
            'current_status'  =>UserSubscription::IS_ACTIVE_SUBSCRIPTION,
        );
        UserSubscription::create($createSubscrArray);
    }

    public function myPlan(){
        try {
             $user =auth()->user();
            $userPlan=$user->stripeUser->activeSubscription->stripePlan->plan_name;
            $SubUuid=$user->stripeUser->activeSubscription->uuid;
            $response['user_plan']= $userPlan;
            $response['sub_uuid']= $SubUuid;
            log::info($response);
            return view('payment.my-plan',$response);
        } catch (Exception $e) {

            return redirect()->back()->with([
                'alert-type' => 'error',
                'message'    => $e->getMessage()
            ]);
        }
    }

    public function cancelSubscription(Request $request){

        $SubUuid =$request->sub_uuid;
        $subRow=UserSubscription::where('uuid',$SubUuid)->first();
        $subscription = StripeHelper::cancelSubscription($subRow->subscription_id);
        if($subscription['status']==false){
            $exception = array('status'=>false,'message' => $subscription['error']);
            return response()->json($exception);
        }
        UserSubscription::where('uuid',$SubUuid)->update(['current_status'=>0]);
        return response()->json(['status'=>true,'message'=>'Your subscription cancel successfully']);
    }

    public function checkCoupon(Request $request)
    {
        try {
            log::info($request);
            $couponCode =$request->coupon_code;
            $couponResult = StripeHelper::retrieveCoupon($couponCode);
             log::info($couponResult);
            if ($couponResult['status']== false || $couponResult['status']=='') {
                $response= response()->json(['status'=>false,'message' => 'Invalid coupon code.']);
            } else {
                if ($couponResult['data']->amount_off!=null) {
                    $amount =$couponResult['data']->amount_off/100;
                } else {
                    $amount ='';
                }
                $response= response()->json(['status'=>true, 'message' => 'This coupon is applied.','data' =>$couponResult['data'], 'amount' =>$amount]);
            }
            return $response;

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message'    => $e->getMessage()
            ]);
        }
    }

    public function updatePlan(Request $request){
        try {
            $user= auth()->user();
            if ($request->isMethod('GET')) {
                $planDetails = Plan::select('id','plan_name','uuid')->active()->get();
                $user = auth()->user();
                $activePlanId=$user->stripeUser->activeSubscription->plan_id;
                return view('payment.update-plan',['planDetails'=>$planDetails,'activePlanId'=>$activePlanId]);
            }

            if ($request->isMethod('POST')) {
                DB::beginTransaction();
                $subcriptionId =$user->stripeUser->activeSubscription->subscription_id;
                $subscription = StripeHelper::retrieveSubscription($subcriptionId);
                if($subscription['status']==false){
                    $exception = array('status'=>false,'message' => $subscription['error']);
                    return response()->json($exception);
                }
                $planId= $request->plan_id;
                $planRes = Plan::select('stripe_plan_id')->where('id',$planId)->first();
                $data = [
                    'items' => [
                        [
                            'id'    => $subscription['data']->items->data[0]->id,
                            'price' => $planRes->stripe_plan_id,
                        ],
                    ],
                ];

                // update subscription
                $updateSubscriptionResult = StripeHelper::updateSubscription($subcriptionId,$data);
                if($updateSubscriptionResult['status'] == false) {   
                    return response()->json(array('status'=>false,'message' =>$updateSubscriptionResult['error']));
                }

                $startDate = date('Y-m-d H:i:s',$updateSubscriptionResult['data']->current_period_start);
                $endDate   = date('Y-m-d H:i:s',$updateSubscriptionResult['data']->current_period_end);
                $updateSubscrArray = array(
                    'plan_id'         => $planId,
                    'start_date'      => $startDate,
                    'end_date'        => $endDate,
                    'plan_amount'     => $updateSubscriptionResult['data']->plan->amount/100
                );
                
                // update user subscription details in db
                UserSubscription::where(['id'=>$user->stripeUser->activeSubscription->id])->update($updateSubscrArray);
                DB::commit();
                return response()->json(['status'=>true,'message'=>'Your subscription update successfully']);
            }
            
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage(); die;
            return redirect()->back()->with([
                'status' => true,
                'message'    => $e->getMessage()
            ]);
        }
    }

    public function logout(){
        Auth::logout();
        return redirect('/login');
    }
}
