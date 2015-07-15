<?php

/**
Copyright (c) 2013 - IgniteWoo.com

Portions Copyright (c) 2011 - WooThemes

*/

if ( ! defined( 'ABSPATH' ) ) exit;

class IgniteWoo_Vendor_Stores_Commissions {

	private $dir;
	
	private $file;
	
	private $assets_dir;
	
	private $assets_url;
	
	var $token;

	function __construct() {
	
		global $ignitewoo_vendors;
	
		$this->dir = dirname( __FILE__ );
		
		$this->file = __FILE__;
		
		$this->assets_dir = $ignitewoo_vendors->assets_dir;
		
		$this->assets_url = $ignitewoo_vendors->assets_url;
		
		$this->token = 'vendor_commission';

		// Register post type
		add_action( 'init' , array( $this , 'register_post_type' ), 9999 );

		if ( is_admin() ) {

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 11 );

			// Handle custom fields for post
			add_action( 'admin_menu', array( $this, 'meta_box_setup' ), 20 );
			add_action( 'save_post', array( $this, 'meta_box_save' ) );

			// Handle commission paid status
			add_action( 'post_submitbox_misc_actions', array( $this, 'custom_actions_content' ) );
			add_action( 'save_post', array( $this, 'custom_actions_save' ) );

			// Modify text in main title text box
			add_filter( 'enter_title_here', array( $this, 'enter_title_here' ) );

			// Display custom update messages for posts edits
			add_filter( 'post_updated_messages', array( $this, 'updated_messages' ) );

			// Handle post columns
			add_filter( 'manage_edit-' . $this->token . '_columns', array( $this, 'register_custom_column_headings' ), 10, 1 );
			add_action( 'manage_posts_custom_column', array( $this, 'register_custom_columns' ), 10, 2 );
			
			add_filter( 'manage_edit-' . $this->token . '_sortable_columns', array( $this, 'column_register_sortable' ), 1, 1 );
			
			add_filter( 'request', array( $this, 'column_orderby' ), 1, 1 );
			
			// Handle commissions table filtering for vendors
			add_action( 'restrict_manage_posts', array( $this, 'paid_filter_option' ) );
			add_filter( 'request', array( $this, 'paid_filter_action' ) );

			// Add bulk actions to commissions table
			add_action( 'admin_footer-edit.php', array( $this, 'add_bulk_action_options' ) );
			add_action( 'load-edit.php', array( $this, 'generate_commissions_csv' ) );
			add_action( 'load-edit.php', array( $this, 'mark_all_commissions_paid' ) );

			// Display admin page notices
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		}

	}

	
	public function register_post_type() {

		$labels = array(
			'name' => _x( 'Commissions', 'post type general name' , 'ignitewoo_vendor_stores' ),
			'singular_name' => _x( 'Commission', 'post type singular name' , 'ignitewoo_vendor_stores' ),
			'add_new' => _x( 'Add New', $this->token , 'ignitewoo_vendor_stores' ),
			'add_new_item' => sprintf( __( 'Add New %s' , 'ignitewoo_vendor_stores' ), __( 'Commission' , 'ignitewoo_vendor_stores' ) ),
			'edit_item' => sprintf( __( 'Edit %s' , 'ignitewoo_vendor_stores' ), __( 'Commission' , 'ignitewoo_vendor_stores' ) ),
			'new_item' => sprintf( __( 'New %s' , 'ignitewoo_vendor_stores' ), __( 'Commission' , 'ignitewoo_vendor_stores' ) ),
			'all_items' => sprintf( __( '%s' , 'ignitewoo_vendor_stores' ), __( 'Commissions' , 'ignitewoo_vendor_stores' ) ),
			'view_item' => sprintf( __( 'View %s' , 'ignitewoo_vendor_stores' ), __( 'Commission' , 'ignitewoo_vendor_stores' ) ),
			'search_items' => sprintf( __( 'Search %a' , 'ignitewoo_vendor_stores' ), __( 'Commissions' , 'ignitewoo_vendor_stores' ) ),
			'not_found' =>  sprintf( __( 'No %s Found' , 'ignitewoo_vendor_stores' ), __( 'Commissions' , 'ignitewoo_vendor_stores' ) ),
			'not_found_in_trash' => sprintf( __( 'No %s Found In Trash' , 'ignitewoo_vendor_stores' ), __( 'Commissions' , 'ignitewoo_vendor_stores' ) ),
			'parent_item_colon' => '',
			'menu_name' => __( 'Commissions' , 'ignitewoo_vendor_stores' )
		);

		
		$show_in_menu = current_user_can( 'manage_woocommerce' ) ? 'woocommerce' : false;

		$args = array(
			'labels' => $labels,
			'public' => false,
			'show_ui' => true,
			'capability_type' => 'shop_order',
			'map_meta_cap' => true,
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			'show_in_menu' => $show_in_menu,
			'hierarchical' => false,
			'show_in_nav_menus' => false,
			'rewrite' => false,
			'query_var' => false,
			'supports' => array( 'custom-fields' ),
			'has_archive' => false,
		);

		register_post_type( $this->token, $args );
		
	}


	public function paid_filter_option() {
		global $typenow;

		if ( $typenow != $this->token )
			return;

		$selected = isset( $_GET['paid_status'] ) ? $_GET['paid_status'] : '';

		?>
		
		<select name="paid_status" id="dropdown_product_type">
		
			<option value=""><?php _e( 'Show all paid statuses', 'ignitewoo_vendor_stores' ) ?></option>
			
			<option value="paid" <?php selected( $selected, 'paid', false ) ?>><?php _e( 'Paid', 'ignitewoo_vendor_stores' )?></option>
			
			<option value="unpaid" <?php selected( $selected, 'unpaid', false ) ?>><?php _e( 'Unpaid', 'ignitewoo_vendor_stores' ) ?></option>
			
		</select>

		<?php
		
		
	}


	public function paid_filter_action( $request ) {
		global $typenow;

		if ( $typenow != $this->token )
			return $request;
			
		$paid_status = isset( $_GET['paid_status'] ) ? $_GET['paid_status'] : '';

		if ( empty( $paid_status ) )
			return $request; 

		$request['meta_key'] = '_paid_status';
		
		$request['meta_value'] = $paid_status;
		
		$request['meta_compare'] = '=';

		return $request;
	}


	function column_register_sortable( $columns ) {
	
		$columns['_order_id'] = 'order_id';
	
		$columns['_commission_order_id'] = 'commission_order_id';

		$columns['_commission_product'] = 'commission_product';

		$columns['_commission_vendor'] = 'commission_vendor';
		
		$columns['_amount'] = 'amount';
		
		$columns['_shipping'] = 'shipping';
		
		$columns['_tax'] = 'tax';
		
		$columns['_commission_amount'] = 'commission_amount';
		
		$columns['_paid_status'] = 'paid_status';
		
		return $columns;
	}

	
	function column_orderby( $vars ) {
	
		if ( isset( $vars['orderby'] ) && 'order_id' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'orderby' => 'ID'
			) );
		}
		
		if ( isset( $vars['orderby'] ) && 'commission_order_id' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => '_order_id',
				'orderby' => 'meta_value_num'
			) );
		}
		
		if ( isset( $vars['orderby'] ) && 'commission_vendor' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => '_commission_vendor',
				'orderby' => 'meta_value'
			) );
		}
		
		if ( isset( $vars['orderby'] ) && 'commission_product' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => '_commission_product',
				'orderby' => 'meta_value_num'
			) );
		}
		
		if ( isset( $vars['orderby'] ) && 'amount' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => '_amount',
				'orderby' => 'meta_value_num'
			) );
		}
		
		if ( isset( $vars['orderby'] ) && 'shipping' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => '_shipping',
				'orderby' => 'meta_value_num'
			) );
		}
		
		if ( isset( $vars['orderby'] ) && 'tax' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => '_tax',
				'orderby' => 'meta_value_num'
			) );
		}
		
		if ( isset( $vars['orderby'] ) && 'commission_amount' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => '_commission_amount',
				'orderby' => 'meta_value_num'
			) );
		}
		
		if ( isset( $vars['orderby'] ) && 'paid_status' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => '_paid_status',
				'orderby' => 'meta_value'
			) );
		}
		
		return $vars;
	}
	
	
	public function register_custom_column_headings( $defaults ) {
	
		$new_columns = array(
			'_order_id' => __( 'Commission' , 'ignitewoo_vendor_stores' ),
			'_commission_order_id' => __( 'Order ID' , 'ignitewoo_vendor_stores' ),
			'_commission_order_id' => __( 'Order ID' , 'ignitewoo_vendor_stores' ),
			'_commission_product' => __( 'Product' , 'ignitewoo_vendor_stores' ),
			'_commission_vendor' => __( 'Vendor' , 'ignitewoo_vendor_stores' ),
			'_amount' => __( 'Amount' , 'ignitewoo_vendor_stores' ),
			'_shipping' => __( 'Shipping' , 'ignitewoo_vendor_stores' ),
			'_tax' => __( 'Tax' , 'ignitewoo_vendor_stores' ),
			'_commission_amount' => __( 'Total' , 'ignitewoo_vendor_stores' ),
			'_paid_status' => __( 'Status' , 'ignitewoo_vendor_stores' )
		);

		$last_item = '';

		if ( isset( $defaults['title'] ) ) { unset( $defaults['title'] ); }
		if ( isset( $defaults['date'] ) ) { unset( $defaults['date'] ); }

		if ( count( $defaults ) > 2 ) {
		
			$last_item = array_slice( $defaults, -1 );

			array_pop( $defaults );
		}
		
		$defaults = array_merge( $defaults, $new_columns );

		if ( $last_item != '' )
			foreach ( $last_item as $k => $v ) {
				$defaults[$k] = $v;
				break;
			}

		return $defaults;
	}


	public function register_custom_columns( $column_name, $id ) {
		global $ignitewoo_vendors;

		$data = get_post_meta( $id , $column_name , true );
		
		switch ( $column_name ) {
		
			case '_order_id' : 
			
				$order = new WC_Order( $data );
				
				if ($order->user_id) 
					$user_info = get_userdata($order->user_id);

				if (isset($user_info) && $user_info) :

				$user = '<a href="user-edit.php?user_id=' . esc_attr( $user_info->ID ) . '">';

				if ($user_info->first_name || $user_info->last_name) $user .= $user_info->first_name.' '.$user_info->last_name;
				else $user .= esc_html( $user_info->display_name );

				$user .= '</a>';

				else :
					$user = __('Guest', 'ignitewoo_vendor_stores');
				endif;

				ob_start();
				
				echo '<a href="'.admin_url('post.php?post='.$id.'&action=edit').'"><strong>'.sprintf( __('Commission ID %s', 'ignitewoo_vendor_stores'), $id ).'</strong></a><br/>' . sprintf( __('Order %s', 'ignitewoo_vendor_stores'), $order->get_order_number() ) . '<br/>' . __('Made by', 'ignitewoo_vendor_stores') . ' ' . $user;


				if ($order->billing_email) :
					echo '<br/><small class="meta">'.__('Email:', 'ignitewoo_vendor_stores') . ' ' . '<a href="' . esc_url( 'mailto:'.$order->billing_email ).'">'.esc_html( $order->billing_email ).'</a></small>';
				endif;
				
				if ($order->billing_phone) :
					echo '<br/><small class="meta">'.__('Tel:', 'ignitewoo_vendor_stores') . ' ' . '<a href="' . esc_url( 'tel:' . $order->billing_phone ).'">' . esc_html( $order->billing_phone ).'</a></small>';
				endif;

				$title = ob_get_clean();
				
				echo apply_filters( 'ignitewoo_vendor_stores_commission_title', $title );

				break;
				

			case '_commission_order_id':
		
				$oid = get_post_meta( $id, '_order_id', true  );
	
				$edit_url = 'post.php?post=' . $oid . '&action=edit';
			
				echo '<a href="' . esc_url( $edit_url ) . '" title="' . __( 'View Order', 'ignitewoo_vendor_stores' ) . '">' . $oid . '</a>';
				
				break;

			case '_commission_product':

				if ( $data && strlen( $data ) > 0 ) {
				
					if ( function_exists( 'get_product' ) ) {
						$product = get_product( $data );
					} else {
						$product = new WC_Product( $data );
					}
					
					
					$edit_url = 'post.php?post=' . $data . '&action=edit';
					
					if ( !empty( $product ) ) { 
						echo 'ID ' . $data . ' - <a href="' . esc_url( $edit_url ) . '" title="' . __( 'View Product', 'ignitewoo_vendor_stores' ) . '">' . $product->get_title() . '</a>';
					}
					
					echo '<br/><style>dt, dd { display: inline-block }</style>';
					
					$meta = new WC_Order_Item_Meta( get_post_meta( $id, '_item_meta', true ) );
					
					$meta->display();
					
				
				}
				
				break;

			case '_commission_vendor':
			
				if ( $data && strlen( $data ) > 0 ) {
				
					$vendor = ign_get_vendor( $data );

					if ( $vendor ) {
						$edit_url = 'edit-tags.php?action=edit&taxonomy=' . $ignitewoo_vendors->token . '&tag_ID=' . $vendor->ID . '&post_type=product';
						echo '<a href="' . esc_url( $edit_url ) . '" title="' . __( 'Edit Vendor', 'ignitewoo_vendor_stores' ) . '">' . $vendor->title . '</a>';
					}
				}
				
				break;

			case '_amount':
			
				if ( empty( $data ) )
					$data = 0;
				
				echo woocommerce_price( $data ); 
				
				break;

			case '_commission_amount':
			
				if ( empty( $data ) )
					$data = 0;
					
				echo woocommerce_price( $data );
				
				break;
			
			case '_tax':
				
				if ( empty( $data ) )
					$data = 0;
				
				echo woocommerce_price( $data );
				
				break;
				
			case '_shipping':
			
				if ( empty( $data ) )
					$data = 0;
				
				echo woocommerce_price( $data );
				
				break;
				
			case '_paid_status':
			
				echo ucfirst( $data );
				
				break;

			default:
				break;
		}

	}
	
	
	public function updated_messages( $messages ) {
		global $post, $post_ID;

		$messages[ $this->token ] = array(
			0 => '', 
			1 => __( 'Commission updated.' , 'ignitewoo_vendor_stores' ),
			2 => __( 'Custom field updated.' , 'ignitewoo_vendor_stores' ),
			3 => __( 'Custom field deleted.' , 'ignitewoo_vendor_stores' ),
			4 => __( 'Commission updated.' , 'ignitewoo_vendor_stores' ),
			5 => isset($_GET['revision']) ? sprintf( __( 'Commission restored to revision from %s.' , 'ignitewoo_vendor_stores' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => __( 'Commission published.' , 'ignitewoo_vendor_stores' ),
			7 => __( 'Commission saved.' , 'ignitewoo_vendor_stores' ),
			8 => __( 'Commission submitted.' , 'ignitewoo_vendor_stores' ),
			9 => sprintf( __( 'Commission scheduled for: %1$s.' , 'ignitewoo_vendor_stores' ), '<strong>' . date_i18n( __( 'M j, Y @ G:i' , 'ignitewoo_vendor_stores' ), strtotime( $post->post_date ) ) . '</strong>' ),
			10 => __( 'Commission draft updated.' , 'ignitewoo_vendor_stores' ),
		);

		return $messages;
	}


	public function custom_actions_content() {
		global $post;
		
		if ( get_post_type( $post ) != $this->token )
			return;
			
		echo '<div class="misc-pub-section misc-pub-section-last">';
		wp_nonce_field( plugin_basename( $this->file ), 'paid_status_nonce' );

		$status = get_post_meta( $post->ID, '_paid_status', true ) ? get_post_meta( $post->ID, '_paid_status', true ) : 'unpaid';

		echo '<input type="radio" name="_paid_status" id="_paid_status-unpaid" value="unpaid" ' . checked( $status, 'unpaid', false ) . ' /> <label for="_paid_status-unpaid" class="select-it">Unpaid</label>&nbsp;&nbsp;&nbsp;&nbsp;';

		echo '<input type="radio" name="_paid_status" id="_paid_status-paid" value="paid" ' . checked( $status, 'paid', false ) . '/> <label for="_paid_status-paid" class="select-it">Paid</label>';
		
		echo '</div>';

	}


	public function custom_actions_save( $post_id ) {
	
		if ( !isset( $_POST['paid_status_nonce'] ) )
			return;
	
		if ( ! wp_verify_nonce( $_POST['paid_status_nonce'], plugin_basename( $this->file ) ) )
			return $post_id;

		if ( isset( $_POST['_paid_status'] ) )
			update_post_meta( $post_id, '_paid_status', $_POST['_paid_status'] );
	    
	}


	public function meta_box_setup() {
		add_meta_box( 'commission-data', __( 'Commission Details' , 'ignitewoo_vendor_stores' ), array( &$this, 'meta_box_content' ), $this->token, 'normal', 'high' );
	}


	public function meta_box_content() {
		global $post_id, $woocommerce;
		
		$fields = get_post_custom( $post_id );

		$field_data = $this->get_custom_fields_settings();

		$html = '';

		$html .= '<input type="hidden" name="' . $this->token . '_nonce" id="' . $this->token . '_nonce" value="' . wp_create_nonce( plugin_basename( $this->dir ) ) . '" />';

		if ( 0 < count( $field_data ) ) {
		
			$html .= '<table class="form-table">' . "\n";
			
			$html .= '<tbody>' . "\n";

			foreach ( $field_data as $k => $v ) {
			
				$data = $v['default'];

				if ( isset( $fields[$k] ) && isset( $fields[$k][0] ) ) {
					$data = $fields[$k][0];
				}

				if ( $k == '_commission_product' ) {

					$option = '<option value=""></option>';
					if ( $data && strlen( $data ) > 0 ) {
						if ( function_exists( 'get_product' ) ) {
							$product = get_product( $data );
						} else {
							$product = new WC_Product( $data );
						}
						$option = '<option value="' . $data . '" selected="selected">' . $product->get_title() . '</option>';
					}

					$html .= '<tr valign="top"><th scope="row"><label for="' . esc_attr( $k ) . '">' . $v['name'] . '</label></th><td><select name="' . esc_attr( $k ) . '" id="' . esc_attr( $k ) . '" class="ajax_chosen_select_products_and_variations" data-placeholder="Search for product&hellip;" style="min-width:300px;">' . $option . '</select>' . "\n";
					
					$html .= '<p class="description">' . $v['description'] . '</p>' . "\n";
					
					$html .= '</td><tr/>' . "\n";

				} elseif ( $k == '_commission_vendor' ) {

					$option = '<option value=""></option>';
					if ( $data && strlen( $data ) > 0 ) {
						$vendor = ign_get_vendor( $data );
						$option = '<option value="' . $vendor->ID . '" selected="selected">' . $vendor->title . '</option>';
					}

					$html .= '<tr valign="top"><th scope="row"><label for="' . esc_attr( $k ) . '">' . $v['name'] . '</label></th><td><select name="' . esc_attr( $k ) . '" id="' . esc_attr( $k ) . '" class="ajax_chosen_select_vendor" data-placeholder="Search for vendor&hellip;" style="min-width:300px;">' . $option . '</select>' . "\n";
					$html .= '<p class="description">' . $v['description'] . '</p>' . "\n";
					$html .= '</td><tr/>' . "\n";

				} else {

					if ( $v['type'] == 'checkbox' ) {
						$html .= '<tr valign="top"><th scope="row">' . $v['name'] . '</th><td><input name="' . esc_attr( $k ) . '" type="checkbox" id="' . esc_attr( $k ) . '" ' . checked( 'on' , $data , false ) . ' /> <label for="' . esc_attr( $k ) . '"><span class="description">' . $v['description'] . '</span></label>' . "\n";
						$html .= '</td><tr/>' . "\n";
					} else {
						if ( floatval( $data ) > 0 ) 
							$data = round( $data, 2 );
						$html .= '<tr valign="top"><th scope="row"><label for="' . esc_attr( $k ) . '">' . $v['name'] . '</label></th><td><input name="' . esc_attr( $k ) . '" type="text" id="' . esc_attr( $k ) . '" class="regular-text" value="' . esc_attr( $data ) . '" />' . "\n";
						$html .= '<p class="description">' . $v['description'] . '</p>' . "\n";
						$html .= '</td><tr/>' . "\n";
					}

				}

			}

			$html .= '</tbody>' . "\n";
			
			$html .= '</table>' . "\n";
		}

			$js = "
			jQuery('select.ajax_chosen_select_products_and_variations').ajaxChosen({
				method: 	'GET',
				url: 		'" . admin_url('admin-ajax.php') . "',
				dataType: 	'json',
				afterTypeDelay: 100,
				data:		{
					action: 		'woocommerce_json_search_products_and_variations',
						security: 		'" . wp_create_nonce("search-products") . "'
				}
			}, function (data) {

				var terms = {};

				$.each(data, function (i, val) {
					terms[i] = val;
				});

				return terms;
			});

			jQuery('select.ajax_chosen_select_vendor').ajaxChosen({
				method: 		'GET',
				url: 			'" . admin_url( 'admin-ajax.php' ) . "',
				dataType: 		'json',
				afterTypeDelay: 100,
				minTermLength: 	1,
				data:		{
					action: 	'ignitewoo_json_search_vendors',
						security: 	'" . wp_create_nonce( 'search-vendors' ) . "'
				}
			}, function (data) {

				var terms = {};

				$.each(data, function (i, val) {
					terms[i] = val;
				});

				return terms;
			});
		";

		if ( function_exists( 'wc_enqueue_js' ) ) 
			wc_enqueue_js( $js );
		else
			$woocommerce->add_inline_js( $js );
		
		
		echo $html;
	}


	public function meta_box_save( $post_id ) {
		global $post, $messages;

		if ( ( get_post_type() != $this->token ) || ! wp_verify_nonce( $_POST[ $this->token . '_nonce'], plugin_basename( $this->dir ) ) ) {
			return $post_id;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;
			

		$field_data = $this->get_custom_fields_settings();
		
		$fields = array_keys( $field_data );

		foreach ( $fields as $f ) {

			if ( isset( $_POST[$f] ) )
				${$f} = strip_tags( trim( $_POST[$f] ) );


			if ( 'url' == $field_data[$f]['type'] )
				${$f} = esc_url( ${$f} );


			if ( ${$f} == '' )
				delete_post_meta( $post_id , $f , get_post_meta( $post_id , $f , true ) );
			else
				update_post_meta( $post_id , $f , ${$f} );
	
		}

	}


	public function enter_title_here( $title ) {
		if ( get_post_type() == $this->token ) {
			$title = __( 'Enter the commission title here (optional)' , 'ignitewoo_vendor_stores' );
		}
		return $title;
	}


	public function get_custom_fields_settings() {
		$fields = array();

		$fields['_commission_product'] = array(
			'name' => __( 'Product:' , 'ignitewoo_vendor_stores' ),
			'description' => __( 'The product associated with this commission.' , 'ignitewoo_vendor_stores' ),
			'type' => 'select',
			'default' => '',
			'section' => 'commission-data'
		);

		$fields['_commission_vendor'] = array(
			'name' => __( 'Vendor:' , 'ignitewoo_vendor_stores' ),
			'description' => __( 'The store vendor that will receive this payment.' , 'ignitewoo_vendor_stores' ),
			'type' => 'select',
			'default' => '',
			'section' => 'commission-data'
		);

		$fields['_commission_amount'] = array(
			'name' => __( 'Amount:' , 'ignitewoo_vendor_stores' ),
			'description' => __( 'The total amount of this commission' , 'ignitewoo_vendor_stores' ),
			'type' => 'text',
			'default' => 0.00,
			'section' => 'commission-data'
		);

		return $fields;
	}


	public function add_bulk_action_options() {
		global $post_type;

		if ( $post_type != $this->token )
			return;
			
		?>
		
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('<option>').val('export').text('<?php _e('Export unpaid commissions to CSV', 'ignitewoo_vendor_stores' ); ?>').appendTo("select[name='action']");
				jQuery('<option>').val('export').text('<?php _e('Export unpaid commissions to CSV', 'ignitewoo_vendor_stores' ); ?>').appendTo("select[name='action2']");
				jQuery('<option>').val('mark_paid').text('<?php _e('Mark all commissions as paid', 'ignitewoo_vendor_stores' ); ?>').appendTo("select[name='action']");
				jQuery('<option>').val('mark_paid').text('<?php _e('Mark all commissions as paid', 'ignitewoo_vendor_stores' ); ?>').appendTo("select[name='action2']");
			});
		</script>

		<?php
	}


	public function generate_commissions_csv() {
		global $ignitewoo_vendors, $typenow;

		if ( $typenow != $this->token )
			return;

	    	// Confirm list table action
	    	$wp_list_table = _get_list_table( 'WP_Posts_List_Table' );
	    	
		$action = $wp_list_table->current_action();
		
		if ( $action != 'export' ) 
			return;

		check_admin_referer( 'bulk-posts' );

		$headers = array(
			'Recipient',
			'Payment',
			'Currency',
			'Customer ID',
			'Note'
	    	);

	    	$args = array(
	    		'post_type' => $this->token,
	    		'post_status' => array( 'publish', 'private' ),
	    		'meta_key' => '_paid_status',
	    		'meta_value' => 'unpaid',
	    		'posts_per_page' => -1
		);
		
		$commissions = get_posts( $args );

		if ( empty( $commissions ) ) {
			set_transient( 'ign_no_commissions', 1, 60 );
			return;
		}

		$commission_totals = array();

		foreach( $commissions as $commission ) {
			
			$commission_data = ign_get_commission( $commission->ID );

			if ( !isset( $commission_totals[ $commission_data->vendor->ID ] ) )
				$commission_totals[ $commission_data->vendor->ID ] = (int) 0;
			
			$commission_totals[ $commission_data->vendor->ID ] += (float) $commission_data->amount;
		}

	    	$currency = get_woocommerce_currency();
	    	
	    	$payout_note = sprintf( __( 'Commissions earned from %1$s as of %2$s on %3$s', 'ignitewoo_vendor_stores' ), get_bloginfo( 'name' ), date( 'H:i:s' ), date( 'd-m-Y' ) );

		$commissions_data = array();
		
		foreach( $commission_totals as $vendor_id => $total ) {

			//$vendor = ign_get_vendor( $commission_data->vendor->ID );
			$vendor = ign_get_vendor( $vendor_id );

			if ( isset( $vendor->paypal_email ) && strlen( $vendor->paypal_email ) > 0 )
				$recipient = $vendor->paypal_email;
			else
				$recipient = $vendor->title;

			$commissions_data[] = array(
				$recipient,
				round( $total, 2 ),
				$currency,
				$vendor_id,
				$payout_note
			);
		}

	    	$date = date( 'Y-m-d-H-i-s' );
	    	
	    	$filename = 'store-vendor-commissions-' . $date . '.csv';

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

		fputcsv( $file, $headers );

		foreach ( $commissions_data as $commission )
			fputcsv( $file, $commission );

		fclose( $file );
		
		$csv = ob_get_clean();

		echo $csv;
		
		die();

	}


	public function mark_all_commissions_paid() {
		global $ignitewoo_vendors, $typenow;

		if ( $typenow != $this->token )
			return;

	    	$wp_list_table = _get_list_table( 'WP_Posts_List_Table' );
	    	
		$action = $wp_list_table->current_action();
		
		if ( $action != 'mark_paid' ) 
			return;

		check_admin_referer( 'bulk-posts' );

		$args = array(
			'post_type' => $this->token,
			'post_status' => array( 'publish', 'private' ),
			'meta_key' => '_paid_status',
			'meta_value' => 'unpaid',
			'posts_per_page' => -1
		);
		
		$commissions = get_posts( $args );

		foreach( $commissions as $commission )
			update_post_meta( $commission->ID, '_paid_status', 'paid', 'unpaid' );

		$redirect = add_query_arg( 'message', 'paid', $_REQUEST['_wp_http_referer'] );

		wp_safe_redirect( $redirect );
		
		exit;
	
	}


	public function admin_enqueue_scripts() {
		global $woocommerce, $pagenow, $typenow;

		if ( $this->token != $typenow ) 
			return;
			 
		// Load admin CSS
		wp_enqueue_style( 'product_vendors_admin', $this->assets_url . '/css/admin.css' );

		if ( in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) && $typenow == $this->token ) {

			if ( ! wp_style_is( 'woocommerce_chosen_styles', 'queue' ) )
				wp_enqueue_style( 'woocommerce_chosen_styles', $woocommerce->plugin_url() . '/assets/css/chosen.css' );

			// Load Chosen JS
			wp_enqueue_script( 'chosen' );
			
			wp_enqueue_script( 'ajax-chosen' );
		}
		
	}


	public function admin_notices() {
		global $current_screen, $pagenow, $post_type;

		$message = false;

		if ( in_array( $pagenow, array( 'edit.php', 'post.php', 'post-new.php' ) ) && $post_type == $this->token ) {
		
			if ( isset( $_GET['message'] ) && $_GET['message'] == 'paid' ) {
			
				$message = sprintf( __( '%1$sAll commissions have been marked as %2$spaid%3$s.%4$s', 'ignitewoo_vendor_stores' ), '<div id="message" class="updated"><p>', '<b>', '</b>', '</p></div>' );
				
			} else {
			
				$vendors = ign_get_vendors();
				
				if ( ! $vendors ) {
					$message = sprintf( __( '%1$s%2$sYou need to add vendors before commissions can be created.%3$s %4$sClick here to add your first vendor%5$s.%6$s', 'ignitewoo_vendor_stores' ), '<div id="message" class="updated"><p>', '<b>', '</b>', '<a href="' . esc_url( admin_url( 'edit-tags.php?taxonomy=shop_vendor&post_type=product' ) ) . '">', '</a>', '</p></div>' );
				}
				
			}
			
		}
		
		if ( get_transient( 'ign_no_commissions' ) ) { 
		
			delete_transient( 'ign_no_commissions' );
			
			$message = sprintf( __( '%1$sNo commissions to export.%2$s', 'ignitewoo_vendor_stores' ), '<div id="message" class="updated"><p><strong>', '</strong></p></div>' );
			
		}

		if ( $message )
			echo $message;

	}

}