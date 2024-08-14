<!DOCTYPE html>
<html lang="en">

<head>
    @include("payment.header")
</head>
<body> 
<div class="row">
    <div class="col-md-12">
        @include('payment.navbar')
    </div>
    <div class="col-md-12">
      <div class="container">
      <center><h2><b>My Plan Details</b></h2></center> 
      <div class="intake_card_wrapper">
          <div class="intake_card">
              <div class="row payment-info-wrap">
                  <div class="col-md-12">
                      <ul class="list-group mb-3">
                          <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                          <h4 class="my-0">Acive Plan</h4>
                          <small class="text-muted"><b>{{$user_plan}}</b></small>
                        </div>
                                    </li>
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                          <div>
                            <h4 class="my-0">Cancel Subscription:   <a href="javascript:void(0)" data-plan_id="1"  class="btn btn-danger cancel_plan">Cancel</a></h4>
                          </div>
                          <input type="hidden" id="sub_uuid" value="{{$sub_uuid}}">
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                          <div>
                            <h4 class="my-0">Change Plan:   <a href="{{route('update-plan')}}"  class="btn btn-primary">Update</a></h4>
                          </div>
                        </li>
                      </ul>
                  </div>
              </div>
          </div>
      </div>
    </div>
  </div>
</div>
</body>
@include("payment.footer")