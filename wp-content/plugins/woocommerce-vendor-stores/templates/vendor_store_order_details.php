<?php
/**
 * Order details
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $ignitewoo_vendors, $user_ID, $note_add_error, $note_added;

$order_total = 0;

$vendor_data = ign_get_user_vendor( $user_ID );

if ( empty( $vendor_data ) ) { 

	_e( 'Error locating vendor record', 'ignitewoo_vendor_stores' );
	
	return;
	
}
	
$vendor_id = $vendor_data->ID;

$order_id = absint( $_GET['view_vendor_order'] ); 

if ( $order_id <= 0 ) { 

	_e( 'Unable to locate order', 'ignitewoo_vendor_stores' );
	
	return;

}

$order = $ignitewoo_vendors->get_order( $order_id );

if ( empty( $order ) ) { 

	_e( 'Unable to locate order', 'ignitewoo_vendor_stores' );
	
	return;

} 

// Was a new note sent? If so show a message
$is_new_customer_note = get_transient( 'note_added_' . $order->id . '_' . $user_ID );

if ( false !== $is_new_customer_note ) { 
	delete_transient( 'note_added_' . $order->id . '_' . $user_ID, 90 );
	?>
	<div class="new_order_note_added">
		<?php _e( 'New note added', 'ignitewoo_vendor_stores' )?> 
		<?php if ( '2' == $is_new_customer_note ) { ?>
			<?php echo ' '; ?>
			<?php _e( 'and sent to customer', 'ignitewoo_vendor_stores' )?>
		<?php } ?>
	</div>
	<?php
}

// Error adding new note? 
if ( get_transient( 'note_add_error_' . $order->id . '_' . $user_ID ) ) {
	delete_transient( 'note_add_error_' . $order->id . '_' . $user_ID );
	?>
	<div class="new_order_note_add_error">
		<?php _e( 'Error adding new note', 'ignitewoo_vendor_stores' )?> 
	</div>
	<?php
}

?>

<div class="vendor_order">

<h2>
	<?php echo sprintf( __( 'Order #%s Details', 'ignitewoo_vendor_stores' ), $order->id ); ?>
	
	<?php if ( class_exists( 'IgniteWoo_PDF_Invoice' ) && 'yes' == $ignitewoo_vendors->settings['packing_slips'] ) { ?>
			
		<?php
		$nonce = wp_create_nonce( 'pdf_gen_from_list' );
		
		$settings = IgniteWoo_PDF_Invoice::get_settings();

		// Is the plugin set for HTML or PDF style packing slips?
		if ( 'html' == $settings['packing_slip_type'] ) { 
		
		?>
			<a class="button" href="#" onclick="window.open( '<?php echo admin_url( '/edit.php?post_type=shop_order&amp;get_packing_slip=' . $order_id . '&amp;_wpnonce=' . $nonce ) ?>', 'mywindow','width=960,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=no,resizable=yes'); return false;"><?php _e( 'Packing Slip','ignitewoo_vendor_stores' ) ?></a>
		
		<?php } else { ?>
		
			<a class="button" href="&get_packing_slip=<?php echo $order_id ?>&_wpnonce=<?php echo $nonce ?>" ><?php _e( 'Packing Slip','ignitewoo_vendor_stores' ) ?></a>
		
		<?php } ?>
		
	<?php } ?>
			
</h2>

<table class="shop_table order_details">
	<thead>
		<tr>
			<th class="product-name"><?php _e( 'Product', 'ignitewoo_vendor_stores' ); ?></th>
			<th class="product-total"><?php _e( 'Total', 'ignitewoo_vendor_stores' ); ?></th>
		</tr>
	</thead>
	
	<tbody>
		<?php
		if ( sizeof( $order->order_items ) > 0 ) {

			foreach($order->order_items as $item) {

				$_product = get_product( $item['variation_id'] ? $item['variation_id'] : $item['product_id'] );

				echo '
					<tr class = "' . esc_attr( apply_filters( 'woocommerce_order_table_item_class', 'order_table_item', $item, $order ) ) . '">
						<td class="product-name">' .
							apply_filters( 'woocommerce_order_table_product_title', '<a href="' . get_permalink( $item['product_id'] ) . '">' . $item['name'] . '</a>', $item ) . ' ' .
							apply_filters( 'woocommerce_order_table_item_quantity', '<strong class="product-quantity">&times; ' . $item['qty'] . '</strong>', $item );

				$item_meta = new WC_Order_Item_Meta( $item['item_meta'] );
				
				$item_meta->display();

				if ( $_product && $_product->exists() && $_product->is_downloadable() && $order->is_download_permitted() ) {

					if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) ) 
						$download_file_urls = $order->get_item_downloads( $item['product_id'], $item['variation_id'], $item );
					else 
						$download_file_urls = $order->get_downloadable_file_urls( $item['product_id'], $item['variation_id'], $item );

					$i     = 0;
					
					$links = array();

					foreach ( $download_file_urls as $file_url => $download_file_url ) {

						$filename = woocommerce_get_filename_from_url( $file_url );

						$links[] = '<small><a href="' . $download_file_url . '">' . sprintf( __( 'Download file%s', 'ignitewoo_vendor_stores' ), ( count( $download_file_urls ) > 1 ? ' ' . ( $i + 1 ) . ': ' : ': ' ) ) . $filename . '</a></small>';

						$i++;
					}

					echo implode( '<br/>', $links );
				}

				echo '</td><td class="product-total">';
				
				$amount = ign_calculate_product_commission( $item['line_total'], $item['item_meta']['_product_id'][0], $item['variation_id'], $vendor_id  );
				
				$order_total += $amount;
				
				echo woocommerce_price( $amount );
				
				echo '</td></tr>';

				// Show any purchase notes
				if ($order->status=='completed' || $order->status=='processing') {
					if ($purchase_note = get_post_meta( $_product->id, '_purchase_note', true))
						echo '<tr class="product-purchase-note"><td colspan="3">' . apply_filters('the_content', $purchase_note) . '</td></tr>';
				}
				
	
				$shipping = array();
				
				$shipping_due = get_post_meta( $order->id, '_vendor_shipping_due', true );

				if ( 'yes' == $ignitewoo_vendors->settings['include_shipping'] ) { 
					$shipping[ $vendor_id ] = ign_calculate_shipping_commission( $item['product_id'], $item['variation_id'], $vendor_id, $shipping_due[ $vendor_id ] );
				}

			}
		}

		do_action( 'woocommerce_order_items_table', $order );
		?>
	</tbody>
	
	<?php 
	
	$subtotal = $order_total; 
	
	//$shipping_due = get_post_meta( $order->id, '_vendor_shipping_due', true );
			
	if ( !empty( $shipping[ $vendor_id ] ) )
		$order_total += $shipping[ $vendor_id ];
	
	$tax_due = array();
	
	if ( 'yes' == $ignitewoo_vendors->settings['give_vendor_tax'] ) { 
		
		$tax_due = get_post_meta( $order->id, '_vendor_tax_due', true );

		if ( !empty( $tax_due[ $vendor_id ] ) )
			$order_total += $tax_due[ $vendor_id ];
			
	}
		
	?>
	
	<tfoot>
		<tr>
			<th scope="row"><?php _e( 'Subtotal', 'ignitewoo_vendor_stores' ) ?></th>
			<td><?php echo woocommerce_price( $subtotal ) ?></td>
		</tr>
		
		<?php if ( !empty( $shipping_due[ $vendor_id ] ) ) { ?>
		<tr>
			<th scope="row"><?php _e( 'Shipping', 'ignitewoo_vendor_stores' ) ?></th>
			<td><?php echo woocommerce_price( $shipping[ $vendor_id ] ) ?></td>
		</tr>
		<?php } ?>
		
		<?php if ( !empty( $tax_due[ $vendor_id ] ) ) { ?>
		<tr>
			<th scope="row"><?php _e( 'Taxes', 'ignitewoo_vendor_stores' ) ?></th>
			<td><?php echo woocommerce_price( $tax_due[ $vendor_id ] ) ?></td>
		</tr>
		<?php } ?>
		
		<tr>
			<th scope="row"><?php _e( 'Total', 'ignitewoo_vendor_stores' ) ?></th>
			<td><?php echo woocommerce_price( $order_total ) ?></td>
		</tr>

	</tfoot>

	
	
</table>

<?php if ( get_option('woocommerce_allow_customers_to_reorder') == 'yes' && $order->status=='completed' ) : ?>
	<p class="order-again">
		<a href="<?php echo esc_url( $woocommerce->nonce_url( 'order_again', add_query_arg( 'order_again', $order->id, add_query_arg( 'order', $order->id, get_permalink( woocommerce_get_page_id( 'view_order' ) ) ) ) ) ); ?>" class="button"><?php _e( 'Order Again', 'ignitewoo_vendor_stores' ); ?></a>
	</p>
<?php endif; ?>

<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>

<?php if ( 
	'yes' == $ignitewoo_vendors->settings['show_order_email'] ||
	'yes' == $ignitewoo_vendors->settings['show_order_billing_phone'] ||
	'yes' == $ignitewoo_vendors->settings['show_order_billing_address'] ||
	'yes' == $ignitewoo_vendors->settings['show_order_shipping_address']
	) : ?>

	<header>
		<h2><?php _e( 'Customer details', 'ignitewoo_vendor_stores' ); ?></h2>
	</header>

<?php endif; ?>


	<dl class="customer_details">
	<?php
		if ( 'yes' == $ignitewoo_vendors->settings['show_order_email'] && $order->billing_email ) 
			echo '<dt>'.__( 'Email:', 'ignitewoo_vendor_stores' ).'</dt><dd>'.$order->billing_email.'</dd>';
			
		if ( 'yes' == $ignitewoo_vendors->settings['show_order_billing_phone'] && $order->billing_phone ) 
			echo '<dt>'.__( 'Telephone:', 'ignitewoo_vendor_stores' ).'</dt><dd>'.$order->billing_phone.'</dd>';
	?>
	</dl>

<?php if ( 'no' == get_option( 'woocommerce_ship_to_billing_address_only' ) ) : ?>

	<div class="col2-set addresses">

		<div class="col-1">

<?php endif; ?>

		<?php if ( 'yes' == $ignitewoo_vendors->settings['show_order_billing_address'] ) : ?>
	
			<header class="title">
				<h3><?php _e( 'Billing Address', 'ignitewoo_vendor_stores' ); ?></h3>
			</header>
			<address><p>
				<?php
					if (!$order->get_formatted_billing_address()) _e( 'N/A', 'ignitewoo_vendor_stores' ); else echo $order->get_formatted_billing_address();
				?>
			</p></address>
			
		<?php endif; ?>

	<?php if ( 'no' == get_option( 'woocommerce_ship_to_billing_address_only' ) ) : ?>

		</div><!-- /.col-1 -->

		<div class="col-2">

			<?php if ( 'yes' == $ignitewoo_vendors->settings['show_order_shipping_address'] ) : ?>
		
				<header class="title">
					<h3><?php _e( 'Shipping Address', 'ignitewoo_vendor_stores' ); ?></h3>
				</header>
				<address><p>
					<?php
						if (!$order->get_formatted_shipping_address()) _e( 'N/A', 'ignitewoo_vendor_stores' ); else echo $order->get_formatted_shipping_address();
					?>
				</p></address>
				
			<?php endif; ?>
			
		</div><!-- /.col-2 -->

	</div><!-- /.col2-set -->

	<?php endif; ?>

<?php /** Order notes input form - Vendors can add order notes, either private, or for the customer */ ?>
	
	<div class="clear"></div>

	<div class="vendor_order_notes"> 
	
		<h2><?php _e( 'Order notes', 'ignitewoo_vendor_stores' )?></h2>
		
		<?php // list existing order notes ?>
		
		<?php 

		$args = array(
			'post_id' => $order_id,
			'status' => 'approve',
			'type' => ''
		); 

		remove_all_filters('comments_clauses' );

		$notes = get_comments( $args );
		
