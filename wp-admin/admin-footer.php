<style tytext/csspe="text/css">
	.loading{
		top:0;
		left: 0;
		position: fixed;
		bottom: 0;
		right: 0;
		/*display: block;*/
		background: #CCC;
		/* IE */
		-moz-opacity: 0.6;
		/* Mozilla */
		opacity: 0.6;
		z-index: 999999
	}

	.loading-icon{
		height: 100px;
		position: absolute;
		top:50%;
		margin-top:-50px; 
		left: 50%;
		margin-left: -50px;
	}
	.migrate-db{
		  cursor: pointer;
	}
</style>

<?php
/**
 * WordPress Administration Template Footer
 *
 * @package WordPress
 * @subpackage Administration
 */

// don't load directly
if ( !defined('ABSPATH') )
	die('-1');
?>
<script type="text/javascript">

document.getElementById('wp-admin-bar-migrate_db').onclick=function(){
	
	var r = confirm("Do you really want to migrate alpha DB with Live DB?");
	if (r == true) {
		jQuery('#loading').css('display','block');
		window.location.href = '<?php echo get_site_url(); ?>/wp-admin/db_migration/migration.php';
		//alert('Done');
		// jQuery('#loading').css('display','block');
		// //Ajax REquest
		// var xhr = new XMLHttpRequest();
		// xhr.open('GET', encodeURI('<?php echo get_site_url(); ?>/wp-admin/migration.php'));
		// xhr.onload = function() {
		//     if (xhr.status === 200) {
		//        jQuery('#loading').css('display','none');
		//        if(xhr.responseText == 'error'){
		//       	  alert('You need to log-in as admin to execute that Migration script!!!')
		//        }
		//     }
		//     else {
		//         alert('Request failed.  Returned status of ' + xhr.status);
		//     }
		// };
		// xhr.send();
		//jQuery('#loading').css('display','none');
		//window.location.assign("http://localhost/edukart/wp-admin/migration.php");
	}else{
	    alert('You clicked to cancel Migration!!!');
	    return false;
	}
};

</script>
<div class="loading" id="loading" style="display:none;">
<img class="loading-icon" src="<?php echo get_site_url() . '/wp-admin/images/page-loading.gif';?>" altr= "Loading..."/>
</div>

<div class="clear"></div></div><!-- wpbody-content -->
<div class="clear"></div></div><!-- wpbody -->
<div class="clear"></div></div><!-- wpcontent -->

<div id="wpfooter">
	<?php
	/**
	 * Fires after the opening tag for the admin footer.
	 *
	 * @since 2.5.0
	 */
	do_action( 'in_admin_footer' );
	?>
	<p id="footer-left" class="alignleft">
		<?php
		$text = sprintf( __( 'Thank you for creating with <a href="%s">WordPress</a>.' ), __( 'https://wordpress.org/' ) );
		/**
		 * Filter the "Thank you" text displayed in the admin footer.
		 *
		 * @since 2.8.0
		 *
		 * @param string $text The content that will be printed.
		 */
		echo apply_filters( 'admin_footer_text', '<span id="footer-thankyou">' . $text . '</span>' );
		?>
	</p>
	<p id="footer-upgrade" class="alignright">
		<?php
		/**
		 * Filter the version/update text displayed in the admin footer.
		 *
		 * WordPress prints the current version and update information,
		 * using core_update_footer() at priority 10.
		 *
		 * @since 2.3.0
		 *
		 * @see core_update_footer()
		 *
		 * @param string $content The content that will be printed.
		 */
		echo apply_filters( 'update_footer', '' );
		?>
	</p>
	<div class="clear"></div>
</div>
<?php
/**
 * Print scripts or data before the default footer scripts.
 *
 * @since 1.2.0
 *
 * @param string $data The data to print.
 */
do_action( 'admin_footer', '' );

/**
 * Prints any scripts and data queued for the footer.
 *
 * @since 2.8.0
 */
do_action( 'admin_print_footer_scripts' );

/**
 * Print scripts or data after the default footer scripts.
 *
 * The dynamic portion of the hook name, `$GLOBALS['hook_suffix']`,
 * refers to the global hook suffix of the current page.
 *
 * @since 2.8.0
 *
 * @param string $hook_suffix The current admin page.
 */
do_action( "admin_footer-" . $GLOBALS['hook_suffix'] );

// get_site_option() won't exist when auto upgrading from <= 2.7
if ( function_exists('get_site_option') ) {
	if ( false === get_site_option('can_compress_scripts') )
		compression_test();
}

?>

<div class="clear"></div></div><!-- wpwrap -->
<script type="text/javascript">if(typeof wpOnload=='function')wpOnload();</script>
</body>
</html>
