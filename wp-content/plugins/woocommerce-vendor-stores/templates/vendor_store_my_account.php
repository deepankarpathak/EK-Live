<?php
/**

Displays the vendor's store controls and data

*/

// Not a vendor? 
if ( !current_user_can( 'vendor' ) ) { 

	_e( "You're not a vendor at this site", 'ignitewoo_vendor_stores' );
	
	return;

}


// Viewing an order? 
if ( isset( $_GET['view_vendor_order'] ) && absint( $_GET['view_vendor_order'] ) > 0 ) { 

	store_vendors_get_template( 'vendor_store_order_details.php' );

	return;

}

global $ignitewoo_vendors;

$vendor = ign_get_user_vendor();

if ( !empty( $vendor->ID ) ) 
	$vendor_info = ign_get_vendor( $vendor->ID );
else
	$vendor_info = false;
	
// Main landing page
?>

<div class="vendor_store_dashboard">

	<?php if ( empty( $vendor_info ) ) { ?>
	
		<p><?php _e( 'You need to configure your vendor store!', 'ignitewoo_vendor_stores' )?></p>
	
	<?php } else if ( empty( $vendor_info->description ) ) { ?>
	
		<p><?php _e( 'Setting a desciption for your store might help with sales!', 'ignitewoo_vendor_stores' )?></p>
	
	<?php } ?>
	
	<?php if ( !get_woocommerce_term_meta( $vendor->ID, 'thumbnail_id', true ) ) { ?>
	
		<p><?php _e( 'Add a logo to your store improve brand recognition', 'ignitewoo_vendor_stores' )?></p>
	
	<?php } ?>
	

	<h2><?php _e( 'Store URL', 'ignitewoo_vendor_stores' ) ?></h2>
	
	<?php if ( !empty( $vendor->ID ) ) { ?>
		<p><?php echo get_term_link( $vendor->ID, $ignitewoo_vendors->token )?></p>
	<?php } ?>

	
	<?php

	// Controls
	store_vendors_get_template( 'vendor_store_controls.php' );
		
	// This month's earnings
	store_vendors_get_template( 'vendor_store_month_earnings.php' );

	// Report data - The WooCommerce dashboard reports are better, but if you want to use this simple reporting version
	// then go for it. 
	// store_vendors_get_template( 'vendor_store_reports.php' );
		
	// Orders
	store_vendors_get_template( 'vendor_store_orders.php' );
	?>

</div>
