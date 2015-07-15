<?php 
/**

Template used to display store logo. Typical loads when the WooCommerce archive-content.php template is used.

This file is loaded via the do_action( 'woocommerce_archive_description' ) hook in the archive-content.php template, using a priority of 1. 

*/

// Get the store's logo image if one exists
$store_image = vendor_store_get_logo();

// No to display? 
if ( empty( $store_image ) )
	return;
?>

<div class="vendor_store_logo">
	<img src="<?php echo $store_image ?>">
</div>