<?php 
/**

Template used to display a link to request access to become a vendor. 

Displayed at the top of the My Account page when the related setting is enabled

*/

global $ignitewoo_vendors; 

// Do not display when the settings disallow access requests
if ( 'none' == $ignitewoo_vendors->settings['vendor_access'] )
	return;

// Do not display for admins or shop managers
// WARNING: Do not remove this check otherwise admins or shop managers will have their role reduced to "Pending Vendor"
// if they request vendor access!
if ( current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) )
	return;
	
// Is the user already a vendor? 
if ( current_user_can( 'vendor' ) ) {

	?>
	
	<div style="border: 1px solid #008900; background-color: #daffda; padding: 0 10px; margin-bottom: 15px">
		<?php _e( "You're a vendor on this site!", 'ignitewoo_vendor_stores' )?>
	</div>

	<?php 
	
	return;
	
}

// Did the user successfully request vendor access? If so display a message and return
if ( !current_user_can( 'vendor' ) && get_user_meta( $user_ID, 'vendor_request', true ) )  { 

	?>
	
	<div style="border: 1px solid #008900; background-color: #daffda; padding: 0 10px; margin-bottom: 15px">
		<?php _e( 'Your vendor request is pending review.', 'ignitewoo_vendor_stores' )?>
	</div>
	
	<?php

	return;
}

	
?>
<h2><?php _e( 'Become a Vendor', 'ignitewoo_vendor_stores' ) ?></h2>

<?php
// Has the user configured their billing info? If not, tell them to do so first
global $user_ID;

$fname = get_user_meta( $user_ID, 'billing_first_name', true );

$lname = get_user_meta( $user_ID, 'billing_last_name', true );

if ( empty( $fname ) || empty( $lname ) ) { 

	?>
	
	<p>
	
		<?php 
		_e( "Want to become a vendor and sell your items on this site? First configure your billing information", 'ignitewoo_vendor_stores' );
		?>
		
	<p>
	
	<?php
	
	return;

}

// Otherwise show them the info to become vendor
?>

<p>
	<?php _e( "Want to become a vendor and sell your items on this site? Click the button to request access. We'll respond back within a few days", 'ignitewoo_vendor_stores' )?>
</p>

<?php
	$url = add_query_arg( array( 'request_vendor_access' => 'go' ) );
?>

<a href="<?php echo $url ?>" class="button"><?php _e( 'Request vendor access' ) ?></a>

<div style="clear:both; margin-bottom: 15px"></div>