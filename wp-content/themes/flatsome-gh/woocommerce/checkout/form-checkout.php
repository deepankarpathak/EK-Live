<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

global $woocommerce, $flatsome_opt;

wc_print_notices();

?>

<script type="text/javascript">

    function validateemail(emailadd) {
        
	console.log(emailadd);
	var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);

	if ( ! pattern.test( emailadd) ) {
		return false;
	}
	return true;
    }

    jQuery(document).ready(function() {
        var show_billing_form;
<?php
if (is_user_logged_in()) {
    ?>
            show_billing_form = true;
<?php } else {
    ?>
            show_billing_form = false;
    <?php
}
?>
        
        
        if (show_billing_form == true) {
            $("#customer_details").show();
        }
        
        jQuery("#username").on('keyup change',function(event) {
		//alert(event.type);
            var emailadd = $("#username").val();
            
            $('#billing_email').val(emailadd);

            if (validateemail(emailadd)) {
                jQuery("#username").removeClass("email_error");
                if (show_billing_form == true) {
                    $("#customer_details").show();
                }
                else {
                    $('#loading_ajax').show();
                    $.ajax({
                        url: "<?php echo get_site_url() . "/wp-admin/admin-ajax.php" ?>",
                        type: 'POST',
                        data: 'action=validate_email&username=' + emailadd,
                        success: function(result) {
                       
                            $("#customer_details").show();
                            $('#loading_ajax').hide();
                            $("#username").addClass("username_back");
                            $(".customer-info").addClass("active");
                            $('select[name=billing_state] option:eq(0)').text("Select State...");
                            $("#edit_email").click(function(){
                                $("#username").removeClass("username_back");
                                $("#username").focus();
                            });
                        }
                    });
                }
            }
            else {
                $("#username").removeClass("username_back");
                jQuery("#username").addClass("email_error");
                $("#customer_details").hide();
            }

        });
    });
</script>

<div class="large-7 columns">

    <?php

    
    
    do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout
    if (!$checkout->enable_signup && !$checkout->enable_guest_checkout && !is_user_logged_in()) {
        echo apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce'));
        return;
    }

// filter hook for include new pages inside the payment method
    $get_checkout_url = apply_filters('woocommerce_get_checkout_url', $woocommerce->cart->get_checkout_url());
    ?>


    <!-- LOGIN -->
    <?php
    if (!defined('ABSPATH'))
        exit; // Exit if accessed directly
    if (is_user_logged_in() || !$checkout->enable_signup) {

    } else {
        $info_message = apply_filters('woocommerce_checkout_login_message', __('', 'woocommerce'));
        ?>

        <?php // if(in_array( 'nextend-facebook-connect/nextend-facebook-connect.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && $flatsome_opt['facebook_login_checkout'])  { ?> 
                    <!--<a href="<?php //echo wp_login_url();    ?>?loginFacebook=1&redirect=<?php // echo the_permalink();    ?>" class="button medium facebook-button " onclick="window.location = '<?php // echo wp_login_url();    ?>?loginFacebook=1&redirect='+window.location.href; return false;"><i class="icon-facebook"></i><?php //_e('Login / Register with <strong>Facebook</strong>','flatsome');    ?></a>
                            <p class="woocommerce-info"><?php // echo esc_html( $info_message );   ?> <a href="#" class="showlogin"><?php //_e( 'Click here to login', 'woocommerce' );   ?></a></p>
        <?php // } else {  ?>
                            <p class="woocommerce-info"><?php //echo esc_html( $info_message );   ?> <a href="#" class="showlogin"><?php //_e( 'Click here to login', 'woocommerce' );   ?></a></p>-->
        <?php // } ?>	

        <?php
        woocommerce_login_form(
                array(
                    'redirect' => get_permalink(woocommerce_get_page_id('checkout')),
                    'hidden' => false
                )
        );
    }
    ?>


</div><!-- .large-12 -->

<form name="checkout" method="post" class="checkout" action="<?php echo esc_url($get_checkout_url); ?>">
<!--         <div class ="checkout-group">
            <h3><?php _e( 'Billing Details', 'woocommerce' ); ?></h3>
        </div>
 --> 
    <div class="large-12 small-12 columns email_login custom-form-row active">
        <input type="text" class="input-text" name="username" id="username" autocomplete="off" placeholder="Email*" />
        <div class="email-text">This email would be used for your EduKart account and all communicaton.</div>
        <a class="edit-btn" id="edit_email">Edit</a>
    </div>
    <div class="clear"></div>

    <div class="large-12 small-12 columns customer-info custom-form-row">
            <h2>Address</h2>
            <div id="customer_details_filled">
                <div id="name_phone"></div>
                <div id="full_address"></div>
                <a class="edit-btn" id="edit_address">Edit</a>
            </div>
            <div id="customer_details" class="large-12 columns">
                <!--<div class="row">-->
                <?php if (sizeof($checkout->checkout_fields) > 0) : ?>

                <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                <div class="checkout-group woo-billing clearfix">
                    <?php do_action('woocommerce_checkout_billing'); ?>
                    <button id="billing_next_btn" onclick="return validate_billing_form()">Next</button>
                    <div id="customer_details_error">
                        Kindly fill all the (*) marked fields.
                    </div>
                </div>
                <!-- <div class="checkout-group woo-shipping">
                    <?php 
                    /* Remove Addition information from address
                        do_action('woocommerce_checkout_shipping');
                    */
                    ?>
                </div> -->
            </div><!-- .large-7 -->
    </div>

    <div class="large-12 small-12 order-detail columns custom-form-row">
            <h2>Payment</h2>
            <div class="large-12 columns total-amount">
                <div class="order-review">
                    <?php do_action('woocommerce_checkout_after_customer_details'); ?>
                    <?php endif; ?>
                    <?php do_action('woocommerce_checkout_order_review'); ?>
                </div>
                <!-- .large-5 -->
            </div><!-- .row -->
    </div>


