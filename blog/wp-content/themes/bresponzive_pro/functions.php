<?php
/*
Author: RAJA CRN
URL: ThemePacific.com
*/

/*===================================================================================*/
/*  Load ThemePacific FrameWork Assets
/*==================================================================================*/

		define('TPACIFIC_DIR', get_template_directory());
		define('TPACIFIC_URI', get_template_directory_uri());	    
		define('TPACIFIC_ADMIN', TPACIFIC_DIR . '/admin');
		define('TPACIFIC_ADMINURI', TPACIFIC_URI . '/admin');          
		define('TPACIFIC_JS', TPACIFIC_URI . '/js'); 
		define('TPACIFIC_CSS', TPACIFIC_URI . '/css');
		define('TPACIFIC_IMG', TPACIFIC_URI . '/images');
  		define('TPACIFIC_WIDGET', TPACIFIC_ADMIN . '/widgets');
 		include_once (TPACIFIC_ADMIN.'/index.php');
 		include_once (TPACIFIC_DIR.'/custom_fn/tpcrn_functions.php');

$themename="bresponZive";
$themefolder = "bresponZive";

define ('theme_name', $themename );
define ('theme_ver' , 1  );
 
// Notifier Info
$notifier_name = $themename;
$notifier_url = "http://demo.themepacific.com/xml/".$themefolder.".xml";


 
// Constants for the theme name, folder and remote XML url
define( 'MTHEME_NOTIFIER_THEME_NAME', $themename );
define( 'MTHEME_NOTIFIER_THEME_FOLDER_NAME', $themefolder );
define( 'MTHEME_NOTIFIER_XML_FILE', $notifier_url );
define( 'MTHEME_NOTIFIER_CACHE_INTERVAL', 1 );
include (TPACIFIC_ADMIN . '/notifier/update-notifier.php');
 
/*===================================================================================*/
/* Theme Support
/*==================================================================================*/

/*-- Post thumbnail + Menu Support + Formats + Feeds --*/
function bresponZive_themepacific_theme_support_image()
{
 
 		add_theme_support('post-thumbnails' );
		add_image_size('mag-image', 340, 160,true);
 		add_image_size('blog-image', 220, 180,true);		
		add_image_size('sb-post-thumbnail', 70, 70,true);
		add_image_size('sb-post-big-thumbnail', 365, 180,true);
		add_theme_support( 'automatic-feed-links' );
 		load_theme_textdomain( 'bresponZive', get_template_directory() . '/languages' );

 			register_nav_menus(
			array(
 				'topNav' => __('Top Menu','bresponZive' ),
 				'mainNav' => __('Cat Menu','bresponZive' ),
			)		
		);
	
 }
 add_action( 'after_setup_theme', 'bresponZive_themepacific_theme_support_image' );

 add_theme_support( 'infinite-scroll', array(
	'type'           => 'click',
    'container'  => 'themepacific_infinite',
    'footer'     => 'false',
) );
/*===================================================================================*/
/* Functions
/*==================================================================================*/

