<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// following files need to be included
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");

$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";

$paramList = $_POST;

$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application’s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.

include_once "../../../../../wp-load.php";
get_header();
?>
<div class="page-wrapper page-title-center">
<div class="row">

<div role="main" class="large-12 columns" id="content">
<div class="entry-content">
<table style="width:60%;margin:0 auto;">
<?php

if($isValidChecksum === TRUE) {

	if ($_POST["STATUS"] == "TXN_SUCCESS") {
?>
		<header class="entry-header text-center">
			<h1 class="entry-title">Thank You!</h1>
			<div class="tx-div medium"></div>
		</header>
<?php
		echo "<tr><th style='font-size:14px;'><b>Your transaction was <u>successful</u>, and following are the transaction details:</b></th></tr>";
		//Process your transaction here as success transaction.
		//Verify amount & order id received from Payment gateway with your application's order id and amount.
	}
	else {
?>
		<header class="entry-header text-center">
			<h1 class="entry-title">OOPS! Looks like something went wrong!</h1>
			<div class="tx-div medium"></div>
		</header>
<?php
		echo "<tbody><tr><td><font color='red'><b>Your Transaction failed.</b></font></td></tr>";
	}

	if (isset($_POST) && count($_POST)>0 )
	{ 
		echo "<tr><td>Order Id</td><td>" . $_POST['ORDERID'] ."</td></tr>";
		echo "<tr><td>Amount</td><td>" . $_POST['TXNAMOUNT'] ."</td></tr>";
		echo "<tr><td>Currency</td><td>" . $_POST['CURRENCY'] ."</td></tr>";
		echo "<tr><td>Transaction ID</td><td>" . $_POST['TXNID'] ."</td></tr>";
		echo "<tr><td>Status</td><td><u><b>".( ($_POST['STATUS'] == 'TXN_SUCCESS') ? 'Successful' : 'NOT Successful') ."</b></u></td></tr>";
		echo "<tr><td>Date</td><td>" . $_POST['TXNDATE'] ."</td></tr>";
		echo "<tr><td>Bank Name</td><td>" . $_POST['BANKNAME'] ."</td></tr>";
		echo "<tr><td>Payment Mode</td><td>" . $_POST['PAYMENTMODE'] ."</td></tr>";
		//foreach($_POST as $paramName => $paramValue) {
		//		echo "<tr><td class='".$paramname."'>" . $paramName . "</td><td>" . $paramValue ."</td></tr>";
		//}
	}

	gh_paynow_page_payment($_POST);
	gh_paytm_kit_mail_to_customer($_POST);

}
else {
	echo "<tr><th><b>OOPS! Something seriously went wrong. For any further help, you can contact us <a href='".site_url()."/contact-us/'>here</a>.</b></th></tr>";
	//Process transaction as suspicious.
}
?>
</tbody></table>
</div>
</div>
<?php
get_footer();

?>
