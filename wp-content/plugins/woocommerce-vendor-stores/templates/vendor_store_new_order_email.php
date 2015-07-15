<?php
/**
 * Vendor's new order email - this will only contain items in the order sold by the vendor
 */
 
global $ignitewoo_vendors;
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

<p><?php printf( __( 'You have received an order from %s. Their order is as follows:', 'ignitewoo_vendor_stores' ), $order->billing_first_name . ' ' . $order->billing_last_name ); ?></p>

<?php do_action( 'woocommerce_email_before_order_table', $order, true ); ?>

<h2><?php printf( __( 'Order: %s', 'ignitewoo_vendor_stores'), $order->get_order_number() ); ?> (<?php printf( '<time datetime="%s">%s</time>', date_i18n( 'c', strtotime( $order->order_date ) ), date_i18n( woocommerce_date_format(), strtotime( $order->order_date ) ) ); ?>)</h2>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', 'ignitewoo_vendor_stores' ); ?></th>
			
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Quantity', 'ignitewoo_vendor_stores' ); ?></th>
			
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Commission', 'ignitewoo_vendor_stores' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach( $items as $item ) { ?>
		
		<tr>
			<td>
				<?php echo $item['name'] ?>
				<?php
				if ( !empty( $item['item_meta'] ) ) { 
					
					$item_meta = new WC_Order_Item_Meta( $item['item_meta'] );
				
					$item_meta->display();

				}
				?>
			</td>
			
			<td><?php echo $item['qty'] ?></td>
			
			<td><?php echo woocommerce_price( ign_calculate_product_commission( $item['line_total'], $item['product_id'], $item['variation_id'], $item['vendor_id'] ) ) ?></td>
		</tr>
		
		<?php } ?>
	</tbody>

</table>

<?php do_action('woocommerce_email_after_order_table', $order, true); ?>

<?php do_action( 'woocommerce_email_order_meta', $order, true ); ?>

<?php if ( 
	'yes' == $ignitewoo_vendors->settings['show_order_email'] ||
	'yes' == $ignitewoo_vendors->settings['show_order_billing_phone'] ||
	'yes' == $ignitewoo_vendors->settings['show_order_billing_address'] ||
	'yes' == $ignitewoo_vendors->settings['show_order_shipping_address']
	) :
?>
	<h2><?php _e( 'Customer details', 'ignitewoo_vendor_stores' ); ?></h2>
<?php endif; ?>

<?php if ( 'yes' == $ignitewoo_vendors->settings['show_order_email'] && $order->billing_email ) : ?>
	<p><strong><?php _e( 'Email:', 'ignitewoo_vendor_stores' ); ?></strong> <?php echo $order->billing_email; ?></p>
<?php endif; ?>

<?php if ( 'yes' == $ignitewoo_vendors->settings['show_order_billing_phone'] && $order->billing_phone ) : ?>
	<p><strong><?php _e( 'Tel:', 'ignitewoo_vendor_stores' ); ?></strong> <?php echo $order->billing_phone; ?></p>
<?php endif; ?>

<?php if ( 'yes' == $ignitewoo_vendors->settings['show_order_billing_address'] || 'yes' == $ignitewoo_vendors->settings['show_order_shipping_address'] ) : ?>

	<table cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top;" border="0">

		<tr>
			<?php if ( 'yes' == $ignitewoo_vendors->settings['show_order_billing_address'] ) : ?>
		
			<td valign="top" width="50%">

				<h3><?php _e( 'Billing address', 'ignitewoo_vendor_stores' ); ?></h3>

				<p><?php echo $order->get_formatted_billing_address(); ?></p>

			</td>
			
			<?php endif; ?>
			
			<?php if ( 'yes' == $ignitewoo_vendors->settings['show_order_shipping_address'] ) : ?>
			
				<?php if ( get_option( 'woocommerce_ship_to_billing_address_only' ) == 'no' && ( $shipping = $order->get_formatted_shipping_address() ) ) : ?>

				<td valign="top" width="50%">

					<h3><?php _e( 'Shipping address', 'ignitewoo_vendor_stores' ); ?></h3>

					<p><?php echo $shipping; ?></p>

				</td>

				<?php endif; ?>
				
			<?php endif; ?>
		</tr>

	</table>

<?php endif; ?>

<?php do_action( 'woocommerce_email_footer' ); ?>