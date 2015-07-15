<script type="text/javascript">

jQuery(window).load(function() {
		jQuery(function(){
		
		jQuery('.camera_wrap').camera({
				height				: '360px',
				loader				: 'bar',
				loaderColor			: '<?php echo $data['feat_slide_loader']; ?>',
				loaderBgColor		: '#2C2727', 
				loaderOpacity		: 1.0,	 
				loaderPadding		: 0,	 
				loaderStroke		: 4,
				pagination			: false,
				navigation			: true,
				autoAdvance			: true,
 
				easing				: 'easeInOutExpo',
				fx					: '<?php echo $data['feat_slide_trans']; ?>',
				playPause			: false,	//true or false, to display or not the play/pause buttons
				pieDiameter			: 38,
				piePosition			: 'rightTop',	//'rightTop', 'leftTop', 'leftBottom', 'rightBottom'
				rows				: 4,
				slicedCols			: 6,
				slicedRows			: 4,
				opacityOnGrid		: false,
				thumbnails			: false,
				portrait			: false,
				time				: 7000,	//milliseconds between the end of the sliding effect
				transPeriod			: 1500,	//lenght of the sliding effect in milliseconds
			});
		
		
 
		});
	});
</script>
	<!-- .fluid_container-->
<div class="fluid_container clearfix">
   <!-- #camera_wrap_2 -->
  <div class="camera_wrap camera_orange_skin  " id="camera_wrap_2">
 
	<?php
							 if(!$data['feat_slide_category'] == '0') {
							$cat_id = get_cat_ID( $data['feat_slide_category'] );
							$args = array('cat' => $cat_id,'showposts' => 5);
							$flex_posts = new WP_Query($args); 
		   while($flex_posts->have_posts()): $flex_posts->the_post();  
		
		 if(has_post_thumbnail()){  
			$image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
 					<div data-thumb="<?php echo $image[0]; ?>" data-src="<?php echo $image[0]; ?>" data-link="<?php the_permalink(); ?>">
 							<div class="camera_caption moveFromLeft">
  							   	<div class="list-block-slide clearfix">
								<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3> 	
								</div>
 							</div>
					 </div>	
 		 
		    <?php } ?>
					
		  <?php endwhile; ?>
		
		  <?php wp_reset_query();?>
			 
<?php } else {  
    						
			$e_ids=explode(",",$data['page_id_slider']);
			$flex_pageposts = new WP_Query( array('post_type' => array('post','page'), 'post__in' => $e_ids,'orderby' => 'post__in'  ) );

		   while($flex_pageposts->have_posts()): $flex_pageposts->the_post();  
		
		 if(has_post_thumbnail()){  
			$image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
 					<div data-thumb="<?php echo $image[0]; ?>" data-src="<?php echo $image[0]; ?>" data-link="<?php the_permalink(); ?>">
 							<div class="camera_caption moveFromLeft hii">
  							   	<div class="list-block-slide clearfix">
								<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3> 	
								</div>
 							</div>
					 </div>	
 		 
		    <?php } ?>
					
		  <?php endwhile; ?>
		
		  <?php wp_reset_query();?>

<?php } ?>
		  
 
	</div><!-- /#camera_wrap_2 -->
</div>
<!-- /.fluid_container -->

 
<div style="clear:both; display:block;"></div>