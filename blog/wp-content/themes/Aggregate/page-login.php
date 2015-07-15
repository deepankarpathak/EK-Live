<?php 
/*
Template Name: Login Page
*/
?>

<?php 
	$et_ptemplate_settings = array();
	$et_ptemplate_settings = maybe_unserialize( get_post_meta($post->ID,'et_ptemplate_settings',true) );
	
	$fullwidth = isset( $et_ptemplate_settings['et_fullwidthpage'] ) ? (bool) $et_ptemplate_settings['et_fullwidthpage'] : false;
?>

<?php get_header(); ?>

<div id="main-content" class="clearfix<?php if($fullwidth) echo(' fullwidth');?>">
	<div id="left-area">
		<?php get_template_part('includes/breadcrumbs','page'); ?>
		<div id="entries">
			<div class="entry post clearfix">
				<?php get_template_part('loop','page'); ?>
				
				<div id="et-login">
					<div class='et-protected'>
						<div class='et-protected-form'>
							<form action='<?php echo home_url(); ?>/wp-login.php' method='post'>
								<p><label><?php esc_html_e('Username','Aggregate'); ?>: <input type='text' name='log' id='log' value='<?php echo esc_attr($user_login); ?>' size='20' /></label></p>
								<p><label><?php esc_html_e('Password','Aggregate'); ?>: <input type='password' name='pwd' id='pwd' size='20' /></label></p>
								<input type='submit' name='submit' value='Login' class='etlogin-button' />
							</form> 
						</div> <!-- .et-protected-form -->
						<p class='et-registration'><?php esc_html_e('Not a member?','Aggregate'); ?> <a href='<?php echo site_url('wp-login.php?action=register', 'login_post'); ?>'><?php esc_html_e('Register today!','Aggregate'); ?></a></p>
					</div> <!-- .et-protected -->
				</div> <!-- end #et-login -->
				
				<div class="clear"></div>
			</div> <!-- end .entry -->
			
		</div> <!-- end #entries -->
	</div> <!-- end #left-area -->

	<?php if (!$fullwidth) get_sidebar(); ?>
		
<?php get_footer(); ?>