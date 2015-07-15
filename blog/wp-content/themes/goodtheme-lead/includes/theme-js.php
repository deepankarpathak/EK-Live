<?php
if (!is_admin()) add_action( 'wp_print_scripts', 'gtt_add_javascript' );
function gtt_add_javascript( ) {
wp_enqueue_script('jquery');

wp_enqueue_script('gtt_tabs', get_bloginfo('template_directory').'/includes/js/gtt_tabs.js', array( 'jquery' ) );
    if(is_home()){
     
    }
}
?>