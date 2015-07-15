<?php

if ( ! defined( 'ABSPATH' ) ) exit;


/**
Produce a dropdown list of months
**/
function ign_vendor_report_month_dropdown( $name = "month", $selected = null ) {

	$dd = '<select name="'.$name.'" id="'.$name.'">';

	$dd .= '<option value="all">' . __( 'All months', 'ignitewoo_vendor_stores' ) . '</option>';
	
	$selected = is_null( $selected ) ? date( 'n', time() ) : $selected;

	for ( $i = 1; $i <= 12; $i++ ) {

		$dd .= '<option value="'.$i.'"';

		if ($i == $selected)
			$dd .= ' selected';
			
		$mon = date( "F", mktime( 0, 0, 0, ( $i + 1 ), 0, 0  ) );
		
		$dd .= '>' . $mon . '</option>';
	}

	$dd .= '</select>';

	echo $dd;
}


/**
Produce a dropdown list of years
**/
function ign_vendor_report_year_dropdown(  $name = "year", $selected = null ) {	

	$dd = '<select name="' . $name . '" id="' . $name . '">';
	
	$dd .= '<option value="all">' . __( 'All years', 'ignitewoo_vendor_stores' ) . '</option>';

	$current_year = date( 'Y' );
	
	$down_to = $current_year - 5;

	for ( $i = $current_year; $i >= $down_to; $i-- ) {
	
		$dd .= '<option value="' . $i . '" ' . selected( $selected, $i, false ) . '>' . $i . '</option>';
	}
	
	$dd .= '</select>';

	echo $dd;
}

global $ign_tracking_providers; 

$ign_tracking_providers = apply_filters( 'ign_vendor_stores_tracking_providers', array(
	'Australia' => array(
		'Australia Post'
			=> 'http://auspost.com.au/track/track.html?id=%1$s',
	),
	'Austria' => array(
		'post.at' =>
			'http://www.post.at/sendungsverfolgung.php?pnum1=%1$s',
		'dhl.at' =>
			'http://www.dhl.at/content/at/de/express/sendungsverfolgung.html?brand=DHL&AWB=%1$s'
	),
	'Brazil' => array(
		'Correios'
			=> 'http://websro.correios.com.br/sro_bin/txect01$.QueryList?P_LINGUA=001&P_TIPO=001&P_COD_UNI=%1$s'
	),
	'Canada' => array(
		'Canada Post'
			=> 'http://www.canadapost.ca/cpotools/apps/track/personal/findByTrackNumber?trackingNumber=%1$s',
	),
	'Czech Republic' => array(
		'PPL.cz'
			=> 'http://www.ppl.cz/main2.aspx?cls=Package&idSearch=%1$s',
		'Česká pošta'
			=> 'http://www.ceskaposta.cz/cz/nastroje/sledovani-zasilky.php?barcode=%1$s&locale=CZ&send.x=52&send.y=8&go=ok',
		'DHL.cz'
			=> 'http://www.dhl.cz/content/cz/cs/express/sledovani_zasilek.shtml?brand=DHL&AWB=%1$s',
		'DPD.cz'
			=> 'https://tracking.dpd.de/cgi-bin/delistrack?pknr=%1$s&typ=32&lang=cz',
	),
	'Finland' => array(
		'Itella'
			=> 'http://www.posti.fi/itemtracking/posti/search_by_shipment_id?lang=en&ShipmentId=%1$s',
	),
	'France' => array(
		'Colissimo'
			=> 'http://www.colissimo.fr/portail_colissimo/suivre.do?language=fr_FR&colispart=%1$s',
	),
	'Germany' => array(
		'Hermes'
			=> 'https://tracking.hermesworld.com/?TrackID=%1$s',
		'Deutsche Post DHL'
			=> 'http://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&idc=%1$s',
		'UPS Germany'
			=> 'http://wwwapps.ups.com/WebTracking/processInputRequest?sort_by=status&tracknums_displayed=1&TypeOfInquiryNumber=T&loc=de_DE&InquiryNumber1=%1$s',
	),
	'India' => array(
		'DTDC'
			=> 'http://www.dtdc.in/dtdcTrack/Tracking/consignInfo.asp?strCnno=%1$s',
	),
	'Netherlands' => array(
		'PostNL'
			=> 'https://mijnpakket.postnl.nl/Claim?Barcode=%1$s&Postalcode=%2$s&Foreign=False&ShowAnonymousLayover=False&CustomerServiceClaim=False',
		'DPD.NL'
			=> 'http://track.dpdnl.nl/?parcelnumber=%1$s',
	),
	'South African' => array(
		'SAPO'
			=> 'http://sms.postoffice.co.za/TrackingParcels/Parcel.aspx?id=%1$s',
	),
	'Sweden' => array(
		'Posten AB'
			=> 'http://server.logistik.posten.se/servlet/PacTrack?xslURL=/xsl/pactrack/standard.xsl&/css/kolli.css&lang2=SE&kolliid=%1$s',
	),
	'United Kingdom' => array(
		'City Link'
			=> 'http://www.city-link.co.uk/dynamic/track.php?parcel_ref_num=%1$s',
		'DHL'
			=> 'http://www.dhl.com/content/g0/en/express/tracking.shtml?brand=DHL&AWB=%1$s',
		'DPD'
			=> 'http://www.dpd.co.uk/tracking/trackingSearch.do?search.searchType=0&search.parcelNumber=%1$s',
		'ParcelForce'
			=> 'http://www.parcelforce.com/portal/pw/track?trackNumber=%1$s',
		'Royal Mail'
			=> 'http://www.royalmail.com/track-trace?trackNumber=%1$s',
		'TNT Express (consignment)'
			=> 'http://www.tnt.com/webtracker/tracking.do?requestType=GEN&searchType=CON&respLang=en&
respCountry=GENERIC&sourceID=1&sourceCountry=ww&cons=%1$s&navigation=1&g
enericSiteIdent=',
		'TNT Express (reference)'
			=> 'http://www.tnt.com/webtracker/tracking.do?requestType=GEN&searchType=REF&respLang=en&r
espCountry=GENERIC&sourceID=1&sourceCountry=ww&cons=%1$s&navigation=1&gen
ericSiteIdent=',
		'UK Mail'
			=> 'https://old.ukmail.com/ConsignmentStatus/ConsignmentSearchResults.aspx?SearchType=Reference&SearchString=%1$s',
	),
	'United States' => array(
		'Fedex'
			=> 'http://www.fedex.com/Tracking?action=track&tracknumbers=%1$s',
		'OnTrac'
			=> 'http://www.ontrac.com/trackingdetail.asp?tracking=%1$s',
		'UPS'
			=> 'http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=%1$s',
		'USPS'
			=> 'https://tools.usps.com/go/TrackConfirmAction_input?qtc_tLabels1=%1$s',
	),
) );

