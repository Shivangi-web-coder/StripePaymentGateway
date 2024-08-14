<?php
 
namespace App\Helpers;

use Exception;
class StripeHelper {
 
   //Function for get existing stripe customer detail
   public static function getOneCustomer($customer_id){
       $stripe = new \Stripe\StripeClient(ENV('STRIPE_SECRET') );
       try {
           $allCustomers = $stripe->customers->retrieve($customer_id);
           return [
               'status' => true,
               'error' => null,
               'data' => $allCustomers
           ];
       } catch (\Stripe\Exception\InvalidRequestException $e) {
           return [
               'status' => false,
               'error' => $e->getMessage()
           ];
       }catch (\Stripe\Exception\AuthenticationException $e) {
           return [
               'status' => false,
               'error' => $e->getMessage()
           ];
       } catch(Exception $e) {
           return [
               'status' => false,
               'error' => $e->getMessage()
           ];
       }
   }
 
   //Function for create new stripe customer
   public static function createCustomer($data,$token){
       $stripe = new \Stripe\StripeClient( ENV('STRIPE_SECRET') );
       try {
           $created = $stripe->customers->create($data);
           return [
               'status' => true,
               'error' => null,
               'data' => $created
           ];
       } catch (\Stripe\Exception\InvalidRequestException $e) {
           return [
               'status' => false,
               'error' => $e->getMessage()
           ];
       }catch (\Stripe\Exception\AuthenticationException $e) {
           return [
               'status' => false,
               'error' => $e->getMessage()
           ];
       } catch(Exception $e) {
           return [
               'status' => false,
               'error' => $e->getMessage()
           ];
       }
   }
 
   //Function for create new subscription
   public static function createSubscription($customer_id,$pirce_plan_id,$couponCode=NULL,$trialEndTime=NULL){
       $stripe = new \Stripe\StripeClient( ENV('STRIPE_SECRET') );
       try {
           $created = $stripe->subscriptions->create([
               'customer' => $customer_id,
               'items' => [
                  ['price' => $pirce_plan_id],
               ],
               'coupon'=>$couponCode,
               'trial_end' => $trialEndTime,
             ]);
            
           return [
               'status' => true,
               'error' => null,
               'data' => $created
           ];
       } catch (\Stripe\Exception\InvalidRequestException $e) {
           return [
               'status' => false,
               'error' => $e->getMessage()
           ];
       }catch (\Stripe\Exception\AuthenticationException $e) {
           return [
               'status' => false,
               'error' => $e->getMessage()
           ];
       } catch(Exception $e) {
           return [
               'status' => false,
               'error' => $e->getMessage()
           ];
       }
   }
   //Function for cancel stripe subscription
   public static function cancelSubscription($sub_id){
       $stripe = new \Stripe\StripeClient( ENV('STRIPE_SECRET') );
       try {
           $cancelled = $stripe->subscriptions->cancel($sub_id);
           return [
               'status' => true,
               'error' => null,
               'data' => $cancelled
           ];
       } catch (\Stripe\Exception\InvalidRequestException $e) {
           return [
               'status' => false,
               'error' => $e->getMessage()
           ];
       }catch (\Stripe\Exception\AuthenticationException $e) {
           return [
               'status' => false,
               'error' => $e->getMessage()
           ];
       } catch(Exception $e) {
           return [
               'status' => false,
               'error' => $e->getMessage()
           ];
       }
   }

    public static function retrieveCoupon($coupon=null)
    {
        $stripe = new \Stripe\StripeClient(ENV('STRIPE_SECRET'));
        try {
            $result = $stripe->coupons->retrieve($coupon,
            ['expand' => ['applies_to']]
            );
            return [
                'status' => true,
                'error' => null,
                'data' => $result
            ];
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public static function retrieveSubscription($sub_id){
        $stripe = new \Stripe\StripeClient( ENV('STRIPE_SECRET') );
        try {
            $retrieve = $stripe->subscriptions->retrieve($sub_id);
            return [
                'status' => true,
                'error' => null,
                'data' => $retrieve
            ];
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        }catch (\Stripe\Exception\AuthenticationException $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        } catch(Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public static function updateSubscription($sub_id, $data = array()){
        $stripe = new \Stripe\StripeClient( ENV('STRIPE_SECRET') );
        try {
            $updated = $stripe->subscriptions->update($sub_id,$data);
            return [
                'status' => true,
                'error' => null,
                'data' => $updated
            ];
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        }catch (\Stripe\Exception\AuthenticationException $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        } catch(Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
