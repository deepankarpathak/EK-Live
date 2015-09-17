<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $flatsome_opt;

get_header('shop'); ?>
<?php
echo '<div id="ses_de_gh" style="display:none;">'.$wp_session['ip'].' | '.$wp_session['country'].' | '.$wp_session['currency'].' | '.$wp_session['conversion_rate'].' | '.$wp_session['ip_type'].' | '.$wp_session['all_ip'].'</div>';
?>
<?php if(@$_GET['review_done'] == 'yes'){ ?>
	<div class="woocommerce-message message-success">
		<?php _e( 'Your Review has been saved, After approval it will display.', 'woocommerce' ); ?>
	</div>
<?php } ?>

<div class="row product-page">
<div class="large-12 columns">

	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 */
		do_action('woocommerce_before_main_content');
	?>

		<?php while ( have_posts() ) : the_post(); ?>

		<?php 
		if($flatsome_opt['product_sidebar'] == "right_sidebar") {
			woocommerce_get_template_part( 'content', 'single-product-rightcol'); 
		} else if($flatsome_opt['product_sidebar'] == "left_sidebar") {
			woocommerce_get_template_part( 'content', 'single-product-leftcol'); 
		} else {
			woocommerce_get_template_part( 'content', 'single-product' ); 
		}
		?>

		<?php endwhile; // end of the loop. ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('woocommerce_after_main_content');
	?>


</div><!-- end large-12 -->
</div><!-- end row product-page -->

<?php get_footer('shop'); ?>
