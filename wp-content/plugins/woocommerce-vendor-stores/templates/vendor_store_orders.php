<?php

/**

Template used to display the vendor's list of orders

*/

global $ignitewoo_vendors; 

$days = 90;

// Get orders for the past X days
$orders = $ignitewoo_vendors->get_orders( $days );

// Build an export URL that exports to CSV orders for the past X days
$csv_export_url = wp_nonce_url( add_query_arg( array( 'vendor_export_to_csv' => 'true', 'days' => $days ) ), 'vendor_export_to_csv' );

?>

<div style="clear:both: margin: 20px 0">

	<h2><?php _e( 'Orders', 'ignitewoo_vendor_stores' )?></h2>
	
	<?php if ( !empty( $orders ) ) { ?>
	
	<p>
		<a class="button" href="<?php echo $csv_export_url ?>"><?php _e( 'Export orders to CSV file', 'ignitewoo_vendor_stores' ) ?></a>
	</p>
	
	<p>
	
		<?php echo sprintf( __( 'Orders for the past %s days', 'ignitewoo_vendor_stores' ), $days ) ?>
		<br/>
		<em><?php echo sprintf( __( 'Note that totals do not include shipping and taxes', 'ignitewoo_vendor_stores' ), $days ) ?></em>
	</p>
	
	<table class="shop_table my_account_orders vendor_orders_table"> 
	
		<thead>
			<tr>
				<th><?php _e( 'Order', 'ignitewoo_vendor_stores' )?></th>
				<th><?php _e( 'Date', 'ignitewoo_vendor_stores' )?></th>
				<th><?php _e( 'Status', 'ignitewoo_vendor_stores' )?></th>
				<th><?php _e( 'Total', 'ignitewoo_vendor_stores' )?></th>
				
				<?php if ( class_exists( 'IgniteWoo_PDF_Invoice' ) && 'yes' == $ignitewoo_vendors->settings['packing_slips'] ) { ?>
				
				<th></th>
				
				<?php } ?>
			</tr>
		</thead>
		
		<tbody>
			<?php
			
			if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) )
				$statuses = wc_get_order_statuses();
			else 
				$statuses = null;
				
			foreach( $orders as $order ) { 

				$url = add_query_arg( array( 'view_vendor_order' => $order->ID ), get_permalink() );
			?>
			
			<tr>
				<td>
					<a title="<?php _e( 'View Order Details', 'ignitewoo_vendor_stores' )?>" href="<?php echo $url ?>">#<?php echo $order->ID ?></a>
				</td>
				
				<td>
					<?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->post_date ) ); ?>
				</td>
				
				<td>
					<?php 
						if ( !empty( $statuses ) )
							echo $statuses[ $order->status ];
						else 
							echo ucwords( $order->status );
					?>
				</td>
				
				<td>
					<?php echo sprintf( _n( '%s for %s item', '%s for %s items', $order->qty, 'ignitewoo_vendor_stores' ), woocommerce_price( $order->total ), $order->qty ); ?>
				</td>
				
				<?php if ( class_exists( 'IgniteWoo_PDF_Invoice' ) && 'yes' == $ignitewoo_vendors->settings['packing_slips'] ) { ?>
		
					<?php
					$nonce = wp_create_nonce( 'pdf_gen_from_list' );
					
					$settings = IgniteWoo_PDF_Invoice::get_settings();

					// Is the plugin set for HTML or PDF style packing slips?
					if ( 'html' == $settings['packing_slip_type'] ) { 
					
					?>
				
					<td><a class="button" href="#" onclick="window.open( '<?php echo admin_url ('/edit.php?post_type=shop_order&amp;get_packing_slip=' . $order->ID . '&amp;_wpnonce=' . $nonce ) ?>', 'mywindow','width=960,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=no,resizable=yes'); return false;"><?php _e( 'Packing Slip','ignitewoo_vendor_stores' ) ?></a></td>
					
					<?php } else { ?>
					
					<td><a class="button" href="?get_packing_slip=<?php echo $order->ID ?>&_wpnonce=<?php echo $nonce ?>" ><?php _e( 'Packing Slip','ignitewoo_vendor_stores' ) ?></a></td>
					
					<?php } ?>
					
				<?php } ?>
			</tr>
			
			<?php
			}
			
			?>
		
		</tbody>
	</table>
	
	<?php } else { ?>
	
		<p><em><?php echo sprintf( __( 'No orders in the past %s days', 'ignitewoo_vendor_stores' ), $days ) ?></em></p>
	
	<?php } ?>
	
</div>