<?php

/**

Template displayed near the bottom of the My Account page to show earnings reports

*/

global $ignitewoo_vendors; 
?>

<div style="clear:both"> 

	<h2><?php _e( 'Vendor Earnings Reports', 'ignitewoo_vendor_stores' )?></h2>
	
	<?php
	if ( !empty( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'vendor_reports' ) ) { 
	
		$data = vendor_store_earnings_data();
	}
	
	$selected_month = isset( $_POST['report_month'] ) ? $_POST['report_month'] : '';
		
	$selected_year = isset( $_POST['report_year'] ) ? $_POST['report_year'] : '';
		
	?>
	
	<div class="product_vendors_report_form">
	
		<form name="product_vendors_report" action="<?php the_permalink()?>" method="post">
		
			<?php wp_nonce_field( 'vendor_reports' )?>
			
			<?php _e( 'Generate a report for:', 'ignitewoo_vendor_stores' ) ?>
			
			<?php ign_vendor_report_month_dropdown( 'report_month', $selected_month ) ?>
			
			<?php ign_vendor_report_year_dropdown( 'report_year', $selected_year ) ?>

			<input type="submit" class="button" value="<?php _e( 'Submit', 'ignitewoo_vendor_stores' )?>" />
			
		</form>
	</div>
	
	<?php
	
	if ( !empty( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'vendor_reports' ) ) {

	?>
	
	<table class="shop_table" cellspacing="0">
		<thead>
			<tr>
				<th><?php _e( 'Product', 'ignitewoo_vendor_stores' )?></th>
				<th><?php _e( 'Sales', 'ignitewoo_vendor_stores' )?></th>
				<th><?php _e( 'Earnings', 'ignitewoo_vendor_stores' )?></th>
			</tr>
		</thead>
		<tbody>
	
		<?php 
		if ( !empty( $data ) && count( $data ) > 0 ) {
	
			foreach( $data as $product_id => $product ) {
			
				if ( 'total' == $product_id ) 
					continue;
			?>
			
			<tr>
				<td>
					<a href="<?php echo esc_url( $product['product_url'] )?>"><?php echo $product['product'] ?></a>
				</td>
				<td>
					<?php echo $product['sales'] ?>
				</td>
				<td>
					<?php echo get_woocommerce_currency_symbol() . number_format( $product['earnings'], 2 ) ?>
				</td>
			</tr>

			<?php } ?>
			
			<tr>
				<td colspan="2">
					<strong><?php _e( 'Total', 'ignitewoo_vendor_stores' )?></strong>
				</td>
				<td>
					<?php echo get_woocommerce_currency_symbol() . number_format( $data['total'], 2 )?>
				</td>
			</tr>

			<?php
			
			
		} else {
		
			?>
			
			<tr>
				<td colspan="3">
					<em><?php _e( 'No sales found', 'ignitewoo_vendor_stores' )?></em>
				</td>
			</tr>
			
			<?php
		}

		?>
		
		</tbody>
	</table>
	
	<?php } ?>
			
</div>