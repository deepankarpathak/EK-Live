<div id="featured">
	<a id="left-arrow" href="#"><?php esc_html_e('Previous','Aggregate'); ?></a>
	<a id="right-arrow" href="#"><?php esc_html_e('Next','Aggregate'); ?></a>

	<div id="slides">
		<?php global $ids;
		$ids = array();
		$arr = array();
		$i=0;
		
		$featured_cat = get_option('aggregate_feat_cat'); 
		$featured_num = (int) get_option('aggregate_featured_num'); 
	
		if (get_option('aggregate_use_pages') == 'false') query_posts("showposts=$featured_num&cat=".get_cat_ID($featured_cat));
		else {
			global $pages_number;
			
			if (get_option('aggregate_feat_pages') <> '') $featured_num = count(get_option('aggregate_feat_pages'));
			else $featured_num = $pages_number;
			
			query_posts(array
							('post_type' => 'page',
							'orderby' => 'menu_order',
							'order' => 'ASC',
							'post__in' => (array) get_option('aggregate_feat_pages'),
							'showposts' => (int) $featured_num
						));
		} ?>
		<?php if (have_posts()) : while (have_posts()) : the_post();
		global $post; ?>
			<div class="slide">
				<?php
				$width = 958;
				$height = 340;
				$small_width = 95;
				$small_height = 54;
				$titletext = get_the_title();
	
				$thumbnail = get_thumbnail($width,$height,'',$titletext,$titletext,false,'Featured');
				
				$arr[$i]['thumbnail'] = get_thumbnail($small_width,$small_height,'',$titletext,$titletext,false,'Small');
				$arr[$i]['titletext'] = $titletext;
				
				$thumb = $thumbnail["thumb"];
				print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, ''); ?>
				<div class="featured-top-shadow"></div>
				<div class="featured-bottom-shadow"></div>	
				<div class="featured-description">
					<p class="meta-info"><?php esc_html_e('Posted','Aggregate'); ?> <?php esc_html_e('by','Aggregate'); ?> <?php the_author_posts_link(); ?> <?php esc_html_e('on','Aggregate'); ?> <?php the_time(esc_attr(get_option('aggregate_date_format'))) ?></p>
					<h2 class="featured-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p><?php truncate_post(410); ?></p>
					<a href="<?php the_permalink(); ?>" class="readmore"><?php esc_html_e('Read More', 'Aggregate'); ?></a>
				</div> <!-- end .description -->
			</div> <!-- end .slide -->
		<?php $ids[] = $post->ID; $i++; endwhile; endif; wp_reset_query(); ?>
	</div> <!-- end #slides -->
</div> <!-- end #featured -->

<div id="controllers" class="clearfix">
	<ul>
		<?php for ($i = 0; $i < $featured_num; $i++) { ?>
			<li>
				<div class="controller">
					<a href="#"<?php if ( $i == 0 ) echo ' class="active"'; ?>>
						<?php print_thumbnail( $arr[$i]['thumbnail']['thumb'], $arr[$i]['thumbnail']["use_timthumb"], $arr[$i]['titletext'], $small_width, $small_height ); ?>
						<span class="overlay"></span>
					</a>
				</div>	
			</li>
		<?php } ?>
	</ul>
	<div id="active_item"></div>
</div> <!-- end #controllers -->