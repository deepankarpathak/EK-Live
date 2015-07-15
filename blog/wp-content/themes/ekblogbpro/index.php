<?php get_header(); ?>
<!--#blocks-wrapper-->
<div id="blocks-wrapper" class="clearfix">
<!--#blocks-left-or-right-->

	<div id="blocks-left" class="eleven columns clearfix">	
 	
			<?php
				$hb_layout = $data['homepage_blocks_content']['enabled'];

				if ($hb_layout):

				foreach ($hb_layout as $key=>$value) {

					switch($key) {
					 case 'hb_big_slider':
					 
					  if($data['offline_feat_slide'] != "0") { include_once('includes/flex-slider.php'); }
 
				     break;
 					 case 'hb_nor_blog':
					?>
 					 <h2 class="blogpost-wrapper-title"><?php _e('Recent Posts', 'bresponZive'); ?></h2>	
							<?php include_once('includes/blog_loop.php');?>
							
							<?php
					 break;
					 case 'hb_mag_1':
					  if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Magazine Style Widgets)')) 
						break;	
					}

				}

				endif;						
						?>
   	</div>
 	<!-- /blocks col -->
 <?php get_sidebar();  ?>
 <?php get_footer(); ?>