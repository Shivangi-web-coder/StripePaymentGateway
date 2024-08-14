
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
          <h2 style="text-align:center">Upgrade and Downgrade Plan</h2>

          <div class="columns">
            <ul class="price">
              <li class="header">Basic</li>
              <li class="grey">$ 99.00 / month</li>
              <li>10GB Storage</li>
              <li>10 Emails</li>
              <li>10 Domains</li>
              <li>1GB Bandwidth</li>
              @if($activePlanId==$planDetails[0]->id)
                  <li class="grey"><a href="#" class="button">Active Plan</a></li>
              @else
              <li class="grey"><a href="javascript:void(0)"  data-plan_id="{{$planDetails[0]->id}}" class="button update_plan">Update</a></li>
              @endif
            </ul>
          </div>
          <div class="columns">
            <ul class="price">
              <li class="header" style="background-color:#04AA6D">Pro</li>
              <li class="grey">$ 399.00 / year</li>
              <li>25GB Storage</li>
              <li>25 Emails</li>
              <li>25 Domains</li>
              <li>2GB Bandwidth</li>

              @if($activePlanId==$planDetails[1]->id)
                  <li class="grey"><a href="#" class="button">Active Plan</a></li>
              @else
                  <li class="grey"><a href="javascript:void(0)" data-plan_id="{{$planDetails[1]->id}}" data-update_plan_url="{{route('update-plan')}}" class="button update_plan">Update</a></li>
              @endif
            </ul>
          </div>
        </div>
      </div>
    </div>
</body>
@include("payment.footer")
<script>
 $(document).on("click",".update_plan",function(){
  let text ="Are you change plan.";
  if (confirm(text) == true) {
      let plan_id= $(this).data("plan_id");
      let token = $('meta[name="csrf-token"]').attr('content');
      let URL = $(this).data("update_plan_url");
      $.ajax({
        url:URL,
        method:"POST",
        data:{plan_id:plan_id,_token:token},
        beforeSend:function(){
        },
        success:function(res){
          if(res.status){
            alert(res.message);
            window.location.href="{{route('myPlan')}}"
          }else{
            alert(res.message);
          }
        },
        error:function(res){
          console.log(res)
        }
      })
  } 

 })
</script>