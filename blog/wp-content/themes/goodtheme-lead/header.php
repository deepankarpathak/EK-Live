<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">

<!-- BEGIN html head -->
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
<?php global $options;
foreach ($options as $value) {
if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); } }
      ?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/style-<?php echo $pov_color; ?>.css" type="text/css" media="screen" />
<?php if (function_exists('wp_enqueue_script') && function_exists('is_singular')) : ?>
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php endif; ?>
<?php wp_head(); ?>

<!--[if lte IE 6]>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/ie.css" />
<![endif]-->

</head>
<!-- END html head -->

<body>

<div id="all">



		

	<!-- BEGIN header -->
	<div id="header">
		
        
        <!-- begin pages -->
		<div id="menu">
		<ul id="nav">
		<?php if (is_home()) { ?>
            <li class="current_page_item"><a href="<?php echo get_option('home'); ?>">Home</a></li>
        <?php } else { ?>
           <li><a href="<?php echo get_option('home'); ?>">Home</a></li>
        <?php } ?>    
        <?php wp_list_pages('title_li=&depth=2&sort_column=menu_order'); ?>
	</ul>
		</div>
		<!-- end pages -->
        
        
		<!-- begin logo -->
        
        <div id="hed">
		<div class="logo">
		<?php global $options;
		foreach ($options as $value) {
    	if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
		}?>
		<h1><a href="<?php echo get_settings('home'); ?>/"><?php if($pov_logo) { ?><img src="<?php echo $pov_logo;?>" alt="Go Home"/><?php } else { bloginfo('name'); } ?>	</a></h1>
        <?php if ($pov_desc == "true") { } else { ?><?php } ?>
		
		</div>
		<!-- end logo -->
		
		
        
        <!-- Content Ad Starts -->
        
        <!--
    	<div class="headad">
		<?php if (!get_option('pov_ad_head_disable') && !$is_paged && !$ad_shown) { include (TEMPLATEPATH . "/header_ad.php"); $ad_shown = true; }?>
    	</div>
        -->
        
		<!-- Content Ad Ends -->
        
		
		</div>
		
		
		
		<!-- begin categories -->
		<!-- Category Nav Starts -->
			<div id="cat_navi" class="wrap">
				<ul id="secnav">
					
					<?php if (get_option('pov_home_link')) : ?>
					
					<?php endif; ?>

					<?php foreach ( (get_categories('exclude='.get_option('pov_cat_ex') ) ) as $category ) { if ( $category->category_parent == '0' ) { ?>
  
                    <li>
                        <a href="<?php echo get_category_link($category->cat_ID); ?>"><?php echo $category->cat_name; ?><br/> <span><?php echo $category->category_description; ?></span></a>
                        
                        <?php if (get_category_children($category->cat_ID) ) { ?>
                        <ul><?php wp_list_categories('title_li&child_of=' . $category->cat_ID ); ?></ul>
                        <?php } ?>
                    </li>
	
					<?php } } ?>
                    
				</ul>
			</div>
			<!-- Category Nav Ends -->
		<!-- end categories -->
	<div style="clear: both;"></div>
	</div>
	<!-- END header -->
    
<!-- BEGIN wrapper -->
<div id="wrapper">
