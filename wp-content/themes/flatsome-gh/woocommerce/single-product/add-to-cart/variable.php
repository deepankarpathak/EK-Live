<?php
/**
 * Variable product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $product, $post;

    $show_inquire_now = $product->get_attribute("show-inquire-form");
   
    
?>

<?php  do_action( 'woocommerce_before_add_to_cart_form' ); ?>
<script>
    jQuery(document).ready(function(){
       $('.see_fee').click(function(){
        $('.tabs li').removeClass('active');
         $('.fee_tab').addClass('active colortab');  
		 $('.entry-content').removeClass('active');
		 $('.entry-content').css('display', 'none');
          $('#tab-fee').addClass('panel entry-content active'); 
		  $('#tab-fee').css('display', 'block');
        }) ;
    });
</script>
<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">
    <script type="text/javascript">
        jQuery(document).ready(function (){
        jQuery('.single_popup_button').click(function(){ 	
	jQuery('#edu-enquiry-popup').css('display', 'block');
	jQuery('.edu-enquiry-bg').css('display', 'block');
 	});	
jQuery('.edu-enquiry-bg').click(function(){ 	
	jQuery('#edu-enquiry-popup').css('display', 'none');
	jQuery('.edu-enquiry-bg').css('display', 'none');
 	});	
	
jQuery('.bxslider').bxSlider({
				  auto: true,
				  autoControls: true
	});	
 });
        </script>
	<?php 
//        echo "<pre>";
//        print_r($available_variations);
////        echo count($available_variations);
//        echo "</pre>";
//        exit;
        
         if ( ! empty( $available_variations ) ) : ?>
        
            <?php if(count($available_variations)> 1){ ?>
		<table class="variations custom" cellspacing="0">
			<tbody>
				<?php $loop = 0; foreach ( $attributes as $name => $options ) : $loop++; ?>
					<tr>
						<td class="label"><label for="<?php echo sanitize_title($name); ?>"><?php echo wc_attribute_label( $name ); ?></label></td>
						<td class="value"><div class="select-wrapper"><select class="custom" id="<?php echo esc_attr( sanitize_title( $name ) ); ?>" name="attribute_<?php echo sanitize_title( $name ); ?>">
							<option value=""><?php echo __( 'Choose an option', 'woocommerce' ) ?>&hellip;</option>
							<?php
								if ( is_array( $options ) ) {

									if ( isset( $_REQUEST[ 'attribute_' . sanitize_title( $name ) ] ) ) {
										$selected_value = $_REQUEST[ 'attribute_' . sanitize_title( $name ) ];
									} elseif ( isset( $selected_attributes[ sanitize_title( $name ) ] ) ) {
										$selected_value = $selected_attributes[ sanitize_title( $name ) ];
									} else {
										$selected_value = '';
									}

									// Get terms if this is a taxonomy - ordered
									if ( taxonomy_exists( $name ) ) {

										$orderby = wc_attribute_orderby( $name );

										switch ( $orderby ) {
											case 'name' :
												$args = array( 'orderby' => 'name', 'hide_empty' => false, 'menu_order' => false );
											break;
											case 'id' :
												$args = array( 'orderby' => 'id', 'order' => 'ASC', 'menu_order' => false, 'hide_empty' => false );
											break;
											case 'menu_order' :
												$args = array( 'menu_order' => 'ASC', 'hide_empty' => false );
											break;
										}

										$terms = get_terms( $name, $args );

										foreach ( $terms as $term ) {
											if ( ! in_array( $term->slug, $options ) )
												continue;

											echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $term->slug ), false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
										}
									} else {

										foreach ( $options as $option ) {
											echo '<option value="' . esc_attr( sanitize_title( $option ) ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $option ), false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
										}

									}
								}
							?>
						</select></div> <?php
							if ( sizeof($attributes) == $loop )
								echo '<a class="reset_variations" href="#reset">' . __( 'Clear selection', 'woocommerce' ) . '</a>';
						?></td>
					</tr>
		        <?php endforeach;?>
			</tbody>
		</table>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<?php do_action( 'woocommerce_before_single_variation' ); ?>
        <?php
            echo '<div id="ses_de_gh" style="display:none;">'.$wp_session['ip'].' | '.$wp_session['country'].' | '.$wp_session['currency'].' | '.$wp_session['conversion_rate'].' | '.$wp_session['ip_type'].' | '.$wp_session['all_ip'].'</div>';
        ?>
    <div class="single_variation"> </div><span class="see_free_details"><a class="see_fee"> <?php echo "see fee details" ?></a></span>

		<div class="single_variation_wrap" style="display:none;">

			<!--<div class="variations_button">-->
				<?php woocommerce_quantity_input(); ?>

			<!--</div>-->

			<input type="hidden" name="add-to-cart" value="<?php echo $product->id; ?>" />
			<input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
			<input type="hidden" name="variation_id" value="" />

			<?php do_action( 'woocommerce_after_single_variation' ); ?>
		</div>
                <div class="variations_button">
                            
                       <?php 
                            $fee_type = $product->get_attribute("fee-type");
                       
                       if($show_inquire_now == 'No'  ){
                            
                        ?>
                    
				<button type="submit" class="single_add_to_cart_button button alt"><?php echo $product->single_add_to_cart_text(); ?></button>
                       <?php } else { 
                                
                           echo gh_inquire_now_feature();
                         
                       }
                       ?>

		</div>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
            <?php } else {  ?>
                <table class="variations custom" cellspacing="0">
                    <tbody>
                        <tr>
                            <td class="label">Fee Type</td>
                            <td class="value"> <div class="select-wrapper">
                                <select class="custom" id="<?php echo $available_variations[0]['variation_id'] ?>" name="attribute_pa_fee-type" >
                                    <option class="active" value ="<?php echo $available_variations[0]['attributes']['attribute_pa_fee-type']; ?>"><?php echo $available_variations[0]['attributes']['attribute_pa_fee-type']; ?></option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
    <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
        <?php
            echo '<div id="ses_de_gh" style="display:none;">'.$wp_session['ip'].' | '.$wp_session['country'].' | '.$wp_session['currency'].' | '.$wp_session['conversion_rate'].' | '.$wp_session['ip_type'].' | '.$wp_session['all_ip'].'</div>';
        ?>
                <div style = "float:left;font-size:16px;font-weight:normal;padding:15px 0;"><span class="price"><span class="amount"><?php echo $product->get_price_html(); ?></span></span></div> <span class="see_free_details"><a class="see_fee"> <?php echo "see fee details" ?></a></span>
                 
                <div class="single_variation_wrap" style="display:none;">

			<!--<div class="variations_button">-->
				<?php woocommerce_quantity_input(); ?>

			<!--</div>-->

			<input type="hidden" name="add-to-cart" value="<?php echo $product->id; ?>" />
			<input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
			<input type="hidden" name="variation_id" value="" />

			<?php do_action( 'woocommerce_after_single_variation' ); ?>
		</div>
                <?php $referral_cashback = $product->get_attribute("referral-cashback"); 
			//print_r($_COOKIE['referee']);
			if($referral_cashback){
				//print_r($_COOKIE['referee']);
				if(isset($_COOKIE['referee']) AND $_COOKIE['referee'] != '' ){
					//echo $product->get_price();
					//echo "<br>";
					$referrer = json_decode(stripslashes($_COOKIE['referee']), true);
					$effective_total = (int)$product->get_price() - (int)$referral_cashback; 
					//$_SESSION['cash_back'] = (int)$referral_cashback;
					//$_SESSION['ir_coupon_code'] = $referrer['code'];
					//$_SESSION['effective_total'] = $effective_total;
					//$_SESSION['referral_details'] = $referrer;
					?>
					<div class = "product_detail_referral"><span class="product_detail_cashback">Get Cashback</span>Effective Fee<b><?php echo ' Rs. '. number_format_i18n((int)$effective_total) ;?></b> <span class="referral_tc" title= "You and your friend get cash in your Paytm wallet with your referral code. With EduKart's Referral Program, one can earn cashback everytime their referral code is used." >&nbsp;&nbsp;&nbsp;</span><a style="color:#8a5926 !important;"><u><i>T&C</i></u></a></div>
					<?php
					
				}else{
					unset($_SESSION['cash_back']);
					unset($_SESSION['ir_coupon_code']);
					unset($_SESSION['effective_total']);
					unset($_SESSION['referral_details']);
		?>
		<div class = "product_detail_referral"><span class="product_detail_cashback">cashback</span>Share your referral code with friends & get <b><?php echo 'Rs.'. number_format_i18n((int)$referral_cashback) ;?></b> cashback <span class="referral_tc" title= "You and your friend get cash in your Paytm wallet with your referral code. With EduKart's Referral Program, one can earn cashback everytime their referral code is used." >&nbsp;&nbsp;&nbsp;</span><a style="color:#8a5926 !important; cursor:pointer;"><u><i>T&C</i></u></a></div>
		<?php }
			}


 ?>
                <div class="variations_button">
                            
                       <?php 
                            $fee_type = $product->get_attribute("fee-type");
                       
                       if($show_inquire_now == 'No'  ){
                            
                        ?>
                    
				<button type="submit" class="single_add_to_cart_button button alt"><?php echo $product->single_add_to_cart_text(); ?></button>
                       <?php } else { 
                                
                           echo gh_inquire_now_feature();
                         
                       }
                       ?>

		</div>
                 <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
            <?php } ?>
                 
	<?php  else : ?>


		<p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>

	<?php endif; 
        // do_action("woocommerce_single_product_summary");
        //echo $product->get_price_html();
          ?>
         

</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
