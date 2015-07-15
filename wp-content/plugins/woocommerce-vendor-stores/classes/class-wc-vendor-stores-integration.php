<?php 


/**
Copyright (c) 2013 - IgniteWoo.com
*/

if ( ! defined( 'ABSPATH' ) ) exit;


if ( !class_exists( 'WC_Integration' ) )
	return;

class IgniteWoo_Product_Vendor_Settings extends WC_Integration { 

	function __construct() {
	
		$this->id = 'ignitewoo_vendor_stores';
		
		$this->method_title = __( 'Vendor Stores', 'ignitewoo_vendor_stores' );

		$this->init_form_fields();

		$this->init_settings();
		
		if ( !$this->is_valid_for_use() && 'mass_pay_auto' == $this->settings['payment_processing'] )
			$this->method_description = __( '<div class="error"><p>WARNING: PayPal does not support your store currency. You cannot use Mass Pay to automatically pay your vendors. You must use Mass Pay in manual mode, or select a different payment processing method</p></div>', 'ignitewoo_vendor_stores' );
		
		add_action( 'woocommerce_update_options_integration_' . $this->id , array( &$this, 'process_admin_options') );
		
		//add_action( 'woocommerce_settings_save_ignitewoo', array( &$this, 'process_admin_options') );
		
		add_action( 'admin_head', array( $this, 'admin_head' ) );

	}
	
	
	 function is_valid_for_use() {

		if ( ! in_array( get_woocommerce_currency(), apply_filters( 'woocommerce_paypal_supported_currencies', array( 'AUD', 'BRL', 'CAD', 'MXN', 'NZD', 'HKD', 'SGD', 'USD', 'EUR', 'JPY', 'TRY', 'NOK', 'CZK', 'DKK', 'HUF', 'ILS', 'MYR', 'PHP', 'PLN', 'SEK', 'CHF', 'TWD', 'THB', 'GBP', 'RMB' ) ) ) ) return false;

		return true;
	}
    
    
	function admin_head() { 
		global $ignitewoo_vendors; 
		
		wp_enqueue_script( 'chosen', $ignitewoo_vendors->assets_url . '/js/jquery.chosen.js' );
		
		wp_enqueue_script( 'ajax-chosen', $ignitewoo_vendors->assets_url . '/js/ajax-chosen' );
		
		wp_enqueue_style( 'chosen_css', $ignitewoo_vendors->assets_url . '/css/chosen.css' );
		
		?>
		<style>
			#woocommerce_ignitewoo_vendor_stores_allowed_product_options_chzn,
			#woocommerce_ignitewoo_vendor_stores_allowed_product_options_chzn input,
			#woocommerce_ignitewoo_vendor_stores_allowed_product_types_chzn,
			#woocommerce_ignitewoo_vendor_stores_allowed_product_types_chzn input {
				width: 200px !important;
			}
		</style>
		<script>
		jQuery( document ).ready( function() { 
			jQuery( '.chosen' ).chosen();
		})
		</script>
		<?php
	}
	
	function init_form_fields() {
		global $wpdb, $ignitewoo_vendors;

		if ( !taxonomy_exists( 'shop_vendor' ) )
			$ignitewoo_vendors->register_vendors_taxonomy();
			
		$sql = 'select ID, post_title from ' . $wpdb->posts . ' where post_type="page" and post_status="publish"';
		
		$pages = $wpdb->get_results( $sql );
		
		if ( !empty( $pages ) )
		foreach( $pages as $k => $t ) 
			$page_data[ $t->ID ] = $t->post_title;
			
		$vd_page_id = get_option( 'woocommerce_vendor_store_vendor_dashboard_page_id' );
			
		$to = get_option( 'woocommerce_new_order_recipient', '' );
		
		if ( empty( $to ) )
			$to = get_option( 'admin_email' );
	
		$pay_methods = apply_filters( 'igintewoo_vendor_stores_payment_methods', 
			array( 
				'mass_pay_manual' => __( 'PayPal Mass Pay - Manually', 'ignitewoo_vendor_stores' ), 'mass_pay_auto' => __( 'PayPal Mass Pay - Automatically', 'ignitewoo_vendor_stores' ),
			) 
		);

		$sql = 'select * from ' . $wpdb->term_taxonomy . ' tt left join ' . $wpdb->terms . ' t on t.term_id = tt.term_id 
		where tt.taxonomy = "product_type" ';
		
		$types = $wpdb->get_results( $sql );
		
		if ( empty( $types ) || is_wp_error( $types ) )
			$types = array();
			
		foreach( $types as $t )
			$post_types[ $t->slug ] = __( ucwords( $t->name ), 'ignitewoo_vendor_stores' );
			
		$vendors = get_users ( array( 'role' => 'vendor' ) );
		
		if ( empty( $vendors ) )
			$all_vendors = array();
		else 
			foreach( $vendors as $vendor ) { 
			
				$store_name = '?';
				
				$store = get_user_meta( $vendor->data->ID, 'product_vendor', true );

				if ( !empty( $store ) ) { 
				
					$term = get_term( $store, 'shop_vendor' );

					if ( !empty( $term->name ) && !is_wp_error( $term ) )
						$store_name = $term->name;
				}
					
			
				$all_vendors[ $vendor->data->ID ] = $vendor->data->user_login . ' ( ' . $store_name . ' )';
			}	

		$this->form_fields = array(

			'general' => array( 
					'type' => 'title',
					'title' => __( 'General', 'ignitewoo_vendor_stores' ),
				),
			'default_slug' => array(
					'title' 		=> __( 'Vendor Store Slug', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'The slug used for the store permalinks. WARNING: This can only contain letters, numbers, dashes, and underscores', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'text',
					'default' 		=> 'vendor',
					'css'			=> 'width: 100px'
				),
			'vendor_access' => array(
					'title' 		=> __( 'Vendor Registration', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'How do vendors gain access to create a store and post products', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'select',
					'default' 		=> 'request_access',
					'options'		=> array( 'request_access' => __( 'Users can request access to post products', 'ignitewoo_vendor_stores' ), 'automatic' => __( 'All access requests are granted automatically', 'ignitewoo_vendor_stores' ), 'none' => __( 'Users cannot request access. Admins will manually grant access', 'ignitewoo_vendor_stores' ) ),
				),
			'dashboard_page' => array(
					'title' 		=> __( 'Vendor Dashboard Page', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Select the page used for the Vendor Dashboard. This page should include the <code>[vendor_store_dashboard]</code> shortcode.', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'select',
					'default' 		=> $vd_page_id,
					'class'			=> 'chosen',
					'options'		=> $page_data,
				),
			'default_commission' => array(
					'title' 		=> __( 'Default Commission', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Default commission if the vendor has no commission defined in their profile. DO NOT included a currency symbol', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'text',
					'default' 		=> '',
					'css'			=> 'width: 60px'
				),
			'default_commission_for' => array(
					'title' 		=> __( 'Default Commission For', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Select who receives the default commission', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'select',
					'default' 		=> 'vendor',
					'options'		=> array( 
								'vendor' => __( 'Vendor', 'ignitewoo_vendor_stores' ),
								'store' => __( 'Store', 'ignitewoo_vendor_stores' ),
								),
				),
			'include_shipping' => array(
					'title' 		=> __( 'Include Shipping', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Include shipping when calculating vendor commission ( only applicable when commission is a percentage and "Give per item shipping cost to vendor" is enabled', 'ignitewoo_vendor_stores' ),
					'label'			=> __( 'Enable', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'checkbox',
					'default' 		=> 'no',
				),
			'store' => array( 
					'type' => 'title',
					'title' => __( 'Stores', 'ignitewoo_vendor_stores' ),
				),	
			'show_store_logo' => array(
					'title' 		=> __( 'Display Logos', 'ignitewoo_vendor_stores' ),
					'label' => __( 'Enable', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Display store logo image on store archive pages', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'checkbox',
					'default' 		=> 'yes',
				),
				
			'logo_width' => array(
					'title' 		=> __( 'Logo Width', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Logo width in pixels. Images are "soft cropped" to retain aspect ratio. Logos may not display at this exact size.', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'text',
					'default' 		=> '400',
					'css'			=> 'width: 60px'
				),
			'logo_height' => array(
					'title' 		=> __( 'Logo Height', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Logo height in pixels. Images are "soft cropped" to retain aspect ratio. Logos may not display at this exact size.', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'text',
					'default' 		=> '200',
					'css'			=> 'width: 60px'
				),
			'allow_vendor_html' => array(
					'title' 		=> __( 'Allow HTML', 'ignitewoo_vendor_stores' ),
					'label' => __( 'Enable', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Allow vendors to add HTML to their store description', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'checkbox',
					'default' 		=> 'yes',
				),
			'products' => array( 
					'type' => 'title',
					'title' => __( 'Product Editing', 'ignitewoo_vendor_stores' ),
				),
			'allowed_product_types' => array(
					'title' 		=> __( 'Allowed Product Types', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Select which product types vendors can post', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'multiselect',
					'default' 		=> array( 'simple', 'variable' ),
					'options'		=> $post_types,
					'class' 		=> 'chosen',
				),
			'hide_product_panels' => array(
					'title' 		=> __( 'Hide Product Panels', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Select which product data panels to hide from vendors', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'multiselect',
					'default' 		=> array(),
					'options'		=> array(
									'inventory'      => 'Inventory',
									'shipping'       => 'Shipping',
									'linked_product' => 'Linked Products',
									'attributes'     => 'Attributes',
									'advanced'       => 'Advanced',
								),
					'class' 		=> 'chosen',
				),	
			'hide_product_misc' => array(
					'title' 		=> __( 'Hide Product Fields', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Select which product data panels to hide from vendors', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'multiselect',
					'default' 		=> array( 'taxes' ),
					'options'		=> array(
									'taxes' => 'Taxes',
									'sku' => 'SKU',
								),
					'class' 		=> 'chosen',
				),
			'hide_product_type_options' => array(
					'title' 		=> __( 'Hide Product Type Options', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Select which product data panels to hide from vendors', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'multiselect',
					'default' 		=> array( ),
					'options'		=> array(
									'virtual'      => 'Virtual',
									'downloadable' => 'Downloadable',
								),
					'class' 		=> 'chosen',
				),
			/*
			'allowed_product_options' => array(
					'title' 		=> __( 'Allowed Product Options', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Select which product options vendors can post', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'multiselect',
					'default' 		=> array( 'virtual', 'downloadable' ),
					'options'		=> array( 
								'virtual' => __( 'Virtual', 'ignitewoo_vendor_stores' ),
								'downloadable' => __( 'Downloadable', 'ignitewoo_vendor_stores' ),
								),
					'class' 		=> 'chosen',
				),
			*/
			'new_post_email_addy' => array(
					'title' 		=> __( 'New Product Notice Recipients', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'A list of addresses (comma separated) who receive notices when new products are posted by vendors. Defaults to <code>' . $to . '</code>', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'text',
					'default' 		=> $to,
				),
			'new_product_subject' => array(
					'title' 		=> __( 'New Product Notice Title', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Message subject. Use <code>{blogname}</code> to insert the site name', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'text',
					'default' 		=> __( '[{blogname}] New Product Posted', 'ignitewoo_vendor_stores' ),
				),
			'new_product_message' => array(
					'title' 		=> __( 'New Product Notice Message', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Message content. Use <code>{username}</code> to insert the user name and <code>{url}</code> to insert a URL to view / edit the post', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'text',
					'default' 		=> __( 'A new product is being posted by {username} . You can view it at the {url}. Keep in mind that they may not be finished editing the product yet', 'ignitewoo_vendor_stores' ),
				),
			'automatic_threshold' => array(
					'title' 		=> __( 'Automatic Approval', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Automatically publish newly posted items if the user has at least this many published items. Set to 0 ( zero ) to publish new items immediately with no review required. Leave blank to disable this feature.', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'text',
					'default' 		=> '',
					'css'			=> 'width: 60px'
				),
			'trusted_vendors' => array(
					'title' 		=> __( 'Trusted Vendors', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Select vendors than can post product without administrative review', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'multiselect',
					'default' 		=> array( ),
					'options'		=> $all_vendors,
					'class' 		=> 'chosen',
				),
			'editor_css' => array(
					'title' 		=> __( 'Editor Page CSS', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'You can add custom CSS to the product edit page by entering it here', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'textarea',
					'default' 		=> '',
					
				),
			'products_display' => array( 
					'type' => 'title',
					'title' => __( 'Product Display', 'ignitewoo_vendor_stores' ),
				),
			'show_soldby_cart' => array(
					'title' 		=> __( 'Show Sold By in Cart', 'ignitewoo_vendor_stores' ),
					'label' => __( 'Enable', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Show "Sold by" label in cart and checkout', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'checkbox',
					'default' 		=> 'yes',
				),
			'show_soldby_email' => array(
					'title' 		=> __( 'Show Sold By in Email', 'ignitewoo_vendor_stores' ),
					'label' => __( 'Enable', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Show "Sold by" label in customer order emails', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'checkbox',
					'default' 		=> 'yes',
				),
			'seller_tab_show' => array(
					'title' 		=> __( 'Show Seller Tab', 'ignitewoo_vendor_stores' ),
					'label' => __( 'Enable', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Show the seller tab on single product pages.', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'checkbox',
					'default' 		=> 'yes',
					'css'			=> 'width: 60px'
				),
			'seller_tab_title' => array(
					'title' 		=> __( 'Seller Tab Label', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'The seller tab label on shown single product pages.', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'text',
					'default' 		=> __( 'Seller', 'ignitewoo_vendor_stores' ),
				),
			'add_inquire_form' => array(
					'title' 		=> __( 'Add "Contact Seller" Tab', 'ignitewoo_vendor_stores' ),
					'label' => __( 'Enable', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Add a tab to single product pages that allows a shopper to send the vendor a message. This requires a free <a target="_blank"  href="https://www.google.com/recaptcha/">ReCaptcha</a> account.', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'checkbox',
					'default' 		=> 'yes',
				),
			'inquire_tab_title' => array(
					'title' 		=> __( 'Contact Tab Label', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'The contact tab label on shown single product pages.', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'text',
					'default' 		=> __( 'Ask a Question', 'ignitewoo_vendor_stores' ),
				),
			'inquire_pub_key' => array(  
						'title'		=> __('ReCaptcha Public Key', 'ignitewoo_vendor_stores'),
						'description' 		=> __('Enter your ReCaptcha public key', 'ignitewoo_vendor_stores'),
						'type' 		=> 'text',
						'default'		=> ''
					),
			'inquire_pvt_key' => array(  
						'title'		=> __('ReCaptcha Private Key', 'ignitewoo_vendor_stores'),
						'description' 		=> __('Enter your ReCaptcha private key', 'ignitewoo_vendor_stores'),
						'type' 		=> 'text',
						'default'		=> ''
					),
			'coupons' => array( 
					'type' => 'title',
					'title' => __( 'Coupons', 'ignitewoo_vendor_stores' ),
				),
			'enable_coupons' => array(
					'title' 		=> __( 'Vendor Coupons', 'ignitewoo_vendor_stores' ),
					'label' => __( 'Enable', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Allow vendors to create their own coupons for items in their own stores. Requires that you have coupons enabled in WooCommerce.', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'checkbox',
					'default' 		=> 'yes',
				),
			'orders' => array( 
					'type' => 'title',
					'title' => __( 'Orders', 'ignitewoo_vendor_stores' ),
				),
			'show_order_email' => array(
					'title' 		=> __( 'Show Email Address', 'ignitewoo_vendor_stores' ),
					'label' => __( 'Enable', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Allow vendors to see buyer email address', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'checkbox',
					'default' 		=> 'yes',
				),
			'show_order_billing_address' => array(
					'title' 		=> __( 'Show Billing Address', 'ignitewoo_vendor_stores' ),
					'label' => __( 'Enable', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Allow vendors to see buyer billing address', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'checkbox',
					'default' 		=> 'yes',
				),
			'show_order_billing_phone' => array(
					'title' 		=> __( 'Show Phone Number', 'ignitewoo_vendor_stores' ),
					'label' => __( 'Enable', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Allow vendors to see buyer phone number', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'checkbox',
					'default' 		=> 'yes',
				),
			'show_order_shipping_address' => array(
					'title' 		=> __( 'Show Shipping Address', 'ignitewoo_vendor_stores' ),
					'label' => __( 'Enable', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Allow vendors to see buyer shipping address', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'checkbox',
					'default' 		=> 'yes',
				),
			'add_vendor_to_note' => array(
					'title' 		=> __( 'Add Vendor to Note', 'ignitewoo_vendor_stores' ),
					'label' => __( 'Enable', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'When a vendor adds a note to an order prepend the vendor store name to the note', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'checkbox',
					'default' 		=> 'yes',
				),
			'give_vendor_tax' => array(
					'title' 		=> __( 'Give order item tax to vendor', 'ignitewoo_vendor_stores' ),
					'label' => __( 'Enable', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'If taxes are applied to items in the cart then give the tax to the vendor. When enabled any shipping tax is also given to the vendor if you also enable the option to give vendors per item shipping amount', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'checkbox',
					'default' 		=> 'yes',
				),
			'give_vendor_shipping' => array(
					'title' 		=> __( 'Give per item shipping cost to vendor', 'ignitewoo_vendor_stores' ),
					'label' => __( 'Enable', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'If shipping costs applied to items in the cart then give the shipping cost to the vendor ( only works with certain shipping methods, see the documentaton! )', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'checkbox',
					'default' 		=> 'yes',
				),
			);
			
			if ( class_exists( 'IgniteWoo_PDF_Invoice' ) ) { 
			
				$this->form_fields = array_merge( $this->form_fields, array(
				'packing_slips' => array(
						'title' 		=> __( 'Packing Slips', 'ignitewoo_vendor_stores' ),
						'label' => 		__( 'Enable', 'ignitewoo_vendor_stores' ),
						'description'		=> __( 'Enable vendors to print packing slips', 'ignitewoo_vendor_stores' ),
						'type' 			=> 'checkbox',
						'default' 		=> 'no',
					),
				));
				
			}
			
			if ( class_exists( 'IgniteWoo_Auctions' ) ) { 
				$this->form_fields = array_merge( $this->form_fields, array(
				'auctions' => array( 
						'type' => 'title',
						'title' => __( 'Auctions', 'ignitewoo_vendor_stores' ),
					),
				'disallow_owner_bidding' => array(
						'title' 		=> __( 'Disallow Owner Bidding', 'ignitewoo_vendor_stores' ),
						'label' => 		__( 'Enable', 'ignitewoo_vendor_stores' ),
						'description'		=> __( 'Do not allow auction owner to bid on their auctions', 'ignitewoo_vendor_stores' ),
						'type' 			=> 'checkbox',
						'default' 		=> 'no',
					),
				));
				
			}
			
			$this->form_fields = array_merge( $this->form_fields, array(
			'payments' => array( 
					'type' => 'title',
					'title' => __( 'Payment Processing', 'ignitewoo_vendor_stores' ),
				),
			'disable_instant_pay' => array(
					'title' 		=> __( 'Disable Instant Payments', 'ignitewoo_vendor_stores' ),
					'label' => 		__( 'Enable', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Display all instant payments', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'checkbox',
					'default' 		=> 'no',
				),
			'paypal_required' => array(
					'title' 		=> __( 'PayPal Required', 'ignitewoo_vendor_stores' ),
					'label' => __( 'Enable', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Are vendors required to provide a PayPal email address?', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'select',
					'default' 		=> 'yes',
					'options'		=> array( 
									'yes' => __( 'Yes', 'ignitewoo_vendor_stores' ),
									'no' => __( 'No', 'ignitewoo_vendor_stores' ),
									'disabled' => __( 'PayPal fields disabled', 'ignitewoo_vendor_stores' ),
								),
				),
			'payment_processing' => array(
					'title' 		=> __( 'Payment Processing', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Pay vendors using this method', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'select',
					'options'		=> $pay_methods,
					'default' 		=> __( 'mass_pay_manual', 'ignitewoo_vendor_stores' ),

				),
			'payment_processing_interval' => array(
					'title' 		=> __( 'Payment Interval', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'ONLY USED FOR MASS PAY. Required if using Mass Pay in automatic mode. Automatically process commission payments every X number of days from the date the commission is recorded.', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'text',
					'default' 		=> '14',
					'css'			=> 'width: 60px'
				),
			'masspay' => array( 
					'type' => 'title',
					'title' => __( 'PayPal Mass Pay API Settings', 'ignitewoo_vendor_stores' ),
				),
			'paypal_test_mode' => array(
					'title' 		=> __( 'PayPal Transaction Mode', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Sandbox or Production', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'select',
					'options'		=> array( 'sandbox' => __( 'Sandbox', 'ignitewoo_vendor_stores' ), 'live' => __( 'Production', 'ignitewoo_vendor_stores' ), ),
					'default' 		=> 'live'
				),
			'paypal_email' => array(
					'title' 		=> __( 'PayPal Email Address', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Your PayPal email address', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'text',
					'default' 		=> '14',
				),
			'paypal_username' => array(
					'title' 		=> __( 'PayPal Username', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Your PayPal API username', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'text',
					'default' 		=> '14',
				),
			'paypal_password' => array(
					'title' 		=> __( 'PayPal Password', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Your PayPal API Password', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'password',
					'default' 		=> '14',
				),
			'paypal_sig' => array(
					'title' 		=> __( 'PayPal API Signature', 'ignitewoo_vendor_stores' ),
					'description'		=> __( 'Your PayPal API Signature', 'ignitewoo_vendor_stores' ),
					'type' 			=> 'text',
					'default' 		=> '14',
				),
		)) ;
		
		if ( !get_option( 'woocommerce_ignitewoo_vendor_stores_settings' ) ) {
		
			foreach( $this->form_fields as $field => $vals ) 
				if ( isset( $vals['default'] ) )
					$settings[ $field ] = $vals['default'];
				
			update_option( 'woocommerce_' . $this->id . '_settings', $settings );
		} 
		
	}
	
	
	function process_admin_options() { 
		global $wp_rewrite;

		parent::process_admin_options();
		
		$wp_rewrite->flush_rules();
	
	}
	
}

add_action( 'woocommerce_integrations', 'ignitewoo_vendor_stores', 990, 1 );

function ignitewoo_vendor_stores( $integrations ) {

	$integrations[] = 'IgniteWoo_Product_Vendor_Settings';
	
	return $integrations;
}

