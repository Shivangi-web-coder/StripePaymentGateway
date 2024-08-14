
<!DOCTYPE html>
<html lang="en">

<head>
    @include("payment.header")
</head>
<style>
    .navbar {
    background-color: #046eaa !important;
    border-radius: 0px;
}
.navbar-nav-right {
    float: right !important;
    margin-top: 10px !important;
}
.logout {
    color: #ffffff !important;
    margin-right: 40px !important;
    font-size: 16px;
    font-weight: 800;
}
</style>
<body>
<div class="row">
    <div class="col-md-12">
        <nav class="navbar p-0 fixed-top d-flex flex-row">
    <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
        <div class="navbar-nav navbar-nav-right">
            <a href="{{route('logout')}}" class="logout"><strong>LOGOUT</strong></a>
        </div>
    </div>
</nav>
    </div>
    <div class="col-md-12">
        <div class="container">
            <center><h2><b>STRIPE PAYMENT FORM</b></h2></center> 
            <form class="payment-card-wrapper" action="{{route('pay')}}" id="payment-form">
                @csrf
                <input type="hidden" name="stripe_token" id="stripe_token" value="">
                <input type="hidden" id="stripe_pk" value="{{env('STRIPE_KEY')}}">
                <input type="hidden" name="plan_id" value="{{$plan->uuid}}">
                <div class="intake_card_wrapper">
                    <div class="intake_card">
                        <div class="row payment-info-wrap">
                        <div class="col-md-8">
                        <div class="col-md-12">
                                <div class="mb-4">
                                    <label class="form-label  label-modify">Card Number <span class="text-danger">*</span></label>
                                    <input type="tel" size="20" data-stripe="number" id="cart_number" data-msg-required="Please enter card number." class="form-control cc-card-bg card-number cc-number check_token" autocomplete="cc-number" name="card_number" placeholder="**** **** **** 4242">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label  class="form-label  label-modify text-left">Expiration Date <span class="text-danger">*</span></label>
                                    <input type="tel" size="2" data-stripe="exp_month" id="card_expiry" data-msg-required="Please enter card expiry date." class="form-control card-expiry cc-exp check_token" autocomplete="cc-exp" name="card_expiry" placeholder="MM/YYYY">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label  label-modify text-left">CVV <span class="text-danger">*</span></label>
                                    <input type="tel" size="4" data-stripe="cvc" data-msg-required="Please enter cvv number." class="form-control card-cvc cc-cvc check_token" autocomplete="off" name="card_cvv" id="card_cvv" placeholder="ex.548">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label  class="form-label  label-modify text-left">Coupon code</label><span id="clear_coupon"></span>
                                <input type="text" name="coupon_code" class="form-control" id="check_coupon" value=""  data-check-coupon="{{ route('check-coupon') }}" placeholder="Coupon">
                                <span id="check_vaild_coupon"></span>
                            </div>
                            <div class="col-md-6">
                                <label  class="form-label  label-modify text-left"></label>
                            <button class=" form-control btn-primary" id="couponApply">Apply Coupon</button>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <label  class="form-label  label-modify text-left">Address <span class="text-danger">*</span></label>
                                    <input type="text" name="address"  class="form-control" data-rule-required="true" data-msg-required="Please enter address." placeholder="Address">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="mb-4">
                                    <label class="form-label  label-modify text-left">City <span class="text-danger">*</span></label>
                                    <input type="text" name="city" id="city" class="form-control " data-rule-required="true" data-msg-required="Please enter city." placeholder="City">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="mb-4">
                                    <label  class="form-label  label-modify text-left">State <span class="text-danger">*</span></label>
                                    <input type="text" name="state" id="state" class="form-control " data-rule-required="true" data-msg-required="Please enter state." placeholder="State">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="mb-4">
                                    <label class="form-label  label-modify text-left">Zipcode <span class="text-danger">*</span></label>
                                    <input type="text" name="zip_code" id="zipcode" class="form-control" data-rule-required="true" data-msg-required="Please enter zipcode." placeholder="Zipcode">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="col-md-12"><br>
                                    <ul class="list-group mb-3">
                                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                                        <div>
                                            <h6 class="my-0">Product name</h6>
                                            <small class="text-muted">{{$plan->plan_name}}</small>
                                        </div>
                                        <span class="text-muted">${{$plan->plan_price}}</span>
                                        </li>
                                        <!-- <li class="list-group-item d-flex justify-content-between lh-condensed">
                                        <div>
                                            <h6 class="my-0">Second product</h6>
                                            <small class="text-muted">Brief description</small>
                                        </div>
                                        <span class="text-muted">$8</span>
                                        </li> -->
                                        <li class="list-group-item d-flex justify-content-between bg-light" id="showcoupondiv" style="display: none;" >
                                        <div class="text-success">
                                            <h6 class="my-0">Promo code</h6>
                                            <small id="c_code"></small>
                                        </div>
                                        <span class="text-success" id="c_code_price"></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                        <span>Total (USD)</span>
                                        <strong id="total_price">${{$plan->plan_price}}</strong>
                                        </li>
                                    </ul>
                            </div>
                        </div>
                            
                        </div>
                    </div>
                </div><br>
                <div class="card-btn-wrap">
                    <button type="submit" class="btn btn-primary" id="pay_button" value="SUBMIT">
                        <span class="spinner-border submit-btn-loader" role="status" style="display:none"></span>
                        <span class="submit-btn-text">Pay</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
