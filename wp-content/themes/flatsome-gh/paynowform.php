<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.8/jquery.validate.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() { 
        $('#tab1').click(function(){
          $("#tab1").addClass("green");
          $("#tab1").removeClass("gray");
          $("#tab2").addClass("gray");
          $("#tab2").removeClass("green");
          $("#paynow_form_for_existing").hide();
          $("#paynow_form_for_new").show();
          
        });
        $('#tab2').click(function(){
          $("#tab2").addClass("green");
          $("#tab2").removeClass("gray");
          $("#tab1").addClass("gray");
          $("#tab1").removeClass("green");
          $("#paynow_form_for_existing").show();
          $("#paynow_form_for_new").hide();
        }); 
        $('#indiancurrency').click(function(){
            $("#pay_with_paytm").show();
            $("#pay_with_paypal").hide();
            
        });
        $('#othercurrency').click(function(){
            $("#pay_with_paypal").show();
            $("#pay_with_paytm").hide();
        });
        $('#existindiancurrency').click(function(){
            $("#pay_with_paytm_existing").show();
            $("#pay_with_paypal_existing").hide();
            
        });
        $('#existothercurrency').click(function(){
            $("#pay_with_paypal_existing").show();
            $("#pay_with_paytm_existing").hide();
        });
                
        $('#pay_with_paytm').click(function() {
                    $("#paynow_form_for_new").validate();  // This is not working and is not validating the form
                });
        $('#pay_with_paytm').click(function() {
                    $("#paynow_form_for_existing").validate();  // This is not working and is not validating the form
                });
        $('#pay_with_paypal').click(function() {
                    $("#paynow_form_for_new").validate();  // This is not working and is not validating the form
                });
        $('#pay_with_paypal').click(function() {
                    $("#paynow_form_for_existing").validate();  // This is not working and is not validating the form
                });
        $('#pay_with_paytm_existing').click(function() {
                    $("#paynow_form_for_new").validate();  // This is not working and is not validating the form
                });
        $('#pay_with_paytm_existing').click(function() {
                    $("#paynow_form_for_existing").validate();  // This is not working and is not validating the form
                });
        $('#pay_with_paypal_existing').click(function() {
                    $("#paynow_form_for_new").validate();  // This is not working and is not validating the form
                });
        $('#pay_with_paypal_existing').click(function() {
                    $("#paynow_form_for_existing").validate();  // This is not working and is not validating the form
                });
        $('#paynow_form_for_new').validate( {
			rules: {
				'first_name': {
					required: true,							
					},
				'email': {
					required: true,
                                        email: true
					},
				'phone': {
					required: true,	
                                        },
				'amount':{
					required:true,	
                                         number: true
					}
				},
			messages: {
				'first_name': {
					required: 'Please enter your name.',
					},
				'email': {
					required: 'Please enter a valid email address.',					
					},
				'phone': {
					required: 'Please enter valid phone number.',	
					},
                                'amount':{
                                        required: 'Please enter valid amount.',
                                 }
				
				},

		});
        $('#paynow_form_for_existing').validate( {
			rules: {
                                'cust_id': {
					required: true,							
					},
                                'course': {
					required: true,							
					},
				'first_name': {
					required: true,							
					},
				'email': {
					required: true,	
                                        email: true
					},
				'phone': {
					required: true,	
					},
				'amount':{
					required:true,	
                                        number: true
					}
				},
			messages: {
                                'cust_id': {
					required: 'Please enter your customer id.',
					},
                                'course': {
					required: 'Please enter your Course Name.',
					},
				'first_name': {
					required: 'Please enter your name.',
					},
				'email': {
					required: 'Please enter a valid email address.',					
					},
				'phone': {
					required: 'Please enter valid mobile number.',							
					},
                                 'amount':{
                                        required: 'Please enter valid amount.',
                                    }
				
				},

		});
                
    });
