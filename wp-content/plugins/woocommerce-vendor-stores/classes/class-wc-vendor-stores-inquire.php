<?php

/**

Handles public product inquiries


Copyright (c) 2013 - IgniteWoo.com

Portions Copyright (c) 2011 - WooThemes

*/

if ( ! defined( 'ABSPATH' ) ) exit;

class IgniteWoo_Vendor_Stores_Inquire {


	public function __construct() { 

		add_action( 'woocommerce_product_tabs', array(&$this, 'product_enquiry_tab'), 25);
		
		add_action( 'woocommerce_product_tab_panels', array(&$this, 'product_enquiry_tab_panel'), 25);
		
		add_action( 'wp_ajax_woocommerce_product_enquiry_post', array(&$this, 'process_form'));
		
		add_action( 'wp_ajax_nopriv_woocommerce_product_enquiry_post', array(&$this, 'process_form'));

		add_action( 'woocommerce_product_options_general_product_data', array(&$this, 'write_panel'));
		
		add_action( 'woocommerce_process_product_meta', array(&$this, 'write_panel_save'));
		
	} 

	
	function product_enquiry_tab( $tabs = array() ) {
		global $post, $ignitewoo_vendors;
		
		if (get_post_meta($post->ID, 'woocommerce_disable_product_enquiry', true)=='yes') return;
		
		$title = $ignitewoo_vendors->settings['inquire_tab_title'];
		
		if ( empty( $title ) )
			$title = __( 'Ask a Question', 'ignitewoo_vendor_stores' );
			
		$tabs['enquiry'] = array(
			'title' => $title,
			'priority' => 25,
			'callback' => array( $this, 'product_enquiry_tab_panel' )
		);

		return $tabs;
	

	}
	
	
	function product_enquiry_tab_panel() {
		global $post, $woocommerce, $ignitewoo_vendors;

		if ( 'yes' == get_post_meta( $post->ID, 'woocommerce_disable_product_enquiry', true ) ) 
			return;
			
		// Recaptcha keys
		$publickey = $ignitewoo_vendors->settings['inquire_pub_key'];
					
		$privatekey = $ignitewoo_vendors->settings['inquire_pvt_key'];
		
		if ( empty( $publickey ) || empty( $privatekey ) ) {
			?>
		
			<div class="panel" id="tab-enquiry">	
			
				<p style="color:#cf0000; font-weight:bold;">
				
				<?php _e( 'The ReCaptcha public or private key is not set', 'ignitewoo_vendor_stores' )?>
			
				</p>
			</div>
		
			<?php
			
			return;
		
		}
		
		if ( is_user_logged_in() )
			$current_user = get_user_by( 'id', get_current_user_id() );
	
		?>
		

		
			<form action="" method="post" id="product_enquiry_form">
				
				<?php do_action( 'ignitewoo_product_enquiry_before_form' ); ?>
				
				<p class="form-row form-row-first">
					<label for="product_enquiry_name"><?php _e( 'Name', 'ignitewoo_vendor_stores' ); ?></label>
					<input type="text" class="input-text" name="product_enquiry_name" id="product_enquiry_name" placeholder="<?php _e( 'Your name', 'ignitewoo_vendor_stores' ); ?>" value="<?php if (isset($current_user)) echo $current_user->user_nicename; ?>" />
				</p>
				
				<p class="form-row form-row-last">
				
					<label for="product_enquiry_email"
						><?php _e( 'Email address', 'ignitewoo_vendor_stores' ); ?>
					</label>
				
					<input type="text" class="input-text" name="product_enquiry_email" id="product_enquiry_email" placeholder="<?php _e( 'Your email address', 'ignitewoo_vendor_stores' ); ?>" value="<?php if (isset($current_user)) echo $current_user->user_email; ?>" />
				</p>
				
				<div class="clear"></div>
				
				<?php do_action( 'igintewoo_product_enquiry_before_message' ); ?>
				
				<p class="form-row notes">
					<label for="product_enquiry_message"><?php _e( 'Comments or Questions', 'ignitewoo_vendor_stores' ); ?></label>
					<textarea class="input-text" name="product_enquiry_message" id="product_enquiry_message" rows="5" cols="20" placeholder="<?php _e( 'What would you like to know?', 'ignitewoo_vendor_stores' ); ?>"></textarea>
				</p>
				
				<?php do_action( 'ignitewoo_product_enquiry_after_message' ); ?>
				
				<div class="clear"></div>
				
				<?php

					if ( $publickey && $privatekey ) :
					
						if ( !function_exists( 'recaptcha_get_html' ) ) 
							require_once( $ignitewoo_vendors->plugin_dir . '/classes/recaptchalib.php' );
						
						?>
						
						<div class="form-row notes">
						
							<script type="text/javascript">
								var RecaptchaOptions = {
									theme : "clean"
								};
							</script>
							
							<?php echo recaptcha_get_html($publickey); ?>
						</div>
						
						<div class="clear"></div>
						
						<?php
						
					endif;
				?>
				
				<p>
					<input type="hidden" name="product_id" value="<?php echo $post->ID; ?>" />
					
					<input type="submit" id="send_product_enquiry" value="<?php _e( 'Send', 'ignitewoo_vendor_stores' ); ?>" class="button" />
				</p>
				
				<?php do_action( 'ignitewoo_product_enquiry_after_form' ); ?>
				
			</form>
			
			<script type="text/javascript">
				
				jQuery(function(){
					jQuery( '#send_product_enquiry').click(function(){
						
						// Remove errors
						jQuery( '.product_enquiry_result').remove();
						
						// Required fields
						if (!jQuery( '#product_enquiry_name').val()) {
							jQuery( '#product_enquiry_form').before( '<p style="display:none;" class="product_enquiry_result woocommerce_error"><?php _e( 'Enter your name', 'ignitewoo_vendor_stores' ); ?></p>' );
							jQuery( '.product_enquiry_result').fadeIn();
							return false;
						}
						
						if (!jQuery( '#product_enquiry_email').val()) {
							jQuery( '#product_enquiry_form').before( '<p style="display:none;" class="product_enquiry_result woocommerce_error"><?php _e( 'Enter your email address', 'ignitewoo_vendor_stores' ); ?></p>' );
							jQuery( '.product_enquiry_result').fadeIn();
							return false;
						}
						
						if (!jQuery( '#product_enquiry_message').val()) {
							jQuery( '#product_enquiry_form').before( '<p style="display:none;" class="product_enquiry_result woocommerce_error"><?php _e( 'Please enter a comment', 'ignitewoo_vendor_stores' ); ?></p>' );
							jQuery( '.product_enquiry_result').fadeIn();
							return false;
						}

						// Block elements
						jQuery( '#product_enquiry_form').block({message: null, overlayCSS: {background: '#fff url(<?php echo $woocommerce->plugin_url(); ?>/assets/images/ajax-loader.gif) no-repeat center', opacity: 0.6}});
						
						// AJAX post
						var data = {
							action: 			'woocommerce_product_enquiry_post',
							security: 			'<?php echo wp_create_nonce("product-enquiry-post"); ?>',
							post_data:			jQuery( '#product_enquiry_form').serialize()
						};
							
						jQuery.post( '<?php echo str_replace( array( 'https:', 'http:'), '', admin_url( 'admin-ajax.php' ) ); ?>', data, function( response ) {									
							if ( response == 'SUCCESS' ) {
								
								jQuery( '#product_enquiry_form').before( '<p style="display:none;" class="product_enquiry_result woocommerce_message vendor_store_message"><?php echo apply_filters( 'product_enquiry_success_message', __( 'Comments sent!', 'ignitewoo_vendor_stores')); ?></p>' );
								
								jQuery( '#product_enquiry_form textarea').val( '' );
								
							} else {
								
								if (window.Recaptcha) {
									Recaptcha.reload();
								}
								
								jQuery( '#product_enquiry_form').before( '<p style="display:none;color:#cf0000;font-weight:bold" class="product_enquiry_result woocommerce_error vendor_store_error">' + response + '</p>' );
								
							}
							
							jQuery( '#product_enquiry_form').unblock();
							
							jQuery( '.product_enquiry_result').fadeIn();
						
						});
						
						return false;
						
					});
				});
				
			</script>

		<?php
	}
	
	
	function process_form() {
		global $woocommerce, $ignitewoo_vendors;
		
		check_ajax_referer( 'product-enquiry-post', 'security' );
		
		do_action( 'ignitewoo_product_enquiry_process_form' );
		
		$post_data = array();
		
		parse_str($_POST['post_data'], $post_data);
		
		$name = isset($post_data['product_enquiry_name']) ? woocommerce_clean($post_data['product_enquiry_name']) : '';
		
		$email = isset($post_data['product_enquiry_email']) ? woocommerce_clean($post_data['product_enquiry_email']) : '';
		
		$enquiry = isset( $post_data['product_enquiry_message'] ) ? strip_tags( $post_data['product_enquiry_message'] ) : '';
		
		if ( empty( $enquiry ) ) { 
		
			_e( 'Enter a message to send', 'ignitewoo_vendor_stores' );
			
			die();
		}
		
		$product_id = isset($post_data['product_id']) ? (int) $post_data['product_id'] : 0;
		
		
		if ( !$product_id ) { 
		
			_e( 'Invalid product', 'ignitewoo_vendor_stores' );
			
			die();
		}
		
		if ( !is_email( $email ) ) {
		
			_e( 'Please enter a valid email address', 'ignitewoo_vendor_stores' );
			
			die();
			
		}
		
		$product = get_post( $product_id );
		
		if ( empty( $product ) || is_wp_error( $product ) ) { 
		
			_e( 'Product processing error while sending message', 'ignitewoo_vendor_stores' );
			
			die;
		}
		
		$user = new WP_User( $product->post_author );
		
		if ( empty( $user ) || is_wp_error( $user ) ) { 
		
			_e( 'Error locating recipient while sending message', 'ignitewoo_vendor_stores' );
			
			die;
		}

		$send_to = $user->data->user_email; 
		
		if ( empty( $send_to ) || is_wp_error( $send_to ) ) { 
		
			_e( 'Error locating recipient email while sending message', 'ignitewoo_vendor_stores' );
			
			die;
		}

		
		// Recaptcha
		$publickey = $ignitewoo_vendors->settings['inquire_pub_key'];
					
		$privatekey = $ignitewoo_vendors->settings['inquire_pvt_key'];
		
		if ( $publickey && $privatekey ) {
		
			if ( !function_exists( 'recaptcha_get_html')) 
				require_once( $ignitewoo_vendors->plugin_dir . '/classes/recaptchalib.php' );
			
			$resp = recaptcha_check_answer($privatekey, $_SERVER['REMOTE_ADDR'], 			$post_data['recaptcha_challenge_field'], $post_data['recaptcha_response_field']);

			if ( empty( $resp->is_valid ) || true !== $resp->is_valid ) { 
				
				_e( 'Please double check the captcha field.', 'ignitewoo_vendor_stores' );
				
				die();
			}
		
		}
		
		$sitename = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		
		$subject = apply_filters( 'ignitewoo_product_enquiry_email_subject', sprintf( __( '[ %s ] Product Enquiry - %s', 'ignitewoo_vendor_stores'), $sitename, $product->post_title ) );
		
		$message = array();
		
		$message['greet'] = __( 'Hi, ', 'ignitewoo_vendor_stores' );
		
		$message['space_1'] = '';
		
		$message['intro'] = sprintf(__("You have been contacted by %s (%s) about %s (%s). Their comments are as follows: ", 'ignitewoo_vendor_stores'), $name, $email, $product->post_title, get_permalink($product->ID));
		
		$message['space_2'] = '';
		
		$message['message'] = $enquiry;
		
		$message['space_3'] = '';
		
		$message['space_4'] = '';
		
		$message['sig'] = sprintf( __( 'This is an automated message delivered by the contact form on your product page at %s. To disable these message edit your product and turn off the contact form feature.', 'ignitewoo_vendor_stores' ), $sitename ); 
		
		$message = implode( "\n", apply_filters( 'ignitewoo_product_enquiry_email_message', $message ) );
		
		$headers = 'From: ' . $name . ' <' . $email . '>' . "\r\n";
		
		$headers .= 'Reply-to: ' . $name . ' <' . $email . '>' . "\r\n";
		
		if ( wp_mail( $send_to, $subject, $message, $headers ) ) 
			echo 'SUCCESS'; 
		else 
			echo 'Error sending message';

		die();
	}

	
	function write_panel() {
		?>
		
		<div class="options_group">
		
			<?php woocommerce_wp_checkbox( array( 'id' => 'ignitewoo_disable_product_enquiry', 'label' => __( 'Disable contact form', 'ignitewoo_vendor_stores') ) ) ?>
		
		</div>
		
		<?php 
	}
	
	
	function write_panel_save( $post_id ) {
		$woocommerce_disable_product_enquiry = isset($_POST['woocommerce_disable_product_enquiry']) ? 'yes' : 'no';
		
		update_post_meta($post_id, 'ignitewoo_disable_product_enquiry', $woocommerce_disable_product_enquiry);
		
	}
	
}

$ignitewoo_vendor_stores_inquire = new IgniteWoo_Vendor_Stores_Inquire();