add_action( 'woocommerce_order_details_after_order_table', 'ign_tracking_number_box', 1, 1 );
function ign_tracking_number_box( $order ) {
	global $woocommerce, $ign_tracking_providers, $wpdb, $user_ID;

	if ( !function_exists('') )
		require_once( $woocommerce->plugin_path() . '/includes/admin/wc-meta-box-functions.php' );
		
	$order_id = $order->id;
	
	$vendor_data = null; 
	
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
		
		if ( empty( $vendor->ID ) || $user_ID != $post_author ) 
			continue;
			
		// this is the id to the store itself, which contains the vendor user ID if needed
		$vendor_data = $vendor;
		
		break;
		
	}

	if ( empty( $vendor_data ) )
		return;
		
	$selected_provider = get_post_meta( $order_id, '_ign_vendor_tracking_info', true );

	if ( !empty( $selected_provider ) )
	foreach( $selected_provider as $k => $v ) {
	
		if ( $k == $vendor_data->ID ) {
			$selected_provider = $v;
			break;
		}
	
	}

	echo '<h2>' . __( 'Tracking Number & Status', 'ignitewoo_vendor_stores' ) . '</h2>';
	
	if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ) { 
	
		$st = wc_get_order_statuses();
	
		$statuses[] = '';
		
		foreach( $st as $k => $v ) { 
			$statuses[ ucwords( $v ) ] = ucwords( $v );
		}
		
	} else { 
		
		$st = get_terms( 'shop_order_status', array( 'hide_empty' => false ) );

		$statuses[] = '';
		
		foreach( $st as $s ) { 
			$statuses[ ucwords( $s->name ) ] = ucwords( $s->name );
		}
		
	}
	
	$status = ( !empty( $selected_provider['status'] ) ) ? $selected_provider['status'] : '';
	
	?>
	<div id="status_updated"></div>
	<?php
	
	woocommerce_wp_select( array(
		'id' 			=> 'custom_status',
		'label' 		=> __('Status:', 'ignitewoo_vendor_stores') . '<br/>',
		'placeholder' 	=> '',
		'description' 	=> '',
		'options' 	=> $statuses,
		'value'		=> $status,
		'class'		=> 'order_status_select'
	) );
	


	if ( ! $selected_provider )
		$selected_provider = sanitize_title( apply_filters( 'woocommerce_shipment_tracking_default_provider', '' ) );
	
	echo '<p class="form-field tracking_provider_field"><label for="tracking_provider">' . __('Provider:', 'ignitewoo_vendor_stores') . '</label><br/><select id="tracking_provider" name="tracking_provider" class="chosen_select" style="width:250px" >';

	echo '<option value="">' . __('Custom Provider', 'ignitewoo_vendor_stores') . '</option>';

	foreach ( $ign_tracking_providers as $provider_group => $providers ) {

		echo '<optgroup label="' . $provider_group . '">';

		foreach ( $providers as $provider => $url ) {

			echo '<option value="' . sanitize_title( $provider ) . '" ' . selected( sanitize_title( $provider ), $selected_provider['provider'], true ) . '>' . $provider . '</option>';

		}

		echo '</optgroup>';

	}

	echo '</select> ';

	
	woocommerce_wp_text_input( array(
		'id' 			=> 'custom_tracking_provider',
		'label' 		=> __('Provider Name:', 'ignitewoo_vendor_stores') . '<br/>',
		'placeholder' 	=> '',
		'description' 	=> '',
		'value'			=> ( !empty( $selected_provider['provider'] ) ? $selected_provider['provider'] : '' )
	) );

	woocommerce_wp_text_input( array(
		'id' 			=> 'tracking_number',
		'label' 		=> __('Tracking number:', 'ignitewoo_vendor_stores') . '<br/>',
		'placeholder' 	=> '',
		'description' 	=> '',
		'value'			=> ( !empty( $selected_provider['number'] ) ? $selected_provider['number'] : '' )
	) );

	woocommerce_wp_text_input( array(
		'id' 			=> 'custom_tracking_link',
		'label' 		=> __('Tracking link:', 'ignitewoo_vendor_stores') . '<br/>',
		'placeholder' 	=> 'http://',
		'description' 	=> '',
		'value'			=> ( !empty( $selected_provider['link'] ) ? $selected_provider['link'] : '' )
	) );

	woocommerce_wp_text_input( array(
		'id' 			=> 'date_shipped',
		'label' 		=> __('Date shipped:', 'ignitewoo_vendor_stores')  . '<br/>',
		'placeholder' 	=> 'YYYY-MM-DD',
		'description' 	=> '',
		'class'			=> 'date-picker-field',
		'value'			=> ( !empty( $selected_provider['date'] ) ) ? date( 'Y-m-d', $selected_provider['date'] ) : ''
	) );

	echo '<p class="preview_tracking_link"><a href="" target="_blank">' . __('Click here to track your shipment', 'ignitewoo_vendor_stores') . '</a></p>';

	echo '
		<div id="tracking_updated"></div>
		<p class="tracking_button_wrap">
			<input type="button" class="button add_tracking_number" value="' . __('Update Tracking Number', 'ignitewoo_vendor_stores') . '"> 
			<input id="send_tracking_to_customer" type="checkbox" name="send_tracking_to_customer" value="yes"> 
			<span>' . __('Send tracking number to customer', 'ignitewoo_vendor_stores')  . '</span>
		</p>';
	
	$provider_array = array();

	foreach ( $ign_tracking_providers as $providers ) {
		foreach ( $providers as $provider => $format ) {
			$provider_array[sanitize_title( $provider )] = urlencode( $format );
		}
	}
	
	$ajax_url = admin_url( 'admin-ajax.php' );

	$nonce = wp_create_nonce( 'ign_update_tracking' );
	
	if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) )
		$ajax_loader = str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/images/ajax-loader@2x.gif';
	else
		$ajax_loader = str_replace( array( 'http:', 'https:' ), '', $woocommerce->plugin_url() ) . '/assets/images/ajax-loader@2x.gif';
	
	$js = "
	
		jQuery( '.date-picker-field' ).datepicker( {dateFormat: 'yy-mm-dd' } );
	
		jQuery( '#custom_status' ).change( function() { 
		
			selectbox = jQuery( this );
			
			selectbox.parent().block({ message: null, overlayCSS: { background: '#fff url(\'" . $ajax_loader . "\') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6 } });
			
			var status = jQuery( this ).val();
			
			data = { _wpnonce: '" . $nonce . "', oid: " . $order_id . ", status: status, update_status_only: 1 }
			
			jQuery.post( '" . $ajax_url . "', { action:'ign_update_tracking', data: data }, function( data ) {
				selectbox.parent().unblock();
				if ( 'done' == data ) { 
					jQuery( '#status_updated' ).html( '" .  __( 'Status updated', 'ignitewoo_vendor_stores ' ) . "' ).show().fadeOut( 2000 );
				} else { 
					jQuery( '#status_updated' ).html( '" .  __( 'ERROR UPDATING STATUS', 'ignitewoo_vendor_stores ' ) . "' ).show().fadeOut( 2000 );
				}
			})
		
		});
		
		jQuery( '.add_tracking_number' ).click( function( e ) { 
	
			e.preventDefault();
			
			var link = jQuery( '#custom_tracking_link' ).val();
			var provider = jQuery( '#tracking_provider' ).val();
			
			if ( jQuery( '#send_tracking_to_customer' ).is( ':checked' ) )
				send_it = 'yes';
			else
				send_it = '';
			
			if ( '' == provider ) {
				var provider = jQuery( '#custom_tracking_provider' ).val();
			}

			var tracking_number = jQuery( '#tracking_number' ).val();			
			var date_shipped = jQuery( '#date_shipped' ).val();	
			
			if ( '' == provider && '' == link ) {
				alert( '" . __( 'Enter all tracking information first', 'ignitewoo_vendor_stores ' ). "' )
				return;
			} else if ( 
				( 'undefined' == provider || '' == provider ) ||
				( 'undefined' == tracking_number || '' == tracking_number ) ||
				( 'undefined' == date_shipped || '' == date_shipped )
				
			) {
			
				alert( '" . __( 'Enter all tracking information first', 'ignitewoo_vendor_stores ' ). "' )
				return;
			}

			data = { provider: provider, tracking_number: tracking_number, link: link, date_shipped: date_shipped, _wpnonce: '" . $nonce . "', oid: " . $order_id . ", send_it: send_it }
			
			jQuery.post( '" . $ajax_url . "', { action:'ign_update_tracking', data: data }, function( data ) { 
			
				if ( 'done' == data ) { 
					jQuery( '#tracking_updated' ).html( '" .  __( 'Tracking information updated', 'ignitewoo_vendor_stores ' ) . "' ).show().fadeOut( 2000 );
				} else { 
					jQuery( '#tracking_updated' ).html( '" .  __( 'ERROR UPDATING TRACKING INFO', 'ignitewoo_vendor_stores ' ) . "' ).show().fadeOut( 2000 );
				}
			})
		
		})
		
		jQuery('p.custom_tracking_link_field, p.custom_tracking_provider_field').hide();

		jQuery('input#custom_tracking_link, input#tracking_number, #tracking_provider').change(function(){

			var tracking = jQuery('input#tracking_number').val();
			var provider = jQuery('#tracking_provider').val();
			var providers = jQuery.parseJSON( '" . json_encode( $provider_array ) . "' );

			var postcode = jQuery('#_shipping_postcode').val();

			if ( ! postcode )
				postcode = jQuery('#_billing_postcode').val();

			postcode = encodeURIComponent( postcode );

			var link = '';

			if ( providers[ provider ] ) {
				link = providers[provider];
				link = link.replace( '%251%24s', tracking );
				link = link.replace( '%252%24s', postcode );
				link = decodeURIComponent( link );

				jQuery('p.custom_tracking_link_field, p.custom_tracking_provider_field').hide();
			} else {
				jQuery('p.custom_tracking_link_field, p.custom_tracking_provider_field').show();

				link = jQuery('input#custom_tracking_link').val();
			}

			if ( link ) {
				jQuery('p.preview_tracking_link a').attr('href', link);
				jQuery('p.preview_tracking_link').show();
			} else {
				jQuery('p.preview_tracking_link').hide();
			}

		}).change();
	";

	if ( function_exists( 'wc_enqueue_js' ) ) {
		wc_enqueue_js( $js );
	} else {
		$woocommerce->add_inline_js( $js );
	}
}

add_action( 'wp_ajax_ign_update_tracking', 'ign_update_tracking' );
add_action( 'wp_ajax_nopriv_ign_update_tracking', 'ign_update_tracking' );

function ign_update_tracking() { 
	global $user_ID, $wpdb;

	if ( empty( $_POST['data']['_wpnonce'] ) )
		die;
		
	if ( !wp_verify_nonce( $_POST['data']['_wpnonce'], 'ign_update_tracking' ) )
		die;

	if ( empty( $_POST['data']['oid'] ) )
		die;
	
	$order = new WC_Order( absint( $_POST['data']['oid'] ) );
	
	if ( empty( $order ) )
		die;
	
	$ok_to_set_tracking = false; 
	
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
		
		if ( empty( $vendor->ID ) || $user_ID != $post_author ) 
			continue;
	
		$ok_to_set_tracking = true;
		
		// this is the id to the store itself, which contains the vendor user ID if needed
		$vendor_id = $vendor->ID;
		
	}

	if ( !$ok_to_set_tracking )
		die;
		
	//$_POST = $wpdb->_escape( $_POST );

	if ( !empty( $_POST['data']['custom_provider'] ) )
		$provider = !empty( $_POST['custom_provider'] ) ? $_POST['custom_provider'] : ''; 
	else 
		$provider = !empty( $_POST['data']['provider'] ) ? $_POST['data']['provider'] : '';
		
	$number = !empty( $_POST['data']['tracking_number'] ) ? $_POST['data']['tracking_number'] : '';
	
	$date = !empty( $_POST['data']['date_shipped'] ) ? $_POST['data']['date_shipped'] : '';
	
	$url = !empty( $_POST['data']['link'] ) ? $_POST['data']['link'] : '';
	
	$status = !empty( $_POST['data']['status'] ) ? $_POST['data']['status'] : '';
	
	if ( !empty( $_POST['data']['send_it'] ) && 'yes' == $_POST['data']['send_it'] )
		$send_it = true;
	else 
		$send_it = false;
	
	if ( !empty( $_POST['custom_provider'] ) )
		$note = sprintf( __( 'A tracking number has been added to your order. Shipper: %s, Tracking number: %s, Date: %s, URL: %s' ), $provider, $number, $date, $url );
	else 
		$note = sprintf( __( 'A tracking number has been added to your order. Shipper: %s, Tracking number: %s, Date: %s' ), $provider, $number, $date );

	// Add a customer order note
	$order->add_order_note( $note, $send_it );
	
	$data = array(
		'provider' => $provider,
		'number' => $number,
		'date' => ( !empty( $date ) ) ? strtotime( $date ) : '',
		'link' => $url,
		'status' => $status
	);
	
	$tracking_data = get_post_meta( $order->id, '_ign_vendor_tracking_info', true );
	
	$tracking_data[ $vendor_id ] = $data;
	
	// Add to post meta for the order - put all tracking in one collective option as an array with vendor_id => array( data )
	update_post_meta( $order->id, '_ign_vendor_tracking_info', $tracking_data );
		
	// Update order item meta
	foreach( $order->get_items() as $item_id => $item ) { 
	
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
		
		if ( empty( $vendor->ID ) || $user_ID != $post_author ) 
			continue;

			
		// If we're only updating the status then only write status info
		if ( empty( $_POST['update_status_only'] ) ) {
			wc_update_order_item_meta( $item_id, __( 'Vendor', 'woocommerce' ), $vendor->title );
			wc_update_order_item_meta( $item_id, __( 'Shipper', 'woocommerce' ), strtoupper( str_replace( '-', ' ', $provider ) ) );
			wc_update_order_item_meta( $item_id, __( 'Shipper Tracking', 'woocommerce' ), $number );
			wc_update_order_item_meta( $item_id, __( 'Ship Date', 'woocommerce' ), date_i18n( __( 'l, jS F Y', 'ign_vendors_shipment_tracking' ), strtotime( $date ) ) );
		}

		wc_update_order_item_meta( $item_id, __( 'Item Processing Status', 'woocommerce' ), $status );
		
	}
	
	die( 'done' );
	
	
}
			
/**
Returns amount due to vendor based on order item line total - no consideration for qty
*/
function ign_calculate_product_commission( $line_total, $product_id, $variation_id = null, $vendor_id = null, $order_id = null, $item = null ) { 
	global $user_ID, $ignitewoo_vendors;

	if ( empty( $vendor_id ) ) { 
	
		$vendors = ign_get_product_vendors( $product_id );

		if ( !empty( $vendors[0] ) )
			$vendor_id = $vendors[0]->ID;
		
	}
	
	if ( !empty( $variation_id ) ) 
		$commission = ign_get_commission_percent( $variation_id, $vendor_id );
	else 
		$commission = ign_get_commission_percent( $product_id, $vendor_id );
	
	// No commission? Store gets it all so return zero for vendor's commission
	if ( empty( $commission ) || false == $commission )
		return 0; //$line_total;
		
	$for = $commission['for']; 
	
	$commission = $commission['com']; 
	
	$percent = strpos( $commission, '%' );

	$discount = 0;
	
	if ( !empty( $order_id ) )
		$discount = ign_calculate_coupon_discounts( $order_id, $product_id, $item, $line_total );
	
	if ( !empty( $discount ) )
		$line_total = $line_total - $discount;

//var_dump( $product_id, 'line total: ' . $line_total, 'discount: ' . $discount );
//echo '<p>';
	// if commission is percentage then calculate against line total
	// otherwise commission is a fixed amount
	if ( false !== $percent ) {

		$commission = str_replace( '%', '', $commission );
		
		$commission = ( $commission / 100 );
		
		$commission = ( $commission * $line_total );
		
		if ( 'vendor' == $for ) {
		
			return $commission;
			
		} else if ( 'store' == $for ) { // store gets the defined commission, so subtract that from line total and the remainder is for the vendor
			
			return $line_total - $commission;
	
		}
		
		return $commission;
	}
	
	if ( 'vendor' == $for ) 
		return $commission;
	else if ( 'store' == $for )
		return $line_total - $commission;
	
}

// calculate coupon discounts
function ign_calculate_coupon_discounts( $order_id, $product_id, $item, $line_total ) { 
	global $ignitewoo_vendors;

	if ( empty( $order_id ) ) 
		return 0;
		
	$order = new WC_Order( $order_id );
	
	if ( empty( $order ) )
		return 0;
	
	$coupons = $order->get_used_coupons();
	
	if ( sizeof( $coupons ) <= 0 ) 
		return 0;

	//$p = get_product( $product_id );

	//if ( empty( $p ) )
	//	return 0;
		
	$discount = 0;
	
	foreach( $coupons as $coupon ) { 

		$c = new WC_Coupon( $coupon );

		if ( empty( $c ) )
			continue;
		
		// Is the coupon a product coupon and valid for the product? 
		if ( $c->type == 'fixed_product' || $c->type == 'percent_product' ) {
			
			$is_valid_for_product = true; // $ignitewoo_vendors->coupon_is_valid_for_product( false, $p, $c );
			
			if ( $c->type == 'fixed_product' && $is_valid_for_product ) {

				$discount = $line_total < $c->amount ? $line_total : $c->amount;

				if ( $discount > 0 ) 
					$discount = $discount * $item['qty'];

			} elseif ( $c->type == 'percent_product' && $is_valid_for_product ) {

				$discount = round( ( ( $line_total / 100 ) * $c->amount ) , 2 );

			}
			
		// Is the coupon a cart discount? If so the admin created it so it applies to all items 
		// if WC says it is valid - couldn't get applied if it weren't
		} else if ( $c->type == 'fixed_cart' || $c->type == 'percent' ) {
 
			// Subtotal before shipping - as if WC 2.1 discounts don't apply to shipping charges
			$subtotal = ign_get_order_subtotal( $order );

			if ( 'fixed_cart' == $c->type && $subtotal > 0 ) { 
			// calculate what money amount of the cart total the product represents
			
				$percentage = round( $item['line_total'] / $subtotal, 2 );
			
				$discount = round( ( ( $subtotal * $percentage ) / 100 ) * $c->amount, 2 );
			
			} else if ( 'percent' == $c->type && $subtotal > 0 ) { 
			// calculate what money percentage of the cart total the product represents
				
				$discount = round( ( $item['line_total'] / 100 ) * $c->amount, 2 );
							
			}
		

		}
		
	}
	
	return $discount;

}


function ign_get_order_subtotal( $order, $compound = false, $tax_display = '' ) { 

	$subtotal = 0;

	if ( ! $compound ) {
		foreach ( $order->get_items() as $item ) {

			if ( ! isset( $item['line_subtotal'] ) || ! isset( $item['line_subtotal_tax'] ) ) {
				return '';
			}

			$subtotal += $item['line_subtotal'];

			if ( 'incl' == $tax_display ) {
				$subtotal += $item['line_subtotal_tax'];
			}
		}

		//$subtotal = wc_price( $subtotal, array('currency' => $order->get_order_currency()) );

		if ( $tax_display == 'excl' && $order->prices_include_tax ) {
			//$subtotal .= ' <small>' . WC()->countries->ex_tax_or_vat() . '</small>';
		}

	} else {

		if ( 'incl' == $tax_display ) {
			return '';
		}

		foreach ( $order->get_items() as $item ) {

			$subtotal += $item['line_subtotal'];

		}

		// Add Shipping Costs
		$subtotal += $order->get_total_shipping();

		// Remove non-compound taxes
		foreach ( $order->get_taxes() as $tax ) {

			if ( ! empty( $tax['compound'] ) ) {
				continue;
			}

			$subtotal = $subtotal + $tax['tax_amount'] + $tax['shipping_tax_amount'];

		}

		// Remove discounts
		$subtotal = $subtotal - $order->get_cart_discount();

	}
	
	return $subtotal;

}

// Calculate shipping amount - used when Include Shipping setting is turned on for commission calculations
// This is also called by the shipping class to get a percentage of tax due
function ign_calculate_shipping_commission( $product_id, $variation_id = null, $vendor_id = null, $shipping ) { 
	global $user_ID, $ignitewoo_vendors;

	if ( empty( $shipping ) )
		return 0;
		
	if ( empty( $vendor_id ) ) { 
	
		$vendors = ign_get_product_vendors( $product_id );

		if ( !empty( $vendors[0] ) )
			$vendor_id = $vendors[0]->ID;
		
	}
	
	if ( !empty( $variation_id ) ) 
		$commission = ign_get_commission_percent( $variation_id, $vendor_id );
	else 
		$commission = ign_get_commission_percent( $product_id, $vendor_id );
	
	// No commission? Store gets it all so return zero for vendor's commission
	if ( empty( $commission ) || false == $commission )
		return 0; 
		
	$for = $commission['for']; 
	
	$commission = $commission['com']; 
	
	$percent = strpos( $commission, '%' );

	// if commission is percentage then calculate
	// otherwise do nothing
	if ( false !== $percent ) {

		$commission = str_replace( '%', '', $commission );
		
		$commission = ( $commission / 100 );

		$commission = ( $commission * floatval( $shipping ) );
	
		if ( 'vendor' == $for ) {

			return $commission;
			
		} else if ( 'store' == $for ) { // store gets the defined commission, so subtract that from line total and the remainder is for the vendor
		
			return floatval( $shipping ) - $commission;
	
		}
		
		return $commission;
	}

	return $shipping;
	
	/*
	if ( 'vendor' == $for ) 
		return $commission;
	else if ( 'store' == $for )
		return $shipping - $commission;
	*/
}



/**
 * Get the commission assigned to the product or vendor - product takes precedence. 
 * When there's no commission settings store gets entire amount - e.g. returns empty array of commission and who it's for
 */
function ign_get_commission_percent( $product_id = 0, $vendor_id = 0 ) {
	global $ignitewoo_vendors;

	if ( $product_id > 0 ) { 
	
		$com = get_post_meta( $product_id, '_product_vendors_commission', true );
		
		$for = get_post_meta( $product_id, '_product_vendors_commission_for', true );

		if ( empty( $for ) )
			$for = 'vendor';
	}
	
	if ( empty( $com ) && $vendor_id > 0 ) {
	
		$vendor_data = get_option( $ignitewoo_vendors->token . '_' . $vendor_id );
		
		if ( empty( $vendor_data['commission'] ) ) 
			return false;

		$com = $vendor_data['commission'];
		
		$for = $vendor_data['commission_for'];
		
	}

	if ( empty( $com ) ) 
		$com = $ignitewoo_vendors->settings['default_commission']; 
		
	if ( empty( $com ) )
		return array();

	if ( empty( $for ) )
		$for = 'vendor';
		
	return array( 'com' => $com, 'for' => $for );
}


/**
Display the amount the vendor store earned so far this month 
*/
function vendor_store_display_month_earnings() { 
	global $ignitewoo_vendors;
	
	echo $ignitewoo_vendors->vendor_month_earnings();
		
}


/**
Retrieve vendor store earnings data
*/
function vendor_store_earnings_data() { 
	global $ignitewoo_vendors;
	
	return $ignitewoo_vendors->vendor_totals_data();
}



/**
 * For searching vendors while editing a store in the admin area 
 */
add_action('wp_ajax_ignitewoo_json_search_vendors', 'ignitewoo_json_search_vendors');

function ignitewoo_json_search_vendors() {
	global $ignitewoo_vendors;

	check_ajax_referer( 'search-vendors', 'security' );

	header( 'Content-Type: application/json; charset=utf-8' );

	$term = urldecode( stripslashes( strip_tags( $_GET['term'] ) ) );

	if ( empty( $term ) )
		die();

	$found_vendors = array();

	$args = array(
		'hide_empty' => false,
		'search' => $term
	);
	
	$vendors = get_terms( $ignitewoo_vendors->token, $args );

	if ( $vendors )
		foreach ( $vendors as $vendor )
			$found_vendors[ $vendor->term_id ] = $vendor->name;


	echo json_encode( $found_vendors );
	
	die();
}


/**
 * Get an array of all vendors
 */
function ign_get_vendors() {
	global $ignitewoo_vendors;

	$vendors_array = false;

	$args = array( 'hide_empty' => false );
	
	$vendors = get_terms( $ignitewoo_vendors->token, $args );

	if ( !is_array( $vendors ) || count( $vendors ) <= 0 )
		return $vendors_array;
		
	foreach ( $vendors as $vendor ) {
	
		if ( !isset( $vendor->term_id ) )
			continue;
			
		$vendor_data = ign_get_vendor( $vendor->term_id );
		
		if ( $vendor_data )
			$vendors_array[] = $vendor_data;
		
	}

	return $vendors_array;
}


/**
 * Get a specific vendor's info by vendor id 
*/
function ign_get_vendor( $vendor_id = 0 ) {
	global $ignitewoo_vendors;

	$vendor = false;

	if ( empty( $vendor_id ) ) 
		return $vendor_id;
		
	$vendor_data = get_term( $vendor_id, $ignitewoo_vendors->token );

	$vendor_info = get_option( $ignitewoo_vendors->token . '_' . $vendor_id );

	$vendor = new stdClass();

	if ( !is_object( $vendor_data ) || count( $vendor_data ) <= 0 || !isset( $vendor_data->term_id ) )
		return $vendor; 
	
	$vendor->ID = $vendor_data->term_id;
	
	$vendor->title = $vendor_data->name;
	
	$vendor->slug = $vendor_data->slug;
	
	$vendor->description = $vendor_data->description;

	$vendor->url = get_term_link( $vendor_data, $ignitewoo_vendors->token );
	
	$vendor->admins = ign_get_vendor_admins( $vendor_id );
	
	if ( !is_array( $vendor_info ) || count( $vendor_info ) <= 0 )
		return $vendor;
		
	$vendor->commission = isset( $vendor_info['commission'] ) ? $vendor_info['commission'] : '';
	
	$vendor->commission_for = isset( $vendor_info['commission_for'] ) ? $vendor_info['commission_for'] : 'vendor';
	
	$vendor->paypal_email = isset( $vendor_info['paypal_email'] ) ? $vendor_info['paypal_email'] : '';

	return $vendor;
}


/**
 * Get the vendor assigned to a specific product
 */
function ign_get_product_vendors( $product_id = 0 ) {
	global $ignitewoo_vendors;
	
	if ( empty( $product_id ) )
		return false;
		
	$vendors = false;

	$vendors_data = wp_get_post_terms( $product_id, $ignitewoo_vendors->token );

	if ( empty( $vendors_data ) || is_wp_error( $vendors_data ) )
		return false;
		
	foreach( $vendors_data as $vendor_data ) {
		
		$vendor = ign_get_vendor( $vendor_data->term_id );
		
		if ( $vendor )
			$vendors[] = $vendor;
		
	}

	return $vendors;
}


/**
* Get vendor assigned to coupon
*/
function ign_get_coupon_vendor( $coupon_id = 0 ) {
	global $user_ID;
	
	if ( empty( $coupon_id ) )
		return false;
		
	$post_data = get_post( $coupon_id );

	if ( empty( $post_data ) )
		return false;
		
	if ( $post_data->post_author == $user_ID )
		return $post_data->post_author;
	else
		return false;
}

/**
 * Get all commissions for a specific vendor
 */
function ign_get_vendor_commissions( $vendor_id = 0, $year = false, $month = false, $day = false ) {

	$commissions = false;

	if ( empty( $vendor_id ) ) 
		return false; 

	$args = array(
		'post_type' => 'vendor_commission',
		'post_status' => array( 'publish', 'private' ),
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key' => '_commission_vendor',
				'value' => $vendor_id,
				'compare' => '='
			)
		)
	);

	if ( $year ) 
		$args['year'] = $year;
	
	if ( $month ) 
		$args['monthnum'] = $month;
	
	if ( $day ) 
		$args['day'] = $day;

	$commissions = get_posts( $args );

	return $commissions;
}


