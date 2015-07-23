<?php
/*
Template Name: Paynow Processing
*/

//print_r($_POST);
//exit;

if(!isset($_POST['pay_with_paytm']) AND !isset($_POST['pay_with_paypal']) AND !isset($_POST['pay_with_paytm_existing']) AND !isset($_POST['pay_with_paypal_existing'])){
    $url = site_url();
    wp_safe_redirect($url);
}
    $gh_payment_reference_no = "EDU_".$_POST['email']."_".time();
   // $gh_payment_reference_no = encrypt_decrypt_gh($gh_payment_reference_no, 'encrypt');
    $gh_payment_reference_no = md5($gh_payment_reference_no, 'encrypt');
   // echo $gh_payment_reference_no ;
//exit;

    if(empty($_POST['CUST_ID'])){
            $custid_err = "Customer ID is required.";
        }else{
            $student_id = trim($_POST['cust_id']);
        }
        if(empty($_POST['course'])){
            $course_err = "Course is required.";
        }else{
            $course = trim($_POST['course']);
        }
        if(empty($_POST['first_name'])){
            $name_err = "Name is required.";
        }else{
            $student_name = trim($_POST['first_name']);
        }
        if(empty($_POST['email'])){
            $email_err = "Email is required.";
        }else{
            $student_email = trim($_POST['email']);
        }
        if(empty($_POST['phone'])){
            $phone_err = "Phone No. is required.";
        }else{
            $student_phone = trim($_POST['phone']);   
        }
        if(empty($_POST['amount'])){
            $fee_err = "Fee is required.";
        }else{
            $student_fee = trim($_POST['amount']);
        }
        $student_desc = $_POST['description'];
    
    
    
	//echo $_POST['pay_with_paytm'] ;
	//exit;
    if(isset($_POST['pay_with_paytm']) && $_POST['pay_with_paytm'] == 'Pay Now' ){
        $pg_type = 'paytm';
       
    }elseif(isset($_POST['pay_with_paypal'])){

        $pg_type = 'paypal';
    
    }elseif(isset($_POST['pay_with_paytm_existing'])){
        $pg_type = 'paytm';
//        $url = get_site_url();
//        $action_url .= $url."/wp-content/themes/flatsome-gh/pg/PaytmKit/pgRedirect.php";
        
    }
    elseif(isset($_POST['pay_with_paypal_existing'])){
        $pg_type = 'paypal';
    }
    
    email_form_details_to_admin();
    
    function email_form_details_to_admin(){
        global $gh_payment_reference_no, $pg_type;            
        if($_POST['user_type'] == 'new_user'){
            foreach($_POST as $key => $value){
               $name = $_POST['first_name'];
               $email = $_POST['email'];
               $mobileno = $_POST['phone'];
               $amount = $_POST['amount']; 
				$date = new DateTime();
            }
            $maildata = "<html><head></head><body><p>Dear Admin,</p><br><table width='100%' border='0' cellspacing ='0' cellpadding: '10'><tbody><tr bgcolor='white'><td><h3>payment gateway email notification</h3></td></tbody></table>";
            $maildata .="<table border='0' width='100%' cellspacing ='1' cellpadding: '10' background='light-blue'><tbody>";
			$maildata .="<tr bgcolor='#CCCCCC'><td colspan='2'><strong>Customer Information :</strong></td></tr>";
			$maildata .="<tr><td>Name :</td><td>".$name."</td></tr>";            
			$maildata .="<tr><td>Email :</td><td>".$email."</td></tr>";
            $maildata .="<tr><td>Phone :</td><td>".$mobileno."</td></tr>";
			$maildata .="<tr><td colspan='2'>&nbsp;</td></tr>";
			$maildata .="<tr bgcolor='#CCCCCC'><td colspan='2'><strong>Payment Information :</strong></td></tr>";
            $maildata .="<tr><td>Amount :</td><td>".$amount."</td></tr>";
            $maildata .="<tr><td>Payment Method :</td><td>".$pg_type."</td></tr>";
            $maildata .="<tr><td>Refrence No :</td><td style='color:red;'>".$date->getTimestamp()."</td></tr>";
			$maildata .="<tr><td colspan='2'>&nbsp;</td></tr>";
            $maildata .="</tbody></table><br/>";
			$maildata .="<table><tbody><tr><td><div><strong>Note:</strong> This email was sent to you after the user submitted AND before s/he was redirected to the payment ".$pg_type.". You may receive one more email after the successful payment at the payment gateway. </div></td></tr><tr><td><br></tbody></table>";
			$maildata .="<table  width='100%'><tbody><tr><td ><hr ><div style='font-size: 9px;' > Declaration:<br><a href='http://EduKart.com' target='_blank'>EduKart.com</a> is a trademark of Earth Education Valley Pvt. Ltd. This invoice shows the final selling price of the course and is inclusive of all taxes, shipping and handling charges. <br style='' class=''>Company's PAN: AACCE6152C <br>Company's Service Tax No: AACCE6152CSD001 <br>CIN: U80302DL2011PTC213971 </div><div style='font-size: 9px;' class=''> Terms and Conditions:<br>1)The sale is governed by the Cancellation and Refund Policy as mentioned on <a target='_blank' href='http://www.edukart.com' style='' class=''>www.edukart.com</a>.<br style='' class=''>2)Interest @ 24% per annum will be charged on overdue accounts.<br>3)Subject to Delhi jurisdiction only.<br style='' class=''></div><hr style='' class=''></td></tr></tbody></table>";
			$maildata .="</body></html>";
            $mailto  = get_option('admin_email');
            $subject = 'New User Fee';
            add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
            wp_mail($mailto, $subject, $maildata);

        }elseif($_POST['user_type'] == 'existing_user'){
            global $gh_payment_reference_no, $pg_type; 
            foreach($_POST as $key => $value){
                $cust_id = $_POST['cust_id'];
                $course = $_POST['course'];
                $name = $_POST['first_name'];
                $email = $_POST['email'];
                $mobileno = $_POST['phone'];
                $amount = $_POST['amount'];
            }
            $maildata = "<html><head></head><body><h3 style='background-color:light-blue;padding:10px,5px;'>Note: This email was sent to you after the user submitted AND before s/he was redirected to the payment ".$pg_type.". You may receive one more email after the successful payment at the payment gateway. </h3>";
            $maildata .="<table border='0' cellspacing ='1' cellpadding: '10' background='light-blue'><tbody><tr><td><strong>Cust ID :</strong></td><td>".$cust_id."</td></tr>";
            $maildata .="<tr><td><strong>Course :</strong></td><td>".$course."</td></tr>";
            $maildata .="<tr><td><strong>Name :</strong></td><td>".$name."</td></tr>";
            $maildata .="<tr><td><strong>Email :</strong></td><td>".$email."</td></tr>";
            $maildata .="<tr><td><strong>Phone :</strong></td><td>".$mobileno."</td></tr>";
            $maildata .="<tr><td><strong>Amount :</strong></td><td>".$amount."</td></tr>";
            $maildata .="<tr><td><strong>Refrence No :</strong></td><td style='color:red;'>".$gh_payment_reference_no."</td></tr>";
            $maildata .="</tbody></table></body></html>";
            $mailto  = get_option('admin_email');
            $subject = 'New User Fee';
            add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
            wp_mail($mailto, $subject, $maildata);
        }
    }
    
    function encrypt_decrypt_gh($string='', $action='encrypt'){
        /*
         * PHP mcrypt - Basic encryption and decryption of a string
         */
        
        $secret_key = "edukart";

        // Create the initialization vector for added security.
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);

        if($action == 'encrypt'){
            // Encrypt $string
            $encrypted_string = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $secret_key, $string, MCRYPT_MODE_CBC, $iv);    
            return $encrypted_string;
        }elseif($action == 'decrypt'){
            // Decrypt $string
            $decrypted_string = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $secret_key, $string, MCRYPT_MODE_CBC, $iv);
            return $decrypted_string;
        }
        
    }
    
     function print_pg_form($pg_type, $field_values, $reference_no){
        
        if($pg_type == 'paypal'){
            // $paypal_action_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
            $paypal_action_url = 'https://www.paypal.com/cgi-bin/webscr';
            
            echo '<form method="post" id="payment_process" action="'.$paypal_action_url.'">

            <input name="cmd" type="hidden" value="_xclick" />
            <input name="bn" type="hidden" value="EduKart_BuyNow_WPS_IN" />
            <input name="business" type="hidden" value="accounts@edukart.com" />
            <input name="custom" type="hidden" value="'.$_POST['email'].'" />

            <input name="amount" type="hidden" value="'.$_POST['amount'].'" />
            <input name="item_name" type="hidden" value="Payment at EduKart.com" />

            <input name="return" type="hidden" value="'.site_url().'" />
            <input name="cancel_return" type="hidden" value="'.site_url().'" />
            <input name="notify_url" type="hidden" value="'.site_url().'" />

            <input name="rm" type="hidden" value="POST" />
            <input name="cbt" type="hidden" value="Return to EduKart.com" />
            <input name="no_shipping" type="hidden" value="1" />
            <input name="no_note" type="hidden" value="1" />
            <input name="cpp_logo_image" type="hidden" value="https://static.e-junkie.com/sslpic/141691.f75308630b6ee33eaa24a28a632b45fa.jpg" />
            <input name="image_url" type="hidden" value="https://static.e-junkie.com/sslpic/141691.f75308630b6ee33eaa24a28a632b45fa.jpg" />

            <!--<input type="image" src="https://www.paypalobjects.com/webstatic/en_US/btn/btn_addtocart_120x26.png" border="0" name="submit" alt="">-->

            </form>';
            
        }elseif($pg_type == 'paytm'){
            $action_url = get_site_url()."/wp-content/themes/flatsome-gh/pg/PaytmKit/pgRedirect.php";
			$number  = $_POST['phone'];
			$gh_cust = str_split($number, 5);
			$gh_cust_id = $gh_cust[0]."15".$gh_cust[1];
			$_SESSION['paynow_name'] = $_POST['first_name'];
			$_SESSION['paynow_email'] = $_POST['email'];
			$_SESSION['paynow_phone'] = $_POST['phone'];
            echo '
                <form method ="post" action="'.$action_url.'" id="payment_process">
				
                <input type="hidden" name="CUST_ID" value="'.$gh_cust_id.'">
                <input type="hidden" name="TXN_AMOUNT" value="'.$_POST['amount'].'">
		<input type="hidden" name="EMAIL" value="'.$_POST['email'].'">
		<input type="hidden" name="CALLBACK_URL" value="'.get_site_url().'/wp-content/themes/flatsome-gh/pg/PaytmKit/pgResponse.php"/>

                </form>
            ';
        }else{
            return false;
        }

    }
    
    get_header();
   ?>
<script type="text/javascript">
  $(window).load(function() {
    document.forms['payment_process'].submit()
  });
</script>
<div style="display:block;margin:10% auto 10% auto; text-align: center;width:100%;"><img  src= "<?php echo get_stylesheet_directory_uri(); ?>/images/loader.gif"/></div>
<?php 
    print_pg_form($pg_type, $_POST, $gh_payment_reference_no); 
    

    get_footer();
