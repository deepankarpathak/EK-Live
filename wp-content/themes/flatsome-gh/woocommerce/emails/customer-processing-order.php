<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>



<?php
/**
 * Customer processing order email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */
//global $woocommerce;

//$order = new WC_Order;
//print_r($order->get_user());
////print_r($order->id);
//exit;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

 <div style="" class="">
  <table width="100%" cellspacing="0" cellpadding="10" border="0" align="center" >
    <tbody style="" class="">
      <tr style="" class="">
        <td style="" class="">Dear <?php echo $order->billing_first_name; ?>, <br style="" class="">
          <br style="" class="">
          Greetings from <a href="http://EduKart.com" target="_blank" style="color:#000; font-weight: bold;" class="">EduKart.com</a>!.<br style="" class=""> We are pleased to inform you that your Order No. <?php echo $order->get_order_number(); ?> is being processed. Our team will get back to you within 48 working hours for any further details required. </td>
        <td width="47%" align="right" style="" class=""><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?> - <?php bloginfo( 'description' ); ?>" rel="home"> 
							<img src="<?php echo get_site_url();?>/wp-content/themes/flatsome-gh/images/logo.jpg" title="edukart" /> 
						</a></td>
      </tr>
      <tr bgcolor="white" style="" class="">
        <td style="" class=""><h3 style="" class=""><b style="" class="">Invoice</b></h3></td>
      </tr>
    </tbody>
  </table>
  <table width="100%" cellspacing="0" cellpadding="2" border="0" >
    <tbody style="" class="">
      <tr bgcolor="#CCCCCC" style="" class="">
        <td colspan="2" style="" class=""><b style="" class="">Order Information</b></td>
      </tr>
      <tr style="" class="">
        <td style="" class="">Order Number:</td>
        <td style="" class=""><?php echo $order->get_order_number(); ?></td>
      </tr>
      <tr style="" class="">
        <td style="" class="">Order Date:</td>
        <td style="" class=""><?php echo $order->order_date; ?></td>
      </tr>
      <tr style="" class="">
        <td style="" class="">Order Status:</td>
        <td style="" class=""><b style="" class=""><?php echo $order->get_status(); ?></b></td>
      </tr>
      <tr style="" class="">
        <td colspan="2" style="" class=""> </td>
      </tr>
      <tr bgcolor="#CCCCCC" style="" class="">
        <td colspan="2" style="" class=""><b style="" class="">Customer Information</b></td>
      </tr>
      <tr valign="top" style="" class="">

        <td width="50%" style="" class=""><table width="100%" cellspacing="0" cellpadding="2" border="0" style="" class="">
            <tbody style="" class="">
              <tr style="" class="">
                <td style="" class="">Email:</td>
                <td style="" class=""><?php if ($order->billing_email) : ?><?php echo $order->billing_email; ?><?php endif; ?></td>
              </tr>
	      <tr style="" class="">
                <td style="" class="">Phone No:</td>
                <td style="" class=""><?php echo $order->billing_phone; ?></td>
              </tr>
            </tbody>
          </table></td>
	<td width="50%" style="" class=""><table width="100%" cellspacing="0" cellpadding="2" border="0" >
            <tbody style="" class="">
              <tr style="" class="">
               <?php wc_get_template( 'emails/email-addresses.php', array( 'order' => $order ) ); ?>
		<?php echo WC()->countries->countries[$order->billing_country]; ?>
              </tr>
            </tbody>
          </table></td>
        
      </tr>
      <tr bgcolor="#CCCCCC" style="" class="">
        <td colspan="2" style="" class=""><b style="" class="">Order Items</b></td>
      </tr>
      <tr style="" class="">
        <td colspan="2" style="" class=""><table width="100%" cellspacing="0" cellpadding="2" border="0" style="" class="">
             
          </table></td>
      </tr>
      <tr style="" class="">
        <td colspan="2" style="" class=""><table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Price', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php echo $order->email_order_items_table( $order->is_download_permitted(), true, $order->has_status( 'processing' ) ); ?>
	</tbody>
	<tfoot>
		<?php
		$cashback_amount = get_post_meta($order->id, 'cash_back', true);
		$cashback_code = get_post_meta($order->id, 'ir_coupon_code', true);
		if($cashback_amount != null AND $cashback_amount != ''){
		?>
		<tr>
			<th  scope="row" colspan="2" style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>">Cash-Back : <?php echo  $cashback_code ; ?></th><td style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php  echo gh_get_local_currency_symbol() . ' '.number_format_i18n( gh_get_currency_updated_price($cashback_amount), 2); ?></td>
		</tr>
		<?php  } ?>
		
		<?php
			if ( $totals = $order->get_order_item_totals() ) {
				$i = 0;
				foreach ( $totals as $total ) {
					$i++;
					?><tr>
						<th scope="row" colspan="2" style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['label']; ?></th>
						<td style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
					</tr><?php
				}
			}
		?>
	</tfoot>
