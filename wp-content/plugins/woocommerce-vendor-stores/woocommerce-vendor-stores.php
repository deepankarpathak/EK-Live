<?php
/**
* Plugin Name: WooCommerce Vendor Stores
* Version: 2.2.5
* Plugin URI: http://ignitewoo.com
* Description: Allows the public to establish their own storefront on your site. Works with WooCommerce products and IgniteWoo Auctions Pro
* Author: IgniteWoo.com
* Author URI: http://ignitewoo.com
 */

if ( !defined( 'ABSPATH' ) ) 
	exit;

add_action( 'plugins_loaded', 'ignitewoo_vendor_stores_init', 90 );

function ignitewoo_vendor_stores_init() { 

	if ( !class_exists( 'Woocommerce' ) && !class_exists( 'WC' ) )
		return;

	global $ignitewoo_vendors;
	
	add_action( 'plugins_loaded', 'ignitewoo_vendor_stores_admin', 100 );
	
	add_action( 'admin_init', 'ignitewoo_vendor_stores_dashboard', 100 );

	require_once( 'wc-vendor-stores-functions.php' );

	require_once( 'classes/class-wc-vendor-stores-vendors.php' );

	require_once( 'classes/class-wc-vendor-stores-widget.php' );
	
	require_once( 'classes/class-wc-vendor-stores-shipping.php' );
	
	$ignitewoo_vendors = new IgniteWoo_Vendor_Stores();
	
	$ignitewoo_vendors->shipping = new IgniteWoo_Vendor_Stores_Shipping();
	
	if ( !empty( $ignitewoo_vendors->settings['add_inquire_form'] ) && 'yes' == $ignitewoo_vendors->settings['add_inquire_form'] && !empty( $ignitewoo_vendors->settings['inquire_pub_key'] ) && !empty( $ignitewoo_vendors->settings['inquire_pvt_key'] ) )
		require_once( 'classes/class-wc-vendor-stores-inquire.php' );
	

}



function ignitewoo_vendor_stores_admin() { 
	global $ignitewoo_vendors;

	if ( !is_admin() ) 
		return;

	require_once( 'classes/class-wc-vendor-stores-admin.php' );
	
	require_once( 'classes/class-wc-vendor-stores-product-admin.php' );
	
	require_once( 'classes/class-wc-vendor-stores-integration.php' );
	
	require_once( 'classes/class-wc-vendor-stores-commissions.php' );
	
	$ignitewoo_vendors->commissions = new IgniteWoo_Vendor_Stores_Commissions();

}


function ignitewoo_vendor_stores_dashboard() { 

	require_once( 'classes/class-wc-vendor-stores-dashboard.php' );
}


register_activation_hook( __FILE__, 'ignitewoo_vendor_stores_install' );

function ignitewoo_vendor_stores_install() { 

	require_once( 'classes/class-wc-vendor-stores-install.php' );
	
}


add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ign_vendor_stores_action_links' );

function ign_vendor_stores_action_links( $links ) {

	$plugin_links = array(
		'<a href="http://ignitewoo.com/ignitewoo-software-documentation/" target="_blank">' . __( 'Docs', 'woocommerce' ) . '</a>',
		'<a href="http://ignitewoo.com/" target="_blank">' . __( 'More Plugins', 'woocommerce' ) . '</a>',
		'<a href="http://ignitewoo.com/contact-us" target="_blank">' . __( 'Support', 'woocommerce' ) . '</a>',
	);

	return array_merge( $plugin_links, $links );
}


if ( ! function_exists( 'ignitewoo_queue_update' ) )
	require_once( dirname( __FILE__ ) . '/ignitewoo_updater/ignitewoo_update_api.php' );

$this_plugin_base = plugin_basename( __FILE__ );

add_action( "after_plugin_row_" . $this_plugin_base, 'ignite_plugin_update_row', 1, 2 );

ignitewoo_queue_update( plugin_basename( __FILE__ ), 'd327ef8b3864a55b25fb5ac26550a7e3', '8210' );

