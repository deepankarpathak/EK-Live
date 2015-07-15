<?php

/**
Copyright (c) 2013 - IgniteWoo.com
*/

if ( ! defined( 'ABSPATH' ) ) exit;


class IgniteWoo_Vendor_Stores {

	var $plugin_dir;
	
	var $file;
	
	var $assets_dir;
	
	var $assets_url;
	
	var $token;
	
	var $vendor_reports;

	function __construct() {

		$this->plugin_dir = dirname( dirname( __FILE__ ) );
		
		$this->file = __FILE__;
		
		$this->assets_dir = trailingslashit( $this->plugin_dir ) . '../assets';
		
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/../assets/', __FILE__ ) ) );
		
		$this->token = 'shop_vendor';

		$this->settings = get_option( 'woocommerce_ignitewoo_vendor_stores_settings', array() );
		
		// Set up vendor role
		$this->vendor_caps();
	
		add_filter( 'vendor_stores_vendor_slug', array( &$this, 'vendor_slug' ), 1, 1 );
	
		// Load language file
		add_action( 'init', array( $this, 'load_plugin_textdomain' ), 0 );

		
		// Adjust user caps when threshold is met or exceeded
		add_action( 'init', array( &$this, 'check_current_user_caps' ), 15 );

		// Register new taxonomy -- DO NOT CHANGE PRIORITY!!!!
		add_action( 'init', array( $this, 'register_vendors_taxonomy' ), 5 );

		// Maybe add order note
		add_action( 'init', array( &$this, 'maybe_add_order_note' ), 20 );
		
		// Force vendor to adjust store settings
		add_action( 'init', array( &$this, 'maybe_redirect_vendor' ), 25 );
		
		// CSV export
		add_action( 'init', array( &$this, 'maybe_do_csv_export' ), 30 );
		
		// Various
		add_action( 'init', array( $this, 'init' ), 9000 );
		
		add_action( 'wp', array( $this, 'wp' ), 9000 );
		
		// Maybe process payments - hook for server-based cron
		add_action( 'init', array( &$this, 'cron_process_payments_hook' ), 9999 );
		
		// Maybe add body class
		add_filter( 'body_class', array( &$this, 'maybe_add_body_class' ), 90, 1 );
		
		// Frontend styles
		add_action( 'wp_enqueue_scripts', array( &$this, 'frontend_styles_scripts' ) );
		
		// Process commissions for order
		add_action( 'woocommerce_order_status_completed', array( $this, 'add_commissions' ), 10, 1 );
		add_action( 'woocommerce_order_status_processing', array( $this, 'add_commissions' ), 10, 1 );

		add_action( 'woocommerce_order_status_pending', array( $this, 'remove_commissions' ), 10, 1 );
		add_action( 'woocommerce_order_status_failed', array( $this, 'remove_commissions' ), 10, 1 );
		add_action( 'woocommerce_order_status_refunded', array( $this, 'remove_commissions' ), 10, 1 );
		add_action( 'woocommerce_order_status_cancelled', array( $this, 'remove_commissions' ), 10, 1 );
		add_action( 'woocommerce_order_status_on-hold', array( $this, 'remove_commissions' ), 10, 1 );

		// Send new order emails to vendor
		add_action( 'woocommerce_order_status_pending_to_processing_notification', array( $this, 'new_order_email' ) );
		add_action( 'woocommerce_order_status_pending_to_completed_notification', array( $this, 'new_order_email' ) );
		add_action( 'woocommerce_order_status_pending_to_on-hold_notification', array( $this, 'new_order_email' ) );
		add_action( 'woocommerce_order_status_failed_to_processing_notification', array( $this, 'new_order_email' ) );
		add_action( 'woocommerce_order_status_failed_to_completed_notification', array( $this, 'new_order_email' ) );
		add_action( 'woocommerce_order_status_failed_to_on-hold_notification', array( $this, 'new_order_email' ) );
		
		// Email filters
		add_filter( 'woocommerce_order_product_title', array( &$this, 'show_vendor_in_email' ), 10, 2 );
		add_filter( 'woocommerce_order_item_name', array( &$this, 'show_vendor_in_email' ), 10, 2 );
		
		// Cart filters
		add_filter( 'woocommerce_get_item_data', array( &$this, 'sold_by' ), 10, 2 );

		// Allow vendors access to the WP dashboard
		add_filter( 'woocommerce_prevent_admin_access', array( $this, 'allow_vendor_admin_access' ) );

		// Store report: Total earnings
		add_shortcode( 'vendor_total_earnings', array( $this, 'vendor_total_earnings_report' ) );

		// Store report: This month's earnings
		add_shortcode( 'vendor_month_earnings', array( $this, 'vendor_month_earnings' ) );

		// Vendor Dashboard
		add_shortcode( 'vendor_store_dashboard', array( $this, 'vendor_store_dashboard' ) );
		
		// My account page content and register form
		add_action( 'woocommerce_before_my_account', array( &$this, 'my_account_before' ) );

		
		// Order filters for vendor dashboard on the frontend
		add_filter( 'woocommerce_order_get_items', array( &$this, 'filter_order_items' ), 99, 2 );
		add_filter( 'woocommerce_order_subtotal_to_display', array( $this, 'get_subtotal_to_display' ), 99, 3 );
		add_filter( 'woocommerce_get_formatted_order_total', array( &$this, 'get_formatted_order_total' ), 99, 2 );
		
		// Maybe remove vendor request flag
		add_action( 'edit_user_profile_update', array( &$this, 'maybe_remove_vendor_request_flag' ), 1, 999 );

		// Setup vendor shop page (taxonomy archive)
		add_action( 'template_redirect', array( $this, 'load_product_archive_template' ) );
		add_filter( 'body_class', array( $this, 'set_product_archive_class' ) );
		add_action( 'woocommerce_archive_description', array( $this, 'product_archive_vendor_info' ) );

		// Add vendor info to single product page
		add_filter( 'woocommerce_product_tabs', array( $this, 'product_vendor_tab' ) );

		// Add store image to archive-content.php pages
		add_action( 'woocommerce_archive_description', array( &$this, 'show_store_image' ), 1 );

		// Tracking
		add_action( 'woocommerce_view_order', array( &$this, 'display_tracking_info' ), 10 );
		
		// Coupon
		/*
		add_filter( 'woocommerce_coupon_is_valid', array( &$this, 'coupon_is_valid' ), 999999, 2 );
		add_filter( 'woocommerce_coupon_is_valid_for_product', array( &$this, 'coupon_is_valid_for_product' ), 999999, 3 );
		*/
		// Activation
		register_activation_hook( $this->file, array( $this, 'activation' ) );
		
		// Deactivation
		register_deactivation_hook( $this->file, array( $this, 'deactivation' ) );

	}



