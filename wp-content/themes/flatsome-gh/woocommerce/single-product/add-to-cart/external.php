<?php
/**
 * External product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

global $product;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
<p class="price small" style="margin:10px, 0;"><?php echo $product->get_price_html(); ?></p>
<?php if ($product_url == "#"){
    
    echo gh_inquire_now_feature();
} else{
    ?>
<p class="cart">
	<a href="<?php echo esc_url( $product_url ); ?>" rel="nofollow" class="single_add_to_cart_button button alt"><?php echo $button_text; ?></a>
</p>
<?php } ?>
<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>