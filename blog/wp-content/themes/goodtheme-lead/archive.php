<?php get_header(); ?>

<!-- BEGIN content -->
<div id="content">

<div id="contarch">	
	<!-- Content Ad Starts -->
    
    
    <!--
    <div class="contadarchiv">
	<?php if (!get_option('pov_ad_content_disable') && !$is_paged && !$ad_shown) { include (TEMPLATEPATH . "/content_ad.php"); $ad_shown = true; } ?>
    </div>
    -->
    
	<!-- Content Ad Ends -->


    
    
    
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
</div>
<!-- END content -->

<?php get_sidebar(); get_footer(); ?>
