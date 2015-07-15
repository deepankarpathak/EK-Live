<?php

/**

Template used to display links to the vendor's controls

*/


?>

<div>

	<h2><?php _e( 'Controls', 'ignitewoo_vendor_stores' )?></h2>
	
	<p>
		<a class="button" href="<?php echo admin_url() ?>"><?php _e( 'Vendor Admin Area', 'ignitewoo_vendor_stores' )?></a> 
		
		&nbsp;
		
		<a class="button" href="<?php echo admin_url( 'admin.php?page=vendor_details' ) ?>"><?php _e( 'Store Settings', 'ignitewoo_vendor_stores' )?></a> 
		
		&nbsp;
		
		<?php if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>' ) ) $slug = 'wc-reports'; else $slug = 'woocommerce_reports&tab=sales'; ?>
		
		<a class="button" href="<?php echo admin_url( '/admin.php?page=' . $slug  ) ?>"><?php _e( 'Sales Reports', 'ignitewoo_vendor_stores' )?></a>
		
		<?php /* Enable these if you want individual section links 
		
		<a  class="button" href="<?php echo admin_url( 'edit.php?post_type=product' ) ?>"><?php _e( 'Edit Products', 'ignitewoo_vendor_stores' )?></a> | 
		
		<a class="button" href="<?php echo admin_url( 'post-new.php?post_type=product' ) ?>"><?php _e( 'Add Product', 'ignitewoo_vendor_stores' )?></a> | 
	 
		
		*/?>
	</p>
	
</div>