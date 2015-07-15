<?php
/**
Copyright (c) 2013 - IgniteWoo.com
*/

if ( ! defined( 'ABSPATH' ) ) exit;

/** 
Used for automatically processing commissions via PayPal Mass Pay 
*/

class IgniteWoo_PayPal_MassPay { 

	function __construct() { 
		global $ignitewoo_vendors;
		
		if ( 'mass_pay_auto' != $ignitewoo_vendors->settings['payment_processing'] ) 
			return;
		
		if ( 	empty( $ignitewoo_vendors->settings['paypal_username'] ) ||
			empty( $ignitewoo_vendors->settings['paypal_password'] ) ||
			empty( $ignitewoo_vendors->settings['paypal_sig'] ) || 
			empty( $ignitewoo_vendors->settings['paypal_test_mode'] ) 
		)
			return;
			
		require_once( dirname( __FILE__ ) . '/paypal-api/paypal.class.php' );

		$sandbox = $ignitewoo_vendors->settings['paypal_test_mode'];
		
		if ( 'sandbox' == $sandbox )
			$sandbox = true;
		else
			$sandbox = false;
		
		$args = array(
			'Sandbox' => $sandbox,
			'APIUsername' => $ignitewoo_vendors->settings['paypal_username'],
			'APIPassword' => $ignitewoo_vendors->settings['paypal_password'],
			'APISignature' => $ignitewoo_vendors->settings['paypal_sig'],
		);

		$this->paypal = new PayPal( $args );
		
		$sitename = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

		$this->mass_pay_fields = array(
			'emailsubject' => sprintf( __( 'You received money from %s', 'ignitewoo_vendor_stores' ), $sitename ),
			'currencycode' => get_option( 'woocommerce_currency' ),
			'receivertype' => 'EmailAddress'
		);
		
	}
	
	
	function process_payments() { 
		global $ignitewoo_vendors; 
		
		$payments = $this->get_payments_due();

		if ( !$payments )
			return;
			
		$mp_items = array();
		
		$vendors = array();
		
		$x = 0;

		foreach( $payments as $p ) { 

			if ( empty( $vendors[ $p->vendor_id ] ) )
				$vendors[ $p->vendor_id ] = ign_get_vendor( $p->vendor_id );

			$vendor = $vendors[ $p->vendor_id ];

			if ( empty( $vendor->paypal_email ) )
				continue;
			
			$mp_items[] = array(
				'l_email' => $vendor->paypal_email,
				//'l_receiverid' => '', // DO NOT USE THIS 
				'l_amt' => $p->amount, 
				'l_uniqueid' => $p->ID . '-' . $p->order_id, // Commission Post ID & Order ID
				'l_note' => sprintf( __( 'Payment for Order #%s', 'ignitewoo_vendor_stores' ), $p->order_id )
			);
			
			$cp_items[] = $p->ID;

			// 250 max
			if ( $x >= 249 ) { 
			
				$this->do_masspay( $mp_items, $cp_items );
				
				$mp_items = array();
				
				$cp_items = array();
			
				$x = 0;
			}
			
			$x++;
		
		}

		if ( !empty( $mp_items ) )
			$this->do_masspay( $mp_items, $cp_items );

	}
	
	
	
	function get_payments_due() { 
		global $wpdb, $ignitewoo_vendors;
		
		$delay = $ignitewoo_vendors->settings['payment_processing_interval'];
		
		if ( absint( $delay ) <= 0 )
			return;
	
		$sql = 'select ID, m1.meta_value, m2.meta_value as amount, m3.meta_value as vendor_id, m4.meta_value as order_id  from ' . $wpdb->posts . ' 
			left join ' . $wpdb->postmeta . ' m1 on m1.post_id = ID
			left join ' . $wpdb->postmeta . ' m2 on m2.post_id = ID
			left join ' . $wpdb->postmeta . ' m3 on m3.post_id = ID
			left join ' . $wpdb->postmeta . ' m4 on m4.post_id = ID 
			where post_type="vendor_commission"
			and ( m1.meta_key = "_paid_status" and m1.meta_value="unpaid" )
			and ( m2.meta_key = "_commission_amount" and m2.meta_value !="" ) 
			and ( m3.meta_key = "_commission_vendor" and m3.meta_value !="" ) 
			and ( m4.meta_key = "_order_id" and m4.meta_value !="" ) 
			and ( post_date <= DATE_ADD( post_date, INTERVAL ' . $delay .' DAY ) )';

		$res = $wpdb->get_results( $sql );
		
		return $res;
		
	}
	
	
	function do_masspay( $mp_items, $cp_items ) { 

		$data = array( 'MPFields' => $this->mass_pay_fields, 'MPItems' => $mp_items );

		$res = $this->paypal->MassPay( $data );

		if ( empty( $res ) || empty( $res['ACK'] ) )
			return false;

		if ( 'success' != strtolower( $res['ACK'] ) ) { 
		
			$to = get_option( 'admin_email' );
			
			$subject = __( 'PayPal Mass Pay Failure', 'ignitewoo_vendor_stores' );
			
			$message = sprintf( __( "PayPay Mass Payment Failed. The response from PayPal is as follows: \n %s", 'ignitewoo_vendor_stores' ), print_r( $res, true ) );
			
			wp_mail( $to, $subject, $message );
			
			return false;
			
		}

		
		foreach( $cp_items as $cp ) {
		
			update_post_meta( $cp, '_paid_status', 'paid' );
			
			update_post_meta( $cp, 'paypal_masspay_correlation_id', $res['CORRELATIONID'] );
				
			update_post_meta( $cp, 'paypal_masspay_timestamp', $res['TIMESTAMP'] );

		}
		
		
		if ( 'go' == $_GET['masspay'] ) {

			echo "<pre />\n\n";

			print_r( $res );
			
			die;
			
		}
		
	}
	
}