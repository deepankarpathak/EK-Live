<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $flatsome_opt;
?>
<script>
jQuery( document ).ready( function() {
	var disable_coupon_button = jQuery( 'input#apply_coupon').val();
	var disable_woo_coupon = jQuery( 'input#woo_coupon').val();
	if(disable_woo_coupon == undefined){
		disable_woo_coupon = '';
	}
	if(disable_coupon_button != '' || disable_woo_coupon != ''){
		jQuery('input[type="button"]').attr("disabled", "disabled");
		jQuery( 'input#coupon_code').attr("disabled", "disabled");
		jQuery('.alert-box.alert.animated.fadeIn.woocommerce-error').css('display','none');
		jQuery('.coupon_one_message').css('display','block');
		
	}	
       jQuery( 'input[type="button"]').click( function( ev ) {
        var code = jQuery( 'input#coupon_code').val();
 	var cb_amount = jQuery( 'input#cart_cashback_amount').val();
	var cart_total = jQuery( 'input#cart_subtotal').val();

        data = {
            action: 'referral_validation',
            coupon_code: code,
	    cashback_amount: cb_amount,
	    cart_subtotal:cart_total
        }

       jQuery.post( woocommerce_params.ajax_url, data, function( returned_data ) {

            if( returned_data == 'failed' ) {
		jQuery('input[type="submit"]').trigger('click');
            } else {
		var returnedData = JSON.parse(returned_data);
		location.reload();
		jQuery('tr.coupon_cashback td').html( returnedData.cashback);
		jQuery('tr.effecive_total td').html( returnedData.effectivetotal);
		//jQuery('').attr('disabled','disabled');
            }
        })
    }); 
	jQuery( '.remove_cashback, .woocommerce-remove-coupon ').click( function( ev ) {
        var code = '';
	var prod_id = jQuery( 'input#product_id').val();
        data = {
            action: 'remove_cashback',
            coupon_code: code,
	    product_id: prod_id,
        }
       jQuery.post( woocommerce_params.ajax_url, data, function( returned_data ) {

            if( returned_data == 'failed' ) {
		jQuery('input[type="submit"]').trigger('click');
            }
        })
    }); 
});


</script>

<?php 
$items = $woocommerce->cart->get_cart();
	$i =0;
        foreach($items as $item => $values) { 
            $_product = $values['data']->post; 
            $product_id[$i] = $_product->ID;
		$i++;
        } 
	
?>
<script type="text/javascript">
  window.vizLayer = {
    geo: "sg",
    account_id: "VIZVRM3503",
    vertical: "ecommerce",
    type: "shopping_cart",
pid1: <?php echo $product_id[0]; ?>, 
pid2: <?php echo $product_id[1]; ?>, 
pid3: <?php echo $product_id[2]; ?>, 
currency: <?php gh_get_local_currency_symbol(); ?>, 
cartval: <?php echo $woocommerce->cart->total; ?>
}; 

(function(){try{var viz = document.createElement("script"); viz.type = "text/javascript";viz.async = true; viz.src = ("https:" == document.location.protocol ?"https://in-tags.vizury.com" : "http://in-tags.vizury.com")+ "/analyze/pixel.php?account_id=vst";var s = document.getElementsByTagName("script")[0];s.parentNode.insertBefore(viz, s);viz.onload = function() {try {pixel.parse();} catch(i){}};viz.onreadystatechange = function() {if (viz.readyState == "complete" || viz.readyState == "loaded"){try {pixel.parse();}catch(i){}}};}catch(i){}})();

</script>

<?php if(isset($_GET['remove_coupon'])){ session_destroy(); } ?>

<!-- <div class="checkout-breadcrumb">
		<h1>
			<span class="title-cart"><?php _e('Applied Courses', 'flatsome'); ?></span>
                        <span class="icon-angle-right divider"></span>    
			<span class="title-checkout"><?php _e('Checkout details', 'flatsome'); ?></span>  
			<span class="icon-angle-right divider"></span>  
			<span class="title-thankyou"><?php _e('Enrollment Complete', 'flatsome'); ?></span>
		</h1>
