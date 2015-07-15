<?php get_header(); ?>

<!-- BEGIN content -->
<div id="content">

	<?php
	if (have_posts()) :
	while (have_posts()) : the_post(); 
	?>
    
    
	<!-- begin post -->
	<div class="single">
	<h2><?php the_title(); ?></h2>
	<?php the_excerpt(); ?>
    <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
    <p><?php the_tags(); ?></p>
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
