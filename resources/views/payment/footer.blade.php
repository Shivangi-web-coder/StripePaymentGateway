<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/3.0.0/jquery.payment.min.js" ></script>
<script src="https://js.stripe.com/v2/"></script>
<script>

$(document).ready(function(){

    $(document).on("click",'.payment_type',function(){
        var url = $(this).data("card_pyament");
        window.location=url
    })
    // stripe code start
    $('[data-numeric]').payment('restrictNumeric');
    $('.cc-number').payment('formatCardNumber');
    $('.cc-exp').payment('formatCardExpiry');
    $('.cc-cvc').payment('formatCardCVC');

    })
    $.validator.addMethod('cardNumber', function(value, element, param){
    return $.payment.validateCardNumber(value);
    }, 'Invalid Card Number');

    $.validator.addMethod('cardExpiry', function(value, element, param){
    return $.payment.validateCardExpiry(param);
    }, 'Invalid Expiry Date');

    $.validator.addMethod('cardCVC', function(value, element, param){
    return $.payment.validateCardCVC(value, param);
    }, 'Invalid CVC Number');

    // error invalid-feedback jnj_form_text_cap
    var stripeCardPaymentForm = $('#payment-form');
    stripeCardPaymentForm.validate({
        debug: false,
        errorClass: "error invalid-feedback jnj_form_text_cap",
        errorElement: "span",
        rules: {
        amount :{
            required:true
        },
        card_number: {
            required: true,
            cardNumber: true
        },
        card_expiry: {
            required: true,
            cardExpiry: function(element){
            return $(element).payment('cardExpiryVal');
            }
        },
        card_cvv: {
            required: true,
            cardCVC: function(element){
                return $.payment.cardType($(element).parents('.stripe-card').find('.card-number').val());
            } 
        },
        address: {
            required:true
        },
        state: {
            required:true
        },
        city: {
            required:true
        },
        zip_code: {
            required:true
        },
        order_type : {
            required :true
        }
    },
    messages: {
        cardnumber: {
            required: "Please enter card number.",
            cardNumber: "Please enter valid card number."
        },
        cardexpiry: {
            required: "Please enter card expiry details.",
            cardExpiry: "Please enter valid dxpiry details."
        },
        cardcvc: {
        required: "Please Enter card cvc number.",
            cardCVC: "Please enter valid cvc number."
        }
    },
    submitHandler: function (form) {
        var URL = $(form).attr('action');
        var formData = new FormData($(form)[0]);
        let token = $('meta[name="csrf-token"]').attr('content');
        formData.append("_token",token);
        $.ajax({
            url: URL,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function(){
                $('#pay_button').css('pointer-events','none');
                $('body').css('pointer-events','none');
                $('#pay_button').text("Loding.....");
            },
            success: function (res) {
                if(res.status){
                    alert(res.message)
                    window.location.href="{{route('myPlan')}}"
                }else{
                    alert(res.message)
                }
            },
            error: function (error) {
                console.log('errr',error)
            }
        });
    }
    });

    $(document).on("change",".check_token",function(){
    let card_number = $("#cart_number").val();
    let cardDate = $("#card_expiry").val()
    let cvvNumber = $("#card_cvv").val();
    if(card_number!='' && cardDate!='' && cvvNumber!=''){
        GenerateStripeToken();
    }
    })
    function GenerateStripeToken(){
    var cardDate = $("#card_expiry").val()
    var cvvNumber = $("#card_cvv").val();
    const dateMonthArry = cardDate.split(" / ");

    Stripe.setPublishableKey($("#stripe_pk").val());
    var card_number = $("#cart_number").val();
    var exp_month   = dateMonthArry[0];
    var exp_year    = dateMonthArry[1];
    var cvv_number  = cvvNumber;
    
    Stripe.createToken({
        number: card_number?card_number:'',
        cvc: cvv_number?cvv_number:'',
        exp_month: exp_month?exp_month:'',
        exp_year: exp_year?exp_year:''
    }, stripeHandleResponse);
    }

    /**
    * stripeHandleResponse
    *
    * @return void
    */
    function stripeHandleResponse(status, response) {
    if (response.error) {
        console.log(response.error.message);
    } else {
        var token = response['id'];
        $("#stripe_token").val(token);
        console.log(token);
    }
    }

    $(document).on("click",".cancel_plan",function(){
    let text ="Are you want to cancel plan.";
    if (confirm(text) == true) {
        let token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url:"{{route('cancel-subscription')}}",
            method:"POST",
            data:{_token:token,sub_uuid:$('#sub_uuid').val()},
            beforeSend:function(){
            },
            success:function(res){
            if(res.status){
                alert(res.message);
                window.location.href="{{route('plan')}}"
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
</body>
</html>