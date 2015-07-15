<?php get_header(); ?>

<!-- BEGIN content -->
<div id="content">

	<!-- begin featured news -->
	<div class="featured">
	<!--<h2>Featured</h2>-->
    
    <div class="featlist">
	<div class="highlight"></div>

    
    
	<div id="featured">
	<?php 
	$highcat = get_option('pov_story_category'); 
	$highcount = get_option('pov_story_count');
	
	$my_query = new WP_Query('category_name= '. $highcat .'&showposts='.$highcount.'');
while ($my_query->have_posts()) : $my_query->the_post();$do_not_duplicate = $post->ID;
	?>

	<div class="fblock">
    <div class="thumb">
	<?php $screen = get_post_meta($post->ID,'screen', true); ?>

<!--

<a href="<?php the_permalink(); ?>" rel="bookmark"><img src="<?php echo ($screen); ?>" width="477" height="217" alt=""  /></a>

-->

	</div>




    <div class="auth"><small>

<!-- commenting date + catagory + comment on blog entry

  <?php the_time('F jS, Y') ?> | In <?php the_category(', '); ?> | <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>

-->

</small> </div>
    
	<h3><a style="color:#D45F55" href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>

	<div class="cont"> 	
	<?php the_excerpt(); ?>
    
	</div>


    <div class="readmore">
     <a style="color"#FFFFFF"href="<?php the_permalink() ?>" title="Permanent Link to <?php the_title(); ?>">Read More &raquo;</a> 
    </div>
    <div style="clear: both;"></div>
	</div>
	<?php endwhile; ?>


	<!-- Content Ad Starts -->
    
    <!--
    <div class="contad">
	<?php if (!get_option('pov_ad_content_disable') && !$is_paged && !$ad_shown) { include (TEMPLATEPATH . "/content_ad.php"); $ad_shown = true; } ?>
    </div>
    -->
    
	<!-- Content Ad Ends -->


</div>
 <div class="clear"></div>	
	</div>
	</div>
	<!-- end featured news -->
	
    
    
    
	<?php
	if (have_posts()) :
	$odd = false;
	while (have_posts()) : the_post();
	$odd = !$odd;
	?>
	
	<!-- begin post -->
	<div class="<?php if ($odd) echo 'uneven '; ?>post">
	<div class="uvod">
	<a href="<?php the_permalink(); ?>"><?php dp_attachment_image($post->ID, 'small', 'alt="' . $post->post_title . '"'); ?></a>
    
    <p class="category"><?php the_category(', '); ?></p>
	<p class="comments"><?php comments_popup_link('{0}', '{1}', '{%}'); ?></p>
	</div>
    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    
	<p><?php the_excerpt(); ?></p>
	
    </div>
	<!-- end post -->
	
	<?php endwhile; ?>
	
		<div class="postnav">
		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>
		</div>
	
	<?php else : ?>
	<div class="notfound">
	<h2>Not Found</h2>
	<p>Sorry, but you are looking for something that is not here.</p>
	</div>
	<?php endif; ?>

</div>
<!-- END content -->

<?php get_sidebar(); get_footer(); ?>