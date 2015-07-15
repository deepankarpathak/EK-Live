<?php
/*
Plugin Name: Facebook Recommend Button
Plugin URI: http://wordpress.org/extend/plugins/facebook-recommend/
Description: Very Simple light weight plugin to integrate facebook like button on every post
Version: 1.0
Author: Md. Ali Ahsan Rana	
Author URI: http://codesamplez.com
License: GPL2
*/
/*  Copyright 2010  MD. ALI AHSAN RANA  (email : rana_cse_ruet@yahoo.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function the_iframe($url){

 $iframe = "<iframe src='http://www.facebook.com/plugins/like.php?href={$url}&amp;layout=button_count&amp;show_faces=true&amp;width=280&amp;action=recommend&amp;colorscheme=light&amp;height=30' scrolling='no' frameborder='0' style='border:none; overflow:hidden; height:30px' allowTransparency='true'></iframe>";

return $iframe;	
}

function like_fb($content)  {
	//retrieve post id
	$post_id =  get_the_ID();
	//retrieve post url
	$url = get_permalink($post_id);
	//encode the url
	$url = urlencode($url);
	//add like button at the beginning of the content
	
	$content = $content.the_iframe($url,false);
	return $content;
}
//add_action ( 'the_content', 'fb_like_home');
add_filter('the_content', 'like_fb'); 
add_filter('the_excerpt', 'like_fb'); 



