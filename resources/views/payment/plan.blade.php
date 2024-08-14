
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
        <div class="columns">
          <ul class="price">
            <li class="basic-header">Basic plan</li>
            <li class="grey">$ {{$planDetails[0]->plan_price}} / month</li>
            <li>10GB Storage</li>
            <li>10 Emails</li>
            <li>10 Domains</li>
            <li>1GB Bandwidth</li>
            <li class="grey"><a href="{{route('check-out',$planDetails[0]->uuid)}}" class="button">Get start</a></li>
          </ul>
        </div>
        <div class="columns">
          <ul class="price">
            <li class="pro-header">Pro plan</li>
            <li class="grey">$ {{$planDetails[1]->plan_price}} / year</li>
            <li>25GB Storage</li>
            <li>25 Emails</li>
            <li>25 Domains</li>
            <li>2GB Bandwidth</li>
            <li class="grey"><a href="{{route('check-out',$planDetails[1]->uuid)}}" class="button">Get start</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</body>
@include("payment.footer")