</div> -->
<?php wc_print_notices(); ?>
<?php do_action( 'woocommerce_before_cart' ); ?>

<form action="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" method="post">
 <div class="row">
<div class="large-12 small-12 columns">

<?php do_action( 'woocommerce_before_cart_table' ); ?>
<div class="cart-wrapper custom-cart">
	<div class="clearfix">
		<div class="cart-desc"><img class="lock" src="../wp-content/themes/flatsome-gh/images/lock.png"> Safe and Secure Transaction Guarantee</div>
		<img class="edu-trust" src="../wp-content/themes/flatsome-gh/images/trust.png"> <img class="size-medium pay-opt" src="http://edukart.com/wp-content/uploads/2015/01/pay-option.jpg" alt="pay-option">
	</div>	
<div class="shop_table cart responsive" cellspacing="0">
	
	<div>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		
		<?php
			$ga = 0;

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

				?>
				<div class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
					<input type="hidden" value="<?php echo $product_id; ?>" name="product_id" id="product_id">

					<div class="product-thumbnail">
						<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', str_replace( array( 'http:', 'https:' ), '', $_product->get_image() ), $cart_item, $cart_item_key );

							if ( ! $_product->is_visible() )
								echo $thumbnail;
							else
								printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
						?>
					</div>

				<div class="pro-nm-pro">
					<div class="product-name">
						<?php
							if ( ! $_product->is_visible() )
								echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
							else
								echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title() ), $cart_item, $cart_item_key );

               				// Backorder notification
               				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
               					echo '<p class="backorder_notification">' . __( 'Available on backorder', 'woocommerce' ) . '</p>';
						?>
						<div class="describtion">Narsee Monjee Institute of Management Studies</div>
					</div>

					<div class="product-provider">
					</div>
				</div>

				<div class="pro-totl-rem clearfix">
					<div class="product-subtotal">
						<?php
							echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
							// Meta data
							echo WC()->cart->get_item_data( $cart_item );
						?>
					</div>

					<div class="remove-product">
						<?php
							//echo $_product->get_attribute("referral-cashback");
							//echo WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] );

								$ga = $ga + (int)$_product->get_attribute("referral-cashback");
								echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s"><span class="icon-close"></span></a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key );
							?>
					</div>
					<input type="hidden" id="cart_subtotal" value = "<?php echo  $woocommerce->cart->get_cart_total(); ?>">
				</div>	
			</div>
				
				<?php
			}
		}

//echo $ga;
		?>

<?php 
if(isset($_COOKIE['referee']) AND $_COOKIE['referee'] != '' ){
	$referrer = json_decode(stripslashes($_COOKIE['referee']), true);
	//echo (int)str_replace('Rs.', '', str_replace(',', '', $woocommerce->cart->get_cart_total())) ."<br>";
	//echo 'cb amount= '.$ga;
	if((int)str_replace('Rs.', '', str_replace(',', '', $woocommerce->cart->get_cart_total())) > (int)$ga){
		$effecive_total = (int)str_replace('Rs.', '', str_replace(',', '', $woocommerce->cart->get_cart_total())) - (int)$ga;
	}
	$_SESSION['cash_back'] = (int)$ga;
	$_SESSION['ir_coupon_code'] = $referrer['code'];
	$_SESSION['effective_total'] = $effecive_total;
	$_SESSION['referral_details'] = $referrer;
}

?>

<input type="hidden" id="cart_cashback_amount" value = "<?php echo $ga; ?>">
<?php
		do_action( 'woocommerce_cart_contents' );
		?>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>

	</div>
</div>



<?php do_action('woocommerce_cart_collaterals'); ?>


</div><!-- .cart-wrapper -->
</div><!-- .large-9 -->
</div><!-- .row -->

<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<?php do_action( 'woocommerce_after_cart' ); ?>


