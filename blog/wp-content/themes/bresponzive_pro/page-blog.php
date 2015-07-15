<?php
/*
Template Name: Blog Page
*/
?>
<?php get_header(); ?>
<!--#blocks-wrapper-->
<div id="blocks-wrapper" class="clearfix">
<!--#blocks-left-or-right-->

	<div id="blocks-left" class="eleven columns clearfix">	
 
 					 <h2 class="blogpost-wrapper-title">Recent Posts </h2>	
							<div class="blog-lists-blog clearfix">
	 <div id="themepacific_infinite" class="blogposts-wrapper clearfix"> 
	<?php $temp = $wp_query; $wp_query= null;
		$wp_query = new WP_Query(); $wp_query->query('showposts=5' . '&paged='.$paged);
		 if (have_posts()) : while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
				 			<div class="blogposts-inner">
<ul>	 		
			<li class="full-left clearfix">					
				<div <?php post_class('magbig-thumb');?>>
						 
						 
					  <?php if ( has_post_thumbnail() ) { ?>
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="post-thumbnail">
								
								<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'mag-image'); ?>
								<img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>"  />
 		 
 							</a>
					  <?php } else { ?>
							<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><img   src="<?php echo get_template_directory_uri(); ?>/images/default-image.png" width="60" height="60" alt="<?php the_title(); ?>" /></a>
						<?php } ?>
						 
   				</div>
						 
				<div class="list-block clearfix">
					<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3> 	
							<div class="post-meta-blog">
					<span class="meta_author"><?php _e('by', 'themepacific'); ?> <?php the_author_posts_link(); ?></span>
					<span class="meta_date"><?php _e('On', 'themepacific'); ?> <?php the_time('F d, Y'); ?></span>
					<span class="meta_comments">  <a href="<?php comments_link(); ?>"><?php comments_number('0 Comment', '1 Comment', '% Comments'); ?></a></span>
					</div>
							
														
					<div class="maga-excerpt clearfix">
					<?php the_excerpt(); ?>
					</div>	
						<div class="themepacific-read-more"><a class="tpcrn-read-more" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">Read More</a>
					</div>	

			</div>
		</li></ul>
<br class="clear" />		</div>		
		
		 
				
				
				
				<?php
				endwhile;
		 ?>
	 </div>				 
		<?php  else: ?>
 				<h2 class="noposts">Sorry, no posts to display!</h2>
	 </div>
 <?php endif;?>
					  
</div>
		<div class="pagination clearfix">
			<?php bresponZive_themepacific_tpcrn_pagination();   ?>
		</div> 
 
   	</div>
 	<!-- /blocks col -->
 <?php get_sidebar();  ?>
 <?php get_footer(); ?>