<?php
/**
 * Login form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( is_user_logged_in() ) 
	return;
?>
<form method="post" id="chklogin" class="login" <?php if ( $hidden ) echo 'style="display:none;"'; ?>>

	<?php do_action( 'woocommerce_login_form_start' ); ?>

	<?php if ( $message ) echo wpautop( wptexturize( $message ) ); ?>
    <div class="large-12 columns">
        <div class ="checkout-group">
            <h3><?php _e( 'Billing Details', 'woocommerce' ); ?></h3>
        </div>
        <div class="small-12 large-6 columns email_login">
            
            <p class="form-row form-row-first">
                    <label for="username"><?php _e( 'Email Address', 'woocommerce' ); ?> <span class="required">*</span></label>
                    <input type="text" class="input-text" name="username" id="username" />
            </p>
        </div>
        
        <div class="small-12 large-6 columns">
            <div id ="loading_ajax" style ="display:none;"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/loader.gif" title="loading"></div>
                <div id ="edkrtpwd">
                    <p>
                        <label for="password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
                        <input class="input-text" type="password" name="password" id="password" />
                    </p>
                </div>

        </div>
    </div>
    <div class="clear"></div>
    <!--<div class="large-12 columns">
        <p class="form-row large-5 columns">
                <label for="edukartpassword" class="">
			<input name="edukartpassword" type="checkbox" id="edukartpassword" value="havepassword" /> <?php //_e( 'Have Edukart Password', 'woocommerce' ); ?>
		</label>
                
	</p>
        <p class="lost_password large-5 columns">
            <a href="<?php// echo esc_url( wc_lostpassword_url() ); ?>"><?php //_e( 'Lost your password?', 'woocommerce' ); ?></a>
	</p>
     </div>-->
	<div class="clear"></div>

	<?php do_action( 'woocommerce_login_form' ); ?>

	<p class="form-row">
		<?php wp_nonce_field( 'woocommerce-login' ); ?>
            <input type="submit" class="button"id ="login_form_check" name="login"  value="<?php _e( 'Continue Application', 'woocommerce' ); ?>" />
		<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ) ?>" />
		<!--<label for="rememberme" class="inline">
			<input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php //_e( 'Remember me', 'woocommerce' ); ?>
		</label>-->
	</p>
	<!--<p class="lost_password">
		<a href="<?php //echo esc_url( wc_lostpassword_url() ); ?>"><?php //_e( 'Lost your password?', 'woocommerce' ); ?></a>
	</p>-->

	<div class="clear"></div>

	<?php do_action( 'woocommerce_login_form_end' ); ?>

</form>