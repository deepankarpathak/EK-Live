<?php get_header(); ?>

<!-- BEGIN content -->
<div id="content">

	<?php if (have_posts()) : the_post(); ?>
	<!-- begin post -->
	<div class="single">
	<h1 style="color:#2A1FAA"><?php the_title(); ?></h1>
	<?php the_content(); ?>
    <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
    <p><?php the_tags(); ?></p>
	</div>
	<!-- end post -->
	
	<div class="comentary">
	<?php comments_template(); ?>
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