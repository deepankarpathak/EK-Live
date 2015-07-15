<?php 

/**
Copyright (c) 2013 - IgniteWoo.com
*/

if ( ! defined( 'ABSPATH' ) ) exit;

class IgniteWoo_Vendor_Store_Admin { 


	function __construct() { 
		global $ignitewoo_vendors;

		add_action( 'init', array( &$this, 'maybe_save_store_settings' ), 1 );
		
		// Custom CSS injection
		add_action( 'admin_head', array( &$this, 'admin_head' ) );
		
		// Admin notice display for store updates
		add_action( 'admin_notices', array( $this, 'store_admin_notices' ) );
		
		// Admin warning for manually creating / editing stores
		add_action( 'admin_notices', array( $this, 'warning_admin_notices' ) );
		
		// Add links under plugin on WP plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $ignitewoo_vendors->file ), array( $this, 'add_settings_link' ) );
		
		// Remove admin menus
		add_action( 'admin_menu', array(&$this, 'remove_admin_menu_pages' ) );
		
		// Add settings to WooCommerce
		//add_filter( 'woocommerce_general_settings', array( $this, 'manage_settings' ) );

		add_action('pre_get_posts', array( &$this, 'restrict_media_library') );

		// Only show vendor's products in dashboard
		add_filter( 'request', array( &$this, 'filter_product_list' ) );
		add_action( 'current_screen', array( &$this, 'restrict_products' ) );

		// Coupons
		add_filter( 'request', array( &$this, 'filter_coupon_list' ) );
		add_action( 'current_screen', array( $this, 'restrict_coupons' ) );
		add_filter( 'woocommerce_json_search_found_products', array( &$this, 'json_filter_products' ), 9999, 1 );
		
		
		if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) ) { 
				
			add_action( 'admin_head', array( &$this, 'admin_head_coupons' ) );
			
			add_filter( 'woocommerce_coupon_discount_types', array( &$this, 'coupon_discount_types' ), 9999999, 1 );
			add_action( 'add_meta_boxes', array( &$this, 'coupons_add_meta_box' ) );
		}
		
		// Handle hiding tabs etc
		add_filter( 'admin_head', array( &$this, 'maybe_filter_types' ), 99999999, 2 );
		
		// Handle order type and option filtering
		add_filter( 'product_type_selector', array( &$this, 'product_type_selector' ), 9999999, 2 );
		//add_filter( 'product_type_options', array( &$this, 'product_type_options' ), 99, 2 );
		
		// Add columns to vendor taxonomy
		add_filter( 'manage_edit-' . $ignitewoo_vendors->token . '_columns', array( $this, 'vendor_cat_columns' ) );
		add_filter( 'manage_' . $ignitewoo_vendors->token . '_custom_column', array( $this, 'vendor_cat_column' ), 10, 3 );
		
		// Add fields to taxonomy
		add_action( $ignitewoo_vendors->token . '_add_form_fields' , array( $this , 'add_vendor_fields' ) , 1 , 1 );
		add_action( $ignitewoo_vendors->token . '_edit_form_fields' , array( $this , 'edit_vendor_fields' ) , 1 , 1 );
		add_action( 'edited_' . $ignitewoo_vendors->token , array( $this , 'save_vendor_fields' ) , 10 , 2 );
		add_action( 'created_' . $ignitewoo_vendors->token , array( $this , 'save_vendor_fields' ) , 10 , 2 );
		
		// Add pages to menu
		add_action( 'admin_menu', array( $this, 'vendor_menu_items' ) );
		
		// Handle saving posts for vendors
		add_action( 'save_post', array( $this, 'add_vendor_to_product' ) );
		
		// Handle email notices 
		add_action( 'save_post', array( $this, 'send_new_post_notice' ), 15, 1 );
				
		// Add reports to WC
		add_filter( 'woocommerce_reports_charts', array( $this, 'add_reports' ) );

		if ( 'yes' == $ignitewoo_vendors->settings['paypal_required'] ) 
			add_filter( 'ign_other_config_requirements', array( &$this, 'paypal_required' ) );
			
	}

	
	function admin_head() { 
		global $typenow, $ignitewoo_vendors;
		
		if ( 'product' != $typenow || !ign_vendor_access() )
			return;
			
		if ( empty( $ignitewoo_vendors->settings['editor_css'] ) )
			return;
	
		?>

		<style><?php echo $ignitewoo_vendors->settings['editor_css'] ?></style>
		
		<?php
		
	}
	
	function paypal_required() { 
	
		return __( 'and PayPal email address' );
	
	}

	public function add_settings_link( $links ) {
		global $ignitewoo_vendors;
		
		$custom_links[] = '<a href="' . admin_url( 'edit-tags.php?taxonomy=shop_vendor&post_type=product' ) . '">' . __( 'Stores', 'ignitewoo_vendor_stores' ) . '</a>';
		
		$custom_links[] = '<a href="' . admin_url( 'edit.php?post_type=' ) . $ignitewoo_vendors->commissions->token .'">' . __( 'Commissions', 'ignitewoo_vendor_stores' ) . '</a>';
		return array_merge( $links, $custom_links );
	}
	
	
	function remove_admin_menu_pages() { 

		remove_menu_page('profile.php');  
	}
	
	
	function product_type_selector( $types = array(), $current_type = '' ) { 
		global $ignitewoo_vendors;
		
		if ( empty( $types ) )
			return $types; 
			
		if ( !current_user_can( 'vendor' ) )
			return $types;
			
		if ( empty( $ignitewoo_vendors->settings['allowed_product_types'] ) )
			return $types;
			
		foreach( $types as $key => $type ) 
			if ( !in_array( $key, $ignitewoo_vendors->settings['allowed_product_types'] ) )
				unset( $types[ $key ] );
	
		return $types; 
		
	}
	


	function restrict_media_library( $wp_query_obj ) {
		global $current_user, $pagenow;

		if ( !is_a( $current_user, 'WP_User') )
			return;
			
		if ( 'admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments' )
			return;

		if ( current_user_can( 'vendor' ) )
			$wp_query_obj->set('author', $current_user->ID );

	}
	
	
	
	/**
	* Only show vendor's products
	*/
	public function filter_product_list( $request ) {
		global $typenow, $current_user, $ignitewoo_vendors;

		if ( !ign_vendor_access() )
			return $request; 
		
		if ( 'product' != $typenow )
			return $request;
			
		wp_get_current_user();

		if ( isset( $current_user->ID ) && $current_user->ID > 0 ) {
		
			$vendor = ign_get_user_vendor( $current_user->ID );

			if ( !empty( $vendor ) && $vendor->slug && strlen( $vendor->slug ) > 0 )
				$request[ $ignitewoo_vendors->token ] = $vendor->slug;
			
		}
	
		return $request;
	}

	
	/**
	* Restrict vendors from editing other vendors' products
	*/
	public function restrict_products() {
		global $typenow, $pagenow, $current_user;

		if ( !ign_vendor_access() )
			return;

		if ( $pagenow != 'post.php' || $typenow != 'product' )
			return;

		if ( isset( $_POST['post_ID'] ) ) 
			return;
			
		wp_get_current_user();
		
		$show_product = false;

		if ( isset( $current_user->ID ) && $current_user->ID > 0 ) {
		
			$vendors = ign_get_product_vendors( $_GET['post'] );
			
			if ( isset( $vendors ) && is_array( $vendors ) ) {
			
				foreach( $vendors as $vendor ) {
				
					$show_product = ign_is_vendor_admin( $vendor->ID, $current_user->ID );
					
					if ( $show_product ) 
						break;
				}
			}
		}

		if ( ! $show_product )
			wp_die( sprintf( __( 'You do not have permission to edit this product. %1$sClick here to view and edit your products%2$s.', 'ignitewoo_vendor_stores' ), '<a href="' . esc_url( 'edit.php?post_type=product' ) . '">', '</a>' ) );

		return;
		
	}
	
	
	public function filter_coupon_list( $request ) {
		global $typenow, $current_user, $ignitewoo_vendors;

		if ( !ign_vendor_access() )
			return $request; 
		
		if ( 'shop_coupon' != $typenow )
			return $request;
			
		// Only works in WC 2.1 or newer
		if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) )
			return $request; 
			
		wp_get_current_user();

		if ( isset( $current_user->ID ) && $current_user->ID > 0 ) {
		
			$vendor = ign_get_user_vendor( $current_user->ID );

			if ( !empty( $vendor ) && $vendor->slug && strlen( $vendor->slug ) > 0 )
				$request[ 'author' ] = $current_user->ID;
			
		}

		return $request;
	}
	
	public function restrict_coupons() {
		global $typenow, $pagenow, $current_user;

		if ( !ign_vendor_access() )
			return;

		if ( $pagenow != 'post.php' || $typenow != 'shop_coupon' )
			return;

		if ( isset( $_POST['post_ID'] ) ) 
			return;
			
		wp_get_current_user();
		
		$show_coupon = false;

		if ( isset( $current_user->ID ) && $current_user->ID > 0 ) {
		
			$is_vendor_owner = ign_get_coupon_vendor( $_GET['post'] );

			if ( $is_vendor_owner ) {
			
				$show_coupon = true;
			}
		}

		if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) )
			$show_coupon = false;
			
		if ( ! $show_coupon )
			wp_die( sprintf( __( 'You do not have permission to edit this coupon. %1$sClick here to view and edit your coupons%2$s.', 'ignitewoo_vendor_stores' ), '<a href="' . esc_url( 'edit.php?post_type=product' ) . '">', '</a>' ) );

		return;
		
	}
	
	function json_filter_products( $products = array() ) { 
		
		if ( empty( $products ) )
			return $products;
			
		foreach( $products as $k => $p ) { 
		
			$is_vendor_owner = ign_get_coupon_vendor( $k );
			
			if ( !$is_vendor_owner ) 
				unset( $products[ $k ] );
		
		}
		
		return $products;
	}
	
	function coupons_add_meta_box() { 

		if ( !current_user_can( 'administrator' ) && !current_user_can( 'shop_manager' ) )
			return;
			
		add_meta_box('authordiv', __('Author'), array( &$this, 'post_author_meta_box' ), 'shop_coupon', 'normal', 'core');
	}

	function post_author_meta_box($post) {
		global $user_ID;
		
		$users = array();
		
		// get list of all authors plus vendors
		$users = get_users( array( 'role' => 'shop_manager' ) );
		$users = array_merge( $users, get_users( array( 'role' => 'administrator' ) ) );
		$users = array_merge( $users, get_users( array( 'role' => 'vendor' ) ) );

		foreach( $users as $u ) 
			$list[] = $u->data->ID;

		?>
		<label class="screen-reader-text" for="post_author_override"><?php _e('Author'); ?></label>
		<?php
			wp_dropdown_users( array(
				'include' =>  $list,
				'name' => 'post_author_override',
				'selected' => empty($post->ID) ? $user_ID : $post->post_author,
				'include_selected' => true
			) );
	}

	
	function coupon_discount_types( $types = array() ) { 
		
		if ( current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) )
			return $types; 
			
		if ( empty( $types ) )
			return $types;
	
		$types = array(
			'fixed_product'   => __( 'Product Discount', 'woocommerce' ),
			'percent_product' => __( 'Product % Discount', 'woocommerce' )
		);
		
		return $types;
		
	}
		
		
	function admin_head_coupons() {
		global $ignitewoo_vendors, $typenow;
		
		if ( 'shop_coupon' != $typenow || !ign_vendor_access() )
			return;
			
		if ( !ign_vendor_access() )
			return;
			
		?>
		
		<style>
		.free_shipping_field, .apply_before_tax_field, .individual_use_field, .exclude_sale_items_field, .minimum_amount_field, .usage_limit_field,#usage_restriction_coupon_data {
			display:none;
		}
		</style>
		
		<script>
		jQuery( document ).ready( function( $ ) { 
			$( '.free_shipping_field, .apply_before_tax_field, .individual_use_field, .exclude_sale_items_field, .minimum_amount_field, .usage_limit_field ' ).remove();
			$( '#usage_restriction_coupon_data .options_group:nth-child(3)').remove();
			$( '#usage_restriction_coupon_data' ).css( 'display', 'block' );
		})
		</script>
		<?php
	}
	
	/*
	function product_type_options( $options ) { 
	
		if ( empty( $options ) )
			return $options; 
			
		if ( !current_user_can( 'vendor' ) )
			return $options;
			
		if ( empty( $ignitewoo_vendors->settings['allowed_product_options'] ) )
			return $options;
			
		foreach( $options as $key => $option ) 
			if ( !in_array( $key, $ignitewoo_vendors->settings['allowed_product_types'] ) )
				unset( $options[ $key ] );
	
		return $options; 
	
	}
	*/
	
	
	/**
	* Add / remove items to dashboard menu
	*/
	public function vendor_menu_items() {
		global $pagenow;
		
		if ( ign_vendor_access() ) {
		
			if ( 'upload.php' == $pagenow ) {
				wp_redirect( admin_url( 'edit.php?post_type=product' ) );
				exit;
			}
						
			add_menu_page( __( 'Store Details', 'ignitewoo_vendor_stores' ), __( 'Store Details', 'ignitewoo_vendor_stores' ), 'edit_products', 'vendor_details', array( $this, 'vendor_details_page' ) );
			
			remove_menu_page( 'upload.php' );
			
		}
	}
	
	function maybe_filter_types() {
		global $ignitewoo_vendors, $typenow;
		
		if ( 'product' != $typenow || !ign_vendor_access() )
			return;
			
		$css = $js = '';
		
		$hide_product_panels = (array) $ignitewoo_vendors->settings['hide_product_panels'];

		$hide_product_misc = (array) $ignitewoo_vendors->settings['hide_product_misc'];

		$product_type_opts = (array) $ignitewoo_vendors->settings['hide_product_type_options'];
		
		foreach ( $hide_product_panels as $key => $value ) {
		
			if ( false !== strpos( $value, 'attributes' ) )
				$css .= '.attribute_tab { display: none !important; }';
				
			if ( $value ) $css .= '.' . $value . '_tab { display:none !important; }';
		}

		foreach ( $product_type_opts as $key => $value ) {
			if ( $value ) $css .= '.variable_is_' . $value . ' { display:none !important; }';
			if ( $value ) $css .= '._' . $value . ' { display:none !important; }';
			if ( $value ) $js .= 'jQuery( document ).ready( function() { jQuery( "' . sprintf( '.variable_is_%s', $value ) . '").parent().remove() } );' . "\n";
			if ( $value ) $js .= 'jQuery( document ).ready( function() { jQuery( "' . sprintf( '#_%s', $value ) . '").parent().remove() } );' . "\n";
		}

		if ( !empty( $hide_product_misc ) && in_array( 'taxes', $hide_product_misc ) ) {
			$css .= '.form-field._tax_status_field, .form-field._tax_class_field{display:none !important;}';
			$css .= '.form-field #_tax_status, .form-field #_tax_class{display:none !important;}';
		}
		
		foreach ( $hide_product_misc as $key => $value ) {
			if ( $value ) $css .= sprintf( '._%s_field{display:none !important;}', $value );
			if ( $value ) $css .= sprintf( '.inside .%s{display:none !important;}', $value );
		}

		echo '<style>' . $css . '</style>';

		echo '<script>' . $js . '</script>';
		
	}

	
	function maybe_save_store_settings() { 
		global $user_ID, $ignitewoo_vendors;
	
		$vendor = ign_get_user_vendor();
		
		$title = '';
		
		$description = '';
		
		$error = false;
		
		$res = false;
		
		if ( !empty( $vendor->ID ) ) { 
		
			// Taxonomy meta data
			$vendor_data = get_option( $ignitewoo_vendors->token . '_' . $vendor->ID );
			
			$vendor_id = $vendor->ID;
			
			// Taxonomy info
			$vendor_info = ign_get_vendor( $vendor->ID );
			
			$title = $vendor_info->title;
			
			$description = $vendor_info->description;
			
		} else { 
		
			$vendor_id = 0;
		
		}

		if ( isset( $_POST['update_vendor'] ) && !empty( $vendor_id ) ) {
		
			if ( ! wp_verify_nonce( $_POST['vendor_update_nonce'], 'vendor_update' ) ) {
			
				wp_die( __( 'Security verification error', 'ignitewoo_vendor_stores' ) );
				
			}
			
			$paypal_address = isset(  $_POST[ 'ignitewoo_vendor_stores_paypal_address' ] ) ? $_POST[ 'ignitewoo_vendor_stores_paypal_address' ] : '';
			
			$vendor_data['paypal_email'] = $paypal_address;
			
			$title = strip_tags( $_POST[ 'ignitewoo_vendor_stores_name' ] );
			
			if ( 'yes' != $ignitewoo_vendors->settings['allow_vendor_html' ] ) 
				$desc = strip_tags( $_POST[ 'ignitewoo_vendor_stores_description'] );
			else
				$desc = $_POST[ 'ignitewoo_vendor_stores_description'];
			
			// For safety - no scripts allowed
			$desc = str_replace( '<script', '<!--script', $desc );
			$desc = str_replace( '</script>', '</script-->', $desc );
			
			$vendor_data = apply_filters( 'ignitewoo_vendor_stores_vendor_settings_save', $vendor_data );

			update_option( $ignitewoo_vendors->token . '_' . $vendor->ID, $vendor_data );

			// Store info
			$args = array(
				'name' => $title,
				'description' => $desc,
			);
			
			if ( !empty( $args['name'] ) )
				$args['slug'] = sanitize_title( $args['name'] );

			// Disables Kses only for textarea saves
			foreach ( array( 'pre_term_description' ) as $filter) {
				remove_filter($filter, 'wp_filter_kses');
			}

			// Disables Kses only for textarea admin displays
			foreach ( array( 'term_description' ) as $filter) {
				remove_filter( $filter, 'wp_kses_data');
			}
			
			$store = wp_update_term( $vendor->ID, $ignitewoo_vendors->token, $args );

			$error = is_wp_error( $store );
			
			if ( isset( $_POST['product_cat_thumbnail_id'] ) )
				update_woocommerce_term_meta( $vendor->ID, 'thumbnail_id', absint( $_POST['product_cat_thumbnail_id'] ) );

			if ( empty( $paypal_address ) && 'yes' == $ignitewoo_vendors->settings['paypal_required'] ) { 
			
				$url = add_query_arg( array( 'store_setup' => 'true' ) );
				
				$url = add_query_arg( array( 'updated' => 'true' ), $url ); 
			
				wp_safe_redirect( $url );
			
				die;
			
			}
			
			
			if ( empty( $error ) ) { 

				$url = remove_query_arg( 'store_setup' );
				
				$url = add_query_arg( array( 'updated' => 'true' ), $url ); 

				wp_safe_redirect( $url );
			
				die;
				
			}
			
			$new_store = $store;
			
		}
		
		// Create New Store for User
		if ( isset( $_POST['update_vendor'] ) && empty( $vendor_id ) ) {
		
			if ( ! wp_verify_nonce( $_POST['vendor_update_nonce'], 'vendor_update' ) ) {
			
				wp_die( __( 'Security verification error', 'ignitewoo_vendor_stores' ) );
				
			}

			$title = strip_tags( $_POST[ 'ignitewoo_vendor_stores_name' ] );
			
			if ( 'yes' != $ignitewoo_vendors->settings['allow_vendor_html' ] ) 
				$desc = strip_tags( $_POST[ 'ignitewoo_vendor_stores_description'] );
			else
				$desc = $_POST[ 'ignitewoo_vendor_stores_description'];

			$new_store = wp_insert_term(
				$title, 
				$ignitewoo_vendors->token, // the taxonomy
				array( 'description'=> $desc )
			);
			
			$error = is_wp_error( $new_store );
			
			if ( empty( $error ) ) { 
			
				update_user_meta( $user_ID, 'product_vendor', $new_store['term_id'] );
				
				$vendor_data = array();
				
				$vendor_data['admins'] = array( $user_ID );
			
				$vendor_data['paypal_email'] = $_POST[ 'ignitewoo_vendor_stores_paypal_address' ];
				
				$vendor_data['commission'] = $ignitewoo_vendors->settings['default_commission'];
				
				$vendor_data['commission_for'] = $ignitewoo_vendors->settings['default_commission_for'];
			
				update_option( $ignitewoo_vendors->token . '_' . $new_store['term_id'], $vendor_data );
				
				$url = remove_query_arg( 'store_setup' ); 
			
				wp_safe_redirect( $url );
			
				die;
			}

		}
		
	}
	
	
	/**
	* Create vendor details page for vendors to edit their own details
	*/
	public function vendor_details_page() {
		global $user_ID, $ignitewoo_vendors;
		
		$vendor = ign_get_user_vendor();
		
		$title = '';
		
		$description = '';
		
		$error = false;
		
		$res = false;
		
		if ( !empty( $vendor->ID ) ) { 
		
			// Taxonomy meta data
			$vendor_data = get_option( $ignitewoo_vendors->token . '_' . $vendor->ID );
			
			$vendor_id = $vendor->ID;
			
			// Taxonomy info
			$vendor_info = ign_get_vendor( $vendor->ID );
			
			$title = $vendor_info->title;
			
			$description = $vendor_info->description;
			
		} else { 
		
			$vendor_id = 0;
		
		}
		
		$paypal_address = '';
		
		if ( isset( $vendor_data['paypal_email'] ) )
			$paypal_address = $vendor_data['paypal_email'];
		
		$thumbnail_id = false;
		
		if ( !empty( $vendor->ID ) ) {
		
			$thumbnail_id = get_woocommerce_term_meta( $vendor->ID, 'thumbnail_id', true );

			if ( $thumbnail_id ) {

				$width = null;
				
				$height = null; 
				
				if ( !empty( $ignitewoo_vendors->settings['logo_width'] ) ) 
					$width = $ignitewoo_vendors->settings['logo_width'];
				
				if ( !empty( $ignitewoo_vendors->settings['logo_height'] ) ) 
					$height = $ignitewoo_vendors->settings['logo_height'];
				
				// If no size is specified in the plugin settings then use the full image size
				if ( !empty( $width ) && !empty( $height ) )
					$size = array( $width, $height );
				else 
					$size = 'large';
					
				$image = wp_get_attachment_image_src( $thumbnail_id, $size ); 

				if ( !empty( $image ) && is_array( $image ) )
					$image = $image[0];

			}
					
			//if ( $thumbnail_id )
			//	$image = wp_get_attachment_thumb_url( $thumbnail_id );
			if ( empty( $thumbnail_id ) ) 
				$image = woocommerce_placeholder_img_src();
				
			wp_enqueue_media();
		}
				
		
		
		?>
		
		<div class="wrap" id="vendor_details">
			
			<div class="icon32" id="icon-options-general"><br/></div>
			
			<h2><?php _e( 'Store Settings', 'ignitewoo_vendor_stores' ) ?></h2>
			
			<div style="margin-top: 15px"></div>
			
			<?php if ( !empty( $_GET['store_setup'] ) && 'true' == $_GET['store_setup'] ) { ?>
				<div class="error">
					<p><?php echo sprintf( __( 'You must configure your store title %s before taking other actions.', 'ignitewoo_vendor_stores' ), apply_filters( 'ign_other_config_requirements', '' ) ) ?></p>
				</div>
			<?php } ?>
			
			<?php if ( !empty( $error ) && !empty( $new_store ) ) { ?>
				<div class="error">
					<?php foreach( $new_store->errors as $key => $msg ) { ?>
						<?php foreach( $msg as $k => $m ) { ?>
							<p style="font-weight:bold">
								<?php echo str_replace( 'term', 'store', $m ) ?>
							</p>
						<?php } ?>
					<?php } ?>
				</div>
			<?php } ?>

			
			<form method="post" action="" enctype="multipart/form-data">
			
				<input type="hidden" name="update_vendor" value="true" />
							
				<?php wp_nonce_field( 'vendor_update', 'vendor_update_nonce' ) ?>
				
				<table class="widefat form-table">
				
				<?php if ( 'disabled' != $ignitewoo_vendors->settings['paypal_required'] ) { ?>
				<tr>
					<th style="vertical-align:top">
						<label for="vendor_paypal_address">
							<?php _e( 'PayPal Email Address', 'ignitewoo_vendor_stores' ) ?>
						</label>
					</th>
					<td>
						<input id="vendor_paypal_address" type="text" name="ignitewoo_vendor_stores_paypal_address" value="<?php echo $paypal_address  ?>"/>
					</td>
				</tr>
				<?php } ?>
				
				<?php do_action( 'ignitewoo_vendor_stores_add_payment_setting_fields', $vendor_id ) ?>
				
				<tr>
					<th style="vertical-align:top">
						<label for="vendor_paypal_address">
							<?php _e( 'Store Name', 'ignitewoo_vendor_stores' ) ?>
						</label>
					</th>
					<td>
						<input id="vendor_store_name" type="text" name="ignitewoo_vendor_stores_name" value="<?php echo $title  ?>"/>
						<p>
						<em><?php _e( 'Store title should be unique. Your store URL will be automatically derived from the title', 'ignitewoo_vendor_stores' ) ?></em>
						</p>
					</td>
				</tr>
				<tr>
					<th style="vertical-align:top">
						<label for="vendor_paypal_address">
							<?php _e( 'Store URL', 'ignitewoo_vendor_stores' ) ?>
						</label>
					</th>
					<td>
						<?php if ( empty( $vendor->ID ) ) { ?>
							<?php _e( 'Not created yet', 'ignitewoo_vendor_stores' ); ?>
						<?php } else { ?>
							<p><?php echo get_term_link( $vendor->title, $ignitewoo_vendors->token ); ?></p>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<th style="vertical-align:top">
						<label for="ignitewoo_vendor_stores_description" style="vertical-align:top">
							<?php _e( 'Store Description', 'ignitewoo_vendor_stores' ) ?>
						</label>
					</th>
					<td>
						<?php if ( 'yes' != $ignitewoo_vendors->settings['allow_vendor_html'] ) { ?>
							<p><?php _e( 'No HTML Allowed', 'ignitewoo_vendor_stores' ) ?></p>
							<textarea id="ignitewoo_vendor_stores_description" name="ignitewoo_vendor_stores_description" cols="80" rows="15"><?php echo $description ?></textarea>
						<?php } else { ?>
							<?php wp_editor( $description, 'ignitewoo_vendor_stores_description', array( 'media_buttons' => true ) ); ?>
						<?php } ?>
						
					</td>
				</tr>
				
				<?php if ( !empty( $vendor->ID ) ) { ?>
				<tr class="form-field">
					<th>
						<label><?php _e( 'Store Logo', 'ignitewoo_vendor_stores' ); ?></label>
					</th>
					
					<td>
					
					<div style="clear:both; margin-bottom:10px"></div>
					
					<div style="line-height:60px;">
						<input type="hidden" id="product_cat_thumbnail_id" name="product_cat_thumbnail_id" value="<?php echo $thumbnail_id ?>"/>
						<button type="submit" class="upload_image_button button"><?php _e( 'Upload logo image', 'ignitewoo_vendor_stores' ); ?></button>
						
						<?php if ( !empty( $thumbnail_id ) ) { ?>
						<button type="submit" class="remove_image_button button"><?php _e( 'Remove logo image', 'ignitewoo_vendor_stores' ); ?></button>
						<?php } ?>
					</div>
					
					<div style="clear:both; margin-bottom:10px"></div>
					
					<?php 
					if ( empty( $thumbnail_id ) )
						$style = 'width: 50px; height: 50px';
					else 
						$style = '';
					?>
					
					<div id="product_cat_thumbnail" style="float:left;margin-right:10px;">
						<img src="<?php echo $image; ?>" style="<?php echo $style ?>" />
					</div>
					<script type="text/javascript">

						// Only show the "remove image" button when needed
						if ( ! jQuery('#product_cat_thumbnail_id').val() )
							jQuery('.remove_image_button').hide();

						// Uploading files
						var file_frame;

						jQuery(document).on( 'click', '.upload_image_button', function( event ){

							event.preventDefault();

							// If the media frame already exists, reopen it.
							if ( file_frame ) {
								file_frame.open();
								return;
							}

							// Create the media frame.
							file_frame = wp.media.frames.downloadable_file = wp.media({
								title: '<?php _e( 'Choose an image', 'ignitewoo_vendor_stores' ); ?>',
								button: {
									text: '<?php _e( 'Use image', 'ignitewoo_vendor_stores' ); ?>',
								},
								multiple: false
							});

							// When an image is selected, run a callback.
							file_frame.on( 'select', function() {
								attachment = file_frame.state().get('selection').first().toJSON();

								jQuery('#product_cat_thumbnail_id').val( attachment.id );
								jQuery('#product_cat_thumbnail img').attr('src', attachment.url );
								jQuery('.remove_image_button').show();
							});

							// Finally, open the modal.
							file_frame.open();
						});

						jQuery(document).on( 'click', '.remove_image_button', function( event ){
							jQuery('#product_cat_thumbnail img').attr('src', '<?php echo woocommerce_placeholder_img_src(); ?>');
							jQuery('#product_cat_thumbnail_id').val('');
							jQuery('.remove_image_button').hide();
							return false;
						});

					</script>
					<div class="clear"></div>
					</td>
				</tr>
				
				<?php } ?>
				
				<?php do_action( 'ignitewoo_vendor_stores_vendor_settings', $vendor_data ) ?>
				
				</table>
				<p class="submit">
					<input name="Submit" type="submit" class="button-primary" value="<?php _e( 'Save Settings', 'ignitewoo_vendor_stores' ) ?>" />
				</p>
			
			</form>
		</div>

		<?php
	}
	
	
	
	public function vendor_cat_columns( $columns ) {
	
		$new_columns = array();
		
		$new_columns['cb']  = $columns['cb'];
		
		$new_columns['thumb'] = __( 'Image', 'ignitewoo_vendor_stores' );

		unset( $columns['cb'] );

		return array_merge( $new_columns, $columns );
	}
	
	
	
	public function vendor_cat_column( $columns, $column, $id ) {
		global $woocommerce;

		if ( $column != 'thumb' )
			return $columns;
			

		$image = '';
		
		$thumbnail_id 	= get_woocommerce_term_meta( $id, 'thumbnail_id', true );

		if ($thumbnail_id)
			$image = wp_get_attachment_thumb_url( $thumbnail_id );
		else
			$image = woocommerce_placeholder_img_src();

		$columns .= '<img src="' . $image . '" alt="Thumbnail" class="wp-post-image" height="48" width="48" />';

		return $columns;
	}
	
	
	/**
	* Add fields to vendor taxonomy (add new vendor screen)
	*/
	public function add_vendor_fields( $taxonomy ) {
		global $woocommerce, $ignitewoo_vendors;

		wp_enqueue_script( 'chosen', $ignitewoo_vendors->assets_url . '/js/jquery.chosen.js' );
		
		wp_enqueue_script( 'ajax-chosen', $ignitewoo_vendors->assets_url . '/js/ajax-chosen' );
		
		wp_enqueue_style( 'chosen_css', $ignitewoo_vendors->assets_url . '/css/chosen.css' );
		
		 wp_enqueue_media();
		
		$commission = $ignitewoo_vendors->settings['default_commission'];
		
		if ( empty( $commission ) )
			$commission = '';

		?>
		<div class="form-field">
			<label for="vendor_paypal_email"><?php _e( 'PayPal email address', 'ignitewoo_vendor_stores' ); ?></label>
			<input type="text" name="vendor_data[paypal_email]" id="vendor_paypal_email" value="" /><br/>
			<span class="description"><?php _e( 'The PayPal email address of the vendor where their profits will be delivered.', 'ignitewoo_vendor_stores' ); ?></span>
		</div>
		

		<div class="form-field">
			<label for="vendor_commission"><?php _e( 'Commission', 'ignitewoo_vendor_stores' ); ?></label>
			<input type="text" name="vendor_data[commission]" id="vendor_commission" value="<?php echo esc_attr( $commission ); ?>" /><br/>
			<span class="description"><?php _e( 'Default commission of the sale received by the vendor. Optionally override this on a per-product basis', 'ignitewoo_vendor_stores' ); ?></span>
		</div>
		
		<div class="form-field">
			<label for="vendor_commission"><?php _e( 'Commission is for', 'ignitewoo_vendor_stores' ); ?>
			<input type="radio" name="vendor_data[commission_for]" id="vendor_commission" value="vendor" checked="checked" /> <?php _e( 'Vendor', 'ignitewoo_vendor_stores' )?> 
			<input type="radio" name="vendor_data[commission_for]" id="vendor_commission" value="store" /> <?php _e( 'Site', 'ignitewoo_vendor_stores' )?>
			</label>
		</div>
		
		<div class="form-field">
			<label for="vendor_admins"><?php _e( 'Store administrator (optional)', 'ignitewoo_vendor_stores' ); ?></label>
			<select name="vendor_data[admins][]" id="vendor_admins" class="ajax_chosen_select_admin" <?php /* multiple="multiple" */ ?> style="width:95%;" data-placeholder="Start typing a user name">
				<option value=""><?php _e( 'None', 'ignitewoo_vendor_stores' )?></option>
			</select><br/>
			<span class="description"><?php _e( 'The user who can manage products, view reports, and adjust store information.', 'ignitewoo_vendor_stores' ); ?></span>
		</div>

		<div class="form-field">
			<label><?php _e( 'Store image', 'ignitewoo_vendor_stores' ); ?></label>
			<div id="product_cat_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo woocommerce_placeholder_img_src(); ?>" width="60px" height="60px" /></div>
			<div style="line-height:60px;">
				<input type="hidden" id="product_cat_thumbnail_id" name="product_cat_thumbnail_id" />
				<button type="submit" class="upload_image_button button"><?php _e( 'Upload/Add image', 'ignitewoo_vendor_stores' ); ?></button>
				<button type="submit" class="remove_image_button button"><?php _e( 'Remove image', 'ignitewoo_vendor_stores' ); ?></button>
			</div>
			<script type="text/javascript">

				 // Only show the "remove image" button when needed
				 if ( ! jQuery('#product_cat_thumbnail_id').val() )
					 jQuery('.remove_image_button').hide();

				// Uploading files
				var file_frame;

				jQuery(document).on( 'click', '.upload_image_button', function( event ){

					event.preventDefault();

					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						file_frame.open();
						return;
					}

					// Create the media frame.
					file_frame = wp.media.frames.downloadable_file = wp.media({
						title: '<?php _e( 'Choose an image', 'ignitewoo_vendor_stores' ); ?>',
						button: {
							text: '<?php _e( 'Use image', 'ignitewoo_vendor_stores' ); ?>',
						},
						multiple: false
					});

					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {
						attachment = file_frame.state().get('selection').first().toJSON();

						jQuery('#product_cat_thumbnail_id').val( attachment.id );
						jQuery('#product_cat_thumbnail img').attr('src', attachment.url );
						jQuery('.remove_image_button').show();
					});

					// Finally, open the modal.
					file_frame.open();
				});

				jQuery(document).on( 'click', '.remove_image_button', function( event ){
					jQuery('#product_cat_thumbnail img').attr('src', '<?php echo woocommerce_placeholder_img_src(); ?>');
					jQuery('#product_cat_thumbnail_id').val('');
					jQuery('.remove_image_button').hide();
					return false;
				});

			</script>
			<div class="clear"></div>
		</div>
		
		<?php do_action( 'ignitewoo_vendor_stores_vendor_settings_div' ) ?>
		
		<style>
		.chosen-container-multi .chosen-choices li.search-field input[type="text"] {
			min-height: 24px;
		}
		</style>
		
		<script>
		jQuery( document ).ready( function($) { 
			jQuery('select.ajax_chosen_select_admin').ajaxChosen(
				{
					method: 'GET',
					url: '<?php echo admin_url( 'admin-ajax.php' )?>',
					dataType: 'json',
					afterTypeDelay: 100,
					minTermLength: 1,
					data: {
							action: 'woocommerce_json_search_customers',
							security: '<?php echo wp_create_nonce( 'search-customers' ) ?>',
							default: ''
					}
					
				}, function (data) {

					var terms = {};

					$.each(data, function (i, val) {
						terms[i] = val;
					});

					return terms;
				}
			);
		});
		</script>
			
		<?php
			
	}

	
	/**
	* Add fields to vendor taxonomy (edit vendor screen)
	*/
	public function edit_vendor_fields( $vendor ) {
		global $woocommerce, $ignitewoo_vendors;

		wp_enqueue_script( 'chosen', $ignitewoo_vendors->assets_url . '/js/jquery.chosen.js' );
			
		wp_enqueue_script( 'ajax-chosen', $ignitewoo_vendors->assets_url . '/js/ajax-chosen' );
		
		wp_enqueue_style( 'chosen_css', $ignitewoo_vendors->assets_url . '/css/chosen.css' );

		wp_enqueue_media();
		
		/*
		$sql = 'select ID, m1.meta_value, m2.meta_value from ' . $wpdb->users . ' 
			left join ' . $wpdb->usermeta . ' m1 on ID = user_id
			left join ' . $wpdb->usermeta . ' m2 on ID = user_id 
			where m1.meta_key="billing_first_name"
			and m2.meta_key="billing_last_name"
			';
			
		$users = $wpdb->get_results( $sql );
		*/	
		
		$vendor_id = $vendor->term_id;
		
		$vendor_data = get_option( $ignitewoo_vendors->token . '_' . $vendor_id );

		$vendor_admins = '';

		if ( isset( $vendor_data['admins'] ) && count( $vendor_data['admins'] ) > 0 ) {
		
			$admins = $vendor_data['admins'];
			
			foreach( $admins as $k => $user_id ) {
			
				if ( empty( $user_id ) )
					continue;
					
				$user = get_userdata( $user_id );
				
				if ( empty( $user ) || is_wp_error( $user ) )
					continue;
					
				$roles = $this->get_current_user_role( $user_id );
				
				// Do not allow administrators to be vendors - that breaks access to WP admin
				if ( in_array( 'administrator', $roles ) ) {
					$vendor_data['admins'][] = array();
					update_option( $ignitewoo_vendors->token . '_' . $vendor_id, $vendor_data );
					continue;
				
				}
					
				$user_display = $user->display_name . '(#' . $user_id . ' - ' . $user->user_email . ')';
				
				$vendor_admins .= '<option value="' . esc_attr( $user_id ) . '" selected="selected">' . $user_display . '</option>';
			}
		}

		$commission = 0;
		
		if ( $vendor_data['commission'] || strlen( $vendor_data['commission'] ) > 0 || $vendor_data['commission'] != '' ) {
			$commission = $vendor_data['commission'];
		}

		$commission_for = $vendor_data['commission_for'];
		
		$paypal_email = '';
		if ( $vendor_data['paypal_email'] || strlen( $vendor_data['paypal_email'] ) > 0 || $vendor_data['paypal_email'] != '' ) {
			$paypal_email = $vendor_data['paypal_email'];
		}

		$thumbnail_id 	= get_woocommerce_term_meta( $vendor_id, 'thumbnail_id', true );

		if ( $thumbnail_id )
			$image = wp_get_attachment_thumb_url( $thumbnail_id );
		else
			$image = woocommerce_placeholder_img_src();
			
		?>
		<tr class="form-field">
		<th scope="row" valign="top"><label for="vendor_admins"><?php _e( 'Store administrator', 'ignitewoo_vendor_stores' ); ?></label></th>
		<td>
			<select name="vendor_data[admins][]" id="vendor_admins" class="ajax_chosen_select_admin" <?php /* multiple="multiple" */ ?> style="width:300px;" data-placeholder="<?php _e( 'Start typing a user name' ) ?>">
				<option value=""><?php _e( 'None', 'ignitewoo_vendor_stores' ) ?></option>
				<?php echo $vendor_admins; ?>
			</select><br/>
			
			<span class="description"><?php _e( 'The user who can manage products, view reports, and adjust store information. ( Optional )', 'ignitewoo_vendor_stores' ); ?></span>
		</td>
		</tr>

		<tr class="form-field">
		<th scope="row" valign="top"><label for="vendor_commission"><?php _e( 'Commission', 'ignitewoo_vendor_stores' ); ?></label></th>
		<td>
			<input type="text" name="vendor_data[commission]" id="vendor_commission" value="<?php echo esc_attr( $commission ); ?>" style="width:300px;"/><br/>
			<span class="description"><?php _e( 'Default commission of the sale received by the vendor. Optionally override this on a per-product basis', 'ignitewoo_vendor_stores' ); ?></span>
		</td>
		</tr>

		<tr class="form-field">
		<th scope="row" valign="top"><label for="vendor_commission"><?php _e( 'Commission', 'ignitewoo_vendor_stores' ); ?></label></th>
		<td>
			<div style="width:20%">
			<input style="float:left;width:20px" type="radio" name="vendor_data[commission_for]" id="vendor_commission" value="vendor" <?php checked( $commission_for, 'vendor', true ) ?>/><label style="float:none"> <?php _e( 'Vendor', 'ignitewoo_vendor_stores' ) ?></label>
			</div>
			
			<div style="width:20%">
			<input  style="float:left; width:20px" type="radio" name="vendor_data[commission_for]" id="vendor_commission" value="store" <?php checked( $commission_for, 'store', true ) ?>/><label style="float:none">  <?php _e( 'Site', 'ignitewoo_vendor_stores' ) ?></label> 
			</div>
			<br/>
			<span class="description"><?php _e( 'Who receives the commission', 'ignitewoo_vendor_stores' ); ?></span>
		</td>
		</tr>
		
		<tr class="form-field">
		<th scope="row" valign="top"><label for="vendor_paypal_email"><?php _e( 'PayPal email address', 'ignitewoo_vendor_stores' ); ?></label></th>
		<td>
			<input type="text" name="vendor_data[paypal_email]" id="vendor_paypal_email" value="<?php echo esc_attr( $paypal_email ); ?>" style="width:300px;" /><br/>
			<span class="description"><?php _e( 'Vendor PayPal email address for commission payments', 'ignitewoo_vendor_stores' ); ?></span>
			</td>
		</tr>
		<tr class="form-field">
			<th><label><?php _e( 'Store image', 'ignitewoo_vendor_stores' ); ?></label></th>
			
			<td>
			<div id="product_cat_thumbnail" style="float:left;margin-right:10px;">
				<img src="<?php echo $image; ?>" width="60px" height="60px" />
			</div>
			<div style="line-height:60px;">
				<input type="hidden" id="product_cat_thumbnail_id" name="product_cat_thumbnail_id" value="<?php echo $thumbnail_id?>"/>
				<button type="submit" class="upload_image_button button"><?php _e( 'Upload/Add image', 'ignitewoo_vendor_stores' ); ?></button>
				<button type="submit" class="remove_image_button button"><?php _e( 'Remove image', 'ignitewoo_vendor_stores' ); ?></button>
			</div>
			<script type="text/javascript">

				 // Only show the "remove image" button when needed
				 if ( ! jQuery('#product_cat_thumbnail_id').val() )
					 jQuery('.remove_image_button').hide();

				// Uploading files
				var file_frame;

				jQuery(document).on( 'click', '.upload_image_button', function( event ){

					event.preventDefault();

					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						file_frame.open();
						return;
					}

					// Create the media frame.
					file_frame = wp.media.frames.downloadable_file = wp.media({
						title: '<?php _e( 'Choose an image', 'ignitewoo_vendor_stores' ); ?>',
						button: {
							text: '<?php _e( 'Use image', 'ignitewoo_vendor_stores' ); ?>',
						},
						multiple: false
					});

					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {
						attachment = file_frame.state().get('selection').first().toJSON();

						jQuery('#product_cat_thumbnail_id').val( attachment.id );
						jQuery('#product_cat_thumbnail img').attr('src', attachment.url );
						jQuery('.remove_image_button').show();
					});

					// Finally, open the modal.
					file_frame.open();
				});

				jQuery(document).on( 'click', '.remove_image_button', function( event ){
					jQuery('#product_cat_thumbnail img').attr('src', '<?php echo woocommerce_placeholder_img_src(); ?>');
					jQuery('#product_cat_thumbnail_id').val('');
					jQuery('.remove_image_button').hide();
					return false;
				});

			</script>
			<div class="clear"></div>
			</td>
		</tr>
		
		<?php do_action( 'ignitewoo_vendor_stores_vendor_settings', $vendor_data ) ?>
		
		<style>
		.chosen-container-multi .chosen-choices li.search-field input[type="text"] {
			min-height: 24px;
		}
		</style>
		
		<script>
		jQuery( document ).ready( function($) { 
			jQuery('select.ajax_chosen_select_admin').ajaxChosen(
				{
					method: 'GET',
					url: '<?php echo admin_url( 'admin-ajax.php' )?>',
					dataType: 'json',
					afterTypeDelay: 100,
					minTermLength: 1,
					data: {
							placeholder: '<?php _e( 'Start typing a user name', 'ignitewoo_vendor_stores' )?>',
							action: 'woocommerce_json_search_customers',
							security: '<?php echo wp_create_nonce( 'search-customers' ) ?>',
							default: ''
					}
					
				}, function (data) {

					var terms = {};

					$.each(data, function (i, val) {
						terms[i] = val;
					});

					return terms;
				}
			);
		});
		</script>
		<?php

	}

	
	/**
	* Save vendor taxonomy fields
	*/
	public function save_vendor_fields( $vendor_id ) {
		global $ignitewoo_vendors;

		if ( !isset( $_POST['vendor_data'] ) )
			return;

		$vendor_data = get_option( $ignitewoo_vendors->token . '_' . $vendor_id );
		
		$keys = array_keys( $_POST['vendor_data'] );

		foreach ( $keys as $key ){
			if ( isset( $_POST['vendor_data'][$key] ) ) {
				$vendor_data[$key] = $_POST['vendor_data'][$key];
			}
		}

		// Get current vendor admins
		$args = array(
			'meta_key' => 'product_vendor',
			'meta_value' => $vendor_id,
			'meta_compare' => '='
			);
			
		$current_vendors = get_users( $args );

		// Remove all current admins
		foreach( $current_vendors as $vendor ) {

			delete_user_meta( $vendor->ID, 'product_vendor' );
			
			// Remove vendor role - users can only be vendors of ONE store
			$ignitewoo_vendors->remove_vendor_caps( $vendor->ID );
			
		}
		

		// Only add selected admins
		if ( isset( $_POST['vendor_data']['admins'] ) && count( $_POST['vendor_data']['admins'] > 0 ) ) {

			// Get all vendors
			$vendors = ign_get_vendors();

			foreach( $_POST['vendor_data']['admins'] as $key => $user_id ) {
			
				$roles = $this->get_current_user_role( $user_id );

				// Do not allow administrators to become vendors - this breaks their access to the WP admin
				if ( !empty( $roles ) && in_array( 'administrator', $roles ) ) { 
					set_transient( 'admin_cannot_be_vendor', 1, 60 );
					unset( $_POST['vendor_data']['admins'][ $key ] );
					delete_user_meta( $user_id, 'product_vendor' );
					continue;
				}
				
				update_user_meta( $user_id, 'product_vendor', $vendor_id );
					
				$ignitewoo_vendors->add_vendor_caps( $user_id );

				// Remove user from all other vendors
				/*
				if ( is_array( $vendors ) && count( $vendors ) > 0 ) {
					foreach( $vendors as $v ) {
					$thign_is_vendor = get_option( $ignitewoo_vendors->token . '_' . $v->ID );
					if ( isset( $thign_is_vendor['admins'] ) && is_array( $thign_is_vendor['admins'] ) ) {
						foreach( $thign_is_vendor['admins'] as $k => $admin ) {
						if ( $admin == $user_id ) {
							unset( $thign_is_vendor['admins'][ $k ] );
							break;
						}
						}
					}
					update_option( $ignitewoo_vendors->token . '_' . $v->ID, $thign_is_vendor );
					}
				}
				*/
			}
			
		} else { 
		
			$vendor_data['admins'] = array();
		
		}

		$vendor_data = apply_filters( 'ignitewoo_vendor_stores_vendor_settings_save', $vendor_data );
		
		update_option( $ignitewoo_vendors->token . '_' . $vendor_id, $vendor_data );

		if ( isset( $_POST['product_cat_thumbnail_id'] ) )
			update_woocommerce_term_meta( $vendor_id, 'thumbnail_id', absint( $_POST['product_cat_thumbnail_id'] ) );
			
	}

	
	function get_current_user_role ( $id ) {
		$user_info = get_userdata( $id );
		return $user_info->roles;
	}

	
	/**
	* Add vendor to product
	*/
	public function add_vendor_to_product( $post_id ) {
		global $ignitewoo_vendors;
			
		if ( 'product' !== get_post_type( $post_id ) )
			return;

		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		if ( ign_vendor_access() ) {
		
			$vendor = ign_get_user_vendor();
		
			if ( isset( $vendor->slug ) && strlen( $vendor->slug ) > 0 ) {
				wp_set_object_terms( $post_id, $vendor->slug, $ignitewoo_vendors->token, false );
			}
			
		}
		
	}
	
	
	function send_new_post_notice( $post_id = null ) { 
		global $post, $ignitewoo_vendors;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;
		
		if ( !current_user_can( 'vendor' ) )
			return;

		if ( empty( $post_id ) )
			return;
			
		if ( 'product' !== get_post_type( $post_id ) )
			return;
			
		if ( 'yes' == get_post_meta( $post_id, '_admin_notice_sent', true ) )
			return;

		if ( empty( $post ) )
			return;

		//if ( 'pending' != $post->post_status )
		//	return;

		if ( empty( $post->post_author ) )
			return;

		if ( empty( $ignitewoo_vendors->settings['new_post_email_addy'] ) )
			return;
		
		$to = explode( ',' , $ignitewoo_vendors->settings['new_post_email_addy'] );

		if ( empty( $to ) )
			return;
	
		$message = $ignitewoo_vendors->settings['new_product_message'];

		$subject = $ignitewoo_vendors->settings['new_product_subject'];
		
		if ( empty( $message ) || empty( $subject ) ) 
			return;
			
		$subject = str_replace( '{blogname}', wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ), $subject );
			
		$message = str_replace( '{url}', admin_url( 'post.php?post=' . $post_id . '&action=edit' ), $message );
				
		$user = get_user_by( 'id', $post->post_author );

		if ( !empty( $user ) && !is_wp_error( $user ) )
			$message = str_replace( '{username}', $user->data->user_login, $message );

		if ( wp_mail( $to, $subject, $message ) )
			update_post_meta( $post_id, '_admin_notice_sent', 'yes' );

	}
	

	/**
	* Add reports for vendors or admins
	*/
	public function add_reports( $charts ) {
		global $ignitewoo_vendors;

		if ( current_user_can( 'vendor' ) && ( !current_user_can( 'administrator' ) && !current_user_can( 'shop_manager' ) ) ) { 
			
			require_once( dirname( __FILE__ ) . '/class-wc-vendor-stores-reports.php' );
		
			$ignitewoo_vendors->vendor_reports = new IgniteWoo_Vendor_Store_Reports();
			
			return $charts; 
			
		}

		if ( current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) ) { 

			require_once( dirname( __FILE__ ) . '/class-wc-vendor-stores-admin-reports.php' );
		
			$ignitewoo_vendors->vendor_admin_reports = new IgniteWoo_Vendor_Store_Admin_Reports();
		
			$charts['product_vendors'] = array(
				'title' => __( 'Vendor Stores', 'ignitewoo_vendor_stores' ),
				'charts' => array(
					array(
						'title'       => __( 'Overview', 'ignitewoo_vendor_stores' ),
						'description' => __( 'General overview of vendor store sales', 'ignitewoo_vendor_stores' ),
						'hide_title'  => true,
						'function'    => array( $ignitewoo_vendors->vendor_admin_reports, 'ignitewoo_vendor_stores_report_overview' )
					),
					array(
						'title'       => __( 'Vendor Store Sales', 'ignitewoo_vendor_stores' ),
						'description' => __( 'General sales statistics for the selected vendor', 'ignitewoo_vendor_stores' ),
						'hide_title'  => false,
						'function'    => array( $ignitewoo_vendors->vendor_admin_reports,  'ignitewoo_vendor_stores_report_vendor_sales' )
					),
					array(
						'title'       => __( 'Vendor Commissions', 'ignitewoo_vendor_stores' ),
						'description' => '',
						'hide_title'  => true,
						'function'    => array( $ignitewoo_vendors->vendor_admin_reports,  'ignitewoo_vendor_stores_report_vendor_commission' )
					),
					array(
						'title'       => __( 'Vendor Product Commissions', 'ignitewoo_vendor_stores' ),
						'description' => '',
						'hide_title'  => true,
						'function'    => array( $ignitewoo_vendors->vendor_admin_reports,  'ignitewoo_vendor_stores_report_vendor_product_commission' )
					),
				)
			);

		}
		
		return $charts;
	}
	
	
	/**
	* Display admin notices in the WP dashboard
	*/
	public function store_admin_notices() {
		global $current_screen;

		$message = false;

		if ( 'toplevel_page_vendor_details' !== $current_screen->base ) 
			return;
			
		if ( !isset( $_GET['updated'] ) )
			return;
			
		switch( $_GET['updated'] ) {
		
			case 'true': 
				$message = sprintf( __( '%1$sStore details have been updated.%2$s', 'ignitewoo_vendor_stores' ), '<div id="message" class="updated"><p>', '</p></div>' );
		
		}

		if ( !empty( $message ) )
			echo $message;

	}

	
	public function warning_admin_notices() {
		global $current_screen;

		$message = false;

		//if ( empty( $_GET['taxonomy'] ) || 'shopvendor' == $_GET['taxonomy'] ) 
		//	return;
		/*
		?>
		
		<div id="error" class="error">
			<p style="font-weight:bold;font-size:1.1em"><?php _e( 'Notice: Do not add users to more than one store!', 'ignitewoo_vendor_stores' )?></p>
		</div>
		
		<?php 
		*/
		
		$dups = $this->check_for_duplicate_admins();
		
		if ( !empty( $dups ) ) { 
			?>

			<div id="error" class="error">
				<p style="font-weight:bold;font-size:1.1em"><?php _e( 'WARNING: The following users are admins of more than one vendor store. Correct this immediately! Navigate to Products -> Stores to adjust store settings.', 'ignitewoo_vendor_stores' )?></p>
				
				<?php
				
				foreach( $dups as $u => $d ) {
				
					$user = new WP_User( $u );
					
					if ( empty( $user ) || is_wp_error( $user ) )
						echo '<p>' . sprintf( __( 'ERROR LOADING USER # %s', 'ignitewoo_vendor_stores'  ), $u ) . '</p>';
					else 
						echo '<p><strong>' . sprintf( __( 'User ID %s', 'ignitewoo_vendor_stores' ), $u . '</strong> ( ' . $user->user_login ) . ') &mdash; <strong>' . __( 'Stores', 'ignitewoo_vendor_stores' ) . ':</strong> ';
						
						echo implode( ', ' , $d['tax'] );

						echo '</p>';
				}
				
				?>
			
			</div>
			
			<?php
		}

		if ( get_transient( 'admin_cannot_be_vendor' ) ) { 
		
			?>
			<div id="error" class="error">
				<p style="font-weight:bold;font-size:1.1em;color:#cf0000"><?php _e( 'WARNING: Administrators cannot become vendors! Please review the store settings and choose a different user', 'ignitewoo_vendor_stores' )?></p>
			</div>
			
			<?php
			
			delete_transient( 'admin_cannot_be_vendor' );
		}
		
	}
	
	
	function check_for_duplicate_admins() { 
		global $ignitewoo_vendors; 
		
		$stores = get_terms( $ignitewoo_vendors->token, array( 'hide_empty' => false ) );
		
		if ( empty( $stores ) || is_wp_error( $stores ) )
			return false;
		
		$tally = array();
		
		// Get a list of all store admins
		foreach( $stores as $store ) {
		
			$data = get_option( $ignitewoo_vendors->token . '_' . $store->term_id );
			
			if ( empty( $data ) || empty( $data['admins'] ) || !is_array( $data['admins'] ) )
				continue;
				
			foreach( $data['admins'] as $admin ) {
			
				if ( empty( $admin ) )
					continue;
					
				if ( empty( $tally[ $admin ] ) ) { 
				
					$tally[ $admin ]['count'] = 1;
					$tally[ $admin ]['tax'][] = $store->name;
					
				} else { 
				
					$tally[ $admin ]['count']++;
					$tally[ $admin ]['tax'][] = $store->name;
				
				}
			
			}
		
		}

		foreach( $tally as $k => $d ) 
		if ( ( $d['count'] ) <= 1 ) 
			unset( $tally[ $k ] );
	
		if ( count( $tally ) > 0 ) 
			return $tally;
			
		return false;
	
	}
	

}

$ignitewoo_vendor_stores_admin = new IgniteWoo_Vendor_Store_Admin();
