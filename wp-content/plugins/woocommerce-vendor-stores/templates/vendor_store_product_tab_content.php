<?php
/**
Template for display the "Seller" tab content on single product pages
*/

global $product_vendor;

?>

<div class="product-vendor">

	<h2><?php echo $product_vendor->title ?></h2>
	
		<?php if ( '' != $product_vendor->description ) { ?>
		
		<p><?php echo $product_vendor->description ?></p>
		
		<?php }?>
		
		<p>
			<a href="<?php echo $product_vendor->url ?>">
				<?php echo sprintf( __( 'More products from %1$s', 'ignitewoo_vendor_stores' ), $product_vendor->title ) ?>
			</a>
		</p>
</div>
