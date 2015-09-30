<?php
/*
Template name: Main Category
*/
get_header(); ?>

<?php if( has_excerpt() ) { ?>
<div class="page-header">
	<?php the_excerpt(); ?>
</div>
<?php } ?>

<div  class="page-wrapper">
	<div class="row">
		<div id="content" class="large-12 columns" role="main">
			<?php if ( function_exists('yoast_breadcrumb') ) {
			  yoast_breadcrumb('<p id="breadcrumbs">','</p>');
			}
			?>
			<?php // get_field("institute") is used to get value in variable?>
			<div class="large-4 columns">
				<div class="main-cat-institutes">
					<h2>INSTITUTE</h2>
					<?php the_field('institutes'); ?>
				</div>	
				<div class="main-cat-specializations">
					<h2>SPECIALIZATIONS</h2>
					<?php the_field('specializations'); ?>
				</div>
				<div class="main-cat-video">
				    <iframe width="310" height="250" src="<?php the_field('video'); ?>" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>

			<div class="large-8 columns">
				<div class="page_name">
					<h1 style="margin-bottom:10px; text-transform: uppercase; font-weight:bold"><?php echo get_the_title(); ?></h1></div>
				<?php the_field('slider'); ?>
				<div class="featured_courses">
					<h1 style="display:inline-block; margin-right:10px">Featured Courses</h1><a href="<?php the_field('featured_courses_see_all_link'); ?>" style="font-weight:bold">See All</a>
					<div class="course_slider" style="background:#EBEBEB; padding:10px 0px"> 
						<?php the_field('featured_courses');?>
					</div>
				</div>

				<div class="explore_categories">
					<h1 style="display:inline-block; margin-right:10px">Explore Categories</h1><a href="<?php the_field('explore_categories_see_all_link'); ?>" style="font-weight:bold">See All</a>
					<div class="explore_categories_gallery"> 
						<?php the_field('explore_categories');?>
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
