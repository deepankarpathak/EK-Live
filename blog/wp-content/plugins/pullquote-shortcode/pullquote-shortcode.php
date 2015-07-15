<?php
/**
 * Plugin Name: Pullquote Shortcode
 * Plugin URI: https://foxnet-themes.fi/downloads/pullquote-shortcode/
 * Description: Register shortcode [pullquote] and add button to tinyMCE editor.
 * Version: 0.1.2
 * Author: Sami Keijonen
 * Author URI: http://foxnet.fi
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package PullquoteShortcode
 * @version 0.1.2
 * @author Sami Keijonen <sami.keijonen@foxnet.fi>
 * @copyright Copyright (c) 2012, Sami Keijonen
 * @link http://wp.smashingmagazine.com/2012/05/01/wordpress-shortcodes-complete-guide/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
 
 /* Set up the plugin on the 'plugins_loaded' hook. */
add_action( 'plugins_loaded', 'pullquote_shortcode_setup' );

/**
 * Plugin setup function.  Loads actions and filters to their appropriate hook.
 *
 * @since 0.1.0
 */
function pullquote_shortcode_setup() {

	/* Get the plugin directory URI. */
	define( 'PULLQUOTE_SHORTCODE_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

	/* Add shortcode [pullquote]. */
	add_shortcode( 'pullquote', 'pullquote_shortcode_pullquote' );
	
	/* Enable shortcode in text widgets. */
	add_filter( 'widget_text', 'do_shortcode' );
	
	/* Enqueue styles. */
	add_action( 'wp_enqueue_scripts', 'pullquote_shortcode_styles' );
	
	/* Add button to tinyMCE editor. */
	add_action( 'init', 'pullquote_shortcode_button' );

}
 
/**
 * Add shortcode [pullquote] and you can add content inside it like this: [pullquote]This is my content[/pullquote]. 
 *
 * @since 0.1.0
 */
 function pullquote_shortcode_pullquote( $atts, $content = null ) {

	return '<div class="pullquote">' . esc_html( $content ) . '</div>';

}

/**
 * Enqueue styles.
 *
 * @since 0.1.0
 */
function pullquote_shortcode_styles() {

	if ( !is_admin() )
		wp_enqueue_style( 'pullquote-shortcode', PULLQUOTE_SHORTCODE_URI . 'css/pullquote-shortcode.css', false, 0.1, 'all' );
	
}

/**
 * Add pullquote button to tinyMCE editor. 
 *
 * @since 0.1.0
 */
function pullquote_shortcode_button() {

   if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
      return;
   }

   if ( get_user_option( 'rich_editing' ) == 'true' ) {
      add_filter( 'mce_external_plugins', 'pullquote_shortcode_add_plugin' );
      add_filter( 'mce_buttons', 'pullquote_shortcode_register_button' );
   }

}

function pullquote_shortcode_register_button( $buttons ) {

   array_push( $buttons, "|", "pullquote" );
   return $buttons;
   
}

function pullquote_shortcode_add_plugin( $plugin_array ) {

   $plugin_array['pullquote'] = PULLQUOTE_SHORTCODE_URI . 'js/pullquote-button.js';
   return $plugin_array;
   
}

 ?>