//var_dump( $args, $notes[0] ); die;

		//add_filter('comments_clauses', 'woocommerce_exclude_order_comments');

		$i = 0;
		
		if ( !empty( $notes ) && !is_wp_error( $notes ) ) { 
			?>
			<ul>
			<?php
			foreach( $notes as $note ) {

				$vendor_comment_id = absint( get_comment_meta( $note->comment_ID, 'vendor_added_note', true ) );
			
				// If zero then no such meta exists or it's an old comment.
				// If the meta key exists and is does not match the current vendor 
				// then it's from a different vendor so skip showing it.
				if ( $vendor_comment_id !== 0 && $vendor_data->ID !== $vendor_comment_id ) 
					continue;
			
				
				if ( 0 != $i % 2 ) 
					$class = 'even';
				else 
					$class = 'alt';

				$i++;
				
				?>
				<li class="<?php echo $class ?>">
				<?php 

					
					echo $note->comment_date . ' - ' . $note->comment_content;
			
					$is_customer_note = get_comment_meta( $note->comment_ID, 'is_customer_note', true);
			
					if ( $is_customer_note )
						echo ' <em class="customer_note">(' . __( 'Sent to customer', 'ignitewoo_vendor_stores' ) . ')</em>';
				?>
				</li>
				<?php 
			}
			?>
			</ul>
			<?php 
		}
		?>
		
		<h2><?php _e( 'Add Order Note', 'ignitewoo_vendor_stores' )?></h2>
		
		<?php $url = add_query_arg( array( 'order' => $order->id ) ); ?>
		
		<form action="<?php echo $url ?>" method="post">
		
			<?php wp_nonce_field( 'add_order_note' ) ?>

			<p><label for="new_order_note"><?php _e( 'No HTML allowed', 'ignitewoo_vendor_stores' )?></label></p>
			
			<textarea name="new_order_note"></textarea>
			
			<p><label for="send_to_customer">
				<input type="checkbox" value="yes" name="send_to_customer"> <?php _e( 'Send note to customer', 'ignitewoo_vendor_stores' ) ?>
			</label></p>
			
			<p>
				<input type="submit" name="submit" value="<?php _e('Add Note', 'ignitewoo_vendor_stores' )?>">
			</p>
	
		</form>
	</div>
	
</div>

<div class="clear"></div>


