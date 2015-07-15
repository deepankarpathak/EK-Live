<?php

/**

Template used to display the vendor's commission earned for the current month

*/

global $ignitewoo_vendors; 

$amount = $ignitewoo_vendors->earnings_this_month();

?>

<div>

	<?php 
	
		$amount = get_woocommerce_currency_symbol() . number_format( $amount, 2 );
		
		$this_month = date( 'F' ) . ' ' . date( 'Y' );

	?>
	
	<h2><?php _e( 'Earnings for', 'ignitewoo_vendor_stores' )?> <?php echo $this_month ?></h2>
	
	<p><?php echo $amount ?> <?php _e( 'to date', 'ignitewoo_vendor_stores' )?></p>
	
</div>