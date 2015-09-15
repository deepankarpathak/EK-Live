<?php
/*
Plugin Name: YITH WooCommerce Advanced Reviews Premium
Plugin URI: http://yithemes.com/themes/plugins/yith-woocommerce-advanced-reviews/
Description: Extends the basic functionality of woocommerce reviews and add a histogram table to the reviews of your products, as well as you see in most trendy e-commerce sites.
Author: Yithemes
Text Domain: ywar
Version: 1.1.10
Author URI: http://yithemes.com/
*/

//region    ****    Check if prerequisites are satisfied before enabling and using current plugin

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

/**
 * Check if Woocommerce is active and exit if it isn't active
 */
if ( ! function_exists( 'WC' ) ) {
	function yith_ywar_premium_install_woocommerce_admin_notice() {
		?>
		<div class="error">
			<p><?php _e( 'YITH WooCommerce Advanced Reviews is enabled but not effective. It requires WooCommerce in order to work.', 'ywar' ); ?></p>
		</div>
	<?php
	}

	add_action( 'admin_notices', 'yith_ywar_premium_install_woocommerce_admin_notice' );

	return;
}

/**
 * Check if a free version is currently active and try disabling before activating this one
 */
if ( ! function_exists( 'yit_deactive_free_version' ) ) {
	require_once 'plugin-fw/yit-deactive-plugin.php';
}
yit_deactive_free_version( 'YITH_YWAR_FREE_INIT', plugin_basename( __FILE__ ) );
//endregion

//region    ****    Define constants  ****

if ( ! defined( 'YITH_YWAR_INIT' ) ) {
	define( 'YITH_YWAR_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YITH_YWAR_SLUG' ) ) {
	define( 'YITH_YWAR_SLUG', 'yith-woocommerce-advanced-reviews' );
}

if ( ! defined( 'YITH_YWAR_SECRET_KEY' ) ) {
	define( 'YITH_YWAR_SECRET_KEY', 'wbJGFwHx426IS4V4vYeB' );
}

if ( ! defined( 'YITH_YWAR_VERSION' ) ) {
	define( 'YITH_YWAR_VERSION', '1.1.10' );
}

if ( ! defined( 'YITH_YWAR_PREMIUM' ) ) {
	define( 'YITH_YWAR_PREMIUM', '1' );
}

if ( ! defined( 'YITH_YWAR_FILE' ) ) {
	define( 'YITH_YWAR_FILE', __FILE__ );
}

if ( ! defined( 'YITH_YWAR_DIR' ) ) {
	define( 'YITH_YWAR_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'YITH_YWAR_URL' ) ) {
	define( 'YITH_YWAR_URL', plugins_url( '/', __FILE__ ) );
}

if ( ! defined( 'YITH_YWAR_ASSETS_URL' ) ) {
	define( 'YITH_YWAR_ASSETS_URL', YITH_YWAR_URL . 'assets' );
}

if ( ! defined( 'YITH_YWAR_TEMPLATE_PATH' ) ) {
	define( 'YITH_YWAR_TEMPLATE_PATH', YITH_YWAR_DIR . 'templates' );
}

if ( ! defined( 'YITH_YWAR_TEMPLATES_DIR' ) ) {
	define( 'YITH_YWAR_TEMPLATES_DIR', YITH_YWAR_DIR . '/templates/' );
}

//endregion

/**
 * Load text domain and start plugin
 */
load_plugin_textdomain( 'ywar', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

/**
 * Init default plugin settings
 */
if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
	require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );

require_once( YITH_YWAR_DIR . 'class.yith-woocommerce-advanced-reviews.php' );
require_once( YITH_YWAR_DIR . 'class.yith-woocommerce-advanced-reviews-premium.php' );

global $YWAR_AdvancedReview;
$YWAR_AdvancedReview = YITH_WooCommerce_Advanced_Reviews_Premium::get_instance();