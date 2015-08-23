<?php
/*
Template Name: Referral
*/
 global $current_user;
      get_currentuserinfo();

     /* echo 'Username: ' . $current_user->user_login . "\n";
      echo 'User email: ' . $current_user->user_email . "\n";
      echo 'User level: ' . $current_user->user_level . "\n";
      echo 'User first name: ' . $current_user->user_firstname . "\n";
      echo 'User last name: ' . $current_user->user_lastname . "\n";
      echo 'User display name: ' . $current_user->display_name . "\n";
      echo 'User ID: ' . $current_user->ID . "\n";*/

if(empty($current_user->user_email)){
    $url = site_url();
    wp_safe_redirect($url);
}
/*if(isset($current_user->user_login)){
	$name = $current_user->user_login;
}*/
if(isset($current_user->user_email)){
	$email = $current_user->user_email;
}
    unset($_COOKIE['referee']);
    get_header();
   // echo $email; echo $name; die;
?>
<div class="referal_page row">
<!-- <iframe style="max-width: 100%;" width="100%" height="650px" frameborder="0" align="middle" src="http://www.ref-r.com/campaign_user/p?brandid=1576&campaignid=5184&bid_e=E1B12D0338B46F598D8123D7C78E9598&t=420&email=<?php //echo $email; ?>&fname=<?php //echo $name; ?>">  </iframe> -->

<iframe style="max-width: 100%;" width="100%" height="900" frameborder="0" align="middle" scrolling="no" src="http://edukart.ref-r.com/?campaignid=5184&email=<?php echo $email; ?>">  </iframe>
</div>
<script>
	$(".referralStats").click(function(){
		
	});
</script>
<?php
    get_footer();
