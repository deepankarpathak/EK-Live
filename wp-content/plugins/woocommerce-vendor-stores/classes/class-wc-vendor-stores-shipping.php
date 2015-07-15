<?php

/**
 * Per vendor shipping calculator
 *
 * Copyright (c) 2013 - IgniteWoo.com
 *
*/


if ( ! defined( 'ABSPATH' ) ) exit;

class IgniteWoo_Vendor_Stores_Shipping { 

	function __construct() {
	
	}

	function get_vendor_shipping_amount( $order, $item_id, $product, $vendor_id ) {
		global $woocommerce, $ignitewoo_vendors, $wpdb;

		$shipping = 0;

		// Check for drop shippers first
		$shipping = $order->get_item_meta( $item_id, '_ign_shipping_cost', true );

		if ( !empty( $shipping ) )
			return $shipping;
	
		if ( empty( $order->shipping_method ) && version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) ) {
		
			if ( !method_exists( $order, 'get_shipping_methods' ) )
				$order = new WC_Order( $order->id );

			// 2.1+ get line items for shipping
			$shipping_methods = $order->get_shipping_methods();

			// Assumes there is only one shipping method for the entire order
			foreach ( $shipping_methods as $shipping ) {
				$order->shipping_method = $shipping['item_meta']['method_id'][0];
				break;
			}
		}

		$_product = get_product( $product['product_id'] );

		if ( !$_product->needs_shipping() )
			return $shipping;

		if ( empty( $order->shipping_method ) )
			return $shipping;
			
		switch( $order->shipping_method ) { 
		
			case 'per_product' :

				if ( !function_exists( 'woocommerce_per_product_shipping' ) ) 
					break;
					
				$shipping = $this->per_product_shipping( $order->id, $product, $item_id );

				break;
			
			case 'local_delivery' : 
			
				$local_delivery = get_option( 'woocommerce_local_delivery_settings' );

				if ( 'product' == $local_delivery['type'] && absint( $local_delivery['fee'] ) > 0 )
					$shipping = $product['qty'] * $local_delivery['fee'];

				break;
				
			case 'international_delivery' : 
			
				$intl_delivery = get_option('woocommerce_international_delivery_settings');

				if ( 'item' != $intl_delivery['type'] || absint( $intl_delivery['fee'] ) <= 0 )
					break;
					
				$wc_intl_delivery = new WC_Shipping_International_Delivery();
								
				$fee = $wc_intl_delivery->get_fee( $intl_delivery['fee'], $_product->get_price() );
				
				$shipping = ( $int_delivery['cost'] + $fee ) * $product['qty'];
				
				break; 
				
			case 'fedex_wsdl':
			case 'table_rate_shipping':
			case 'ups_drop_shipping_rate':
			case 'usps_drop_shipping':
			case 'drop_shipping' : 
			
				$shipping = $this->get_drop_ship_fee( $order, $item_id );
			
				break;       
		}

		$shipping = apply_filters( 'ignitewoo_vendor_stores_shipping_due', $shipping, $order->id, $product );

		// If there was not a support method we get an array back, can't use that so send back zero
		if ( is_array( $shipping ) )
			$shipping = 0;
			
		return $shipping;
	}

	function shipping_tax( $order_id, $product_id, $variation_id, $vendor_id ) { 
		global $wpdb, $ignitewoo_vendors;
		
		if ( 'yes' != $ignitewoo_vendors->settings['give_vendor_tax'] )
			return 0;
		
		$sql = "select order_item_meta.meta_value FROM {$wpdb->prefix}woocommerce_order_items as order_items
			LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
			WHERE order_items.order_id = {$order_id} AND order_item_meta.meta_key = 'shipping_tax_amount';
			";

		$st = $wpdb->get_var( $sql );

		if ( empty( $st ) )
			return 0;

		// returns the amount of shipping tax due, calculation takes into consideration the vendor commission
		// setting. If it's not a percent then the entire tax is due, 
		// otherwise only a percentage of the tax is due.
		$st = ign_calculate_shipping_commission( $product_id, $variation_id = null, $vendor_id = null, $st );

		return $st;

	}
	
	function per_product_shipping( $order_id, $product, $item_id ) {
		global $woocommerce;

		$cost = 0;
		
		$pp_shipping = false;

		$order = new WC_Order( $order_id );
		
		// Data required for PPS to calculation shipping
		$item['destination']['country'] = $order->shipping_country;
		
		$item['destination']['state'] = $order->shipping_state;
		
		$item['destination']['postcode'] = $order->shipping_postcode;

		if ( $product['variation_id'] )
			$pp_shipping = woocommerce_per_product_shipping_get_matching_rule( $product['variation_id'], $item );
		
		if ( empty( $pp_shipping ) )
			$pp_shipping = woocommerce_per_product_shipping_get_matching_rule( $product['product_id'], $item );

		if ( $pp_shipping ) {
		
			$cost = $pp_shipping->rule_item_cost * $product['qty'];
			
			$cost += $pp_shipping->rule_cost;
		} else { 
		
			// Use defaul cost since no per product rule set
			$settings = get_option( 'woocommerce_per_product_settings' );
			
			if ( !empty( $settings ) )
				$cost = $settings['cost'];
		}
	
		return $cost;
		
	}

	// Order items are recorded by WooCommerce in the order they were listed in the cart
	// Therefore order item meta is listed in the table in that sort order when considering order_item_id
	function get_drop_ship_fee( $order, $item_id ) { 

		return $order->get_item_meta( $item_id, '_ign_shipping_cost', true );

	}


}