	function activation() { 
	
		$this->rewrite_flush();
		
		// PayPal Mass Pay Cron Hook
		wp_schedule_event( current_time ( 'timestamp', false ), 'hourly', 'maybe_process_mass_pay' );

	}
	
	
	function deactivation() {
	
		wp_clear_scheduled_hook( 'maybe_process_mass_pay' );
	
	}
	
	
	function cron_process_payments_hook() { 

		if ( empty( $_GET['masspay'] ) || 'go' != $_GET['masspay'] )
			return;
		
		$this->maybe_process_mass_pay();

	}

	
	function maybe_process_mass_pay() {
			
		require_once( dirname( __FILE__ ) . '/gateways/class-wc-paypal-masspay.php' );
		
		$mass_pay = new IgniteWoo_PayPal_MassPay();
		
		$mass_pay->process_payments();
	
	}
	
	
	function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'ignitewoo_vendor_stores' );

		// Allow upgrade safe, site specific language files in /wp-content/languages/woocommerce-subscriptions/
		load_textdomain( 'ignitewoo_vendor_stores', WP_LANG_DIR . '/ignitewoo_vendor_stores-'.$locale.'.mo' );

		$plugin_rel_path = apply_filters( 'ignitewoo_translation_file_rel_path', dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		load_plugin_textdomain( 'ignitewoo_vendor_stores', false, $plugin_rel_path );

	}
	
	
	function init() { 
		global $user_ID, $post;
		
		if ( !empty( $this->settings['logo_width'] ) ) 
			$width = $this->settings['logo_width'];
		
		if ( !empty( $this->settings['logo_height'] ) ) 
			$height = $this->settings['logo_height'];
		
		if ( !empty( $width ) && !empty( $height ) ) { 	
			add_image_size( 'store_logo_size', $this->settings['logo_width'], $this->settings['logo_height'], false );
		}
		
		if ( current_user_can( 'vendor' ) )
			return;

		if ( !empty( $user_ID ) && !empty( $_GET['request_vendor_access'] ) && 'go' == $_GET['request_vendor_access'] ) { 

			$result = $this->do_ign_vendor_access_request();

			// Request sent
			if ( 1 === $result ) {
			
				update_user_meta( $user_ID, 'vendor_request', 1 );
				
				$user = new WP_User( $user_ID );

				if ( empty( $user ) || is_wp_error( $user ) ) 
					return;
					
				// Remove customer or subscriber role
				$user->remove_role( 'customer' );
				
				$user->remove_role( 'subscriber' );

				// Add pending vendor role
				$user->add_role( 'pending_vendor' );
			
				$url = remove_query_arg( 'request_vendor_access' );
								
				wp_redirect( $url );
				
				die;
				
			}
			
			// Request automatically granted per settings
			if ( true === $result ) {
			
				$this->send_vendor_welcome_email( $user_ID );
			
				$url = remove_query_arg( 'request_vendor_access' );
				
				wp_redirect( $url );
				
				die;
				
			}
		}
		
	}

	
	function wp() { 
		global $post, $user_ID;

		if ( !is_product() ) 
			return;
			
		if ( !empty( $post->ID ) && !empty( $this->settings['disallow_owner_bidding'] ) && 'yes' == $this->settings['disallow_owner_bidding'] ) { 
	
			if ( $post->post_author != $user_ID )
				return;;
		
			global $ignitewoo_auctions;

			remove_action( 'woocommerce_single_product_summary', array( $ignitewoo_auctions, 'bid_form' ), $ignitewoo_auctions->bid_form_position );

		}
		
	}
	
	
	function maybe_add_body_class( $classes = array() ) { 
	
		if ( is_tax( 'shop_vendor' ) )
			$classes[] = 'woocommerce';

		return $classes;
		
	}
	
	
	function maybe_add_order_note() { 
		global $user_ID;

		// Maybe add a new note
		if ( !empty( $_POST['new_order_note'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'add_order_note' ) ) { 

			if ( !isset( $_GET['order'] ) || absint( $_GET['order'] ) <= 0 )
				return;
				
			if ( !$order = $this->get_order( absint( $_GET['order'] ) ) )
				return;

			$vendor = ign_get_user_vendor( $user_ID );
			
			if ( empty( $vendor ) )
				returm;
				
			$new_note = strip_tags( $_POST['new_order_note'] );
			
			if ( !empty( $new_note ) ) { 
			
				if ( 'yes' == $this->settings['add_vendor_to_note'] )
					$new_note = __( 'From: ', 'ignitewoo_vendor_stores' ) . ': ' . $vendor->title . '<br>' . $new_note;
				
				if ( empty( $_POST['send_to_customer'] ) )
					$is_new_customer_note = 0;
				else 
					$is_new_customer_note = 1; 

				if ( !empty( $new_note ) )
					$comment_id = $order->add_order_note( $new_note, $is_new_customer_note );

				if ( empty( $comment_id ) )
					set_transient( 'note_add_error_' . $order->id . '_' . $user_ID, 1, 90 );
					// $note_add_error = true;
				else 
					set_transient( 'note_added_' . $order->id . '_' . $user_ID, $is_new_customer_note, 90 );
					//$note_added = true;
			
				// Note which vendor added the note - used to filter notes display when vendors view an order
				add_comment_meta( $comment_id, 'vendor_added_note', $vendor->ID );
					
			} else { 
			
				set_transient( 'note_add_error_' . $order->id . '_' . $user_ID, 1, 90 );
				//$note_add_error = true;
				
			}
			
			wp_redirect( remove_query_arg( 'order' ) );
			
			die;
		}
	}
	
	
	function maybe_redirect_vendor() { 
		global $user_ID, $pagenow, $woocommerce;

		if ( current_user_can( 'administrator' ) )
			return;
			
		// Vendors cannot access profile page, so redirect to dashboard
		if ( 'profile.php' == $pagenow and current_user_can( 'vendor' ) ) {
		
			wp_redirect( admin_url() );
			
			die;
		}
	
		if ( !is_admin() )
			return;
			
		if ( !current_user_can( 'vendor' ) )
			return;
			
		if ( !empty( $_GET['store_setup'] ) )
			return;

		$vendor = ign_get_user_vendor( $user_ID );
		
		$paypal_configed = $title_configed = false;

		if ( 'disabled' == $this->settings['paypal_required'] || 'no' == $this->settings['paypal_required'] ) 
			$paypal_configed = true;
			
		if ( 'yes' == $this->settings['paypal_required'] && !empty( $vendor->paypal_email ) ) 
			$paypal_configed = true;
			
		if ( !empty( $vendor->title ) )
			$title_configed = true;
			
		if ( $paypal_configed && $title_configed )
			return;
		
		$url = admin_url( 'admin.php?page=vendor_details&store_setup=true' );
		
		wp_redirect( $url );
		
		die;
		
	}
	
	
	function frontend_styles_scripts() { 
		global $wp_scripts;
		
		if ( file_exists( get_stylesheet_directory() . '/store_vendors/frontend.css' ) )
			$styles = get_stylesheet_directory_uri() . '/frontend.css';
		else 
			$styles = $this->assets_url . 'css/frontend.css';

		wp_enqueue_style( 'vendor_stores_style', $styles );
		
		if ( !empty( $_GET['view_vendor_order'] ) ) {
			wp_enqueue_script( 'jquery-ui-datepicker' );
		
			$jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.9.2';

			 wp_enqueue_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/' .  $jquery_version . '/themes/smoothness/jquery-ui.css', array(), WOOCOMMERCE_VERSION );
		}
	
	}
	
	
	function vendor_slug( $slug = 'vendor' ) { 
	
		if ( empty( $this->settings['default_slug'] ) )
			return $slug;
			
		return $this->settings['default_slug'];
	
	}
	
	
	function register_vendors_taxonomy() {

		$vendor_slug = apply_filters( 'vendor_stores_vendor_slug', 'vendor' );
		
		$labels = array(
			'name' => __( 'Vendor Stores' , 'ignitewoo_vendor_stores' ),
			'singular_name' => __( 'Store', 'ignitewoo_vendor_stores' ),
			'menu_name' => __( 'Stores' , 'ignitewoo_vendor_stores' ),
			'search_items' =>  __( 'Search Stores' , 'ignitewoo_vendor_stores' ),
			'all_items' => __( 'All Stores' , 'ignitewoo_vendor_stores' ),
			'parent_item' => __( 'Parent Store' , 'ignitewoo_vendor_stores' ),
			'parent_item_colon' => __( 'Parent Store:' , 'ignitewoo_vendor_stores' ),
			'view_item' => __( 'View Store' , 'ignitewoo_vendor_stores' ),
			'edit_item' => __( 'Edit Store' , 'ignitewoo_vendor_stores' ),
			'update_item' => __( 'Update Store' , 'ignitewoo_vendor_stores' ),
			'add_new_item' => __( 'Add New Store' , 'ignitewoo_vendor_stores' ),
			'new_item_name' => __( 'New Store Name' , 'ignitewoo_vendor_stores' ),
			'popular_items' => __( 'Popular Stores' , 'ignitewoo_vendor_stores' ),
			'separate_items_with_commas' => __( 'Separate stores with commas' , 'ignitewoo_vendor_stores' ),
			'add_or_remove_items' => __( 'Add or remove stores' , 'ignitewoo_vendor_stores' ),
			'choose_from_most_used' => __( 'Choose from most used stores' , 'ignitewoo_vendor_stores' ),
			'not_found' => __( 'No stores found' , 'ignitewoo_vendor_stores' ),
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'hierarchical' => false,
			'rewrite' => array( 'slug' => $vendor_slug ),
			'show_admin_column' => true,
		);

		register_taxonomy( $this->token, 'product', $args );
	

	}

	
	function do_ign_vendor_access_request() { 
		global $user_ID; 
		
		if ( empty( $user_ID ) )
			return;

		$user = new WP_User( $user_ID );

		if ( empty( $user ) || is_wp_error( $user ) ) 
			return;
			
		if ( 'automatic' == $this->settings['vendor_access'] ) {

			// Remove customer or subscriber role
			$user->remove_role( 'customer' );
			
			$user->remove_role( 'subscriber' );

			// Add role
			$user->add_role( 'vendor' );
			
			return true;
		
		}

		$settings = get_option( 'woocommerce_new_order_settings' );
		
		$to = $settings['recipient'];

		if ( empty( $to ) )
			$to = get_option( 'woocommerce_email_from_address' );
			
		if ( empty( $to ) )
			$to = get_option( 'admin_email' );

		if ( empty( $to ) )
			return;

		$fname = get_user_meta( $user_ID, 'billing_first_name', true ); 
		
		$lname = get_user_meta( $user_ID, 'billing_last_name', true ); 
		
		$user_edit_url = admin_url( 'user-edit.php?user_id=' . $user_ID );

		$sitename = get_option( 'blogname' );
			
		// handle special chars in the site name
		$sitename = wp_specialchars_decode( $sitename , ENT_QUOTES );
		
		$heading = $subject = __( 'Vendor Access Request', 'ignitewoo_vendor_stores' );

		global $woocommerce;

		$mailer = $woocommerce->mailer(); // woocommerce_email = new WC_Emails();

		if ( !has_action( 'woocommerce_email_header', array( $mailer, 'email_header' ) ) )
			add_action( 'woocommerce_email_header', array( $mailer, 'email_header' ) );
			
		if ( !has_action( 'woocommerce_email_footer', array( $mailer, 'email_footer' ) ) )
			add_action( 'woocommerce_email_footer', array( $mailer, 'email_footer' ) );
			
		if ( !has_action( 'woocommerce_email_order_meta', array( $mailer, 'order_meta' ) ) )
			add_action( 'woocommerce_email_order_meta', array( $mailer, 'order_meta' ), 10, 3 );
			
		$template = locate_template( array( 'store_vendors/vendor_request_email.php' ), false, false );

		ob_start();

		if ( '' != $template ) 
			require_once( $template );
		else 
			require_once( dirname( __FILE__ ) . '/../templates/vendor_request_email.php' );

		$msg_body = ob_get_contents();

		ob_end_clean();

		woocommerce_mail( $to, $subject, $msg_body );
		
		return 1;
		
	}

	
	function maybe_remove_vendor_request_flag( $user_id ) { 
	
		if ( empty( $user_id ) ) 
			return;

		// If the user has the vendor_request flag set then they have not been sent a welcome email yet
		if ( 'vendor' == $_POST['role'] && get_user_meta( $user_id, 'vendor_request' ) ) 
			$this->send_vendor_welcome_email( $user_id );
		
		if ( 'pending_vendor' != $_POST['role'] )
			delete_user_meta( $user_id, 'vendor_request' );
	
	}


	function send_vendor_welcome_email( $user_id ) { 

		$user = new WP_User( $user_id ); 
		
		$to = $user->data->user_email;

		if ( empty( $to ) )
			return;
		
		$fname = get_user_meta( $user_ID, 'billing_first_name', true ); 
		
		$myaccount_page_url = get_permalink( woocommerce_get_page_id( 'myaccount' ) );

		$sitename = get_option( 'blogname' );
			
		// handle special chars in the site name
		$sitename = wp_specialchars_decode( $sitename , ENT_QUOTES );
		
		$heading = $subject = __( 'Vendor Access Granted', 'ignitewoo_vendor_stores' );
		
		$subject = __( 'Welcome!', 'ignitewoo_vendor_stores' );

		global $woocommerce;

		$mailer = $woocommerce->mailer(); // woocommerce_email = new WC_Emails();

		if ( !has_action( 'woocommerce_email_header', array( $mailer, 'email_header' ) ) )
			add_action( 'woocommerce_email_header', array( $mailer, 'email_header' ) );
			
		if ( !has_action( 'woocommerce_email_footer', array( $mailer, 'email_footer' ) ) )
			add_action( 'woocommerce_email_footer', array( $mailer, 'email_footer' ) );
			
		if ( !has_action( 'woocommerce_email_order_meta', array( $mailer, 'order_meta' ) ) )
			add_action( 'woocommerce_email_order_meta', array( $mailer, 'order_meta' ), 10, 3 );

		$template = locate_template( array( 'store_vendors/vendor_welcome_email.php' ), false, false );

		ob_start();

		if ( '' != $template ) 
			require( $template );
		else 
			require( dirname( __FILE__ ) . '/../templates/vendor_welcome_email.php' );

		$msg_body = ob_get_contents();

		ob_end_clean();

		woocommerce_mail( $to, $subject, $msg_body );

	}

	
	function get_total_published( $user_id ) { 
		global $wpdb;
	
		$sql = 'select count(*) from ' . $wpdb->posts . ' where post_status = "publish" and post_author = "'. $user_id . '" and post_type = "product"';
		
		return $wpdb->get_var( $sql );
		
	}
	
	
	function check_current_user_caps() { 
		global $user_ID;

		if ( empty( $user_ID ) )
			return;

		if ( !current_user_can( 'vendor' ) )
			return;

		$threshold = $this->settings['automatic_threshold'];
	
		$trusted_vendors = $this->settings['trusted_vendors'];

		if ( empty( $trusted_vendors ) )
			$trusted_vendors = array();
	
		if ( empty( $threshold ) && empty( $trusted_vendors ) )
			return;

		$user = new WP_User( $user_ID );
			
		if ( in_array( $user_ID, $trusted_vendors )  )
			$user->add_cap( 'publish_products'  );
		else 
			$user->remove_cap( 'publish_products'  );

		$total_published = $this->get_total_published( $user_ID );

		if ( empty( $total_published ) )
			return;

		if ( $total_published >= $threshold ) 
			$user->add_cap( 'publish_products'  );
			
	}
	
	
	function add_vendor_caps( $user_id = 0 ) {
	
		if ( empty( $user_id ) )
			return;
			
		$u = new WP_User( $user_id );

		// Remove customer or subscriber role
		$u->remove_role( 'customer' );
		
		$u->remove_role( 'subscriber' );

		// Add vendor role
		$u->add_role( 'vendor' );
		
	}
	

	function remove_vendor_caps( $user_id = 0 ) {
	
		if ( empty( $user_id ) )
			return;
			
		$u = new WP_User( $user_id );

		// Remove vendor
		$u->remove_role( 'vendor' );

		// Add customer role
		$u->add_role( 'customer' );
		
	}
	

	function vendor_caps() {
	
		$caps = array(
			// "publish_products" => true, // not added unless threshold is met or exceeded
			"read"	=> true,
			"edit_product" => true,
			"read_product" => true,
			"delete_product" => true,
			"edit_products" => true,
			"edit_others_products" => true,
			"delete_products" => true,
			"delete_published_products" => true,
			"delete_others_products" => false,
			"edit_published_products" => true,
			"assign_product_terms" => true,
			"upload_files" => true,
			'view_woocommerce_reports' => true,
		);

		$more_caps = array();
		
		if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) ) { 
		
			if ( !empty( $this->settings['enable_coupons'] ) && 'yes' == $this->settings['enable_coupons'] ) {
			
				$more_caps = array(
					"edit_shop_coupon" => true,
					"read_shop_coupon" => true,
					"delete_shop_coupon" => true,
					"edit_shop_coupons" => true,
					"edit_others_shop_coupons" => true,
					"publish_shop_coupons" => true,
					"read_private_shop_coupons" => true,
					"delete_shop_coupons" => true,
					"delete_private_shop_coupons" => true,
					"delete_published_shop_coupons" => true,
					"delete_others_shop_coupons" => true,
					"edit_private_shop_coupons" => true,
					"edit_published_shop_coupons" => true,	
				);
				
			}
			
		} else { 
		
			$more_caps = array(
				"edit_shop_coupon" => false,
				"read_shop_coupon" => false,
				"delete_shop_coupon" => false,
				"edit_shop_coupons" => false,
				"edit_others_shop_coupons" => false,
				"publish_shop_coupons" => false,
				"read_private_shop_coupons" => false,
				"delete_shop_coupons" => false,
				"delete_private_shop_coupons" => false,
				"delete_published_shop_coupons" => false,
				"delete_others_shop_coupons" => false,
				"edit_private_shop_coupons" => false,
				"edit_published_shop_coupons" => false,	
			);
			
		}
		
		$caps = array_merge( $caps, $more_caps );
		
		if ( isset( $this->settings['automatic_threshold'] ) && '0' == $this->settings['automatic_threshold'] )
			$caps['publish_products'] = true;

		$caps = apply_filters( 'store_vendor_caps', $caps );

		remove_role( 'vendor' );

		add_role( 'vendor', __( 'Vendor', 'ignitewoo_vendor_stores' ), $caps );		
			
		remove_role( 'pending_vendor' );
		
		add_role( 'pending_vendor', __( 'Pending Vendor', 'ignitewoo_vendor_stores' ), array(
				'read'         => true,
				'edit_posts'   => false,
				'delete_posts' => false
			) );
			
	}

	
	function remove_commissions( $order_id ) { 
		global $wpdb;
		
		$sql = 'select ID from ' . $wpdb->posts . ' left join ' . $wpdb->postmeta . ' on ID = post_id 
		where post_type="' . $this->commissions->token . '" and meta_key="_order_id" and meta_value="' . $order_id . '"';
		
		$coms = $wpdb->get_results( $sql );

		if ( empty( $coms ) )
			return;
			
		foreach( $coms as $com )
			wp_delete_post( $com->ID, true );
			
		update_post_meta( $order_id, '_commissions_processed', 'no' );
		
		delete_post_meta( $order_id, '_vendor_shipping_due' );
	
	}
	
	
	function new_order_email( $order_id ) { 
		global $wpdb;

		if ( empty( $order_id ) )
			return;
			
		$order = new WC_Order( $order_id );
		
		if ( empty( $order ) )
			return;
			
		$order_items = array();
		
		foreach( $order->get_items() as $item_key => $item ) { 
		
			$_product = $order->get_product_from_item( $item );

			if ( empty( $_product ) )
				continue;
				
			// get the product author
			$sql = 'select post_author from ' . $wpdb->posts . ' where ID = ' . $_product->id;
			
			$post_author = $wpdb->get_var( $sql );

			if ( empty( $post_author ) )
				continue;
				
			// check if author is a vendor
			$vendor = ign_get_user_vendor( $post_author );

			if ( empty( $vendor->ID ) ) 
				continue;
			
			$item['vendor_id'] = $vendor->ID;
			
			$item['user_id'] = $post_author;
			
			$order_items[ $vendor->ID ][ $_product->id ] = $item;

		}

		if ( empty( $order_items ) )
			return;

		global $woocommerce;

		$mailer = $woocommerce->mailer(); // woocommerce_email = new WC_Emails();

		if ( !has_action( 'woocommerce_email_header', array( $mailer, 'email_header' ) ) )
			add_action( 'woocommerce_email_header', array( $mailer, 'email_header' ) );
			
		if ( !has_action( 'woocommerce_email_footer', array( $mailer, 'email_footer' ) ) )
			add_action( 'woocommerce_email_footer', array( $mailer, 'email_footer' ) );
			
		if ( !has_action( 'woocommerce_email_order_meta', array( $mailer, 'order_meta' ) ) )
			add_action( 'woocommerce_email_order_meta', array( $mailer, 'order_meta' ), 10, 3 );
		
		$settings = get_option( 'woocommerce_new_order_settings' );

		$subject = apply_filters( 'ignitewoo_vendor_stores_new_order_email_subject', $settings['subject'] );
		
		$subject = str_replace( '{site_title}', wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ), $subject );
		
		$subject = str_replace( '{order_number}', '#' . $order_id, $subject );
		
		$subject = str_replace( '{order_date}', date_i18n( woocommerce_date_format(), strtotime( $order->order_date ) ), $subject );

		$email_heading = apply_filters( 'ignitewoo_vendor_stores_new_order_email_subject', $settings['heading'] );
		
		if ( empty( $email_heading ) )
			$email_header = __( 'New Customer Order', 'ignitewoo_vendor_stores' );
			
		// Process order items notices to each vendor in the order
		foreach( $order_items as $vendor_id => $items ) { 
		
			foreach( $items as $item ) { 
			
				$user_id = $item['user_id'];
				
				break;
			
			}
			
			$to = $wpdb->get_var( 'select user_email from ' . $wpdb->users . ' where ID=' . $user_id );
			
			if ( empty( $to ) )
				continue; 
		
			$template = locate_template( array( 'store_vendors/vendor_store_new_order_email.php' ), false, false );

			ob_start();

			if ( '' != $template ) 
				require( $template );
			else 
				require( dirname( __FILE__ ) . '/../templates/vendor_store_new_order_email.php' );

			$msg_body = ob_get_contents();
			
			$headers = apply_filters( 'ignitewoo_vendor_new_order_email_headers', array(), $vendor, $items ); 

			// Send the notice with the items listed
			if ( function_exists( 'wc_mail' ) )
				wc_mail( $to, $subject, $msg_body, $headers );
			else 
				woocommerce_mail( $to, $subject, $msg_body, $headers );
		}
	
	}
	
	
	function show_vendor_in_email( $name, $_product ) { 

		if ( 'yes' != $this->settings['show_soldby_email'] )
			return $name; 
		
		if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) )
			$product = get_post( $_product['product_id'] );
		else 
			$product = get_post( $_product->id );

		$vendor = ign_get_user_vendor( $product->post_author );

		if ( empty( $vendor->ID ) )
			return $name;
			
		$sold_by = $vendor->title;
		
		$name .= '<small><br />' . __('Sold by', 'wc_product_vendor') . ': ' . $sold_by . '</small><br />';

		return $name;
	
	}
	
	
	function sold_by( $values, $cart_item ) {

		if ( 'yes' != $this->settings['show_soldby_cart'] )
			return array();
			
		$data = array();
		
		$author_id = $cart_item['data']->post->post_author;
		
		$vendor = ign_get_user_vendor( $author_id );

		if ( empty( $vendor->ID ) )
			return $data;
			
		$sold_by = $vendor->title;
		
		$data[] = array(
			'name'    => __( 'Sold by', 'wc_product_vendor' ),
			'display' => $sold_by,
		);

		return $data;
	}
	

	function add_commissions( $order_id ) {
//delete_post_meta( $order_id, '_commissions_processed' );
//return;
//var_dump( get_post_meta( $order_id, '_commissions_processed', true ) );
//die;
		// Only process commissions once
		if ( 'yes' == get_post_meta( $order_id, '_commissions_processed', true ) )
			return;

		$order = new WC_Order( $order_id );

		$items = $order->get_items( 'line_item' );

		$total_tax = array();
		
		
		/**

		Payment gateways handle determining if a seller in the order has a related account. 

		If they do the commission is paid to them at the time payment is processed. 

		If not, they note that Seller ID # X was not paid.

		Gateway adds order meta data with an array of sellers IDs => paid or unpaid.

		This plugin checks the order meta to determine who is and isn't paid and adds the commission record accordingly

		*/ 
		
		
		$vendor_tax = array();
		
		$sellers = (array)get_post_meta( $order_id, '_commission_recipients', true );

		foreach( $items as $item_id => $item ) {

			$item_meta = $item['item_meta'];
		
			$product_id = $order->get_item_meta( $item_id, '_product_id', true );
			
			$variation_id = $order->get_item_meta( $item_id, '_variation_id', true );
			
			$line_total = $order->get_item_meta( $item_id, '_line_total', true );
			
			$vendors = ign_get_product_vendors( $product_id );

			if ( empty( $vendors ) )
				continue;

						
			if ( 'yes' == $this->settings['give_vendor_tax'] )
				$tax = !empty( $item['line_tax'] ) ? (float)$item['line_tax'] : 0;
			else 
				$tax = 0;
			
			if ( !empty( $vendor_tax[ $vendors[0]->ID ] ) ) { 
				$vendor_tax[ $vendors[0]->ID ] += $tax; 
				$vendor_tax[ $vendors[0]->ID ] = round( $vendor_tax[ $vendors[0]->ID ], 2 );
			 } else
				$vendor_tax[ $vendors[0]->ID ] = round( $tax, 2 );
				
			$shipping = 0;

			// Get shipping due for the order, for each vendor whose items are part of the order
			if ( 'yes' == $this->settings['give_vendor_shipping'] )
			foreach( $vendors as $vendor ) { 
				
				if ( empty( $vendor->ID ) )
					continue;

				$shipping = $this->shipping->get_vendor_shipping_amount( $order, $item_id, $item, $vendor->ID );

				// Only one vendor per store and per product
				break;
				
			}

			if ( 'yes' == $this->settings['include_shipping'] ) {
			
				$shipping = ign_calculate_shipping_commission( $product_id, $variation_id, $vendor->ID, $shipping );

				$shipping_tax = $this->shipping->shipping_tax( $order_id, $product_id, $variation_id, $vendor->ID );
				
				$tax += $shipping_tax;
				
				$vendor_tax[ $vendors[0]->ID ] += $shipping_tax;
			}

			//$vendors = ign_get_product_vendors( $product_id );
			
			if ( $product_id && $line_total && $vendors ) { 
			
				foreach( $vendors as $vendor ) {
			
					$commission = ign_calculate_product_commission( $line_total, $product_id, $variation_id, $vendor->ID, $order_id, $item );

					if ( empty( $commission ) || false == $commission )
						continue;
						
					/** 
					Each gateway must track a list of sellers of items in the order along with whether they have
					an associated gateway account so that they can be paid. This data is stored as an
					array( Vendor_ID => 'paid' ) or 'unpaid' ) so this plugin is payment gateway agnostic. 
					Simply read the order '_commission_recipients' meta and see who has or hasn't been paid and
					record the commission accordingly. Sites operators will need to check the commissions and
					process any unpaid commissions either via MassPay or some other method. 
					
					If an item has a vendor seller but no related '_commission_recipients' exists for the order
					then the site is using an unsupported split payment gateway and thus the sellers in the order 
					always get their commission recorded as unpaid. 
					*/
					
					$commission_paid = false; // default

					if ( !empty( $sellers ) ) {
					
						foreach( $sellers as $v => $s ) {

							if ( $v == $vendor->ID && 'paid' == $s  )
								$commission_paid = true;
							else if (  $v == $vendor->ID && 'unpaid' == $s )
								$commission_paid = false;
								
						}
						
					} else if ( empty( $sellers ) ) {
					
						$commission_paid = false;
						
					}

					$this->add_new_commission( $order, $vendor->ID, $product_id, $variation_id, $commission, $item_id, $item_meta, $shipping, $tax, $commission_paid );

				}

			}

			
		}

		update_post_meta( $order_id, '_vendor_tax_due', $vendor_tax );

		$vendor_shipping = array();
			
		// Recording shipping due to vendors if that option is on
		if ( 'yes' == $this->settings['give_vendor_shipping'] )
		foreach( $items as $item_id => $item ) {

			$vendors = ign_get_product_vendors( $item['product_id'] );
		
			foreach( $vendors as $vendor ) { 
				
				if ( empty( $vendor->ID ) )
					continue;
					
				$shipping = $this->shipping->get_vendor_shipping_amount( $order, $item_id, $item, $vendor->ID );

				if ( !empty( $vendor_shipping[ $vendor->ID ] ) )
					$vendor_shipping[ $vendor->ID ] += $shipping;
				else
					$vendor_shipping[ $vendor->ID ] = $shipping;
					
				// Only one vendor per store and per product
				break;
				
			}
		}
		
		update_post_meta( $order_id, '_vendor_shipping_due', $vendor_shipping );

		// Mark commissions as processed
		update_post_meta( $order_id, '_commissions_processed', 'yes' );

	}
	

	function add_new_commission( $order, $vendor_id = 0, $product_id = 0, $variation_id = 0, $amount = 0, $item_id, $item_meta, $shipping, $tax, $commission_paid ) {

//var_dump( $this->settings['include_shipping'] );
//var_dump( $amount, $shipping, $tax, $commission_paid ); 
//die;

		if ( empty( $this->commissions ) ) {
		
			require_once( dirname( __FILE__ ) . '/class-wc-vendor-stores-commissions.php' );
	
			$this->commissions = new IgniteWoo_Vendor_Stores_Commissions();
	
		}
		
		$user = '';
		
		if ( $order->user_id ) { 
		
			$user_info = get_userdata( $order->user_id );
	 
			if ($user_info->first_name || $user_info->last_name) 
				$user .= $user_info->first_name.' '.$user_info->last_name;
			else 
				$user .= esc_html( $user_info->display_name );

		} else 
			$user = __('Guest', 'woocommerce');
		
		$title = __( 'For Order #', 'ignitewoo_vendor_stores' ) . $order->id . ' ' . __( 'made by', 'ignitewoo_vendor_stores' ) . ' ' . $user;
		
		$commission_data = array(
			'post_type'     => $this->commissions->token,
			'post_title'    => $title,
			'post_status'   => 'publish',
			'ping_status'   => 'closed',
			'post_excerpt'  => '',
			'post_author'   => 1
		);

		$commission_id = wp_insert_post( $commission_data );

		if ( $vendor_id > 0 ) 
			add_post_meta( $commission_id, '_commission_vendor', $vendor_id );
			
		if ( $product_id > 0 ) 
			add_post_meta( $commission_id, '_commission_product', $product_id );
			
		if ( $variation_id > 0 ) 
			add_post_meta( $commission_id, '_commission_variation', $variation_id );
			
		add_post_meta( $commission_id, '_amount', $amount );


		/* 
		Amount should be commission for the product
		Shipping is the shipping amount to the vendor, taking into consideration the include_shipping setting 
			if yes, shipping amount is calculated using the vendor's commission percentage
			if no, shipping amount is full shipping amount as paid by shopper
		Tax is the tax collected for the product if any
		Total is Amount + Shipping
		
		float(0.92379) float(2.5) float(0.96835)
		float(4.39214) 
		*/

		// If shipping is given to vendor and there is a shipping amount add it to the total?
		if ( $shipping && 'yes' == $this->settings['include_shipping'] )
			$amount += $shipping;
			
		// May add in the tax
		if ( $tax )
			$amount += $tax;

		if ( $amount > 0 ) 
			add_post_meta( $commission_id, '_commission_amount', $amount );
			
		add_post_meta( $commission_id, '_shipping', $shipping );
		
		add_post_meta( $commission_id, '_tax', $tax );

		add_post_meta( $commission_id, '_item_id', $item_id );
		
		add_post_meta( $commission_id, '_item_meta', $item_meta );
		
		// Maybe set commission as paid - if a gateway supports paying vendors immediately and that option 
		// is selected. See add_commissions() 
		if ( $commission_paid )
			add_post_meta( $commission_id, '_paid_status', 'paid' );
		else 
			add_post_meta( $commission_id, '_paid_status', 'unpaid' );
		
		add_post_meta( $commission_id, '_order_id', $order->id );

	}

	
	function allow_vendor_admin_access( $prevent_access ) {

		if ( ign_vendor_access() )
			$prevent_access = false;

		return $prevent_access;
	}


	
	
	function my_account_before() { 
	
		if ( 'request_access' != $this->settings['vendor_access'] && 'automatic' != $this->settings['vendor_access'] )
			return;

		store_vendors_get_template( 'vendor_store_request_access.php' );
	
	
	}
	
	
	function get_order( $order_id = 0 ) { 
		global $user_ID;
		
		if ( empty( $order_id ) )
			return false;
			
		if ( !ign_is_vendor() )
			return false;
		
		$vendor_id = ign_get_user_vendor( $user_ID );

		if ( empty( $vendor_id ) )
			return false;
			
		$vendor_id = $vendor_id->ID;

		$products = ign_get_vendor_products( $vendor_id );

		if ( empty( $products ) )
			return false;
			
		$ids = array();
	
		foreach ( $products as $product )
			$ids[] = $product->ID;
			
		if ( empty( $ids ) )
			return false;
			
		$order = new WC_Order( $order_id ); 

		if ( empty( $order ) )
			return false; 

		$is_owner = false;
		
		$order_total = 0;
		
		$order_qty = 0;
		
		$order_items = $order->get_items(); 
			
		$is_owner = false;
		
		$vendor_items = array();
		
		foreach( $order_items as $item_key => $item ) {

			$_product = $order->get_product_from_item( $item );
			
			if ( empty( $_product ) )
				continue; 

			if ( in_array( $_product->id, $ids ) ) { 

				$is_owner = true;
				
				$variation_id = $order->get_item_meta( $item_key, '_variation_id', true );
				
				$shipping = 0;

				// Get shipping due for the order, for each vendor whose items are part of the order

				if ( 'yes' == $this->settings['give_vendor_shipping'] ) {
				
					$vendors = ign_get_product_vendors( $_product->id );
					
					foreach( $vendors as $vendor ) { 
						
						if ( empty( $vendor->ID ) )
							continue;
							
						$shipping = $this->shipping->get_vendor_shipping_amount( $order, $item_key, $item, $vendor->ID );

						// Only one vendor per store and per product
						break;
						
					}
				}
				
				if ( 'yes' == $this->settings['include_shipping'] )
					$shipping = ign_calculate_shipping_commission( $product->id, $variation_id, $vendor_id, $shipping );
				
				
				$order_total += ign_calculate_product_commission( $item['line_total'], $_product->id, $variation_id, $vendor_id, $order_id, $item );
				
				$order_qty += $item['qty'];
				
				$vendor_items[ $item_key ] = $item;
			}
		}

		if ( !$is_owner )
			return false;

		$order->order_items = $vendor_items;
		
		$order->order_total = $order_total;
		
		$order->order_qty = $order_qty;

		return $order;
		
		
			
	}
	
	
	// Filters order items via WooCommerce hook to reduce the order to only what belongs to the vendor viewing the order
	function filter_order_items( $items, $order ) { 
		global $user_ID;

		if ( is_admin() )
			return $items;
			
		if ( empty(  $this->settings['dashboard_page'] ) || !is_page( $this->settings['dashboard_page'] ) )
			return $items;

		if ( empty( $items ) )
			return $items;
			
		$vendor_data = ign_get_user_vendor( $user_ID );
		
		if ( empty( $vendor_data->ID ) )
			return $items;
			
		$products = ign_get_vendor_products( $vendor_data->ID );
			
		if ( empty( $products ) )
			return $items;
			
		$ids = array();
	
		foreach ( $products as $product )
			$ids[] = $product->ID;
			
		foreach( $items as $item_key => $item ) { 

			if ( empty( $item['item_meta']['_product_id'][0] ) )
				continue;
				
			if ( !in_array( $item['item_meta']['_product_id'][0], $ids ) ) { 
			
				unset( $items[ $item_key ] );
				
			}
		
		}
		
		return $items;
	
	}
	
	
	// Adjust order subtotal display using vendor's actual commission recorded for the sale
	function get_subtotal_to_display( $subtotal, $compound, $order ) {
		global $woocommerce, $user_ID, $wpdb;
		
		if ( is_admin() )
			return $subtotal;
			
		if ( !current_user_can( 'vendor' ) )
			return $subtotal;

		if ( empty( $tax_display ) )
			$tax_display = $order->tax_display_cart;

		$subtotal = 0;

		$vendor_data = ign_get_user_vendor( $user_ID );
		
		if ( empty( $vendor_data->ID ) )
			return $subtotal;
			
		if ( ! $compound ) {

			foreach ( $order->get_items() as $item ) {

				if ( !isset( $item['line_subtotal'] ) || !isset( $item['line_subtotal_tax'] ) ) 
					return;
					
				$sql = 'select m2.meta_value as commission from ' . $wpdb->posts . ' 
					left join ' . $wpdb->postmeta . ' m1 on m1.post_id = ID
					left join ' . $wpdb->postmeta . ' m2 on m2.post_id = ID
					where post_type="vendor_commission" 
					and m1.meta_key="_order_id" and m1.meta_value="'. $order->id . '"
					and m2.meta_key="_commission_amount"
					';
					
				$commission = $wpdb->get_var( $sql );

				$subtotal += $commission;

				if ( $tax_display == 'incl' ) {
					$subtotal += $item['line_subtotal_tax'];
				}

			}

			$subtotal = woocommerce_price( $subtotal );

			if ( $tax_display == 'excl' && $order->prices_include_tax )
				$subtotal .= ' <small>' . $woocommerce->countries->ex_tax_or_vat() . '</small>';

		} else {

			if ( $tax_display == 'incl' )
				return;

			foreach ( $order->get_items() as $item ) {

				$sql = 'select m2.meta_value as commission from ' . $wpdb->posts . ' 
					left join ' . $wpdb->postmeta . ' m1 on m1.post_id = ID
					left join ' . $wpdb->postmeta . ' m2 on m2.post_id = ID
					where post_type="' . $this->commissions->token . '" 
					and m1.meta_key="_order_id" and m1.meta_value="'. $order->id . '"
					and m2.meta_key="_commission_amount"
					';
					
				$commission = $wpdb->get_var( $sql );

				$subtotal += $commission;
			
				// $subtotal += $item['line_subtotal'];

			}

			// Add Shipping Costs
			$subtotal += $this->get_shipping();

			// Remove non-compound taxes
			foreach ( $order->get_taxes() as $tax ) {

				if ( ! empty( $tax['compound'] ) ) 
					continue;

				$subtotal = $subtotal + $tax['tax_amount'] + $tax['shipping_tax_amount'];

			}

			// Remove discounts
			$subtotal = $subtotal - $order->get_cart_discount();

			$subtotal = woocommerce_price( $subtotal );

		}

		return $subtotal;
		
	}
	
	
	// Get order total based on vendor's actual commission as record for the sale
	function get_formatted_order_total( $formatted_total, $order ) {

//Check if sending an order note and if so handle that

		if ( is_admin() || !current_user_can( 'vendor' ) ) 
			return $formatted_total; 
			
		// Item line totals, minus coupons, plus any due taxes, plus shipping
		$formatted_total = woocommerce_price( $order->order_total );

	}
	
	
	function vendor_totals_data() { 
		global $user_ID;
		
		if ( !ign_is_vendor() )
			return false;
		
		$vendor_id = ign_get_user_vendor( $user_ID );

		if ( empty( $vendor_id ) )
			return false;
			
		$vendor_id = $vendor_id->ID;

		$data = array();
		
		$selected_year = ( isset( $_POST['report_year'] ) && $_POST['report_year'] != 'all' ) ? $_POST['report_year'] : false;
		
		$selected_month = ( isset( $_POST['report_month'] ) && $_POST['report_month'] != 'all' ) ? $_POST['report_month'] : false;

		// Get all vendor commissions
		$commissions = ign_get_vendor_commissions( $vendor_id, $selected_year, $selected_month, false );

		$total_earnings = 0;
		
		foreach( $commissions as $commission ) {
		
			$meta = get_post_meta( $commission->ID );

			$earnings = $meta['_commission_amount'][0];
			
			$product_id = $meta['_commission_product'][0];
			
			$item_meta = maybe_unserialize( $meta['_item_meta'][0] );

			$product = get_product( $product_id );

			if ( ! isset( $data[ $product_id ]['product'] ) )
				$data[ $product_id ]['product'] = $product->get_title();
			

			if ( ! isset( $data[ $product_id ]['product_url'] ) ) 
				$data[ $product_id ]['product_url'] = get_permalink( $product_id );
			

			if ( isset( $data[ $product_id ]['sales'] ) ) 
				$data[ $product_id ]['sales'] += $item_meta['_qty'][0];
			else 
				$data[ $product_id ]['sales'] = $item_meta['_qty'][0];
			

			if ( isset( $data[ $product_id ]['earnings'] ) ) 
				$data[ $product_id ]['earnings'] += $earnings;
			else 
				$data[ $product_id ]['earnings'] = $earnings;

			$total_earnings += $earnings;
			
			$data['total'] = $total_earnings;
		}
		
		return $data; 
	}
	
	
	function get_orders( $days = null ) { 
		global $user_ID, $wpdb;
		
		$vendor_data = ign_get_user_vendor( $user_ID );

		if ( !empty( $vendor_data->ID ) )
			$products = ign_get_vendor_products( $vendor_data->ID );
			
		if ( empty( $products ) )
			return array();

		if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ) {
			
			$sql = "
				SELECT posts.ID, posts.post_date, post_status as status from {$wpdb->posts} posts 
				WHERE 	posts.post_type = 'shop_order'
				AND 	posts.post_status IN ('" . implode( "','", apply_filters( 'ignitewoo_vendor_stores_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')";
		} else { 
		
			$sql = "
				SELECT posts.ID, posts.post_date, term.name as status from {$wpdb->posts} posts 
				LEFT JOIN {$wpdb->term_relationships} AS rel ON posts.ID = rel.object_ID
				LEFT JOIN {$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
				LEFT JOIN {$wpdb->terms} AS term USING( term_id )
				WHERE 	posts.post_type = 'shop_order'
				AND 	posts.post_status = 'publish'
				AND 	tax.taxonomy = 'shop_order_status'
				AND term.slug IN ('" . implode( "','", apply_filters( 'ignitewoo_vendor_stores_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) . "')";
		}
		
		
		if ( $days != null ) { 
		
			$start_date = strtotime( date('Ymd', strtotime( date('Ym', current_time('timestamp') ) . '01' ) ) );
	
			$start_date = strtotime( '- ' . $days . ' day', $start_date );
			
			$end_date = strtotime( date('Ymd', current_time( 'timestamp' ) ) );
	
			$sql .= "AND post_date > '" . date('Y-m-d', $start_date ) . "'
			AND post_date < '" . date('Y-m-d', strtotime('+1 day', $end_date ) ) . "' ";
		
		}
		
		$sql .= "ORDER BY posts.post_date DESC";

		$orders = apply_filters( 'ignitewoo_vendor_stores_orders', $wpdb->get_results( $sql ) );

		if ( empty( $orders ) || is_wp_error( $orders ) )
			return array();
			
		$ids = array();
	
		foreach ( $products as $product )
			$ids[] = $product->ID;

		if ( empty( $ids ) )
			return array();

		foreach ( $orders as $key => $order ) {

			$ign_is_vendor_order = false; 
			
			$order_total = 0;
			
			$order_qty = 0;
			
			$order_data = new WC_Order( $order->ID );
			
			if ( empty( $order_data ) )
				continue;
				
			$order_items = $order_data->get_items(); 

			if ( empty( $order_items ) ) {
			
				unset( $orders[ $key ] ); 
				
				continue;
				
			}

			foreach( $order_items as $item_key => $item ) {

				$_product = $order_data->get_product_from_item( $item );
				
				if ( empty( $_product ) )
					continue; 
			
				if ( in_array( $_product->id, $ids ) ) { 

					$ign_is_vendor_order = true;
				
					$variation_id = get_metadata( 'order_item', $item_key, '_variation_id', true );
					
					$shipping = 0;

					// Get shipping due for the order, for each vendor whose items are part of the order

					if ( 'yes' == $this->settings['give_vendor_shipping'] ) {
					
						$vendors = ign_get_product_vendors( $_product->id );
						
						foreach( $vendors as $vendor ) { 
							
							if ( empty( $vendor->ID ) )
								continue;

							$shipping = $this->shipping->get_vendor_shipping_amount( $order_data, $item_key, $item, $vendor->ID );

							// Only one vendor per store and per product
							break;
							
						}
					}

					if ( 'yes' == $this->settings['include_shipping'] )
						$shipping = ign_calculate_shipping_commission( $product->id, $variation_id, $vendor_data->ID, $shipping );
				
					
					$order_total += ign_calculate_product_commission( $item['line_total'], $product->id,  $variation_id, $vendor_data->ID, $order->ID, $item );

					$order_qty += $item['qty'];
				}
			}
	
			if ( !$ign_is_vendor_order ) {
				unset( $orders[ $key ] );
				continue;
			}

			$orders[ $key ]->total = $order_total;
			
			$orders[ $key ]->qty = $order_qty;
		
			$o_meta = get_post_meta( $order->ID );

			$orders[ $key ]->meta = array();
			
			foreach( $o_meta as $k => $d )
				$orders[ $key ]->meta[ $k ] = maybe_unserialize( $d[0] );
		
		}

		return $orders;
		
	}
	
	
	function vendor_total_earnings_report() {

		$data = $this->vendor_totals_data();
	
		ob_start();
		
		store_vendors_get_template( 'vendor_store_reports.php' );
		
		$html = ob_get_clean();
	
		return $html;
	}

	
	function earnings_this_month() { 
		global $user_ID;
		
		if ( !ign_is_vendor() )
			return false;
		
		$vendor_id = ign_get_user_vendor( $user_ID );

		if ( empty( $vendor_id ) )
			return false;
			
		$vendor_id = $vendor_id->ID;
			
		$commissions = ign_get_vendor_commissions( $vendor_id, date( 'Y' ), date( 'm' ), false );

		if ( empty( $commissions ) )
			$amount = 0;
			
		if ( !empty( $commissions ) ) {
		
			$amount = 0;
		
			foreach( $commissions as $commission ) {
			
				$earnings = get_post_meta( $commission->ID, '_commission_amount', true );

				$amount += $earnings;
			
			}
			
		}
		
		return $amount;
		
	}
	
	
	function vendor_month_earnings() {
		
		$amount = $this->earnings_this_month();
	
		ob_start();
		
		store_vendors_get_template( 'vendor_store_month_earnings.php' );
		
		$html = ob_get_clean();

		return $html;
	}
	
	
	function vendor_store_dashboard() { 
	
		ob_start();
		
		store_vendors_get_template( 'vendor_store_my_account.php' );
	
		return ob_get_clean();
		
	}


	function load_product_archive_template() {
		if ( is_tax( $this->token ) ) {
			$template = apply_filters( 'vendor_stores_archive_template', 'archive-product-vendor.php' );
			woocommerce_get_template( $template );
			exit;
		}
	}


	function set_product_archive_class( $classes ) {
	
		if ( is_tax( $this->token ) ) {

			// Add generic classes
			$classes[] = 'ignitewoo_vendor_stores';
			
			$classes[] = 'vendor_store';

			// Get vendor ID
			$vendor_id = get_queried_object()->term_id;

			// Get vendor info
			$vendor = ign_get_vendor( $vendor_id );

			// Add vendor slug as class
			if ( '' != $vendor->slug ) {
				$classes[] = $vendor->slug;
			}
		}
		
		return $classes;
	}


	function product_archive_vendor_info() {
		
		if ( !is_tax( $this->token ) )
			return;
		// Get vendor ID
		$vendor_id = get_queried_object()->term_id;

		// Get vendor info
		$vendor = ign_get_vendor( $vendor_id );
               
                /*
                 * GH: Modify this function according to the need and change it to display logo in this function 
                 */

		$html = '<div class="vendor">';
                $html .= '<div class="vendor_title"><span class="provider">Provider </span> <span class="provider-name">' .$vendor->title . '</span></div>';
                $store_image = vendor_store_get_logo();
                $html .='<div class="logo"><img src="'. $store_image . '"></div>';
             
		if ( '' != $vendor->description ) {
			$html .= '<div class="archive-description">' . $vendor->description . '</div>';
		}
                $html .='</div>'; 

		echo $html;
	}
	

	function product_vendor_tab( $tabs ) {
		global $product;
		
		if ( 'yes' !== $this->settings['seller_tab_show'] )
			return $tabs;

		$vendors = ign_get_product_vendors( $product->id );

		if ( empty( $vendors ) )
			return $tabs;
			
		$title = $this->settings['seller_tab_title'];
		
		$tabs['vendor'] = array(
			'title' => $title,
			'priority' => 20,
			'callback' => array( $this, 'product_vendor_tab_content' )
		);

		return $tabs;
	}

	
	function show_store_image() { 

		if ( 'yes' !== $this->settings['show_store_logo'] )
			return;
			/*
                         * GH: Comment this function because we show image in description on line no: 1868 
                         *
                         */
		//store_vendors_get_template( 'vendor_store_logo.php' );

	}
	
	
	function product_vendor_tab_content() {
		global $product, $product_vendor;

		$html = '';

		$vendors = ign_get_product_vendors( $product->id );
		
		if ( empty( $vendors ) )
			return;
			
		foreach( $vendors as $product_vendor )
			store_vendors_get_template( 'vendor_store_product_tab_content.php' );

	}


	function rewrite_flush() {
	
		$this->register_vendors_taxonomy();
		
		flush_rewrite_rules();
	}


	function maybe_do_csv_export() { 
	
		if ( empty( $_GET['vendor_export_to_csv'] ) )
			return;
			
		if ( !wp_verify_nonce( $_GET['_wpnonce'], 'vendor_export_to_csv' ) ) { 
		
			$url = remove_query_arg( 'vendor_export_to_csv' );
			
			$url = remove_query_arg( '_wpnonce' );
			
			wp_redirect( $url );
			
			die;
			
		}
		
		@ini_set( 'display_errors', 0 );
		
		$days = isset( $_GET['days' ] ) ? absint( $_GET['days'] ) : 90;

		$this->export_to_csv( $days );
	
	}
	
	
	function send_csv( $content ) { 
	
		$date = date( 'Y-m-d-H-i-s' );
		
		$filename = 'store-vendor-commissions-' . $date . '.csv';

		// Set page headers to force download of CSV
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename={$filename}");
		header("Content-Transfer-Encoding: binary");

		ob_start();
		
		$file = fopen( "php://output", 'w' );

		// Add data to file
		// fputcsv( $file, $content );
		
		echo $content;

		// Close file and get data from output buffer
		fclose( $file );
		
		$csv = ob_get_clean();

		// Send CSV to browser for download
		echo $csv;
		
		die();

	}
	
	
	function export_to_csv( $days = 90 ) { 
		
		if ( absint( $days ) <= 0 ) 
			$days = 90;
			
		$orders = $this->get_orders( $days );

		// Send empty file with headers only
		if ( empty( $orders ) ) {
		
			foreach( $columns as $c )
				$content[] = $c;
				
			$csv_data = implode( ',' , $content );
			
			$this->send_csv( $csv_data );
			
		}

		$vendor = ign_get_user_vendor();
		
		if ( !empty( $vendor ) )
			$vendor_id = $vendor->ID;
			
		
		$ids[] = array(); 
		
		$vendor_products = ign_get_vendor_products( $vendor_id );
			
		foreach ( $vendor_products as $vendor_product )
			$vendor_product_ids[] = $vendor_product->ID;	
			
		$columns = array(
			__( 'Order ID', 'ignitewoo_vendor_stores' ),
			__( 'Date', 'ignitewoo_vendor_stores' ),
			__( 'Order Status', 'ignitewoo_vendor_stores' ),
			__( 'Customer Name', 'ignitewoo_vendor_stores' ),
			__( 'SKU', 'ignitewoo_vendor_stores' ),
			__( 'Item Name', 'ignitewoo_vendor_stores' ),
			__( 'Item Variation', 'ignitewoo_vendor_stores' ),
			__( 'Qty', 'ignitewoo_vendor_stores' ),
			__( 'Amount', 'ignitewoo_vendor_stores' ),
			__( 'Tax', 'ignitewoo_vendor_stores' ),
			__( 'Customer Note', 'ignitewoo_vendor_stores' ),
		);
		
		if ( 'yes' == $this->settings['show_order_shipping_address'] ) { 
		
			$columns = array_merge( $columns, array( 
				__( 'Ship To', 'ignitewoo_vendor_stores' ),
				__( 'Shipping Company', 'ignitewoo_vendor_stores' ),
				__( 'Shipping Address 1', 'ignitewoo_vendor_stores' ),
				__( 'Shipping Address 2', 'ignitewoo_vendor_stores' ),
				__( 'Shipping City', 'ignitewoo_vendor_stores' ),
				__( 'Shipping State', 'ignitewoo_vendor_stores' ),
				__( 'Shipping Postal', 'ignitewoo_vendor_stores' ),
				__( 'Shipping Country', 'ignitewoo_vendor_stores' ),
				__( 'Shipping Method', 'ignitewoo_vendor_stores' ),
				__( 'Shipping Amount', 'ignitewoo_vendor_stores' ),
			) );
		
		}
		
		if ( 'yes' == $this->settings['show_order_billing_address'] ) { 
		
			$columns = array_merge( $columns, array( 
				__( 'Billing Company', 'ignitewoo_vendor_stores' ),
				__( 'Billing Address 1', 'ignitewoo_vendor_stores' ),
				__( 'Billing Address 2', 'ignitewoo_vendor_stores' ),
				__( 'Billing City', 'ignitewoo_vendor_stores' ),
				__( 'Billing State', 'ignitewoo_vendor_stores' ),
				__( 'Billing Post code', 'ignitewoo_vendor_stores' ),
				__( 'Billing Country', 'ignitewoo_vendor_stores' ),
			) );
			
		}
			
			/*
			__( 'Customer Name', 'ignitewoo_vendor_stores' ),
			
			__( 'Total Tax', 'ignitewoo_vendor_stores' ),
			__( 'Total Order Value', 'ignitewoo_vendor_stores' ),
			__( 'Order Status', 'ignitewoo_vendor_stores' ),
			__( 'Finalized At' , 'ignitewoo_vendor_stores' ),
			
			__( 'Shipping Method', 'ignitewoo_vendor_stores' ),
			
			__( 'Cart Discount', 'ignitewoo_vendor_stores' ),
			*/
			
		if ( 'yes' == $this->settings['show_order_email'] ) { 
		
			$columns = array_merge( $columns, array( 
				__( 'Billing Email', 'ignitewoo_vendor_stores' )
			) );
			
		}
		
		if ( 'yes' == $this->settings['show_order_billing_phone'] ) { 
		
			$columns = array_merge( $columns, array( 
				__( 'Billing Phone', 'ignitewoo_vendor_stores' )
			) );
			
		}
			
		if ( class_exists('WC_EU_VAT_Number') ) {
			$columns = array_merge( $columns, array( 
				__( 'VAT Number', 'wc-export-csv' )
			) );
		}
			
			/*
			//__( 'Shipping', 'ignitewoo_vendor_stores' ),
			//__( 'Shipping Tax', 'ignitewoo_vendor_stores' ),
			
			//__( 'Cart Discount', 'ignitewoo_vendor_stores' ),
			//__( 'Order Discount', 'ignitewoo_vendor_stores' ),
			
			//__( 'Payment Method', 'ignitewoo_vendor_stores' ),
			//__( 'Shipping Method', 'ignitewoo_vendor_stores' ),
			*/

		$data_fields = array(
			'shipping' => '_order_shipping',
			'shipping_tax' => '_order_shipping_tax',
			'tax' => '_order_tax',
			'cart_discount' => '_cart_discount',
			'order_discount' => '_order_discount',
			'order_total' => '_order_total',
			'payment_method' => '_payment_method_title',
			'shipping_method' => '_shipping_method_title',
			'billing_firstname' => '_billing_first_name',
			'billing_lastname' => '_billing_last_name',
			'billing_email' => '_billing_email',
			'billing_phone' => '_billing_phone',
			'billing_address_1' => '_billing_address_1',
			'billing_address_2' => '_billing_address_2',
			'billing_postcode' => '_billing_postcode',
			'billing_city' => '_billing_city',
			'billing_state' => '_billing_state',
			'billing_country' => '_billing_country',
			'billing_company' => '_billing_company',
			'shipping_firstname' => '_shipping_first_name',
			'shipping_lastname' => '_shipping_last_name',
			'shipping_address_1' => '_shipping_address_1',
			'shipping_address_2' => '_shipping_address_2',
			'shipping_postcode' => '_shipping_postcode',
			'shipping_city' => '_shipping_city',
			'shipping_state' => '_shipping_state',
			'shipping_country' => '_shipping_country',
			'shipping_company' => '_shipping_company',
			'vat_number' => 'VAT Number',
		);

		$separator = ','; 
		
		foreach( $columns as $c ) { 
		
			$headers[] = '"' . $c . '"';
		
		}
		
		$csv = implode( ',' , $headers );
		
		$csv .= "\n";
		
		foreach( $orders as $order ) {

			$wc_order = new WC_Order( $order->ID );

			$items = $wc_order->get_items(); 

			if ( empty( $items ) )
				continue;
				
			// Support for sequential order numbers
			if ( class_exists( 'WC_Seq_Order_Number' ) )
				$order_number = get_post_meta( $order->ID, '_order_number_formatted', true );
			else 
				$order_number = $order->ID;
			

			$order->date = $wc_order->order_date;
			
			$order->status = $wc_order->status;
			
			$order->customer_note = $wc_order->customer_note;
			
			$shipping_due = get_post_meta( $wc_order->id, '_vendor_shipping_due', true );

			if ( !empty( $shipping_due ) && is_array( $shipping_due ) )
				$order->shipping_amount_due = $shipping_due[ $vendor_id ];

			
			if ( 'yes' == $this->settings['include_shipping'] )
				$order->shipping_amount_due = ign_calculate_shipping_commission( $product_id, $variation_id, $vendor->ID, $order->shipping_amount_due );
			

			foreach ( $data_fields as $key => $value ) {
			
				if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) ) { 
				
					if ( 'shipping_method' == $key && empty( $order->shipping_method ) ) {
					
						// 2.1+ get line items for shipping
						$shipping_methods = $wc_order->get_shipping_methods();

						// Assumes there is only one shipping method for the entire order
						foreach ( $shipping_methods as $shipping ) {
			
							$order->$key = $shipping['name'] ;
							break;
						}
						
					} else { 

						if ( '_' == substr( $key, 0, 1 ) )
							$key = substr( $key, 1 );

						$order->$key = $wc_order->__get( $value );
						
					}


				} else {
			
					$order->$key = isset( $wc_order->order_custom_fields[$value] ) ? $wc_order->order_custom_fields[$value]['0'] : '';
				}
			
			}
	
			foreach( $order->meta as $key => $val ) { 
			
				if ( is_array( $val ) )
					continue;
					
				if ( '_' == substr( $key, 0, 1 ) )
					$key = substr( $key, 1 );
				
				$order->$key = $val; 
			
			}

			if ( isset( $items ) && !empty( $items ) ) { 
				
				foreach( $items as $key => $value ) {
				
					$product = $wc_order->get_product_from_item( $value );

					if ( !in_array( $product->id, $vendor_product_ids ) )
						continue;
						
					$replace_pattern = array( '&#8220;', '&#8221;' );

					$order->itemname = str_replace($replace_pattern, '""', $value['name']);

					if ( isset( $product->sku ) )
						$order->itemsku = $product->sku;
					else
						$order->itemsku = '';
					
					$order->item_qty = $value['qty'];
					
					$item_meta = new WC_Order_Item_Meta( $value['item_meta'] );

					$variation = $item_meta->display( true, true );
					
					$variation_id = get_metadata( 'order_item', $key, '_variation_id', true );
					
					if ( $variation )
						$order->item_variation = $variation;
					else
						$order->item_variation = '';
					
					$order->itemtotal = $value['line_total'];

					$order->line_tax = $value['line_tax'];

					$csvdata = array(
						$order_number,
						$order->date,
						$order->status,
						$order->billing_first_name . ' ' . $order->billing_last_name,
						$order->itemsku,
						$order->itemname,
						$order->item_variation,
						$order->item_qty,
						ign_calculate_product_commission( $order->itemtotal, $product->id, $variation_id, $vendor_id, $order->ID, $value  ),
						$order->line_tax,
						$order->customer_note,
					);

					if ( 'yes' == $this->settings['show_order_shipping_address'] ) { 
		
						$csvdata = array_merge( $csvdata, array(
							$order->shipping_firstname . ' ' . $order->shipping_lastname,
							$order->shipping_company,
							$order->shipping_address_1,
							$order->shipping_address_2,
							$order->shipping_city,
							$order->shipping_state,
							$order->shipping_postcode,
							$order->shipping_country,
							$order->shipping_method,
							$order->shipping_amount_due,
						) );
						
					}

					if ( 'yes' == $this->settings['show_order_billing_address'] ) { 
		
						$csvdata = array_merge( $csvdata, array(
							$order->billing_company,
							$order->billing_address_1,
							$order->billing_address_2,
							$order->billing_city,
							$order->billing_state,
							$order->billing_postcode,
							$order->billing_country,
						) );
						
					}

					if ( 'yes' == $this->settings['show_order_email'] ) { 
		
						$csvdata = array_merge( $csvdata, array(
							$order->billing_email
						) );
						
					}
					
					if ( 'yes' == $this->settings['show_order_billing_phone'] ) { 
		
						$csvdata = array_merge( $csvdata, array(
							$order->billing_phone
						) );
						
					}
						
						//$order->tax,
						//$order->order_total,
						
						
						//$order->shipping, 
						//$order->shipping_tax,

						//$order->cart_discount, 
						//$order->order_discount,
						
						//$order->payment_method,
						
						//$order->shipping_method,
						
						//$order->shipping_method,
						
						//$order->cart_discount,

					
					if ( class_exists('WC_EU_VAT_Number') ) {
						$csvdata[] = $order->vat_number;
					}

					$csv .= '"' . implode( '"' . $separator . '"' , $csvdata ) . '"' . "\n";
				}

				//$csv .= '"' . implode( '"' . $separator . '"' , $csvdata ) . '"' . "\n";
			}

		}

		$this->send_csv( $csv );
		
	}

	function display_tracking_info( $order_id, $for_email = false ) {
		global $ign_tracking_providers; 
		
		$data = get_post_meta( $order_id, '_ign_vendor_tracking_info', true );
		
		if ( empty( $data ) )
			return;
			
		foreach( $data as $k => $d ) { 
			
			$vendor = ign_get_vendor( $k );
	
			if ( empty( $vendor ) )
				continue;

			$tracking_provider = $d['provider'];
			$tracking_number   = $d['number'];
			$date_shipped      = $d['date'];
			$link 		   = $d['link'];

			$postcode = get_post_meta( $order_id, '_shipping_postcode', true );

			if ( ! $postcode )
				$postcode = get_post_meta( $order_id, '_billing_postcode', true );

			if ( ! $tracking_number )
				continue;

			if ( $date_shipped )
				$date_shipped = ' ' . sprintf( __( 'on %s', 'wc_shipment_tracking' ), date_i18n( __( 'l, jS F Y', 'ign_vendors_shipment_tracking' ), $date_shipped ) );

			$tracking_link = '';

			$link_format = '';

			foreach ( $ign_tracking_providers as $providers ) {
				
				foreach ( $providers as $provider => $format ) {
					if ( sanitize_title( $provider ) == $tracking_provider ) {
						$link_format = $format;
						$tracking_provider = $provider;
						break;
					}
				}
				
				if ( $link_format ) 
					break;
			}

			if ( !empty( $link_format ) ) {
			
				$link = sprintf( $link_format, $tracking_number, urlencode( $postcode ) );
				
				if ( $for_email ) {
					$tracking_link = '<br/>' . sprintf( __('Track your shipment', 'wc_shipment_tracking') . ': <a href="%s">%s</a>', $link, $link );
				} else {
					$tracking_link = '<br/>' . sprintf( '<a href="%s">' . __('Click here to track your shipment', 'wc_shipment_tracking') . '.</a>', $link, $link );
				}
			
				$tracking_provider = ' ' . __('via', 'wc_shipment_tracking') . ' <strong>' . $tracking_provider . '</strong>';

				echo wpautop( sprintf( __('Your items were shipped from %s %s%s.<br/>Tracking number: <strong>%s</strong>. %s', 'wc_shipment_tracking'),  $vendor->title, $date_shipped, $tracking_provider, $tracking_number, $tracking_link ) );

			} else {

				$custom_tracking_link = $link;
				
				$custom_tracking_provider = $tracking_provider;

				if ( $custom_tracking_provider )
					$tracking_provider = ' ' . __('via', 'wc_shipment_tracking') . ' <strong>' . $custom_tracking_provider . '</strong>';
				else
					$tracking_provider = '';

				if ( $custom_tracking_link ) {
					$tracking_link = '<br/>' . sprintf( '<a href="%s">' . __('Track your shipment', 'wc_shipment_tracking') . '.</a>', $custom_tracking_link );
				} elseif ( strstr( $tracking_number, '<a' ) ) {
					$tracking_link = '<br/>' . sprintf( '<a href="%s">%s.</a>', $tracking_number, $tracking_number );
				} else {
					$tracking_link = '';
				}

				echo wpautop( sprintf( __('Your items were shipped from %s %s%s.<br/>Tracking number: <strong>%s</strong>. %s', 'wc_shipment_tracking'),  $vendor->title, $date_shipped, $tracking_provider, $tracking_number, $tracking_link ) );
			}
		}
			
	}
	
	
	public function coupon_is_valid( $valid, $coupon ) {
		global $woocommerce;

		$post = get_post( $coupon->id );
		
		if ( empty( $post ) || empty( $post->post_author ) )
			return $valid;
			
		$is_vendor = ign_get_user_vendor( $post->post_author );
		
		if ( empty( $is_vendor ) )
			return $valid;

		// Cart discounts cannot be added if the type isn't fixed product or percent product
		if ( $coupon->type != 'fixed_product' && $coupon->type != 'percent_product' ) {
			$valid = false;
		}

		// Product ids - If a product included is found in the cart then its valid
		// If the coupon has no product IDs set then this bit of code doesn't run
		// But the WC hook to check if a coupon is valid for a product will run and do the requisite checking
		if ( sizeof( $coupon->product_ids ) > 0 ) {
		
			$valid = false;
			
			if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
			
				foreach( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {

					if ( 	in_array( $cart_item['product_id'], $coupon->product_ids ) || 
						in_array( $cart_item['variation_id'], $coupon->product_ids ) || 
						in_array( $cart_item['data']->get_parent(), $coupon->product_ids ) 
					)
						$valid = true;
				}
			}
			
		}

		// Excluded Products
		if ( sizeof( $coupon->exclude_product_ids ) > 0 ) {

			if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
			
				foreach( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
				
					if ( 
						in_array( $cart_item['product_id'], $coupon->exclude_product_ids ) ||
						in_array( $cart_item['variation_id'], $coupon->exclude_product_ids ) ||
						in_array( $cart_item['data']->get_parent(), $coupon->exclude_product_ids ) 
					) 
						$valid = false;

				}
			}
		}

		// Category ids - if set then something is wrong because vendors can't set categories
		if ( sizeof( $coupon->product_categories ) > 0 ) {
			$valid = false;
		}

		return $valid;
	}
	
	
	function coupon_is_valid_for_product( $valid, $product, $coupon ) { 

		if ( $coupon->type != 'fixed_product' && $coupon->type != 'percent_product' )
			return $valid;
			
		$post = get_post( $coupon->id );
		
		if ( empty( $post ) || is_wp_error( $post ) || empty( $post->post_author ) )
			return $valid;
			
		// Is coupon author a vendor? 
		$is_vendor = ign_get_user_vendor( $post->post_author );

		// Not a vendor, return 
		if ( empty( $is_vendor ) )
			return $valid;
			
		// Does the product belong to a vendor? 
		$vendor = ign_get_product_vendors( $product->id );

		// Coupon belongs to a vendor, does the product belong to the vendor? 
		// If not the coupon is invalid
		if ( empty( $vendor ) ) 
			return false;

		// Does the product belong to the vendor that published the coupon? 
		// If not then this coupon doesn't apply to the product
		if ( $post->post_author !== $vendor[0]->admins[0]->data->ID )
			return false; 

		$product_cats = wp_get_post_terms( $product->id, 'product_cat', array( "fields" => "ids" ) );

		// Specific products get the discount
		if ( sizeof( $coupon->product_ids ) > 0 ) {

			if ( 
				in_array( $product->id, $coupon->product_ids ) || 
				( isset( $product->variation_id ) && in_array( $product->variation_id, $coupon->product_ids ) ) || 
				in_array( $product->get_parent(), $coupon->product_ids ) 
			)
				$valid = true;

		} else {
			// No product ids - all items for this vendor are discounted
			$valid = true;
		}

		// Are specific product IDs excluded from the discount?
		if ( sizeof( $coupon->exclude_product_ids ) > 0 ) { 
		
			if ( 
				in_array( $product->id, $coupon->exclude_product_ids ) || 
				( isset( $product->variation_id ) && in_array( $product->variation_id, $coupon->exclude_product_ids ) ) || 
				in_array( $product->get_parent(), $coupon->exclude_product_ids ) 
			)
				$valid = false;
		}

		return $valid;
		
	}
	
	
}