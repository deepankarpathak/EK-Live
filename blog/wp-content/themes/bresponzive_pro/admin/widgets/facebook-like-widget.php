<?php
/******************************************************************
	Plugin Name: Faceboook Like Widget							
	Description: Faceboook Like Widget will display your Facebook Fanpage like box
	Author: Raja CRN	
	Author URI: http://themepacific.com
************************************************************************************/
/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */

add_action('widgets_init', 'bresponZive_themepacific_like_box');
/**
 * Register our widget.
 * 'Example_Widget' is the widget class used below.
 *
 * @since 0.1
 */

function bresponZive_themepacific_like_box()

{

	register_widget('bresponZive_themepacific_like_box_widget');

}
/**
 * bresponZive_themepacific_like_box_widget  class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 */


class bresponZive_themepacific_like_box_widget extends WP_Widget {
/**
* Widget setup.
*/
	function bresponZive_themepacific_like_box_widget()
	{ 			/* Widget settings. */
		$widget_ops = array('classname' => 'tpcrn_fb_like', 'description' => 'A Widget to Display Facebook Like Box with faces.');
				/* Widget control settings. */
		$control_ops = array('id_base' => 'tpcrn_fb_like_widget');
				/* Create the widget. */
		$this->WP_Widget('tpcrn_fb_like_widget', 'ThemePacific: Facebook Like Box', $widget_ops, $control_ops);
	}
/**
* Display the widget 
*/		
function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$fb_like_url = $instance['fb_like_url'];
		$width = $instance['width'];		
		$height = $instance['height'];
		$color_scheme = $instance['color_scheme'];
        echo $before_widget;
		/* Display the widget title if it has*/
		if($title) {
			echo $before_title.$title.$after_title;
		}
 		if($fb_like_url){ ?>
<div class="tpcrn-fb-like-box">
		<iframe src="http://www.facebook.com/plugins/likebox.php?href=<?php echo urlencode($fb_like_url); ?>&amp;width=<?php echo $width; ?>&amp;colorscheme=<?php echo $color_scheme; ?>&amp;show_faces=true&amp;stream=false&amp;header=false&amp;height=<?php echo $height; ?>" scrolling="no" frameborder="0" allowTransparency="true"  style="border:none; overflow:hidden; width:<?php echo $width; ?>px; height: <?php echo $height; ?>px;" ></iframe>
</div>
		<?php }
 		echo $after_widget;

	}

/**
* Update the widget settings.
*/	

function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
 		$instance['title'] = strip_tags($new_instance['title']);

		$instance['fb_like_url'] = $new_instance['fb_like_url'];

		$instance['width'] = $new_instance['width'];
		$instance['height'] = $new_instance['height'];

		$instance['color_scheme'] = $new_instance['color_scheme'];
 		return $instance;
 	}
/**
* Displays the widget settings controls on the widget panel.
**/
function form($instance)
	{
	$defaults = array('title' => 'Find us on Facebook', 'fb_like_url' => '', 'width' => '340','height' =>'260', 'color_scheme' => 'light');
 		$instance = wp_parse_args((array) $instance, $defaults); ?>
 		<p>
 			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
 			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
 		</p>

  		<p>
 			<label for="<?php echo $this->get_field_id('fb_like_url'); ?>">Facebook Page URL:</label>

			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('fb_like_url'); ?>" name="<?php echo $this->get_field_name('fb_like_url'); ?>" value="<?php echo $instance['fb_like_url']; ?>" />
			<small>Ex:https://www.facebook.com/themepacific</small>
		</p>
 		<p>
 			<label for="<?php echo $this->get_field_id('width'); ?>">Width:</label>
 			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" value="<?php echo $instance['width']; ?>" />
 		</p>
		<p>
 			<label for="<?php echo $this->get_field_id('height'); ?>">Height:</label>
 			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" value="<?php echo $instance['height']; ?>" />
 		</p>
 		<p>
 			<label for="<?php echo $this->get_field_id('color_scheme'); ?>">Color Scheme:</label> 
 			<select id="<?php echo $this->get_field_id('color_scheme'); ?>" name="<?php echo $this->get_field_name('color_scheme'); ?>" class="widefat" style="width:100%;">
 				<option <?php if ('light' == $instance['color_scheme']) echo 'selected="selected"'; ?>>light</option>
 				<option <?php if ('dark' == $instance['color_scheme']) echo 'selected="selected"'; ?>>dark</option>
 			</select>
 		</p>
 	<?php
 	}
 }

?>