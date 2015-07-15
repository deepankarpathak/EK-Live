<?php

/**
Copyright (c) 2013 - IgniteWoo.com
*/

if ( ! defined( 'ABSPATH' ) ) exit;

class IgniteWoo_Vendor_Stores_Install { 


	function __construct() { 
	
		$this->create_pages();
		
	}

	
	function create_pages() {
		global $wpdb;

		$vendor_page_id = $this->create_page( 'vendor_dashboard', __( 'Vendor Dashboard', 'ignitewoo_vendor_store' ), '[vendor_store_dashboard]');
		
		// Initial setting storage
		update_option( 'woocommerce_vendor_store_vendor_dashboard_page_id', $vendor_page_id );
		
	}
	
	
	function create_page( $slug, $page_title = '', $page_content = '', $post_parent = 0 ) {
		global $wpdb;

		$page_id = get_option( 'woocommerce_vendor_store_' . $slug . '_page_id' );

		if ( $page_id > 0 && get_post( $page_id ) ) {
			return $page_id;
		}

		$page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " WHERE post_name = %s LIMIT 1;", $slug ) );
		
		if ( $page_found )
			return $page_found;

		$page_data = array(
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'post_author'    => 1,
			'post_name'      => $slug,
			'post_title'     => $page_title,
			'post_content'   => $page_content,
			'post_parent'    => $post_parent,
			'comment_status' => 'closed'
		);

		$page_id = wp_insert_post( $page_data );

		return $page_id;
	}
}

$ignitewoo_vendor_store_install = new IgniteWoo_Vendor_Stores_Install();
