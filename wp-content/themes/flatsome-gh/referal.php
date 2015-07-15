<?php
/*
Template Name: Referral
*/

if(!isset($_POST['order_referal_name']) AND !isset($_POST['order_referal_email'])){
    $url = site_url();
    wp_safe_redirect($url);
}
if(isset($_POST['order_referal_name'])){
	$name = $_POST['order_referal_name'];
}
if(isset($_POST['order_referal_email'])){
	$email = $_POST['order_referal_email'];
}


    unset($_COOKIE['referee']);
    get_header();
?>
<div class="referal_page">
<iframe style="max-width: 100%;" width="100%" height="650px" frameborder="0" align="middle" src="http://www.ref-r.com/campaign_user/p?brandid=1576&campaignid=5184&bid_e=E1B12D0338B46F598D8123D7C78E9598&t=420&email=<?php echo $email; ?>&fname=<?php echo $name; ?>">  </iframe>
</div>
<?php

    get_footer();
