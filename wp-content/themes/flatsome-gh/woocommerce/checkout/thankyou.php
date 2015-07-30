<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */
?>
<?php if ($order->payment_method_title != 'Cash on Delivery'){ ?>
<script type="text/javascript" charset="utf-8">
  window.setTimeout(function() {
    document.forms['referral_generate'].submit()
  }, 20000);
</script>

<?php } ?>
<?php
//print_r($order->get_items());
//$order = wc_get_order( $order_id );
	$i = 0;
	foreach( $order->get_items() as $item ) {
		$productid[$i] = wc_get_product( $item['product_id'] )->id;
		$i++;
	}
//print_r($productid);
//echo $order->get_total(); 
?>

<script type="text/javascript">
  window.vizLayer = {
    geo: "sg",
    account_id: "VIZVRM3503",
    vertical: "ecommerce",
    type: "thank_you",
    pid1: <?php echo $productid[0]; ?>, 
    pid2: <?php echo $productid[1]; ?>, 
    pid3: <?php echo $productid[2]; ?>, 
    orderid: <?php echo $order->id; ?>, 
    orderprice: <?php echo $order->get_total(); ?>, 
    currency: <order currency>
 };

(function(){try{var viz = document.createElement("script"); viz.type = "text/javascript";viz.async = true; viz.src = ("https:" == document.location.protocol ?"https://in-tags.vizury.com" : "http://in-tags.vizury.com")+ "/analyze/pixel.php?account_id=vst";var s = document.getElementsByTagName("script")[0];s.parentNode.insertBefore(viz, s);viz.onload = function() {try {pixel.parse();} catch(i){}};viz.onreadystatechange = function() {if (viz.readyState == "complete" || viz.readyState == "loaded"){try {pixel.parse();}catch(i){}}};}catch(i){}})();

</script>

<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( $order ) : ?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<p><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'woocommerce' ); ?></p>

		<p><?php
			if ( is_user_logged_in() )
				_e( 'Please attempt your purchase again or go to your account page.', 'woocommerce' );
			else
				_e( 'Please attempt your purchase again.', 'woocommerce' );
		?></p>

		<p>
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>" class="button pay"><?php _e( 'My Account', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</p>
	<?php else : ?>
<div class="checkout-breadcrumb">
		<h1>
			<span class="title-checkout"><?php _e('Applied Courses', 'flatsome'); ?></span>
                        <span class="icon-angle-right divider"></span>    
			<span class="title-checkout"><?php _e('Checkout details', 'flatsome'); ?></span>  
			<span class="icon-angle-right divider"></span>  
			<span class="title-checkout"><?php _e('Enrollment Complete', 'flatsome'); ?></span>
		</h1>
</div>
<p class="edu-oder-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); ?></p>
 <div class="eduorder-details-info">
    <div class="eduorder-detail-info info1">
     <h3><?php _e( 'Order Details', 'woocommerce' ); ?></h3>
            <ul class="order_details edu-order_details">
                <li class="order">
                    <?php _e( 'Order:', 'woocommerce' ); ?>
                    <strong><?php echo $order->get_order_number(); ?></strong>
                </li>
                <li class="date">
                    <?php _e( 'Date:', 'woocommerce' ); ?>
                    <strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
                </li>
                <li class="total">
                    <?php _e( 'Total:', 'woocommerce' ); ?>
                    <strong><?php echo $order->get_formatted_order_total(); ?></strong>
                </li>
                <?php if ( $order->payment_method_title ) : ?>
                <li class="method">
                    <?php _e( 'Payment method:', 'woocommerce' ); ?>
                    <strong><?php echo $order->payment_method_title; ?></strong>
                </li>
                <?php endif; ?>
            </ul>
    </div>
    <div class="eduorder-detail-info info2">
         <h3><?php _e( 'Customer details', 'woocommerce' ); ?></h2>
             <dl class="customer_details">
    <?php
	
        if ( $order->billing_email ) echo '<dt>' . __( 'Email:', 'woocommerce' ) . '</dt><dd>' . $order->billing_email . '</dd>';
        if ( $order->billing_phone ) echo '<dt>' . __( 'Telephone:', 'woocommerce' ) . '</dt><dd>' . $order->billing_phone . '</dd>';
    
        // Additional customer details hook
        do_action( 'woocommerce_order_details_after_customer_details', $order );
    ?>
    </dl>
    </div>

    <div class="eduorder-detail-info info3">
                 <h3><?php _e( 'Billing Address', 'woocommerce' ); ?></h3>
             <address>
                <?php
                    if ( ! $order->get_formatted_billing_address() ) _e( 'N/A', 'woocommerce' ); else echo $order->get_formatted_billing_address();
                ?>
            </address>
    </div>
</div>
		<div class="clear"></div>

	<?php endif; ?>

 	<div class="edu-order-detail-price">
	<?php do_action( 'woocommerce_thankyou', $order->id ); ?>
</div>
<?php else : ?>

	<p  class="edu-oder-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>

<?php endif; ?>

<?php if($order->payment_method_title == 'Credit / Debit Card / Net Banking'){ ?>
<form action="<?php echo get_site_url(); ?>/referral" method="POST" name ="referral_generate">
	<input type="hidden" name = "order_referal_email" value = "<?php echo $order->billing_email ;?>" >
	<input type="hidden" name = "order_referal_name" value = "<?php echo $order->billing_first_name ; ?>" >
</form>
<?php } else{ ?>
<form action="<?php echo get_site_url(); ?>" method="POST" name ="referral_generate">
	
</form>
<?php } ?>
<img src="<?php 
        $event = 'sale'; // set as sale or register
	$orderID = $order->id; //set order id
        $purchaseValue = $order->get_formatted_order_total();
        $email = $order->billing_email; //set user email
        $mobile = $order->billing_phone; //mobile number of user
        $userParams = urlencode(json_encode(array('fname'=>$order->billing_first_name,'lname'=>$order->billing_last_name))); //user information
        $userCustomParams = urlencode(json_encode(array('customValue'=>'')));
	$http_val = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
	print $http_val.'www.ref-r.com/campaign/t1/settings?bid_e=E1B12D0338B46F598D8123D7C78E9598&bid=1576&t=420&event='.$event.'&email='.$email.'&orderID='.$orderID.'&purchaseValue='.$purchaseValue.'&mobile='.$mobile.'&userParams='.$userParams.'&userCustomParams='.$userCustomParams;
?>" style="position:absolute; visibility:hidden" />
