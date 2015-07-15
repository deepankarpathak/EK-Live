<?php
/*******************************************************************
	Plugin Name: Google+ Follow Widget
	Description: Google+ Follow Widget will display your Google+ Follow	 box
	Author: Raja CRN
	Author URI: http://themepacific.com
**********************************************************************/
/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */

add_action('widgets_init', 'bresponZive_themepacific_gpfollow_box');
/**
 * Register our widget.
 * 'Example_Widget' is the widget class used below.
 *
 * @since 0.1
 */

function bresponZive_themepacific_gpfollow_box()

{

	register_widget('bresponZive_themepacific_gpfollow_box_widget');

}
/**
 * bresponZive_themepacific_gpfollow_box_widget  class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 */


class bresponZive_themepacific_gpfollow_box_widget extends WP_Widget {
/**
* Widget setup.
*/
	function bresponZive_themepacific_gpfollow_box_widget()
	{ 			/* Widget settings. */
		$widget_ops = array('classname' => 'tpcrn_gp_follow', 'description' => 'A widget to Display Google+ Followers Box with faces.');
				/* Widget control settings. */
		$control_ops = array('id_base' => 'tpcrn_gp_follow_widget');
				/* Create the widget. */
		$this->WP_Widget('tpcrn_gp_follow_widget', 'ThemePacific: Google+ Follow Box', $widget_ops, $control_ops);
	}
/**
* Display the widget 
*/		
function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$gp_follow_url = $instance['gp_follow_url'];
	 
        echo $before_widget;
		/* Display the widget title if it has*/
		if($title) {
			echo $before_title.$title.$after_title;
		}
 		if($gp_follow_url): ?>
		<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
<div class="gp-box-widget">
		<div class="g-plus" data-width="340" data-href="<?php echo $gp_follow_url; ?>" data-rel="author"></div>
</div>
		<?php endif;
 		echo $after_widget;

	}

/**
* Update the widget settings.
*/	

function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
 		$instance['title'] = strip_tags($new_instance['title']);

		$instance['gp_follow_url'] = $new_instance['gp_follow_url'];

	 
 		return $instance;
 	}
/**
* Displays the widget settings controls on the widget panel.
**/
function form($instance)
	{
	$defaults = array('title' => 'Circle us on Google+', 'gp_follow_url' => '');
 		$instance = wp_parse_args((array) $instance, $defaults); ?>
 		<p>
 			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
 			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
 		</p>

  		<p>
 			<label for="<?php echo $this->get_field_id('gp_follow_url'); ?>">Google+ Page URL:</label>

			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('gp_follow_url'); ?>" name="<?php echo $this->get_field_name('gp_follow_url'); ?>" value="<?php echo $instance['gp_follow_url']; ?>" />
			<small>Ex:https://plus.google.com/u/0/111626044701452949912</small>
 		</p>
 		<p>
 	 
 	<?php
 	}
 }

?>