</script>
<style>
.edukart_paynow_form {
  min-height: 410px;
}
.paynow-sec-block .paynow-label {
  color: #2b77b3;
  font-size: 16px;
  font-weight: bold;
  margin-left: 0;
  margin-top: 10px;
}
.paynow-sec-block .paynow-innerblock {
  background: none repeat scroll 0 0 white;
  border: 1px solid #cecece;
  border-radius: 0 0 5px 5px;
  float: left;
  padding: 10px 4% 0;
  width: 100%;
}
.paynow-sec-block .mandatory-label {
  color: #c1c1c1;
  font-size: 9px;
  margin-left: 0;
}
.paynow-sec-block .paynow-form ul li {
  list-style: outside none none;
  margin-left: 0;
  padding: 2px 0;
}
.paynow-sec-block .paynow-form .paynow-full-width ul {
  margin-top: 10px;
}
.paynow-sec-block .paynow-form .paynow-full-width {
  margin-top: 5px;
  width: 100%;
}
.paynow-sec-block .paynow-full-width ul li input, .paynow-full-width ul li textarea {
  box-shadow: none;
  font-size: 12px;
  height: 26px;
  padding: 5px 0 0 5px;
  width: 98% !important;
}
.paynow-sec-block .paynow-form .paynow-full-width ul {
  margin-bottom: 0;
  margin-top: 10px;
}
.paynow-sec-block .marginleft {
  margin-left: 0;
}
.paynow-sec-block .paynow-full-width.marginleft > label {
  float: left;
  font-size: 12px;
  margin-right: 15px;
  margin-top: 2px;
}
.paynow-sec-block .paynow-full-width p {
  clear: both;
  display: block;
  float: left;
  margin-bottom: 0;
  margin-top: -7px;
}
.paynow-sec-block .paynow-full-width p span.terms-label {
  margin: 0 0 0 5px;
}
#paynow_form_for_existing .paynow-label {
  font-size: 14px;
}
</style>
            <div class="paynow-form">
		<div class="paynow-first-block">
		
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/Happy-Learning.png" style="width:70%;margin:10%;">
				
		</div>
		<div class="paynow-sec-block">
			<div class="paynow-full-width">
				<span style="margin:0;" id="tab1" class="paynow-tab green">New Student</span>
				<span id="tab2" class="paynow-tab gray ">Existing Student</span>
			</div>
                    <form id="paynow_form_for_new" name="for_new_user" method="post" action="<?php echo get_site_url(); ?>/paynow-processing">
			<div class="paynow-innerblock">
				<div class="paynow-full-width paynow-label">
					Pay Your Course Fee Now !
				</div>
				<div class="paynow-full-width mandatory-label">Fields marked * are mandatory to fill</div>
				<div id="" class="paynow-full-width">
					<ul>
						<li><input type="text" class="required" value="" autocomplete="off" name="first_name" size="20" maxlength="30" tabindex="2" placeholder="Name*" id="first_name"><span class="error"><?php echo $name_err;?></span></li>
						<li><input type="text" class="required" value="" autocomplete="off" name="email" size="20" tabindex="2" placeholder="Email Id*" id="email"><span class="error"><?php echo $email_err;?></span></li>
						<li><input type="text" class="required" value="" autocomplete="off" name="phone" size="20" tabindex="2" placeholder="Mobile*" id="MSISDN"><span class="error"><?php echo $phone_err;?></span></li>
                                                <li><input type="text" class="required" value="" id="amount" name="amount" placeholder="Fee*" tabindex="10" ><span class="error"><?php echo $fee_err;?></span></li>
						<li><textarea name="description" placeholder="Description" id="DESCRIPTION"></textarea></li>

					</ul>
				</div>
				<div class="clear"></div>
                                <div class="paynow-full-width marginleft currency">
				    <span style="font-size: 10px;">Currency:</span><br/>
                                    <span style="float:left;width:100px;"><input type="radio"  class="termscheckbox required" id="indiancurrency" name="indiancurrency"><label for="indiancurrency">INR</label></span>
                                    <span style="float:left;width:150px;"><input type="radio"  class="termscheckbox required" id="othercurrency" name="indiancurrency"><label for="othercurrency">US Dollar</label></span>
                                </div>
                                <div class="clear"></div>
                                <div class="paynow-full-width marginleft"><p>
					<input type="checkbox"  style="float:left;margin-bottom:0;" class="termscheckbox required" id="TERM" name="term">
                                        <span class="terms-label"><label for="TERM">I agree to <a target="_blank" href="#">term &amp; conditions</a></label></span>
                                        <input type="hidden" name="user_type" value="new_user"/>
					<input type="hidden" name="CALLBACK_URL" value="<?php echo get_site_url();?>/wp-content/themes/flatsome-gh/thankyou.php"/>
                                    </p>
                                </div>
                                
                                <div class="clear"></div>
                                <div class="paynow-full-width marginleft margintop">
					<input type="submit" id="pay_with_paytm" style="display:none;" name ="pay_with_paytm" value="Pay Now">
					<input type="submit" id="pay_with_paypal" style="display:none;" name ="pay_with_paypal" value="Pay Now">
				</div>			
		
                        </div>
                    </form>
                    <form id="paynow_form_for_existing" name="for_existing_user" method="post" action="<?php echo get_site_url(); ?>/paynow-processing" style ="display:none;">
			<div class="paynow-innerblock">
				<div class="paynow-full-width paynow-label">
					Pay Your Course Fee Now !
				</div>
				<div class="paynow-full-width mandatory-label">Fields marked * are mandatory to fill</div>
				<div id="" class="paynow-full-width">
					<ul>
                                            <li><input type="text" class="required" value="" autocomplete="off" name="CUST_ID" size="20" maxlength="40" tabindex="2" id="CUST_ID" placeholder="Customer ID" ><span class="error"><?php echo $custid_err;?></span></li>
                                            <li><input type="text" class="required" value="" autocomplete="off" name="course" size="20" maxlength="30" tabindex="2" placeholder="Course*" id="course" ><span class="error"><?php echo $course_err;?></span></li>
                                            <li><input type="text" class="required" value="" autocomplete="off" name="first_name" size="20" tabindex="2" placeholder="Name*" ><span class="error"><?php echo $existname_err;?></span></li>
                                            <li><input type="text" class="required" value="" autocomplete="off" name="email" size="20" maxlength="30" tabindex="2" placeholder="Email Id*" ><span class="error"><?php echo $existemail_err;?></span></li>
                                            <li><input type="text" class="required" value="" autocomplete="off" name="phone" size="20" tabindex="2" placeholder="Mobile*" ><span class="error"><?php echo $existphone_err;?></span></li>
                                            <li><input type="text" class="required" value="" id="amount" name="amount" placeholder="Fee*" tabindex="10" ><span class="error"><?php echo $exist_err;?></span></li>
                                            <li><textarea name="description" placeholder="Description" id="DESCRIPTION"></textarea></li>
					</ul>
				</div>
				<div class="clear"></div>
                                <div class="paynow-full-width marginleft">
				    <span style="font-size: 10px;">Currency:</span><br/>
                                    <input type="radio" class="termscheckbox required" id="existindiancurrency" name="existindiancurrency"><label for="existindiancurrency">INR</label>
                                    <input type="radio" class="termscheckbox required" id="existothercurrency" name="existindiancurrency"><label for="existothercurrency">Other currency</label>
                                </div>
                                <div class="paynow-full-width marginleft"><p>
					<input type="checkbox" style="float:left;" class="termscheckbox required" id="TERM" name="term">
                                        <input type="hidden" name="user_type" value="existing_user"/>
					<input type="hidden" name="CALLBACK_URL" value="<?php echo get_site_url();?>/wp-content/themes/flatsome-gh/thankyou.php"/>
					<span class="terms-label"><label for="TERM">I agree to <a target="_blank" href="#">term &amp; conditions</a></label></span>
                                    </p>
                                </div>
                                
                                <div class="clear"></div>
                                <div class="paynow-full-width marginleft margintop">
                                    <input type="submit" id="pay_with_paytm_existing" style="display:none;" name="pay_with_paytm_existing" value="Pay Now">
                                    <input type="submit" id="pay_with_paypal_existing" style="display:none;" name="pay_with_paypal_existing" value="Pay Now">
				</div>			
		
                        </div>
                    </form>
		</div>
            </div>
		
         
