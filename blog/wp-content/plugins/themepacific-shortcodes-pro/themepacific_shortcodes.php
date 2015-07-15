<?php
/*
Plugin Name: ThemePacific Shortcodes
Plugin URI: http://themepacific.com/wp-plugins/themepacific-shortcode-pro/
Description: A Pro Shortcode Plugin from ThemePacific
Author: Raja CRN, ThemePacific
Author URI: http://themepacific.com
Version: 1.0.1
Copyright 2013, ThemePacific.
Text Domain: themepacific
@package ThemePacific Shortcodes
@category Core
@author ThemePacific
*/
class themePacific_shortcodes {
  public function __construct() {
 		add_action( 'wp_enqueue_scripts', array( $this, 'themepacific_shortcodes_scripts' ) );
		add_action( 'init', array($this,'themepacific_tinymce_init') );
		add_filter('widget_text', 'do_shortcode');
		add_filter('the_content', array($this,'themepacific_arrange_shortcodes'));
 	}
	
 	// tinymce custom button
	function themepacific_tinymce_init() {
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
			return;		
		if ( get_user_option('rich_editing') == 'true' ) {  
			add_filter( 'mce_external_plugins', array($this, 'themepacific_add_plugin') );  
			add_filter( 'mce_buttons', array($this,'themepacific_register_button') ); 
		}  
    }  
	function themepacific_add_plugin($plugin_array) {  
	   $plugin_array['themepacific_shortcodes'] = plugin_dir_url( __FILE__ ) .'tinymce/js/themepacific_shortcodes_tinymce.js';
	   return $plugin_array; 
	}
	function themepacific_register_button($buttons) {  
	   array_push($buttons, "themepacific_shortcodes_button");
	   return $buttons; 
	} 	
	function themepacific_arrange_shortcodes($content){   
		$array = array (
			'<p>[' => '[', 
			']</p>' => ']', 
			']<br />' => ']'
		);
		$content = strtr($content, $array);
		return $content;
	}
	//scripts and styles
 function themepacific_shortcodes_scripts() {
		wp_enqueue_script('jquery');
 		wp_enqueue_script( 'themepacific_shortcodes_main', plugins_url( 'js/themepacific_shortcodes_main.js', __FILE__ ), array( 'jquery','jquery-ui-accordion','jquery-ui-tabs' ),'1.0', true );
  		wp_register_script('themepacific_googlemap',  plugins_url( 'js/themepacific_googlemap.js', __FILE__ ), array( 'jquery' ) );
		wp_register_script('themepacific_googlemap_api', 'https://maps.googleapis.com/maps/api/js?sensor=false', array('jquery'), '1.0', true);
		wp_enqueue_style('themepacific_shortcode_styles', plugins_url( 'css/themepacific_shortcodes_styles.css', __FILE__ ), array(), '2013-08-15' );
 }
}
include_once( plugin_dir_path( __FILE__ ) . 'themepacific_shortcode_fns.php');
$tpcrn_shortcodes = new themePacific_shortcodes;