</table></td>
      </tr>
      <tr style="" class="">
        <td colspan="2" style="" class=""> </td>
      </tr>
      <!--<tr bgcolor="#CCCCCC" style="" class="">
        <td style="" class=""><b style="" class="">Payment Information</b></td>
        <td style="" class=""><b style="" class=""></b></td>
      </tr>
      <tr style="" class="">
        <td style="" class=""><?php echo $order->get_status(); ?></td>
      </tr>-->
    </tbody>
  </table>
  <br style="" class="">
  <br style="" class="">
  <table style="" class="">
    <tbody style="" class="">
      <tr valign="top" style="" class="">
        <td width="53%" align="left" style="" class=""><div style="" class="">Also, if you've earned any cashback, it will be credited to your Paytm wallet after your course is activated. </div>
          <br style="" class="">
          Please feel free to contact us for any further queries. <br style="" class="">
          <br style="" class="">
          Regards,<br style="" class="">
          The EduKart Team <br style="" class="">
          <a href="http://EduKart.com" target="_blank" style="" class="">EduKart.com</a><br style="" class="">
          Call: +91-11-49323333<br style="" class="">
          Email: <a href="mailto:support@EduKart.com" style="" class="">support@EduKart.com</a><br style="" class=""></td>
      </tr>
      <tr style="" class="">
        <td style="" class=""><hr style="" class="">
          <div style="font-size: 9px;" class=""> Declaration:<br style="" class="">
		This website <a href="http://EduKart.com" target="_blank" style="color:#000; font-weight: bold;" class="">(www.edukart.com)</a>	 is owned by Earth Education Valley Pvt Ltd. (the company). This website and the company ​seek to assist students in making informed choices about their distance education needs and connect with course providers. In doing so, this website ​and the company ​do not promote any course provider over the other. This website ​and the company ​seek to be information provider​s​ and ​are​ not Study Centre​, and ha​ve​ no role whatsoever in determining a student's eligibility, the admission decision, tuition fees, academic delivery, examinations and awarding degrees for any university, institution or course provider. Students are strongly advised to visit UGC Distance Education Bureau website to check details such as status of approval/recognition of universities and institutes and their territorial jurisdiction before enrolling for any course. The UGC has from time to time issued advisory notifications in the interest of students. Students are strongly advised to read these notifications at http://www.ugc.ac.in/deb/notices.html and take a considered decision before enrolling for any course. ​The company does not charge any extra fees over and above the prescribed fee for the UGC approved courses. This invoice shows the final selling price of the course and is inclusive of all taxes, shipping and handling charges. <br style="" class="">
            Company's PAN: AACCE6152C <br style="" class="">
            Company's Service Tax No: AACCE6152CSD001 <br style="" class="">
            CIN: U80302DL2011PTC213971 </div>
          
          <hr style="" class=""></td>
      </tr>
    </tbody>
  </table>
 <style>
table h3 {
  display: none !important;
}
 </style> 
</div>

 </body>
</html>
