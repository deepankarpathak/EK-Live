<?php 
/*
Author: Raja CRN, ThemePacific
Author URI: http://themepacific.com
Version: 1.0
Copyright 2013, ThemePacific.
Text Domain: themepacific
@package ThemePacific Shortcodes 
@category Core
@author ThemePacific
*/
/*===================================================================================*/
/*  DropCap shortcode
/*==================================================================================*/

function themepacific_dropcap_shortcode( $atts, $content = null ) {
				extract( shortcode_atts(array(
 				'color'=>'',
				), $atts));
				
		return '<span class="themepacific_sh_dropcap '.$color.'">'.do_shortcode($content). '</span>';  
	}
	add_shortcode('themepacific_dropcap', 'themepacific_dropcap_shortcode');

/*===================================================================================*/
/*  Button shortcode
/*==================================================================================*/
  
function themepacific_button_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'color' => 'blue',
			'url' => 'http://themepacific.com',
			'title' => 'Visit Site',
			'target' => '_blank',
			'rel' => '',
 			 
 		), $atts ) );
		
		
 		$rel = ( $rel ) ? 'rel="'.$rel.'"' : NULL;
		
		$button = NULL;
		$button .= '<a href="' . $url . '" class="themepacific_sh_button ' . $color . '" target="_'.$target.'" title="'. $title .'" '. $rel .'>';
		$button .= $content;
 		$button .= '</a>';
		return $button;
	}
	add_shortcode('themepacific_button', 'themepacific_button_shortcode');
 


/*===================================================================================*/
/*  TextBox shortcode
/*==================================================================================*/

 
function themepacific_box_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'color' => 'blue',
  			'width' => '100%',
  			'text_align' => 'left',
 		  ), $atts ) );
 		  $box_content = '';
		  $box_content .= '<div class="themepacific_sh_box ' . $color . '  " style="text-align:'. $text_align .'; width:'. $width .';">';
		  $box_content .= ' '. do_shortcode($content) .'</div>';
		  return $box_content;
	}
	add_shortcode('themepacific_box', 'themepacific_box_shortcode');
/*===================================================================================*/
/*  Highlight shortcode
/*==================================================================================*/
  
function themepacific_highlight_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'color' => 'blue',
 		  ),
		  $atts ) );
		  return '<span class="themepacific_sh_highlight '. $color .'">' . do_shortcode( $content ) . '</span>';
	
	}
	add_shortcode('themepacific_highlight', 'themepacific_highlight_shortcode');


 /*===================================================================================*/
/*  Testimonial shortcode
/*==================================================================================*/

function themepacific_testimonial_shortcode( $atts, $content = null  ) {
		extract( shortcode_atts( array(
			'author' => '',
			'pos' => '',			 
			'img_url' => '',			 
		  ), $atts ) );
		$testimonial_content = '';
		$testimonial_content .= '<div class="themepacific_sh_testimonial"><div class="themepacific_sh_testimonial-content">';
		$testimonial_content .= $content;
		$testimonial_content .= '<div class="themepacific_sh_testimonial-author">';
		if(!empty($img_url))$testimonial_content .= '<img src="'.$img_url.'">';
		
		$testimonial_content .= '<div class="info">';
		$testimonial_content .= $author .'</div><div class="pos">'.$pos.'</div></div></div><div class="clear"></div></div>';	
		return $testimonial_content;
	}
	add_shortcode( 'themepacific_testimonial', 'themepacific_testimonial_shortcode' );
  
 /*===================================================================================*/
/*  Accordion shortcode
/*==================================================================================*/

 	function themepacific_accordion_shortcode( $atts, $content = null  ) {
		
		extract( shortcode_atts( array(
 		), $atts ) );
		
 		wp_enqueue_script('jquery-ui-accordion');
 		
 		return '<div class="themepacific_sh_accordion">' . do_shortcode($content) . '</div>';
	}
	add_shortcode( 'themepacific_accordion', 'themepacific_accordion_shortcode' );
 
  	function themepacific_accordion_content_shortcode( $atts, $content = null  ) {
		extract( shortcode_atts( array(
			'title' => 'Title',
 		), $atts ) );
		  
	   return '<h3 class="themepacific_sh_accordion-title"><a href="#">'. $title .'</a></h3><div>' . do_shortcode($content) . '</div>';
	}
	
	add_shortcode( 'themepacific_accordion_section', 'themepacific_accordion_content_shortcode' );
 