@include("payment.footer")
<script>
     $(document).on("click","#couponApply",function(e){
        e.preventDefault();
        let coupon_code= $('#check_coupon').val();
        if(coupon_code==""){
            alert("Please enter coupon code.")
            return
        }
        let URL = $('#check_coupon').data('check-coupon'); 
        let token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: URL,
            type: 'POST',
            data: {'coupon_code':coupon_code,_token: token},
            beforeSend: function() {
                $('#couponApply').text('Loading....');
            },
            success: function(res) {
                var planPrice="{{$plan->plan_price}}";
                console.log(res);
                if(res.status){
                    $("#showcoupondiv").css("display","block");
                    if(res.data.percent_off!=null){
                        var totalAmount=(res.data.percent_off*planPrice)/100;
                        console.log(totalAmount);
                    }else{
                        var totalAmount=res.data.amount_off/100;
                    }
                    
                    var totalPayAmount =planPrice-totalAmount
                    $("#check_coupon").val(coupon_code);
                    $("#check_vaild_coupon").css("color","green")
                    $("#check_vaild_coupon").text("Coupon apply sucessfully.");
                    $("#pay_button").css("pointer-events","auto");
                    $("#c_code").text(coupon_code);
                    $("#c_code_price").text('-$'+totalAmount);
                    $("#total_price").text('-$'+ totalPayAmount);
                }else{
                    $("#showcoupondiv").css("display","none");
                    $("#check_vaild_coupon").css("color","red")
                    $("#check_vaild_coupon").text("Invail Coupon code.");
                }
                $("#clear_coupon").html('<a href="javascript:void(0)" id="reset_coupon">clear coupon</a>')
                $('#couponApply').text('Apply Coupon');
            },
            error: function(error) {
                toastr.error(result.message, 'Failure');
        }
    });

    $(document).on("click","#clear_coupon",function(){
        $("#check_vaild_coupon").text("")
        $("#clear_coupon").html("")
        $("#check_coupon").val('');
        $("#c_code").text(coupon_code);
        $("#c_code_price").text('');
        $("#c_code").text('');
        $("#total_price").text('$'+ "{{$plan->plan_price}}")
        $("#pay_button").css("pointer-events","auto");
        $("#showcoupondiv").css("display","none");
    })
    $(document).on("change","#check_coupon",function(){
        $("#pay_button").css("pointer-events","None")
    })
    })
</script>