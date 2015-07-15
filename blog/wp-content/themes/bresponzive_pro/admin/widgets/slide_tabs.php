<?php
/*************************************************
Plugin Name: Tabs Slide Widget
Description: Tabbed Content Widget will display your popular posts, recent posts, Tags in a tabbed format, in your sidebar .
Author: RAJA CRN
Author URI: http://themepacific.com
******************************************************************/
/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
 add_action( 'widgets_init', 'trendify_themepacific_tabs_slide_widget' );
 /**
 * Register our widget.
 * 'Example_Widget' is the widget class used below.
 *
 * @since 0.1
 */
  function trendify_themepacific_tabs_slide_widget() {
	register_widget( 'trendify_themepacific_tabs_slide_widget' );
}
/**
 * Example Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class trendify_themepacific_tabs_slide_widget extends WP_Widget {
  function trendify_themepacific_tabs_slide_widget() {
  $widget_ops = array('classname' => 'tpcrn_tabs_slide_widget','description' => __( 'Stylish Tabbed Widget to dispaly Popular and Recent Posts,tags', 'bresponZive' ));
  $this->WP_Widget('tpcrn-tabs-widget', __( 'ThemePacific : Tabbed Widget', 'bresponZive' ), $widget_ops);	
	}
	
/**
* Display the widget
*/	
  	function widget( $args, $instance ) {
		extract($args);
 		$getnumpost = $instance['getnumpost'];
		?>
 	 <!-- Begin SLIDE TAB -->
		
<div id="popular-rec" class="sidebar-widget">
  <div class="tabs-wrapper2 clearfix">
<ul class="tabs2 clearfix">
<li class="disable"><a href="#"><?php _e('Popular', 'bresponZive'); ?></a></li>
<li class="disable "><a href="#"><?php _e('Recent', 'bresponZive'); ?></a></li>
<li class="disable "><a href="#"><?php _e('Tags', 'bresponZive'); ?></a></li>
</ul> 
<div class="tabs_container2 clearfix">
 <!-- Begin popular posts -->

<div id="tab1" class="tab_content2" style="display: none; ">
	<ul id="popular" class="sb-tabs-wrap">
				<?php global $post;$tpcrn_popularposts = new WP_Query('orderby=comment_count&ignore_sticky_posts=1&showposts='.$getnumpost );
					while ($tpcrn_popularposts->have_posts()) : $tpcrn_popularposts->the_post(); ?>								
					<li>
											<div class="sb-post-thumbnail">

 						<?php if ( has_post_thumbnail() ) { ?>
							 <a class="post-thumbnail" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'sb-post-thumbnail'); ?>
								<img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>"  /></a> 
						<?php } else { ?>
							<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><img  src="<?php echo get_template_directory_uri(); ?>/images/default-image.png" width="60" height="60" alt="<?php the_title(); ?>" /></a>
						<?php } ?>
						</div>
					 <div class="sb-post-list-title">

 							<h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
							<div class="sb-post-meta">
							<p><?php the_time('j M \'y'); ?></p>
 							</div>
					</div>
   					</li>
				<?php endwhile; wp_reset_query(); ?>

				</ul>


 </div>
 <!-- Begin recent posts -->
<div id="tab2" class="tab_content2" style="display: none; ">

				<ul id="recent" class="sb-tabs-wrap">
					<?php $tpcrn_recentposts = new WP_Query('orderby=post_date&order=DESC&ignore_sticky_posts=1&showposts='.$getnumpost );
					while ( $tpcrn_recentposts -> have_posts() ) : $tpcrn_recentposts -> the_post(); ?>
					<li>
						<div class="sb-post-thumbnail">
						<?php if ( has_post_thumbnail() ) { ?>
							 <a class="post-thumbnail" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'sb-post-thumbnail'); ?>
								<img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>"  /></a> 
						<?php } else { ?>
							<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><img   src="<?php echo get_template_directory_uri(); ?>/images/default-image.png" width="60" height="60" alt="<?php the_title(); ?>" /></a>
						<?php } ?>
						</div>
						<div class="sb-post-list-title">
							<h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
							<div class="sb-post-meta">
							<p><?php the_time('j M \'y'); ?></p>
 							</div>
						</div>							
					</li>
					<?php endwhile; wp_reset_query(); ?>
				</ul> 		
  </div>
  	<!-- End recent posts -->
<div id="tab3" class="tab_content2" style="display: none; ">
<div id="tagtab" class="sb-tabs-wrap tagcloud">
 <?php wp_tag_cloud( $args = array('largest' => 8,'number' => 25,'orderby'=> 'count', 'order' => 'DESC' )); ?>
</div>
</div>
	<!-- End tags-->
  
  

</div><!-- End tab container -->
</div><!-- End tab Wrapper -->

		 </div><!-- End tab widget -->
		
		<?php		

	}
	
/**
	 * Update the widget settings.
	 */	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['getnumpost'] = strip_tags( $new_instance['getnumpost']);
 		return $instance;
	}
	
	// Widget form
	
	function form( $instance ) {

				$defaults = array( 'getnumpost' => '3');
   				$instance = wp_parse_args( (array) $instance, $defaults ); ?>
	
 		
		<p><?php _e('To Show Recent Posts and Popular Posts in Stylish Tabbed Content Format','bresponZive'); ?></p>
		
		<p>
			<label for="<?php echo $this->get_field_id('getnumpost'); ?>"><?php _e('Number of Posts to Show:','bresponZive'); ?></label>
			<input id="<?php echo $this->get_field_id('getnumpost'); ?>" type="text" name="<?php echo $this->get_field_name('getnumpost'); ?>" value="<?php echo $instance['getnumpost'];?>"  maxlength="2" size="3" /> 
		</p>
		<?php
			
	}	

}
?>