<?php
/**
 * Packing Slip - Used with IgniteWoo's PDF Invoices & Packing Slips plugin
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $ignitewoo_vendors, $user_ID, $note_add_error, $note_added;

$order_total = 0;

$vendor_data = ign_get_user_vendor( $user_ID );


if ( empty( $vendor_data ) ) { 

	_e( 'Error locating vendor record', 'ignitewoo_vendor_stores' );
	
	return;
	
}
	
$vendor_id = $vendor_data->ID;

$order_id = absint( $_GET['get_packing_slip'] ); 

if ( $order_id <= 0 ) { 

	_e( 'Unable to locate order', 'ignitewoo_vendor_stores' );
	
	return;

}

$order = $ignitewoo_vendors->get_order( $order_id );

if ( empty( $order ) ) { 

	_e( 'Unable to locate order', 'ignitewoo_vendor_stores' );
	
	return;

} 

	if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) )
		$ship_type = $order->get_shipping_method();
	else 
		$ship_type = $order->order_custom_fields['_shipping_method'][0];

	$simg = '<div class="ship_type">' . strtoupper( str_replace( '_', ' ', str_replace( 'UPS UPS', 'UPS', $ship_type ) ) )  . '</div>';


	/* -------- LOGO ------- */
	//$logo = vendor_store_get_logo();

	if ( !$logo || '' == $logo ) {

		$logo = '<div class="sitename">' . $vendor_data->title . '</div>';
		$logo .= '<div class="description">' . $vendor_data->url . '</div>';

	} else  {

		$logo = '<img class="packing_logo" src="'. $logo . '">';

	}


	/* -------- FOOTER ------- */
	$footer = $this->settings['packing_slip_footer_image'];

	if ( !$footer || '' == $footer ) 
		$footer = '<div class="packing_footer">' . __( 'Thanks for you business! &mdash; Visit us again at ', 'woocommerce' ) . get_bloginfo('url') . '</div>';
	else 
		$footer = '<img class="packing_footer_img" src="'. $footer . '">';

	$footer_txt = $this->settings['packing_slip_footer_txt'];

	if ( '' != trim( $footer_txt ) )
		$footer_txt = wpautop( stripslashes( $footer_txt ) );



	/* -------- PRINT CONTROL ------- */
	$print = $this->settings['packing_slip_print'];

	if ( 'auto' == $print ) 
		$maybe_auto_print = 'onload="window.print()"';
	else 
		$maybe_auto_print = '';


	if ( 'yes' == $this->settings['packing_slip_packed_by'] )
		$packed_by = true;
	else
		$packed_by = false;

	if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) )
		$pay_type = $order->payment_method;
	else 
		$pay_type = ucwords( str_replace( '_' , '' , $order->order_custom_fields['_payment_method'][0] ) );

	if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) ) { 
		$shipping_first_name = $order->__get( 'shipping_first_name' );
		$shipping_last_name = $order->__get( 'shipping_last_name' );
	} else { 
		$shipping_first_name = $order->order_custom_fields['_shipping_first_name'][0];
		$shipping_last_name = $order->order_custom_fields['_shipping_last_name'][0];
	}
	
	$order_meta = get_post_custom( $order->id, true );

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
	
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        
        <title><?php _e( 'Store Order Packing Slip', 'ignitewoo_vendor_stores' )?></title>

	<style type="text/css">
		body{
			width:100% !important; 
			-webkit-text-size-adjust:none;
			margin:0; 
			padding:0;
		}

		img{border:none; font-size:14px; font-weight:bold; height:auto; line-height:100%; outline:none; text-decoration:none; text-transform:capitalize;}
		#backgroundTable{height:100% !important; margin:0; padding:0; width:100% !important;}
		mark { background: transparent none; color: inherit; }


		/* Template Styles */
		body {
			background: #ffffff;
		}
		
		#templateContainer{
			border: 1px solid #bebebe;
			-webkit-box-shadow:0 0 0 3px rgba(0,0,0,0.1);
			-webkit-border-radius:6px;
		}

		h1, .h1,
		h2, .h2,
		h3, .h3,
		h4, .h4 {
			color:#282828;
			display:block;
			font-family:Arial;
			font-size:34px;
			font-weight:bold;
			line-height:150%;
			margin-top:0;
			margin-right:0;
			margin-bottom:10px;
			margin-left:0;
			text-align:left;
			line-height: 1.5;
		}

		h2, .h2{
			font-size:30px;
		}

		h3, .h3{
			font-size:26px;
		}

		h4, .h4{
			font-size:18px;
			margin-bottom: 0px;
			padding-bottom: 0px;
		}

		/* ------------------ STANDARD STYLING: HEADER --------------------- */

		#templateHeader{
			background-color: #557da1;
			background: -webkit-linear-gradient(#7797b4, #557da1);
			border-bottom:0;
			-webkit-border-top-left-radius:6px;
			-webkit-border-top-right-radius:6px;
		}

		.headerContent{
			padding:24px;
			vertical-align:middle;
		}

		.headerContent a:link, .headerContent a:visited{
			color:#ffffff;
			font-weight:normal;
			text-decoration:underline;
		}

		/* --------------------- STANDARD STYLING: MAIN BODY --------------------- */

		#templateContainer, .bodyContent{
			background-color:#fdfdfd;
			-webkit-border-radius:6px;
		}

		.bodyContent div{
			color: #737373;
			font-family:Arial;
			font-size:14px;
			line-height:150%;
			text-align:left;
		}

		.bodyContent div a:link, .bodyContent div a:visited{
			color: #505050;
			font-weight:normal;
			text-decoration:underline;
		}

		.bodyContent img{
			display:inline;
			height:auto;
		}

		.pdf_order_items_table tbody td { padding: 12px 0 }
		/* --------------------- STANDARD STYLING: FOOTER --------------------- */

		#templateFooter{
			border-top:0;
			-webkit-border-radius:6px;
		}

		.footerContent div{
			color:#969696;
			font-family:Arial;
			font-size:12px;
			line-height:125%;
			text-align:left;
		}

		.footerContent div a:link, .footerContent div a:visited{
			color:#969696;
			font-weight:normal;
			text-decoration:underline;
		}

		.footerContent img{
			display:inline;
		}

		#credit {
			border:0;
			color:#969696;
			font-family:Arial;
			font-size:12px;
			line-height:125%;
			text-align:center;
		}

		.packing_logo { 
			width: 350px;
		}

		.sitename { 
			color: #333333;
			font-size: 28px;
			font-weight:bold;
		}

		.description { 
			color: #333333;
			font-size: 18px;
		}

		.packing_footer { 
			width: 860px;
			font-size: 18px; 
			font-style: italic;
		}

		.packing_slip_label { 
			color: #ffffff;
			font-size: 24px;
			font-weight: bold;
		}
		.pheader { 
			background-color: #000000;
			padding: 0 10px;
		}

		.pheader h2 { 
			padding-top: 10px;
		}
		.packer { 
			border-radius: 7px;
			height: 40px;
			width: 200px;
			border: 1px solid #333;
			padding-left: 7px;
			padding-top: 2px;
			font-weight: bold;
			text-align:left;
			float:right;
		}
		.ship_to { 
			font-size: 14px;
			font-weight: bold;
		}

		.ship_type { 
			color: #ffffff;
			font-size: 18px;
			font-weight: bold;
		}

		#print_button_box { 
			background-color: #FFFBCC;
			border-color: #E6DB55;
			height: 30px;
			padding: 5px 20px;
			border: 1px dotted #333333;
			margin-bottom: 10px;
		}

		#print_button_box .lefty { 
			float: left;
			padding-top: 5px;
		}

		#print_button_box .righty { 
			float: right;
		}

		#print_button_box button { 
			border: 1px solid #777777;
			-moz-border-radius: 10px;
			-webkit-border-radius: 10px;
			-khtml-border-radius: 10px;
			border-radius: 10px;
			background-color: #efefef;
			

		}
		#contact { font-weight: bold; text-align: center; padding: 7px;}
		#footer_txt { 
			font-size: 14px;
			font-weight: bold;
			text-align:center;
		}
		#policies div { padding: 7px; }
		#policies, #contact{ background-color: #efefef; padding: 7px; }
	</style>
	
	</head>

	<body <?php echo $maybe_auto_print ?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="background: #ffffff;">

	<?php if ( 'html' == $this->settings['packing_slip_type'] && 'button' == $this->settings['packing_slip_print'] ) { ?>
	<div id="print_button_box">
		<div class="lefty"><?php _e( 'Review the packing slip below. Click the print button to print copies', 'woocommerce' ) ?></div>
		<div class="righty"><button onclick="window.print()" > <?php _e('Print Packing Slip', 'woocommerce') ?> </button></div>

	</div>
	<?php } ?>

    	<div style="padding: 10px 0 0 0;">

		<table cellspacing="0" cellpadding="0" style="width: 100%; border: none; margin-bottom: 10px;" border="0" bordercolor="#eee">
		    <tr>
			<td style="width:50%; vertical-align:top">
				<?php echo $logo; ?>
			</td>
			<td style="width:50%; text-align:right" align="right">
			
				<h3 style="text-align:right"><?php echo __('Order #:', 'woocommerce') . ' ' . str_replace( '#', '', $order->get_order_number( $order_id, $order ) ); ?></h3>
			
				<h3 style="text-align:right"><?php echo $shipping_first_name . ' ' . $shipping_last_name;  ?></h3>
						
				<?php if ( '1' == $packed_by ) { ?>
					<div class="packer">Packed By:</div>
				<?php } ?>
			</tr>
		    </tr>
		</table>


		<table class="pheader" cellspacing="0" cellpadding="0" style="width: 100%; border: none;" border="0" bordercolor="#eee">
		    <tr>
			<td style="width:30%">
			    <h2 class="packing_slip_label"><?php _e( 'PACKING SLIP', 'ignitewoo_vendor_stores' ) ?></h2>
			</td>

		    </tr>
		</table>


		<table cellspacing="0" cellpadding="6" style="width: 100%; border: none; border-collapse:collapse" border="0" bordercolor="#eee">

		    
		    <tr>
			<td style="width: 60%">
			    
				<table class="invoice_sub_table" width="100%">
				<tr>
				<?php if ( 'yes' == $ignitewoo_vendors->settings['show_order_shipping_address'] ) { ?>
					<td width="50%" valign="top" class="invoice billing_address">

						<h3><?php _e( 'Billing Address:', 'ignitewoo_vendor_stores' ) ?></h3>
						
						<?php 
						
						echo $order_meta['_billing_first_name'][0] . ' ' . $order_meta['_billing_last_name'][0] . '<br/>';
						
						if ( !empty( $order_meta['_billing_company'][0] ) )
							echo $order_meta['_billing_company'][0] . '<br/>';
							
						echo $order_meta['_billing_address_1'][0] . '<br />';
						
						if ( !empty( $order_meta['_billing_address_2'][0] ) )
							echo $order_meta['_billing_address_2'][0] . '<br/>';

						$locale = array(); 
						
						$locale[] = $order_meta['_billing_city'][0]; 
						
						if ( !empty( $order_meta['_billing_state'][0] ) )
							$locale[] = $order_meta['_billing_state'][0];
						
						if ( !empty( $order_meta['_billing_postcode'][0] ) ) 
							$locale[]= $order_meta['_billing_postcode'][0];
						
						echo implode( ',' , $locale ) . '<br/>';
						
						echo $order_meta['_billing_country'][0] .'<br/>';
						
						if ( $order->billing_phone ) {
							echo $order->billing_phone  . '<br/>';
						}


						
						// VAT or Tax ID - VAT has VAT ID and VAT Country, Tax ID only has no country
						if ( !empty( $customer_tax_id ) && !empty( $customer_tax_country ) ) {
						
							echo '<br/>' . __( 'VAT ID', 'ignitewoo_vendor_stores' ) . ': ' . $customer_tax_id . '</br>';
							
							echo '<br/>' . __( 'VAT Country', 'ignitewoo_vendor_stores' ) . ': ' . $customer_tax_country;
							
						} else if ( !empty( $customer_tax_id ) ) { 
						
							echo '<br/>' . __( 'Tax ID', 'ignitewoo_vendor_stores' ) . ': ' . $customer_tax_id;
							
						}
						
						?>

					</td>
				<?php } ?>
				<?php if ( 'yes' == $ignitewoo_vendors->settings['show_order_shipping_address'] ) { ?>
					<td width="50%" valign="top" class="invoice shipping_address">
						
						<h3><?php _e( 'Shipping Address:', 'ignitewoo_vendor_stores' ) ?></h3>
						
						<?php 
						
						echo $order_meta['_shipping_first_name'][0] . ' ' . $order_meta['_shipping_last_name'][0] . '<br/>';
						
						if ( !empty( $order_meta['_shipping_company'][0] ) )
							echo $order_meta['_shipping_company'][0] . '<br/>';
							
						echo $order_meta['_shipping_address_1'][0] . '<br />';
						
						if ( !empty( $order_meta['_shipping_address_2'][0] ) )
							echo $order_meta['_shipping_address_2'][0] . '<br/>';

						$locale = array(); 
						
						$locale[] = $order_meta['_shipping_city'][0]; 
						
						if ( !empty( $order_meta['_shipping_state'][0] ) )
							$locale[] = $order_meta['_shipping_state'][0];
							
						if ( !empty( $order_meta['_shipping_postcode'][0] ) ) 
							$locale[]= $order_meta['_shipping_postcode'][0];
						
						echo implode( ', ' , $locale ) . '<br/>'; 
						
						echo $order_meta['_shipping_country'][0];
						
						?>

					</td>
				<?php } ?>
				</tr>
				</table>
				
				<table class="pdf_order_items_table" style="width:100%; border: none; margin-top: 15px; border-collapse:collapse">
				<thead>
					<tr>
						<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e('Product', 'woocommerce'); ?></th>
						<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e('Quantity', 'woocommerce'); ?></th>
						<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e('Price', 'woocommerce'); ?></th>
					</tr>
				</thead>

				<tbody>
					<?php
					if ( sizeof( $order->order_items) > 0 ) {

						foreach( $order->order_items as $item ) {

							$_product = get_product( $item['variation_id'] ? $item['variation_id'] : $item['product_id'] );

							echo '
								<tr class = "' . esc_attr( apply_filters( 'woocommerce_order_table_item_class', 'order_table_item', $item, $order ) ) . '">
									<td class="product-name">' .
										apply_filters( 'woocommerce_order_table_product_title', '<a href="' . get_permalink( $item['product_id'] ) . '">' . $item['name'] . '</a>', $item );
										

							$item_meta = new WC_Order_Item_Meta( $item['item_meta'] );
							
							$item_meta->display();

							if ( $_product && $_product->exists() && $_product->is_downloadable() && $order->is_download_permitted() ) {

								if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) ) 
									$download_file_urls = $order->get_item_downloads( $item['product_id'], $item['variation_id'], $item );
								else 
									$download_file_urls = $order->get_downloadable_file_urls( $item['product_id'], $item['variation_id'], $item );

								$i     = 0;
								
								$links = array();

								foreach ( $download_file_urls as $file_url => $download_file_url ) {

									$filename = woocommerce_get_filename_from_url( $file_url );

									$links[] = '<small><a href="' . $download_file_url . '">' . sprintf( __( 'Download file%s', 'ignitewoo_vendor_stores' ), ( count( $download_file_urls ) > 1 ? ' ' . ( $i + 1 ) . ': ' : ': ' ) ) . $filename . '</a></small>';

									$i++;
								}

								echo implode( '<br/>', $links );
							}

							echo '</td><td style="vertical-align:top">'. 
							
							apply_filters( 'woocommerce_order_table_item_quantity', '<strong class="product-quantity">&times; ' . $item['qty'] . '</strong>', $item ) . '</td>
							
							<td class="product-total" style="vertical-align:top">';
							
							$amount = ign_calculate_product_commission( $item['line_total'], $item['item_meta']['_product_id'][0], $item['variation_id'], $vendor_id  );
							
							$order_total += $amount;

							echo  woocommerce_price( $amount );
							
							echo '</td></tr>';

							// Show any purchase notes
							if ($order->status=='completed' || $order->status=='processing') {
								if ($purchase_note = get_post_meta( $_product->id, '_purchase_note', true))
									echo '<tr class="product-purchase-note"><td colspan="3">' . apply_filters('the_content', $purchase_note) . '</td></tr>';
							}
							
				
							$shipping = array();
							
							$shipping_due = get_post_meta( $order->id, '_vendor_shipping_due', true );

							if ( 'yes' == $ignitewoo_vendors->settings['include_shipping'] ) { 
								$shipping[ $vendor_id ] = ign_calculate_shipping_commission( $item['product_id'], $item['variation_id'], $vendor_id, $shipping_due[ $vendor_id ] );
							}

						}
					}

					do_action( 'woocommerce_order_items_table', $order );
					?>
				</tbody>
	
				<?php 
				
				$subtotal = $order_total; 

				//$shipping_due = get_post_meta( $order->id, '_vendor_shipping_due', true );
						
				if ( !empty( $shipping[ $vendor_id ] ) )
					$order_total += $shipping[ $vendor_id ];
				
				$tax_due = array();
				
				if ( 'yes' == $ignitewoo_vendors->settings['give_vendor_tax'] ) { 
					
					$tax_due = get_post_meta( $order->id, '_vendor_tax_due', true );

					if ( !empty( $tax_due[ $vendor_id ] ) )
						$order_total += $tax_due[ $vendor_id ];
						
				}
					
				?>
				
				<tfoot>
					<tr>	
						<td></td>
						<th scope="row"><?php _e( 'Subtotal', 'ignitewoo_vendor_stores' ) ?></th>
						<td><?php echo woocommerce_price( $subtotal ) ?></td>
					</tr>
					
					<?php if ( !empty( $shipping_due[ $vendor_id ] ) ) { ?>
					<tr>
						<td></td>
						<th scope="row"><?php _e( 'Shipping', 'ignitewoo_vendor_stores' ) ?></th>
						<td><?php echo woocommerce_price( $shipping[ $vendor_id ] ) ?></td>
					</tr>
					<?php } ?>
					
					<?php if ( !empty( $tax_due[ $vendor_id ] ) ) { ?>
					<tr>
						<td></td>
						<th scope="row"><?php _e( 'Taxes', 'ignitewoo_vendor_stores' ) ?></th>
						<td><?php echo woocommerce_price( $tax_due[ $vendor_id ] ) ?></td>
					</tr>
					<?php } ?>
					
					<tr>
						<td></td>
						<th scope="row"><?php _e( 'Total', 'ignitewoo_vendor_stores' ) ?></th>
						<td><?php echo woocommerce_price( $order_total ) ?></td>
					</tr>

				</tfoot>

	
				</table>
			</td>

		    </tr>

		</table>
	</div>
	<div class="clear"></div>
	<table cellspacing="0" cellpadding="0" style="width: 100%; border-top: 1px solid #000; margin-top: 25px" border="0" bordercolor="#eee">
		<tr>
		
			<td>
				<?php 
					$vendor_data = get_option( $ignitewoo_vendors->token . '_' . $vendor_id );
					if ( !empty( $vendor_data['packing_slip_footer'] ) )
						echo '<p>' . $vendor_data['packing_slip_footer'] . '</p>';
				?>
			</td>


		</tr>
	</table>
<div class="clear"></div>
