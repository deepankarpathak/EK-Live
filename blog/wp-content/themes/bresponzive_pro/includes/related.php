 <?php
 	  global $data;
	           if (strip_tags($data['re_post_no']) > 9){
					$rp_num = 9;
					}
					else {
						
						$rp_num = strip_tags($data['re_post_no']);
					}
                if ($data['related_posts_tag'] =='category' ) {
						  $categories = get_the_category($post->ID);
                        if ($categories) {
                            $category_ids = array();
                            foreach($categories as $category) $category_ids[] = $category->term_id;

                            $args = array(
                                'category__in' => $category_ids,
                                'post__not_in' => array($post->ID),
                                'posts_per_page' =>  $rp_num,
                            );
                        }
				}
				else {
				
						  $tags = wp_get_post_tags($post->ID);
                        if ($tags) {
                            $tag_ids = array();
                            foreach($tags as $tag) $tag_ids[] = $tag->term_id;

                            $args = array(
                                'tag__in' => $tag_ids,
                                'post__not_in' => array($post->ID),
                                'posts_per_page' =>  $rp_num,
                            );
                        }
				
 				}
				?>
				<div class="tpcrn_related_post clearfix" > <h3 class="tpcrn_r_p"><?php _e('Related Posts', 'bresponZive'); ?></h3><ul class="tpcrn_r_p_blocks">
		<?php	if(!isset($args)){$args='';}	
		if($args){$tpcrn_r_p_loop = new WP_Query( $args );
				

                  
                if ($tpcrn_r_p_loop->have_posts()) { 
                   
					$i=0;
                  while ($tpcrn_r_p_loop->have_posts()) : $tpcrn_r_p_loop->the_post();?>
					
                        <li id="post-<?php the_ID(); ?>" class='tpcrn_r_p_list <?php if($i%3==0){echo "first";}?>' >
							<div class="tpcrn_r_p_thumb">
							<?php if ( has_post_thumbnail() ) { ?>
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'blog-image'); ?>
								<img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>"  /></a> 							
 						<?php } else { ?>
							<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><img  src="<?php echo get_template_directory_uri(); ?>/images/default-image.png" width="234" height="130" alt="<?php the_title(); ?>" /></a>
						<?php } ?>
							</div>
 										 <h3>
										 <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark">
										 <?php the_title(); ?>
										 </a>
										 </h3>
 							 
							       </li>
                     
                    <?php $i++; endwhile;   ?>
                     
                    </ul>
               
                            <?php wp_reset_query();}}
							
						  else { ?>
                    <p><?php _e('No posts found.', 'bresponZive'); ?></p>
                <?php } ?></div> 