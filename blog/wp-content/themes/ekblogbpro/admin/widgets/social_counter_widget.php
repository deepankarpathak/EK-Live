<?php
/*****************************************************************
Plugin Name: Social Subscriber Count Widget	
Description: Tweets Widget will display your Latest Tweets in your sidebar.
Author: ThemePacific Team
Author URI: http://themepacific.com
*********************************************************/
/**
 * Add function to widgets_init that'll load our widget.
  */
add_action('widgets_init', 'bresponZive_themepacific_social_count_subs_widget');
/**
 * Register our widget.
 * 'bresponZive_themepacific_social_count_subs_widget' is the widget class used below.
  */
function bresponZive_themepacific_social_count_subs_widget()
{
	register_widget('bresponZive_themepacific_social_subscount_Widget');
}
/**
 * bresponZive_themepacific_social_count_subs_widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 
 */
class bresponZive_themepacific_social_subscount_Widget extends WP_Widget {
/**
* Widget setup.
*/
function bresponZive_themepacific_social_subscount_Widget()
	{
		/* Widget settings. */
		$widget_ops = array('classname' => 'tpcrn_social_subscount', 'description' => 'Show number of RSS subscribes, twitter followers and facebook fans.');
		/* Widget control settings. */
		$control_ops = array('id_base' => 'tpcrn_social_subscount-widget');
		/* Create the widget. */
		$this->WP_Widget('tpcrn_social_subscount-widget', 'ThemePacific: Social Counter', $widget_ops, $control_ops);
    }
/**
* Display the widget 
*/		
function widget($args, $instance){
		extract($args);
		/* Our Arguments in widget settings. */

		$title = apply_filters('widget_title', $instance['title']);

		$Facebook_Page_ID = $instance['fb_id'];

		$twitter_id = $instance['twitter_id'];
		
 		$feedb_url = $instance['feedb_url'];

		echo $before_widget;
		
	/* Display the widget title if it has*/

		if($title) {

			echo $before_title.$title.$after_title;

		}
 		
 ?><div class="tpcrn-soc-counter">
			<ul class="tpcrn-soc-widget">
			<?php if( $feedb_url ): ?>
				<li class="tpcrn-soc-rss">
					<a href="<?php echo $feedb_url ?>" target="_blank">
						<span class="tpcrn-soc-img"></span>
 						<span><?php _e('Subscribe' , 'bresponZive' ) ?><?php __('Subscribers' , 'bresponZive' ) ?></span>
						<span><?php _e('Feed' , 'bresponZive' ) ?></span>
					</a>
				</li>
			<?php endif; ?>
			<?php if( $twitter_id ):
					$twitter = bresponZive_twitter_follow_count($twitter_id); ?>
				<li class="tpcrn-soc-twitter">
					<a href="http://www.twitter.com/<?php echo $twitter_id; ?>" target="_blank">
					<span class="tpcrn-soc-img"></span>
 						<span><?php _e('Followers' , 'bresponZive' ) ?></small>
						<span><?php echo $twitter['followers_count']; ?></span>
 						
					</a>
				</li>
			<?php endif; ?>
			<?php if( $Facebook_Page_ID ):
						 
			$facebook_like = 'http://graph.facebook.com/'.$Facebook_Page_ID;
			$facebook_data      = get_data_fb($facebook_like,$Facebook_Page_ID);
?>
 				<li class="tpcrn-soc-facebook">
					<a href="http://www.facebook.com/<?php echo $Facebook_Page_ID; ?>" target="_blank">
						<span class="tpcrn-soc-img"></span>
 						<span><?php _e('Likes' , 'bresponZive' ) ?></small>
						<span><?php echo $facebook_data; ?></span>
					</a>
				</li>
			<?php endif; ?>
		 
 			</ul>
			</div>

		<?php
		echo $after_widget;
		
	}
/**
* Update the widget settings.
*/	
function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['fb_id'] = $new_instance['fb_id'];
		$instance['twitter_id'] = $new_instance['twitter_id'];
 		$instance['feedb_url'] = $new_instance['feedb_url'];
		return $instance;
	}
/**
* Displays the widget settings controls on the widget panel.
**/ 
function form($instance)
	{
		$defaults = array('title' => '', 'fb_id' => 'bresponZive', 'twitter_id' => 'bresponZive', 'feedb_url' => '');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('fb_id'); ?>">Facebook ID:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('fb_id'); ?>" name="<?php echo $this->get_field_name('fb_id'); ?>" value="<?php echo $instance['fb_id']; ?>" />
		<small>Enter your Facebook ID Only* (Number ID or Unique URL ID)</small>
		</p>
     	<p>
			<label for="<?php echo $this->get_field_id('twitter_id'); ?>">Twitter ID:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('twitter_id'); ?>" name="<?php echo $this->get_field_name('twitter_id'); ?>" value="<?php echo $instance['twitter_id']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('feedb_url'); ?>">Feedburner URL:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('feedb_url'); ?>" name="<?php echo $this->get_field_name('feedb_url'); ?>" value="<?php echo $instance['feedb_url']; ?>" />
			<small>Enter your Full Link </small>
		</p>
 
	<?php }
}
?>