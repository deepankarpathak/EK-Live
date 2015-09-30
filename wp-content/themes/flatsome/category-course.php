<?php
/*
Template name: Category Course
*/
get_header(); ?>

<?php if( has_excerpt() ) { ?>
<div class="page-header">
	<?php the_excerpt(); ?>
</div>
<?php } ?>

<div  class="page-wrapper main-category">
<div class="row">
<div id="content" role="main">
<?php if ( function_exists('yoast_breadcrumb') ) {
  yoast_breadcrumb('<p id="breadcrumbs">','</p>');
}
?>
<?php // get_field("institute") is used to get value in variable?>
<div class="large-4 left-panel columns">
	<div class="main-cat-institutes">
		<h2>INSTITUTE</h2>
		<?php the_field('institutes'); ?>
	</div>	
	<div class="main-cat-specializations">
		<h2>SPECIALIZATIONS</h2>
		<?php the_field('specializations'); ?>
	</div>
	<div class= "expert-form">
		<ins class="connecto-notification" style="display:block" data-connecto-slot="course-form-slot">
		</ins>
	</div>
	<div class="main-cat-video">
	    <iframe width="310" height="250" src="<?php the_field('video'); ?>" frameborder="0" allowfullscreen></iframe>
	</div>
</div>

<div class="large-8 right-panel columns">
	<div class="page_name">
		<h2 ><?php echo get_the_title(); ?></h2></div>
	<?php the_field('slider'); ?>
	<div class="explore_courses">
		<h2 >Explore Courses</h2><a href="<?php the_field('explore_courses_see_all_link'); ?>" >See All</a>
		<div class="explore_courses"> 
			<?php the_field('explore_courses');?>
		</div>
	</div>
</div>
<div class="page_description">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php the_content(); ?>
	<?php endwhile; ?> 
</div>

</div><!-- #content -->
</div><!-- .row  -->
</div><!-- .page-wrapper -->

<?php get_footer(); ?>
<script>
$(".explore_categories_gallery").find($("img")).each(function(){
	var link_id = $(this).attr('aria-describedby');
	if(link_id != undefined){
		var link = $("#"+link_id).text();
		$(this).css("cursor", "pointer");
		$("#"+link_id).hide();
		$(this).click(function() {
   		   window.location = link;
		});
	}
});
</script>
