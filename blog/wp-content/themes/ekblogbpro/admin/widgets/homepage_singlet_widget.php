<?php
/*****************************************************
Plugin Name: Magazine single thumb Widget
Description: Show Magazine blog Single Thumb Posts.To display your selected category posts, recent posts in Homepage .
Author:	RAJA CRN																					         
Author URI: http://themepacific.com																				
****************************************************************/

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
 
add_action('widgets_init', 'trendify_themepacific_magazine_singlethumb_widgets');
function trendify_themepacific_magazine_singlethumb_widgets(){
 register_widget('trendify_themepacific_magazine_singlethumb_Widget');
 }
 
/**
 * Example Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */

class trendify_themepacific_magazine_singlethumb_Widget extends WP_Widget {
	function trendify_themepacific_magazine_singlethumb_Widget()
	{
		$widget_ops = array('classname' => 'tpcrn_magazine_singlethumb', 'description' => 'Show Magazine blog Single Column Posts.(For Homapage only)');
		$control_ops = array('id_base' => 'tpcrn_magazine_singlethumb-widget');
		$this->WP_Widget('tpcrn_magazine_singlethumb-widget', 'ThemePacific: Magazine 1 column Thumb', $widget_ops, $control_ops);
 	}
/**
* Display the widget
*/	
 	function widget($args, $instance)
 	{
 		extract($args);
		global $post;
  		$title = $instance['title'];
		$posts = $instance['posts'];
 		$get_catego = $instance['get_catego'];
 		echo $before_widget;
 		?>
		<div class="head-span-lines"></div>
<h2 class="blogpost-wrapper-title"><a href="<?php echo get_category_link($get_catego); ?>"><?php  if($title) echo $title;  else  echo get_cat_name($get_catego);?></a> </h2>	

<div class="blog-lists full ">
 
 			<?php
 			$magazine_sing_posts = new WP_Query(array(
 				'showposts' => $posts,
 				'cat' => $get_catego,
 			));
 			$count = 1;
 			?>
  <ul>
    <?php while($magazine_sing_posts->have_posts()): $magazine_sing_posts->the_post(); ?>
 		<?php if($count == 1): ?>
 		<li class="blog-list-big full-left one-third column first">
 				<div class="sb-post-big-thumbnail">
						 
						 
					  <?php if ( has_post_thumbnail() ) { ?>
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="post-thumbnail">
								
								<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'sb-post-big-thumbnail'); ?>
								<img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>"  />
 		 
 							</a>
					  <?php } else { ?>
							<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><img   src="<?php echo get_template_directory_uri(); ?>/images/default-image.png" width="60" height="60" alt="<?php the_title(); ?>" /></a>
						<?php } ?>
						 
   				</div>
						 
			<div class="blog-lists-title">   
								 <h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3> 
								 <div class="time clearfix">
										<span class="meta_author"><?php the_author_posts_link(); ?></span>
										<span class="meta_date">+ <?php the_time('F d, Y'); ?></span>
										<span class="meta_comments">+ <a href="<?php comments_link(); ?>"><?php comments_number('0 Comment', '1 Comment', '% Comments'); ?></a></span>
								 </div>
									 
									</div> 
									<div class="maga-excerpt clearfix">
									<?php the_excerpt(); ?>
									</div>
									<div class="themepacific-read-more"><a class="tpcrn-read-more" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php _e('Read More', 'bresponZive'); ?></a>
									</div>
         </li>
 				<?php else: ?>
     	<li class="full-right one-third column blog-list-small <?php if($count == 2) echo "second"; ?> ">
 			
			<div class="sb-post-thumbnail">
 				<?php if ( has_post_thumbnail() ) { ?>
											
							<a class="post-thumbnail" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'sb-post-thumbnail'); ?>
								<img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>"  /></a>
				<?php } else { ?>
							<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><img  src="<?php echo get_template_directory_uri(); ?>/images/default-image.png" width="60" height="60" alt="<?php the_title(); ?>" /></a>
						<?php } ?>
 			</div>
   			
			<div class="blog-lists-title">
				 
					<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
					</h3><div class="time clearfix">
														<span class="date"><?php the_time('F d, Y'); ?></span>
														</div>
 									
 			</div>
			
     	</li>	
		 							
 				<?php endif; ?>
 				<?php $count++; endwhile; wp_reset_query();?>
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
 		$instance['title'] = $new_instance['title'];
		$instance['posts'] = $new_instance['posts'];
 		$instance['get_catego'] = $new_instance['get_catego'];
 		return $instance;
 	}
	/* Widget form*/
	
 	function form($instance)
 	{
 		$defaults = array('title' => 'Recent Posts', 'posts' =>'5','get_catego' => 'all');
 		$instance = wp_parse_args((array) $instance, $defaults); ?>
 		<p>
 			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
 			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
 		</p>
 		<p>
 			<label for="<?php echo $this->get_field_id('get_catego'); ?>">Filter by Category:</label> 
 			<select id="<?php echo $this->get_field_id('get_catego'); ?>" name="<?php echo $this->get_field_name('get_catego'); ?>" class="widefat get_catego" style="width:100%;">
 				<option value='all' <?php if ('all' == $instance['get_catego']) echo 'selected="selected"'; ?>>Select Categories</option>
 				<?php $get_catego = get_categories('hide_empty=0&depth=1&type=post'); ?>
 				<?php foreach($get_catego as $category) { ?>
 				<option value='<?php echo $category->term_id; ?>' <?php if ($category->term_id == $instance['get_catego']) echo 'selected="selected"'; ?>><?php echo $category->cat_name; ?></option>
 				<?php } ?>
 			</select>
 		</p>
		<p>
 			<label for="<?php echo $this->get_field_id('posts'); ?>">Number of posts to show:</label>
 			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" value="<?php echo $instance['posts']; ?>" />
 		</p>
 	<?php }
 }
 ?>