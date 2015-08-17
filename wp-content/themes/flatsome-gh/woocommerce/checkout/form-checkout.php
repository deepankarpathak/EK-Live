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
        var index_at = emailadd.indexOf('@')
        if (index_at == -1) {
            return false;
        }

        var name = emailadd.substr(0, index_at);
        /* should test name for other invalids*/

        var domain = emailadd.substr(index_at + 1);
        /* should check for extra "@" and any other checks that would invalidate an address of which there are likely many*/
        if (domain.indexOf('@') != -1) {
            return false;
        }

        return domain.indexOf('.') > 1;

    }

    $(document).ready(function() {
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
                            //alert(result);
			// disabling customer details autofill. 
                        /*    var user_info = jQuery.parseJSON(result);
                            var billing_first_name  = user_info.billing_first_name;
                            var billing_last_name  = user_info.billing_last_name;
                            var billing_company  = user_info.billing_company;
                            var billing_address_1  = user_info.billing_address_1;
                            var billing_address_2  = user_info.billing_address_2;
                            var billing_city  = user_info.billing_city;
                            var billing_postcode  = user_info.billing_postcode;
                            var billing_state  = user_info.billing_state;
                            var billing_country  = user_info.billing_country;
                            var billing_phone  = user_info.billing_phone;
                            $("#billing_first_name").val(billing_first_name);
                            $("#billing_last_name").val(billing_last_name);
                            $("#billing_company").val(billing_company);
                            $("#billing_address_1").val(billing_address_1);
                            $("#billing_address_2").val(billing_address_2);
                            $("#billing_city").val(billing_city);
                            $("#billing_postcode").val(billing_postcode);
                            $("#billing_state").val(billing_state);
                            $("#billing_country").val(billing_country);
                            $("#billing_phone").val(billing_phone);
//                            document.getElementById("name").innerHTML = result[0];
//                            var user_id = ID; */
                            //alert(user_id);
                            $("#customer_details").show();
                            $('#loading_ajax').hide();
                        }
                    });
                }
            }
            else {
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
        <span>This email would be used for your EduKart account and all communicaton.</span><a class="edit-btn" href="#">Edit</a>
    </div>
    <div class="clear"></div>

    <div class="large-12 small-12 columns customer-info custom-form-row">
            <h2>Address</h2>
            <div id="customer_details" class="large-12 columns">
                <!--<div class="row">-->
                <?php if (sizeof($checkout->checkout_fields) > 0) : ?>

                <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                <div class="checkout-group woo-billing">
                    <?php do_action('woocommerce_checkout_billing'); ?>
                </div>
                <div class="checkout-group woo-shipping">
                    <?php do_action('woocommerce_checkout_shipping'); ?>
                </div>
            </div><!-- .large-7 -->
    </div>

    <div class="large-12 small-12 order-detail columns custom-form-row">
            <h2>Payment</h2>
            <div class="large-12 columns">
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


