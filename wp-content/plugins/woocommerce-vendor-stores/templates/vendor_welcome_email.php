<?php
/** 
	Template used to notify a user that they have been granted vendor access
*/
?>

<?php if ( !defined('ABSPATH' ) ) die; ?>

<?php do_action('woocommerce_email_header', $heading ); ?>

<p><?php _e( 'This message is from', 'ignitewoo_vendor_stores' )?><?php echo ' ' . $sitename; ?></p>

<p> <?php _e( 'Hi', 'ignitewoo_vendor_stores' ); echo $fname ?>, </p>

<p>
	<?php _e( "You've been granted vendor access", 'ignitewoo_vendor_stores' ) ?>
</p>

<p>
	<?php echo sprintf( __( '<a href="%s">Click here</a> to go to your Vendor Dashboard to adjust your vendor settings and then start posting products!', 'ignitewoo_vendor_stores' ), $myaccount_page_url ) ?>
</p>


<div style="clear:both;"></div>

<?php do_action('woocommerce_email_footer'); ?>