/**
 * Get all products assigned to or published by a vendor
 */
function ign_get_vendor_products( $vendor_id = 0 ) {
	global $ignitewoo_vendors;

	if ( empty( $vendor_id ) ) 
		return false;
		
	$products = false;

	$args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'tax_query' => array(
			array(
				'taxonomy' => $ignitewoo_vendors->token,
				'field' => 'id',
				'terms' => $vendor_id,
			)
		)
	);

	$products = get_posts( $args );

	return $products;
}


/**
 * Get the vendor store data for which user is an admin
 */
function ign_get_user_vendor( $user_id = 0 ) {
	global $current_user;

	if ( $user_id == 0 ) {

		wp_get_current_user();
		
		$user_id = $current_user->ID;
		
	}

	$vendor = false;

	if ( $user_id > 0 ) {
	
		$vendor_id = get_user_meta( $user_id, 'product_vendor', true );

		if ( !empty( $vendor_id ) )
			$vendor = ign_get_vendor( $vendor_id );
	}

	return $vendor;
}


/**
 * Get the user(s) assign to a vendor store
 */
function ign_get_vendor_admins( $vendor_id = 0 ) {
	$admins = false;

	if ( empty( $vendor_id ) )
		return false;
		
	$args = array(
		'meta_key' => 'product_vendor',
		'meta_value' => $vendor_id,
		'meta_compare' => '='
	);

	return get_users( $args );

}

