<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>	
<!-- Meta info -->
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<!-- Title -->
<?php global $data;?>
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
 <?php if($data['custom_feedburner']) : ?>
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php echo esc_url($data['custom_feedburner']); ?>" />
<?php endif; ?>
<?php if($data['custom_favicon']): ?>
<link rel="shortcut icon" href="<?php echo esc_url($data['custom_favicon']); ?>" /> <?php endif;  ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<!-- CSS + jQuery + JavaScript --> 
<link href='http://fonts.googleapis.com/css?family=Open+Sans:regular,bold' rel='stylesheet' type='text/css'/>
<!--[if lt IE 9]> 
<link rel='stylesheet' href='<?php echo get_template_directory_uri(); ?>/css/ie8.css' type='text/css' media='all' />
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script> 
<script type="text/javascript" src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
<!--[if  IE 9]>
<link rel='stylesheet' href='<?php echo get_template_directory_uri(); ?>/css/ie9.css' type='text/css' media='all' /> 
<![endif]-->
<?php 	
if ( is_singular() && get_option( 'thread_comments' ) )		wp_enqueue_script( 'comment-reply' );  
	wp_head();
?>
<?php if($data['tptheme_skins']!='default'){ if (!empty($data['tptheme_skins'])) {?>
<link href="<?php echo get_template_directory_uri(); ?>/css/skins/<?php echo trim($data['tptheme_skins']); ?>.css" rel="stylesheet" media="all" type="text/css" /> 
<?php } } ?>
</head>  

<body <?php body_class();?>> 

<!-- #wrapper -->	
<div id="wrapper" class="container clearfix"> 
   <?php 
     if ( has_nav_menu('topNav') ){ 
   ?>
	<!-- #CatNav -->  
	<div id="catnav">	
		<?php wp_nav_menu(array('theme_location' => 'topNav','container'=> '','menu_id'=> 'catmenu','menu_class'=> ' container clearfix','fallback_cb' => 'false','depth' => 3)); ?>
	</div> 
	<!-- /#CatNav -->  
	<?php } ?> 
<!-- /#Header --> 
<div id="wrapper-container"> 

<div id="header">	
	<div id="head-content" class="clearfix ">
 	 
			<!-- Logo --> 
			<div id="logo">   
				<?php if($data['custom_logo'] !='') { 
				if($data['custom_logo']) {  $logo = $data['custom_logo']; 		
				} else { $logo = get_template_directory_uri() . '/images/logo.png'; 	
				} ?>  <a href="<?php echo esc_url( home_url( '/' ) );  ?>" title="<?php bloginfo( 'name' ); ?>" rel="home"><img src="<?php echo esc_url($logo); ?>" alt="<?php bloginfo( 'name' ) ?>" /></a>    
				<?php } else { ?>   
				<?php if (is_home()) { ?>     
				<h1><a href="<?php echo esc_url( home_url( '/' ) );  ?>" title="<?php bloginfo( 'name' ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1> <span><?php bloginfo( 'description' ); ?></span>
				<?php } else { ?>  
				<h2><a href="<?php echo esc_url( home_url( '/' ) );  ?>" title="<?php bloginfo( 'name' ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h2>  
				<?php } } ?>   
			</div>	 	
			<!-- /#Logo -->
 		
  					<!-- Header Ad -->
		<?php if($data['head_ban_ad_img']) { ?>	
		<div id="header-banner468" class="clearfix">
					<a href="<?php echo $data['head_ad_code']; ?>"> <img src="<?php echo $data['head_ban_ad_img']; ?>" alt=""></a> 
			</div>
		<?php } else { ?>			
		<div id="header-banner468">
				<?php echo $data['head_ad_code']; ?>
		</div>	
		<?php } ?>	
		<!-- /#Header Ad -->
	 	
 	</div>	
 </div>
<!-- /#Header --> 

   <?php 
     if ( has_nav_menu('mainNav') ){ 
   ?>
	<!-- #CatNav -->  
	<div id="catnav" class="secondary">	
		<?php wp_nav_menu(array('theme_location' => 'mainNav','container'=> '','menu_id'=> 'catmenu','menu_class'=> 'catnav  container clearfix','fallback_cb' => 'false','depth' => 3)); ?>
	</div> 
	<!-- /#CatNav -->  
	<?php } ?> 
	
 <?php if($data['ticker_category'] != "0") { ?>	
<script>
 jQuery(document).ready(function () {
 	jQuery.fn.ticker.defaults = {
		speed: 0.10,			
		ajaxFeed: false,
		feedUrl: '',
		feedType: 'xml',
		displayType: 'fade',
		htmlFeed: true,
		debugMode: true,
		controls: true,
		titleText: '<?php echo $data['ticker_title']; ?>',
		direction: 'ltr',	
		pauseOnItems: 3000,
		fadeInSpeed: 600,
		fadeOutSpeed: 300
	};	
 jQuery('#js-news').ticker();
 });
 </script>
<div class="breaking-ticker">
 	<div class="container">
 		<ul id="js-news" class="js-hidden">
				<?php
					$ti_cat = get_cat_ID($data['ticker_category']);
 					$tpcrn_tickerposts = new WP_Query(array(
						'showposts' => $data['ticker_post_no'],
  						'cat' => $ti_cat ,	
  						
  						
					));
 							while( $tpcrn_tickerposts -> have_posts() ) : $tpcrn_tickerposts -> the_post(); ?>
									<li><a  class="news-item" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></li>
									
							<?php  endwhile; wp_reset_query(); ?>

		</ul>
	</div>
 </div>
<?php } ?>
	<!--[if lt IE 8]>
		<div class="msgnote"> 
			Your browser is <em>too old!</em> <a rel="nofollow" href="http://browsehappy.com/">Upgrade to a different browser</a> to experience this site. 
		</div>
	<![endif]-->	
