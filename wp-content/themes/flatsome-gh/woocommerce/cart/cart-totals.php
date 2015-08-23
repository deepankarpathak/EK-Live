<?php
/**
 * Cart totals
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<script>
/*jQuery( document ).ready( function() {
	jQuery( '.remove_cashback').click( function( ev ) {
        var code = '';
	var prod_id = jQuery( 'input#product_id').val();
        data = {
            action: 'remove_cashback',
            coupon_code: code,
	    product_id: prod_id,
        }
       jQuery.post( woocommerce_params.ajax_url, data, function( returned_data ) {
            if( returned_data == 'failed' ) {
		<?php //session_destroy(); ?>
		location.reload();
            }
        })
    }); 
});*/
</script>
<div class="cart_totals <?php if ( WC()->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<h2><?php _e( 'Cart Totals', 'woocommerce' ); ?></h2>

	<table cellspacing="0">

		<tr class="cart-subtotal">
			<th><?php //_e( 'Cart Subtotal', 'woocommerce' ); ?>Subtotal</th>
			<td><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<tr class="coupon_cashback" <?php if(isset($_SESSION['cash_back']) AND $_SESSION['cash_back'] != null ){ echo "style='display:table-row;'";}else{echo "style='display:none;'";}?>><th>CASHBACK: <span><?php echo $_SESSION['ir_coupon_code']; ?></span></th><td><span style="color:#f39c11!important;">
<?php echo 'Rs.'. number_format_i18n( $_SESSION['cash_back'],2); ?> </span><a class="remove_cashback" style="text-decoration:none;color:#777; font-size:10px;"><b>[Remove]</b></a>
</td></tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<input type ="hidden" value= "<?php echo esc_attr( $code ); ?>" id ="woo_coupon" name ="woo_coupon"/>
			<tr class="cart-discount coupon-<?php echo esc_attr( $code ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>
		
		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->tax_display_cart == 'excl' ) : ?>
			<?php if ( get_option( 'woocommerce_tax_total_display' ) == 'itemized' ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
						<th><?php echo esc_html( $tax->label ); ?></th>
						<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total">
					<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
					<td><?php echo wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<tr class="order-total">
			<th><?php //_e( 'Payable Amount ', 'woocommerce' ); ?><strong id="you-pay">You Pay</strong><!-- <span style="text-transform:none !important; font-weight:normal; font-size:10px;">(incl. all taxes)</span> --></th>
			<td><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>
		<tr class="effecive_total" <?php if(isset($_SESSION['cash_back']) AND $_SESSION['cash_back'] != null ){ echo "style='display:table; width:173%;margin-left:-6%;margin-top:-2%;'";}else{echo "style='display:none;'";}?>><td colspan="2">Effective Total: <span style="margin-right:10px;">Rs.<?php echo number_format_i18n( $_SESSION['effective_total'],2); ?></span>
</td></tr>
		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

	</table>

	<?php if ( WC()->cart->get_cart_tax() ) : ?>
		<p><small><?php

			$estimated_text = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
				? sprintf( ' ' . __( ' (taxes estimated for %s)', 'woocommerce' ), WC()->countries->estimated_for_prefix() . __( WC()->countries->countries[ WC()->countries->get_base_country() ], 'woocommerce' ) )
				: '';

			printf( __( 'Note: Shipping and taxes are estimated%s and will be updated during checkout based on your billing and shipping information.', 'woocommerce' ), $estimated_text );

		?></small></p>
	<?php endif; ?>

	<div class="wc-proceed-to-checkout">

		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>

	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