/**
 * Get details for a specific commission record by ID 
 */
function ign_get_commission( $commission_id = 0 ) {

	if ( empty( $commission_id ) )
		return false;
		
	$commission = false;

	$commission = get_post( $commission_id );

	$commission->product = get_post_meta( $commission_id, '_commission_product', true );
	
	$commission->vendor = ign_get_vendor( get_post_meta( $commission_id, '_commission_vendor', true ) );
	
	$commission->base_amount = get_post_meta( $commission_id, '_amount', true ); // before shipping and tax
		
	$commission->amount = get_post_meta( $commission_id, '_commission_amount', true ); // including shipping and tax
	
	$commission->paid_status = get_post_meta( $commission_id, '_paid_status', true );

	$commission->shipping = get_post_meta( $commission_id, '_shipping', true );
	
	$commission->tax = get_post_meta( $commission_id, '_tax', true );
	
	$commission->item_id = get_post_meta( $commission_id, '_item_id', true );
	
	$commission->item_meta = get_post_meta( $commission_id, '_item_meta', true );
	
	$commission->order_id = get_post_meta( $commission_id, '_order_id', true );
	
	return $commission;
}

/**
 * Check if the specified user is the "admin' of the specified vendor store
 */
function ign_is_vendor_admin( $vendor_id = 0, $user_id = 0 ) {
	global $current_user;
	
	if ( $user_id == 0 ) {
	
		wp_get_current_user();
		
		$user_id = $current_user->ID;
	}

	if ( empty( $vendor_id ) || empty( $user_id ) )
		return false;
		

	$vendor = ign_get_vendor( $vendor_id );
	
	if ( empty( $vendor ) || empty( $vendor->admins ) )
		return false;
	
	foreach( $vendor->admins as $admin ) {
	
		if ( $admin->ID == $user_id )
			return true;
	
	}

	return false;
}