/*-- Load Custom Theme Scripts using Enqueue --*/
function bresponZive_themepacific_tpcrn_scripts_method() {
	if ( !is_admin() ) {
			global $data;
		global $tpcrn_typo_options;
		$sys_fonts = array('default', 'arial','trebuchet','Times New Roman','verdana','georgia','tahoma','palatino','helvetica');$foi=0;
		foreach( $tpcrn_typo_options as $name => $value){
			$theme_fonts[] = $value['face'];
		  }
			$theme_fonts_uValue=array_diff($theme_fonts,$sys_fonts); 
			$theme_gfonts = array_unique($theme_fonts_uValue);
		foreach($theme_gfonts as $theme_gfont){
			 $g_web_font = 'http://fonts.googleapis.com/css?family='.str_replace(' ', '+', $theme_gfont).'';
			 wp_enqueue_style( 'google-web-font'.$foi.'', ''.$g_web_font.'', array(), NULL);
			 $foi++; 
		   }
        wp_enqueue_style( 'style', get_stylesheet_uri());  		
 		wp_enqueue_style('camera', get_stylesheet_directory_uri().'/css/camera.css');
		wp_enqueue_style('skeleton', get_stylesheet_directory_uri().'/css/skeleton.css');
  
  		wp_register_script('easing', get_template_directory_uri(). '/js/jquery.easing.1.3.js'); 
  		wp_register_script('ticker', get_template_directory_uri(). '/js/jquery.ticker.js'); 
  		wp_register_script('jquery.mobilemenu.min', get_template_directory_uri(). '/js/jquery.mobilemenu.min.js'); 
 		wp_register_script('themepacific.script', get_template_directory_uri(). '/js/tpcrn_scripts.js', array('jquery'), '1.0', true); 	
 		wp_register_script('camera', get_template_directory_uri(). '/js/camera.min.js',array('jquery'), '2.0',false); 			wp_register_script('jquery.backstretch.min', get_template_directory_uri(). '/js/jquery.backstretch.min.js'); 
	
  		wp_register_script('jquery.mobile.customized.min', get_template_directory_uri(). '/js/jquery.mobile.customized.min.js',array('jquery'), '2.0',false); 
		   $protocol = is_ssl() ? 'https' : 'http';
		$query_args = array(
		   'family' => 'Oswald|PT+Sans|Open+Sans',
 		  
		);

		wp_enqueue_style('google-webfonts',
        add_query_arg($query_args, "$protocol://fonts.googleapis.com/css" ),
        array(), null);
		wp_enqueue_script('jquery');
		wp_enqueue_script('camera');
		wp_enqueue_script('jquery.mobile.customized.min');			
    	wp_enqueue_script('jquery-ui-widget');	
		wp_enqueue_script('jquery.backstretch.min');
  		wp_enqueue_script('jquery.mobilemenu.min');
  		wp_enqueue_script('easing');
  		wp_enqueue_script('ticker');
		wp_enqueue_script('themepacific.script');
 	
	}

}
 /*-----------------------------------------------------------------------------------*/
/* Register sidebars
/*-----------------------------------------------------------------------------------*/
 function bresponZive_themepacific_widgets_init() {

 	register_sidebar(array(
		'name' => 'Default Sidebar',
		'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-head">',
		'after_title' => '</h3>',
	));
	
	register_sidebar(array(
		'name' => 'Magazine Style Widgets',
		'before_widget' => '<div id="%1$s" class="%2$s blogposts-wrapper clearfix">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
 
	
	register_sidebar(array(
		'name' => 'Footer Block 1',
		'before_widget' => '<div id="%1$s" class="%2$s widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
	
	register_sidebar(array(
		'name' => 'Footer Block 2',
		'before_widget' => '<div id="%1$s" class="%2$s widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
	
	register_sidebar(array(
		'name' => 'Footer Block 3',
		'before_widget' => '<div id="%1$s" class="%2$s widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
	register_sidebar(array(
		'name' => 'Footer Block 4',
		'before_widget' => '<div id="%1$s" class="%2$s widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
}
add_action( 'widgets_init', 'bresponZive_themepacific_widgets_init' );

/*===================================================================================*/
/*  Actions + Filters + Translation
/*==================================================================================*/

 /*-- Add Custom Styles--*/ 
add_action('wp_head', 'trendify_themepacific_wp_head');
 
/*-- Multiple Page Nav tweak --*/		
add_filter('wp_link_pages_args','bresponZive_themepacific_single_split_page_links');
 
/*-- Register and enqueue  javascripts--*/
add_action('wp_enqueue_scripts', 'bresponZive_themepacific_tpcrn_scripts_method');
add_action( 'bresponZive_themepacific_tpcrn_cre_def_call', 'bresponZive_themepacific_tpcrn_cre_def');
?>