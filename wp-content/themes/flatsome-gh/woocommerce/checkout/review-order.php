    <?php
/**
 * Review order form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
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
<?php if ( ! $is_ajax ) : ?><div id="order_review" class="clearfix"><?php endif; ?>

	<table class="shop_table small-12 large-5 columns">
		<!-- <thead>
			<tr>
				<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
				<th class="product-total"><?php _e( 'Total', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
				do_action( 'woocommerce_review_order_before_cart_contents' );

				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						?>
						<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
							<td class="product-name">
								<?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ); 

//	print_r($_product);
$product_id = apply_filters( 'woocommerce_cart_item_name', $_product->id, $cart_item, $cart_item_key ); 
?>
								<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); ?>
								<?php echo WC()->cart->get_item_data( $cart_item ); ?>
							</td>
							<td class="product-total">
								<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
							</td>
						</tr>
						<?php
					}
				}

				do_action( 'woocommerce_review_order_after_cart_contents' );
			?>
		</tbody> -->
		<tfoot>

			<tr class="cart-subtotal">
				<th><?php _e( 'Subtotal', 'woocommerce' ); ?></th>
				<td><?php wc_cart_totals_subtotal_html(); ?></td>
			</tr>
<input type="hidden" value="<?php echo $product_id; ?>" name="product_id" id="product_id">
<?php $cashback = $_SESSION['cash_back'] ;?>
			<tr class="coupon_cashback" <?php if(isset($cashback) AND $cashback != null ){ echo "style='display:table-row;'";}else{echo "style='display:none;'";}?>><th>CASHBACK: <span><?php echo $_SESSION['ir_coupon_code']; ?></th><td>
<span style="color:#f39c11!important;"><?php echo 'Rs.'. number_format_i18n($cashback,2); ?></span><a class="remove_cashback" style="text-decoration:none;color:#777; font-size:10px;"><b>[Remove]</b></a>
</td></tr>
			<?php foreach ( WC()->cart->get_coupons( 'cart' ) as $code => $coupon ) : ?>
				<tr class="cart-discount coupon-<?php echo esc_attr( $code ); ?>">
					<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
					<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

				<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

				<?php wc_cart_totals_shipping_html(); ?>

				<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

			<?php endif; ?>

			<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
				<tr class="fee">
					<th><?php echo esc_html( $fee->name ); ?></th>
					<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php if ( WC()->cart->tax_display_cart === 'excl' ) : ?>
				<?php if ( get_option( 'woocommerce_tax_total_display' ) === 'itemized' ) : ?>
					<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
						<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
							<th><?php echo esc_html( $tax->label ); ?></th>
							<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr class="tax-total">
						<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
						<td><?php echo wc_price( WC()->cart->get_taxes_total() ); ?></td>
					</tr>
				<?php endif; ?>
			<?php endif; ?>

			<?php foreach ( WC()->cart->get_coupons( 'order' ) as $code => $coupon ) : ?>
				<tr class="order-discount coupon-<?php echo esc_attr( $code ); ?>">
					<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
					<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

			<tr class="order-total">
				<th><?php _e( 'Payable Amount ', 'woocommerce' ); ?><span style="text-transform:none !important; font-weight:normal; font-size:10px;">(incl. all taxes)</span></th>
				<td><?php wc_cart_totals_order_total_html(); ?></td>
			</tr>
<?php $effect_total =  $_SESSION['effective_total'] ; ?>
			<tr class="effecive_total" <?php if(isset($cashback) AND $cashback != null ){ echo "style='display:table; width:154%;margin-left:-6%;margin-top:-2%;'";}else{echo "style='display:none;'";}?>><td colspan="2" style="text-align:right!important;">Effective Total: <span style="margin-right:15px;">Rs.<?php echo number_format_i18n( $effect_total,2); ?></span>
</td></tr>
			<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

		</tfoot>
	</table>

<div class="large-4 small-12 columns edu-cart-sidebar">
<div class="cart-sidebar">


	<?php woocommerce_cart_totals(); ?>

	
	<!--<input type="submit" class="checkout-button secondary expand button" name="proceed" value="<?php _e( 'Proceed to Checkout', 'woocommerce' ); ?>" />-->
	
	<?php // do_action('woocommerce_proceed_to_checkout'); ?>
		<?php wp_nonce_field( 'woocommerce-cart' ); ?>




		<?php 
		// Coupon Code Here
		if ( WC()->cart->coupons_enabled() ) { ?>
			<div class="coupon">
	        <div class="row">
				<!--<h3 class="widget-title"><?php // _e( 'Coupon', 'woocommerce' ); ?></h3>-->
				<div class="large-6 small-12 columns"><input type="text" name="coupon_code"  id="coupon_code" value="" placeholder="<?php _e( 'Coupon Code', 'flatsome' ); ?>"/> </div>
				<div class="large-6 small-12 columns">
					<input type ="hidden" value = "<?php echo $_SESSION['ir_coupon_code'] ; ?>" name="apply_coupon" id="apply_coupon">
						<input type = "button" class ="<?php  if(isset($_SESSION['cash_back']) AND $_SESSION['cash_back'] != 'null' AND $_SESSION['cash_back'] != ''){ echo "button small expand" ;}else {echo "button small expand coupon_button";} ?>"  value="Apply Coupon">
						<input type="submit" style="display:none;" class="button small expand" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'woocommerce' ); ?>" /></div>
				<?php do_action('woocommerce_cart_coupon'); ?>
			</div>
			</div>
		<?php } ?>
	<div class="coupon_one_message" style = "display:none"><p style="color:red;font-size:10px;">Only one coupon can be applied.</p></div>
	
	<?php woocommerce_shipping_calculator(); ?>

</div><!-- .cart-sidebar -->
</div><!-- .large-3 -->








	<?php $refer = $_SESSION['referral_details']; 
		if(!is_array($refer)){
			$refree_name = $refer->referrer_name;
			$refree_email = $refer->referrer_email;
		}else{
			$refree_name = $refer['referrer_name'];
			$refree_email = $refer['referrer_email'];
		}
?>
	<input type ="hidden" name = "cash_back" value = "<?php echo $cashback; ?>">
	<input type ="hidden" name = "refree_name" value = "<?php echo $refree_name; ?>">
	<input type ="hidden" name = "refree_email" value = "<?php echo $refree_email ; ?>">
	<input type ="hidden" name = "ir_coupon_code" value = "<?php echo $_SESSION['ir_coupon_code'] ; ?>">
	<?php do_action( 'woocommerce_review_order_before_payment' ); ?>

	<div id="payment" class="small-12 large-6 columns payment-way ">
		<?php if ( WC()->cart->needs_payment() ) : ?>
		<ul class="payment_methods methods">
			<?php
				$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
				if ( ! empty( $available_gateways ) ) {

					// Chosen Method
					if ( isset( WC()->session->chosen_payment_method ) && isset( $available_gateways[ WC()->session->chosen_payment_method ] ) ) {
						$available_gateways[ WC()->session->chosen_payment_method ]->set_current();
					} elseif ( isset( $available_gateways[ get_option( 'woocommerce_default_gateway' ) ] ) ) {
						$available_gateways[ get_option( 'woocommerce_default_gateway' ) ]->set_current();
					} else {
						current( $available_gateways )->set_current();
					}

					foreach ( $available_gateways as $gateway ) {
						?>
						<li class="payment_method_<?php echo $gateway->id; ?>">
							<input id="payment_method_<?php echo $gateway->id; ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />
							<label for="payment_method_<?php echo $gateway->id; ?>"><?php echo $gateway->get_title(); ?> <?php echo $gateway->get_icon(); ?></label>
							<?php
								if ( $gateway->has_fields() || $gateway->get_description() ) :
									echo '<div class="payment_box payment_method_' . $gateway->id . '" ' . ( $gateway->chosen ? '' : 'style="display:none;"' ) . '>';
									$gateway->payment_fields();
									echo '</div>';
								endif;
							?>
						</li>
						<?php
					}
				} else {

					if ( ! WC()->customer->get_country() )
						$no_gateways_message = __( 'Please fill in your details above to see available payment methods.', 'woocommerce' );
					else
						$no_gateways_message = __( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' );

					echo '<p>' . apply_filters( 'woocommerce_no_available_payment_methods_message', $no_gateways_message ) . '</p>';

				}
			?>
		</ul>
		<?php endif; ?>

		<div class="form-row place-order order-aggrement">

			<noscript><?php _e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ); ?><br/><input type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php _e( 'Update totals', 'woocommerce' ); ?>" /></noscript>

			<?php wp_nonce_field( 'woocommerce-process_checkout' ); ?>

			<?php do_action( 'woocommerce_review_order_before_submit' ); ?>


			<?php if ( wc_get_page_id( 'terms' ) > 0 && apply_filters( 'woocommerce_checkout_show_terms', true ) ) { 
				$terms_is_checked = apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) );
				?>
				<p class="form-row terms">
					<label for="terms" class="checkbox"><?php printf( __( 'I&rsquo;ve read and accept the <a href="%s" target="_blank">terms &amp; conditions</a>', 'woocommerce' ), esc_url( get_permalink( wc_get_page_id( 'terms' ) ) ) ); ?></label>
                                        <input type="checkbox" class="input-checkbox" name="terms" <?php checked( $terms_is_checked, true ); ?> checked="checked" id="terms" />
				</p>
			<?php } ?>
                        
<!--                         <?php
			$order_button_text = apply_filters( 'woocommerce_order_button_text', __( 'Pay', 'woocommerce' ) );

			echo apply_filters( 'woocommerce_order_button_html', '<input type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '" />' );
			?>
			<?php do_action( 'woocommerce_review_order_after_submit' ); ?>
 -->

                        <?php
/*			$order_button_text = apply_filters( 'woocommerce_order_button_text', __( 'Pay', 'woocommerce' ) );
*/			//$rate = ;
			echo "<div id='term-error'>* Please accept terms & conditions. </div>";
			echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt pay-btn" name="woocommerce_checkout_place_order" id="place_order" data-value="' . esc_attr( $order_button_text ) . '" onclick="return validate_payment()"><div class="btn-main-text">PAY '.WC()->cart->get_total().'</div><div class="btn-sub-menu">(Inclusive of all taxes)</div></button>' );
			?>
			<?php do_action( 'woocommerce_review_order_after_submit' ); ?>


		</div>

		<div class="clear"></div>

	</div>

	
<?php if ( ! $is_ajax ) : ?></div><?php endif; ?>
<?php do_action( 'woocommerce_review_order_after_payment' ); ?>
		<?php if(isset($cashback) AND $cashback != 'null' AND $cashback != ''){?>
	<div class="coupon_successful">
		<div class="coupon_message"><span>Referral Code</span>APPLIED!</div>
		<span class="coupon_details_message">Rs <?php echo $cashback ; ?> cash will be added to your Paytm wallet</span>
		<div class="referal_coupon_tc"><a style= "color:#9a7c54; cursor:pointer;"><u><i>*T&amp;C Applied.</i></u></a></div>
	</div>


<?php } ?>

<script>
function validate_payment(){
	var check = document.getElementById('terms').checked;
	if(check == true){
		$("#term-error").fadeOut();
		return true;
	}
	else{
		$("#term-error").fadeIn();
		return false;
	}
}
</script>