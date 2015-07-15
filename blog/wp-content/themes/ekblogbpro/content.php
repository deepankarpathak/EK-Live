<div class="blogposts-inner">
		<ul>	 		
			<li class="full-left clearfix">					
				<div class='magbig-thumb'>
						 
						 
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
					<span class="meta_author"><?php _e('by', 'bresponZive'); ?> <?php the_author_posts_link(); ?></span>
					<span class="meta_date"><?php _e('On', 'bresponZive'); ?> <?php the_time('F d, Y'); ?></span>
					<span class="meta_comments">  <a href="<?php comments_link(); ?>"><?php comments_number('0 Comment', '1 Comment', '% Comments'); ?></a></span>
					</div>
							
														
					<div class="maga-excerpt clearfix">
					<?php the_excerpt(); ?>
					</div>	
						<div class="themepacific-read-more"><a class="tpcrn-read-more" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php _e('Read More', 'bresponZive'); ?></a>
					</div>	

			</div>
		</li></ul>
	<br class="clear" />		
</div>
