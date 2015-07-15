<?php
/**
 * Functions used for displaying the WooCommerce dashboard widgets
*/


/**
Copyright (c) 2013 - IgniteWoo.com
*/

if ( ! defined( 'ABSPATH' ) ) exit;


class IgniteWoo_Vendor_Store_Dashboard { 


	function __construct() { 

		if ( !current_user_can( 'view_woocommerce_reports' ) )
			return;
			
		if ( !current_user_can( 'vendor' ) )
			return;

		// Remove stock WooCommerce dashboard widget
		remove_action( 'wp_dashboard_setup', 'woocommerce_init_dashboard_widgets' );
		
		remove_action( 'admin_footer', 'woocommerce_dashboard_sales_js' );
		
		add_action( 'wp_dashboard_setup', array( &$this, 'woocommerce_init_dashboard_widgets' ) );
		
		// Remove Default WordPress Widgets
		
		// Right Now Widget
		remove_meta_box( 'dashboard_right_now', 'dashboard', 'core' ); 
		// Comments Widget      
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'core' ); 
		// Incoming Links Widget
		remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'core' );  
		// Plugins Widget
		remove_meta_box( 'dashboard_plugins', 'dashboard', 'core' );       
		// Quick Press Widget
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'core' );  
		// Recent Drafts Widget   
		remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'core' );  
		// WordPress Blog Feed 
		remove_meta_box( 'dashboard_primary', 'dashboard', 'core' );  
		// Other WordPress News       
		remove_meta_box( 'dashboard_secondary', 'dashboard', 'core' ); 
		// WooCommerce
		remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'core' ); 
		
		
	}
	
	

	function woocommerce_init_dashboard_widgets() {
		global $current_month_offset, $the_month_num, $the_year;

		$current_month_offset = 0;

		if (isset($_GET['wc_sales_month'])) $current_month_offset = (int) $_GET['wc_sales_month'];

		$the_month_num = date('n', strtotime('NOW '.($current_month_offset).' MONTH'));
		$the_year = date('Y', strtotime('NOW '.($current_month_offset).' MONTH'));

		$sales_heading = '';

		if ($the_month_num!=date('m'))
			$sales_heading .= '<a href="index.php?wc_sales_month='.($current_month_offset+1).'" class="next">'.date_i18n('F', strtotime('01-'.($the_month_num+1).'-2011')).' &rarr;</a>';

		$sales_heading .= '<a href="index.php?wc_sales_month='.($current_month_offset-1).'" class="previous">&larr; '.date_i18n('F', strtotime('01-'.($the_month_num-1).'-2011')).'</a><span>'.__( 'Monthly Sales', 'ignitewoo_vendor_stores' ).'</span>';

		if ( current_user_can( 'view_woocommerce_reports' ) )
			wp_add_dashboard_widget( 'woocommerce_dashboard_sales', $sales_heading, array( &$this, 'woocommerce_dashboard_sales' ) );

	}

	
	function orders_this_month( $where = '' ) {
		global $the_month_num, $the_year;

		$month = $the_month_num;
		
		$year = (int) $the_year;

		$first_day = strtotime("{$year}-{$month}-01");
		
		//$last_day = strtotime('-1 second', strtotime('+1 month', $first_day));
		
		$last_day = strtotime('+1 month', $first_day);

		$after = date('Y-m-d', $first_day);
		
		$before = date('Y-m-d', $last_day);

		$where .= " AND post_date > '$after'";
		
		$where .= " AND post_date < '$before'";

		return $where;
	}

	
	function woocommerce_dashboard_sales() {

		add_action( 'admin_footer', array( &$this, 'woocommerce_dashboard_sales_js' ) );

		?><div id="placeholder" style="width:100%; height:300px; position:relative;"></div><?php
	}

	
	function woocommerce_dashboard_sales_js() {
		global $woocommerce, $wp_locale, $user_ID, $ignitewoo_vendors;

		$screen = get_current_screen();

		if ( !$screen || 'dashboard' != $screen->id ) 
			return;

		global $current_month_offset, $the_month_num, $the_year;

		// Get orders to display in widget
		add_filter( 'posts_where', array( &$this, 'orders_this_month' ) );

		if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ) {
		
			$args = apply_filters( 'ignitewoo_vendor_stores_dashboard_monthly_sales', array(
				'numberposts'     => -1,
				'orderby'         => 'post_date',
				'order'           => 'DESC',
				'post_type'       => 'shop_order',
				'post_status'     => apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ),
				'post_author'	  => $user_ID,
				'suppress_filters' => false,
			) );
			
		} else { 
		
			$args = apply_filters( 'ignitewoo_vendor_stores_dashboard_monthly_sales', array(
				'numberposts'     => -1,
				'orderby'         => 'post_date',
				'order'           => 'DESC',
				'post_type'       => 'shop_order',
				'post_status'     => 'publish',
				'post_author'	  => $user_ID,
				'suppress_filters' => false,
				'tax_query' => array(
					array(
						'taxonomy' => 'shop_order_status',
							'terms' => apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ),
							'field' => 'slug',
							'operator' => 'IN'
						)
				)
			) );
		}
		
		$orders = get_posts( $args );

		$order_counts = array();
		
		$order_amounts = array();

		$month = $the_month_num;
		
		$year = (int) $the_year;

		$first_day = strtotime( "{$year}-{$month}-01" );
		
		$last_day = strtotime( '-1 second', strtotime( '+1 month', $first_day ) );

		if ( 0 == ( date( 'm' ) - $the_month_num ) ) :
			$up_to = date('d', strtotime('NOW'));
		else :
			$up_to = date('d', $last_day);
		endif;
		
		$count = 0;

		while ( $count < $up_to ) :

			$time = strtotime(date('Ymd', strtotime('+ '.$count.' DAY', $first_day))).'000';

			$order_counts[$time] = 0;
			
			$order_amounts[$time] = 0;

			$count++;
			
		endwhile;

		$vendor_data = ign_get_user_vendor( $user_ID );
		
		if ( !empty( $vendor_data->ID ) )
			$products = ign_get_vendor_products( $vendor_data->ID );

		if ( $orders && $products ) :
				
			$ids = array();
		
			foreach ( $products as $product )
				$ids[] = ( $product->ID );

			foreach ( $orders as $order ) :

				$order_data = new WC_Order( $order->ID );

				if ( 'cancelled' == $order_data->status || 'refunded' == $order_data->status ) 
					continue;

				$order_data->order_total = 0;
				
				foreach ( $order_data->get_items() as $item_id => $item ) {

					$_product = $order_data->get_product_from_item( $item );

					$variation_id = get_metadata( 'order_item', $item_id, '_variation_id', true );
					
					if ( empty( $_product ) )
						continue;

					if ( !in_array( $_product->id, $ids ) ) {

						continue;
						
					} else {
						
						if ( !empty( $item['line_total'] ) )
							$order_data->order_total += ign_calculate_product_commission( $item['line_total'], $_product->id, $variation_id  );
						
					}

				}
				
				$time = strtotime(date('Ymd', strtotime($order->post_date))).'000';

				if (isset($order_counts[$time])) :
					$order_counts[$time]++;
				else :
					$order_counts[$time] = 1;
				endif;

				if (isset($order_amounts[$time])) :
					$order_amounts[$time] = $order_amounts[$time] + $order_data->order_total;
				else :
					$order_amounts[$time] = (float) $order_data->order_total;
				endif;

			endforeach;
		endif;

		remove_filter( 'posts_where', 'orders_this_month' );

		$params = array(
			'currency_symbol' 	=> get_woocommerce_currency_symbol(),
			'number_of_sales' 	=> absint( array_sum( $order_counts ) ),
			'sales_amount'    	=> woocommerce_price( array_sum( $order_amounts ) ),
			'sold' 			=> __( 'Sold', 'ignitewoo_vendor_stores' ),
			'earned'    		=> __( 'Earned', 'ignitewoo_vendor_stores' ),
			'month_names'     	=> array_values( $wp_locale->month_abbrev ),
		);

		$order_counts_array = array();
		
		foreach ($order_counts as $key => $count)
			$order_counts_array[] = array($key, $count);
		
		$order_amounts_array = array();
		
		foreach ($order_amounts as $key => $amount)
			$order_amounts_array[] = array($key, $amount);

		$order_data = array( 'order_counts' => $order_counts_array, 'order_amounts' => $order_amounts_array );

		$params['order_data'] = json_encode($order_data);

		// Queue scripts
		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script( 'woocommerce_dashboard_sales', $woocommerce->plugin_url() . '/assets/js/admin/dashboard_sales' . $suffix . '.js', array( 'jquery', 'flot', 'flot-resize' ), '1.0' );
		
		wp_register_script( 'flot', $woocommerce->plugin_url() . '/assets/js/admin/jquery.flot'.$suffix.'.js', 'jquery', '1.0' );
		
		wp_register_script( 'flot-resize', $woocommerce->plugin_url() . '/assets/js/admin/jquery.flot.resize'.$suffix.'.js', 'jquery', '1.0' );

		wp_localize_script( 'woocommerce_dashboard_sales', 'params', $params );

		wp_print_scripts('woocommerce_dashboard_sales' );
	}
	
}

$ignitewoo_vendor_stores_dashboard = new IgniteWoo_Vendor_Store_Dashboard();