</form><!-- .checkout -->


<?php do_action('woocommerce_after_checkout_form', $checkout); ?>


<script>
jQuery(document).ready(function(){
$("#billing_first_name").attr("placeholder","Name*");
$("#billing_last_name").attr("placeholder","Last Name*");
$("#billing_email").attr("placeholder","testing@edukart.com*");
$("#billing_phone").attr("placeholder","Phone*");
$("#billing_phone").attr('maxlength','15');
$("#billing_city").attr("placeholder","Town / City*");
$("#billing_address_1").attr("placeholder","Full Address*");
$("#billing_postcode").attr("placeholder","Postcode / Zip*");
/*$("#billing_postcode").val("");*/
$("#username").val($("#billing_email").val());
if($("#billing_email").val() == ""){
    $("#billing_email").val($("#username").val());
}
if($("#username").val() != ""){
    $("#customer_details").show();
}

$("#edit_address").click(function(){
    $("#customer_details").show();
    $("#customer_details_filled").hide();
    $(".order-review").hide();
    $(".order-detail").removeClass("active");
});
});

function validate_billing_form(){
    var email = $("#username").val();
    var fname  = $("#billing_first_name").val();
    //var lname  = $("#billing_last_name").val();
    var phone = $("#billing_phone").val();
    var billing_address_1 = $("#billing_address_1").val();
    var billing_country = $("#billing_country").val();
    var country_name = $("#billing_country option:selected").text();
    var billing_state1 = $("#billing_state").val();
    var billing_state = $("#billing_state option:selected").text();
    var billing_city = $("#billing_city").val();
    var billing_postcode = $("#billing_postcode").val();
    var form_data = email + "$" + fname + "$" + phone + "$" + billing_address_1 + "$" + billing_country + "$" + billing_state1 + "$" + billing_city + "$" + billing_postcode;
    $.cookie("form_data",  form_data);

    // For 10 digtis mobile number : var phoneno = /^\d{10}$/;
    var phoneno = /^\d+$/;//^([0|\+[0-9]{1,5})?([7-9][0-9]{9})$/;
    var pincode = /^\d{6}$/;

    var full_name_phone = "<strong>"+fname+"</strong><br>"+phone;
    var full_address = billing_address_1+", "+billing_city+", "+billing_state+", "+country_name+" - "+billing_postcode;
    
    if(fname == "" || phone == "" || billing_address_1 == "" || billing_city == "" || billing_postcode == ""){
        $("#customer_details_error").show()
        return false;
    }
    else if(!phone.match(phoneno)){
        $("#customer_details_error").show();
        $("#customer_details_error").text("Invalid Phone Number only digits accepted.");
        return false;
    }
    else if(phone.length < 10){
        $("#customer_details_error").show();
        $("#customer_details_error").text("Invalid Phone Number not less then 10 digits.");
        return false;   
    }
    else if(billing_state =="Select State..."){
        $("#customer_details_error").show();
        $("#customer_details_error").text("Please select State.");
        return false;
    }
    else if(!billing_postcode.match(pincode)){
        $("#customer_details_error").show();
        $("#customer_details_error").text("Invalid Postal-Code Number (Contains only 6 digits).");
        return false;
    }
    else{
        $("#customer_details_error").hide()
        $("#customer_details_filled").css("display", "inline-block");
        $("#customer_details").hide();
        $("#name_phone").html(full_name_phone);
        $("#full_address").html(full_address);
        $(".order-review").show();
        $(".customer-info").addClass("active");
        $(".order-detail").addClass("active");
    }
    
    return false;
}
</script>

<?php
//print_r($_COOKIE); die;
    if(isset($_COOKIE['form_data'])){
        $form_data = explode("$", $_COOKIE['form_data']);
        unset($_COOKIE['form_data']);
        $_COOKIE['form_data'] = "";
        ?>
        <script>
	jQuery(document).ready(function(){
            $("#username").val("<?php echo $form_data[0];?>");
            $("#billing_email").val("<?php echo $form_data[0];?>");
            $("#billing_first_name").val("<?php echo $form_data[1];?>");
            $("#billing_phone").val("<?php echo $form_data[2];?>");
            $("#billing_address_1").val("<?php echo $form_data[3];?>");
            $("#billing_country").val("<?php echo $form_data[4];?>");
            $("#billing_state").val("<?php echo $form_data[5];?>");
            $("#billing_city").val("<?php echo $form_data[6];?>");
            $("#billing_postcode").val("<?php echo $form_data[7];?>");
            var full_name_phone = "<strong><?php echo $form_data[1];?></strong><br><?php echo $form_data[2];?>";
            var full_address = "<?php echo $form_data[3];?>, <?php echo $form_data[6];?>, "+$("#billing_state option:selected").text()+", "+$("#billing_country option:selected").text()+" - <?php echo $form_data[7];?>";
            $("#name_phone").html(full_name_phone);
            $("#full_address").html(full_address);
            $(".customer-info").addClass("active");
            $("#customer_details_filled").css("display", "inline-block");
            $("#customer_details").hide();
            $(".order-review").show();
            $(".order-detail").addClass("active");
	});
        </script>
        <?php
       }
    ?>
