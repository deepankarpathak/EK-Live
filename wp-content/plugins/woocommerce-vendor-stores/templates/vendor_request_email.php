<?php
/** 
	Template used to notice admins that a user is requesting vendor access
*/
?>

<?php if ( !defined('ABSPATH' ) ) die; ?>

<?php do_action('woocommerce_email_header', $heading ); ?>

<p><?php _e( 'This message is from', 'ignitewoo_vendor_stores' )?><?php echo ' ' . $sitename; ?></p>

<p> <?php _e( 'Hi', 'ignitewoo_vendor_stores' ); ?>, </p>

<p>
	<?php echo sprintf( __( "%s %s requested vendor access", 'ignitewoo_vendor_stores' ), $fname, $lname ) ?>
</p>

<p>
	<?php echo sprintf( __( '<a href="%s">Click here</a> to edit their user account to grant access', 'ignitewoo_vendor_stores' ), $user_edit_url ) ?>
</p>


<div style="clear:both;"></div>

<?php do_action('woocommerce_email_footer'); ?>