/**
 * Check if user has the "Vendor" role 
 */
function ign_is_vendor( $user_id = 0 ) {

	if ( current_user_can( 'vendor' ) )
		return true;
		
	return false;

}


if ( ! function_exists( 'ign_is_vendor_store' ) ) {

	/**
	 * ign_is_vendor_store - Returns true when viewing a vendor store category.
	 */
	function ign_is_vendor_store( $term = '' ) {
		global $ignitewoo_vendors;

		return is_tax( $ignitewoo_vendors->token, $term );
		
	}
}


/** 
Load a template file, either from the theme directory or plugin directory
*/
function store_vendors_get_template( $template = '', $load = true ) { 
	global $ignitewoo_vendors;
	
	if ( empty( $template ) )
		return;
		
	if ( file_exists( get_stylesheet_directory() . '/store_vendors/' . $template ) )
		$file = get_stylesheet_directory() . '/store_vendors/' . $template;
	else 
		$file = $ignitewoo_vendors->plugin_dir . '/templates/' . $template;
	
	if ( $load )
		load_template( $file, false );
	else
		return $file;
		
}


/**
Get the vendor store logo if one was uploaded 
*/
if ( ! function_exists( 'vendor_store_get_logo' ) ) {

	function vendor_store_get_logo() { 

		if ( !ign_is_vendor_store() )
			return;
			
		global $wp_query, $ignitewoo_vendors;
		
		$term = $wp_query->query_vars[ $ignitewoo_vendors->token ];
		
		if ( empty( $term ) )
			return;
			
		$term = get_term_by( 'slug', $term, $ignitewoo_vendors->token );

		if ( empty( $term ) || empty( $term->term_id ) )
			return;

		$image_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true );

		if ( empty( $image_id ) )
			return;
			
		$store_image = null; 
		
		if ( $image_id ) {

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
				
			$store_image = wp_get_attachment_image_src( $image_id, $size ); 

			if ( !empty( $store_image ) && is_array( $store_image ) )
				$store_image = $store_image[0];

		}

		if ( empty( $store_image ) )
			return;
			
		return $store_image;
				
	}
}


/**
 * Check if current user has vendor access to the Vendor aspects of WP admin area
 */
function ign_vendor_access() {

	if ( ign_is_vendor() && !current_user_can( 'manage_woocommerce' ) )
		return true;
	
	return false;
	
}
