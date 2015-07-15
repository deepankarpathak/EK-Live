<?php
/************************************************************
Plugin Name: Author Login Widget												
Description: The Widget will display Author Login Form .
Author: ThemePacific Team
Author URI: http://themepacific.com
************************************************************************************/
/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action('widgets_init', 'bresponZive_themepacific_login_widget' );
 /**
 * Register our widget.
 * 'Example_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function bresponZive_themepacific_login_widget() {
	register_widget( 'bresponZive_themepacific_wplogin_widget' );
}
/**
 * Example Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class bresponZive_themepacific_wplogin_widget extends WP_Widget {

	function bresponZive_themepacific_wplogin_widget() {
		$widget_ops = array( 'classname' => 'tpcrn_login_sb_widget'  );
 		$this->WP_Widget( 'tpcrn_login_sb_widget','ThemePacific: Author Login', $widget_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );

			$title = $instance['title'];
 			echo $before_widget;
			echo $before_title;
			echo $title ; 
			echo $after_title;
			bresponZive_wp_login_form();
			
			echo $after_widget;
		
			
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
  		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title' =>__('Author Login' , 'bresponZive')  );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title : </label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name('title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>



	<?php
	}
}
?>