/*===================================================================================*/
/*  Tab shortcode
/*==================================================================================*/
 function themepacific_tabgroup_shortcode( $atts, $content = null ) {
		
		wp_enqueue_script('jquery-ui-tabs');
 		$defaults = array();
		extract( shortcode_atts( $defaults, $atts ) );
		preg_match_all( '/tab title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );
		$tab_titles = array();
		if( isset($matches[1]) ){ $tab_titles = $matches[1]; }
		$output = '';
		if( count($tab_titles) ){
		    $output .= '<div id="themepacific_sh_tab-'. rand(1, 100) .'" class="themepacific_sh_tabs">';
			$output .= '<ul class="ui-tabs-nav themepacific_sh_clearfix">';
			foreach( $tab_titles as $tab ){
				$output .= '<li><a href="#themepacific_sh_tab-'. sanitize_title( $tab[0] ) .'"  data-toggle="tab">' . $tab[0] . '</a></li>';
			}
		    $output .= '</ul>';
		    $output .= do_shortcode( $content );
		    $output .= '</div>';
		} else {
			$output .= do_shortcode( $content );
		}
		return $output;
	}
add_shortcode( 'themepacific_tabgroup', 'themepacific_tabgroup_shortcode' );

  	function themepacific_tab_shortcode( $atts, $content = null ) {
		$defaults = array(
			'title' => 'Tab',
			'class' => ''
		);
		extract( shortcode_atts( $defaults, $atts ) );
		return '<div id="themepacific_sh_tab-'. sanitize_title( $title ) .'" class="tab-content '. $class .'">'. do_shortcode( $content ) .'</div>';
	}
	add_shortcode( 'themepacific_tab', 'themepacific_tab_shortcode' );
 
/*===================================================================================*/
/*  Toggle shortcode
/*==================================================================================*/
  	function themepacific_toggle_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title' => 'Toggle Title',
		), $atts ) );
 		return '<div class="themepacific_sh_toggle"><h3 class="themepacific_sh_toggle-title">'. $title .'</h3><div class="themepacific_sh_toggle-container">' . do_shortcode($content) . '</div></div>';
	}
	add_shortcode('themepacific_toggle', 'themepacific_toggle_shortcode');
/*===================================================================================*/
/*  GooogleMap shortcode
/*==================================================================================*/
function themepacific_shortcode_googlemaps($atts, $content = null) {
 		extract(shortcode_atts(array(
				'title' => '',
				'location' => '',
				'width' => '', 
				'height' => '300',
				'zoom' => 8,
		), $atts));
		
 		wp_enqueue_script('themepacific_googlemap');
		wp_enqueue_script('themepacific_googlemap_api');
		
		
		$output = '<div id="map_canvas_'.rand(1, 100).'" class="googlemap" style="height:'.$height.'px;width:100%">';
			$output .= (!empty($title)) ? '<input class="title" type="hidden" value="'.$title.'" />' : '';
			$output .= '<input class="location" type="hidden" value="'.$location.'" />';
			$output .= '<input class="zoom" type="hidden" value="'.$zoom.'" />';
			$output .= '<div class="map_canvas"></div>';
		$output .= '</div>';
		
		return $output;
	   
	}
	add_shortcode("themepacific_googlemap", "themepacific_shortcode_googlemaps");
 
/*===================================================================================*/
/*  Content Dividers shortcode
/*==================================================================================*/
 function themepacific_divider_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'style' => 'solid',
 		  ),
		  $atts ) );
	 return '<hr class="themepacific_sh_divider '. $style .'" />';
	}
	add_shortcode( 'themepacific_divider', 'themepacific_divider_shortcode' );
	
/*===================================================================================*/
/*  Post Columns shortcode
/*==================================================================================*/
 function themepacific_column_shortcode( $atts, $content = null ){
		extract( shortcode_atts( array(
			'width' => 'one-third',
			'pos' =>'first',			 
		  ), $atts ) );
		  return '<div class="themepacific_sh_column themepacific_sh_' . $width . ' themepacific_sh_column-'.$pos.'">' . do_shortcode($content) . '</div>';
	}
add_shortcode('themepacific_column', 'themepacific_column_shortcode');