<?php

add_action('init','of_options');

if (!function_exists('of_options'))
{
	function of_options()
	{ 
	  	$framework = 'tpcrn_';

 		//Access the WordPress Categories via an Array
		$of_categories = array();  
		$of_categories_obj = get_categories('hide_empty=0');
		foreach ($of_categories_obj as $of_cat) {
		    $of_categories[$of_cat->cat_name] = $of_cat->cat_name;}
		$categories_tmp = array_unshift($of_categories, "Select a category:");    
 	       
		//Access the WordPress Pages via an Array
		$of_pages = array();
		$of_pages_obj = get_pages('sort_column=post_parent,menu_order');    
		foreach ($of_pages_obj as $of_page) {
		    $of_pages[$of_page->ID] = $of_page->post_name; }
		$of_pages_tmp = array_unshift($of_pages, "Select a page:");       
	
		//Testing 
		$of_options_select = array("one","two","three","four","five"); 
		$of_options_radio = array("one" => "One","two" => "Two","three" => "Three","four" => "Four","five" => "Five");
		
		//Sample Homepage blocks for the layout manager (sorter)
		$of_options_homepage_blocks = array
		( 
			"enabled" => array (
				"placebo" 			=> "placebo", //REQUIRED!
				"hb_big_slider"		=> "Slider ",
				"hb_nor_blog"		=> "Normal Blog Style",
				"hb_mag_1"			=> "Magazine Block ",				
 				
			), 
			"disabled" => array (
				"placebo" => "placebo", //REQUIRED!
  				
			),
		);
 

 		//Stylesheets Reader
		$alt_stylesheet_path = LAYOUT_PATH;
		$alt_stylesheets = array();
		
		if ( is_dir($alt_stylesheet_path) ) 
		{
		    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) 
		    { 
		        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) 
		        {
		            if(stristr($alt_stylesheet_file, ".css") !== false)
		            {
		                $alt_stylesheets[] = $alt_stylesheet_file;
		            }
		        }    
		    }
		}


		/*Background Images Reader*/
		$bg_images_path = get_stylesheet_directory(). '/images/bg/';  
		$bg_images_url = get_template_directory_uri().'/images/bg/';  
		$bg_images = array();
		
		if ( is_dir($bg_images_path) ) {
		    if ($bg_images_dir = opendir($bg_images_path) ) { 
		        while ( ($bg_images_file = readdir($bg_images_dir)) !== false ) {
		            if(stristr($bg_images_file, ".png") !== false || stristr($bg_images_file, ".jpg") !== false) {
		                $bg_images[] = $bg_images_url . $bg_images_file;
		            }
		        }    
		    }
		}
		

		/*-----------------------------------------------------------------------------------*/
		/* TO DO: Add options/functions that use these */
		/*-----------------------------------------------------------------------------------*/
		
		//More Options
		$uploads_arr = wp_upload_dir();
		$all_uploads_path = $uploads_arr['path'];
		$all_uploads = get_option('of_uploads');
		$other_entries = array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
		$body_repeat = array("no-repeat","repeat-x","repeat-y","repeat");
		$body_pos = array("top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right");
		
		// Image Alignment radio box
		$of_options_thumb_align = array("alignleft" => "Left","alignright" => "Right","aligncenter" => "Center"); 
		
		// Image Links to Options
		$of_options_image_link_to = array("image" => "The Image","post" => "The Post"); 


/*-----------------------------------------------------------------------------------*/
/* The Options Array */
/*-----------------------------------------------------------------------------------*/

// Set the Options Array
global $of_options;
$of_options = array();
$of_options[] = array( "name" => __('General Settings','bresponZive'),
                    "type" => "heading");
$of_options[] = array( "name" => "General",
					"desc" => "",
					"id" => "introduction",
					"std" => __('<h3>General Settings</h3>','bresponZive'),
					"icon" => true,
					"type" => "info");
					
					
$of_options[] = array( "name" => __('Custom Favicon','bresponZive'),
					"desc" =>  __('Upload a 16px x 16px Png/Gif image that will represent your website favicon','bresponZive'),
					"id" => "custom_favicon",
					"std" => "",
					"type" => "media"); 
$logo = get_template_directory_uri() . '/images/logo.png';					
$of_options[] = array( "name" => __('Custom Logo','bresponZive'),
					"desc" => __('Upload a Png/Gif image that will represent your website Logo.','bresponZive'),
					"id" => "custom_logo",
					"std" => $logo,
					"type" => "media"); 
 					
$of_options[] = array( "name" => __('Custom Feed URL','bresponZive'),
					"desc" => __('Enter Feedburner URL or Other','bresponZive'),
					"id" => "custom_feedburner",
					"std" => "",
					"type" => "text"); 					
                                               
$of_options[] = array( "name" => __('Tracking Code','bresponZive'),
					"desc" => __('Paste your Google Analytics (or other) tracking code here. Dont forget to add script tags,if not in the code</br><small> &lt;script&gt; ...,.. &lt;/script&gt; </small>','bresponZive'),
					"id" => "google_analytics",
					"std" => "",
					"type" => "textarea");        
$of_options[] = array( "name" => __('Show Footer Widgets', 'bresponZive'),
					"desc" => __('Select to show the Footer Widgets.', 'bresponZive'),
					"id" => "shw_footer_widg",
					"std" => "yes",
					"type" => "select",
					"options" => array('yes'=>'Yes','no'=>'No'));
$of_options[] = array( "name" =>__('Footer Text','bresponZive'),
                    "desc" => __('Add the Copyright text info.','bresponZive'),
                    "id" => "cus_footer_text",
                    "std" => "",
                    "type" => "textarea");   
 
 
					
$of_options[] = array( "name" => __('Home Settings','bresponZive'),
					"type" => "heading");
 
 					
$of_options[] = array( "name" => "Hello there!",
					"desc" => "",
					"id" => "introduction",
					"std" => __('<h3>Home Page Content Organizer</h3>','bresponZive'),
					"icon" => true,
					"type" => "info");

 					
$of_options[] = array( "name" => __('Homepage Content Layout Manager','bresponZive'),
					"desc" => __('Organize how you want the layout to appear on the homepage','bresponZive'),
					"id" => "homepage_blocks_content",
					"std" => $of_options_homepage_blocks,
					"type" => "sorter");
$of_options[] = array( "name" =>__('News Ticker Title','bresponZive'),
					"desc" => 'Enter Title for Ticker in the Header',
					"id" => "ticker_title",					 
					"std" => "Breaking News",
					"type" => "text");						
$of_options[] = array( "name" => __('Breaking News Ticker','bresponZive'),
					"desc" => __('Select category for Breaking News Ticker.','bresponZive'),
					"id" => "ticker_category",
					"std" =>"",
 					"type" => "select",
					"options" => $of_categories);
$of_options[] = array( "name" =>__('Enter Number of  News Ticker Posts','bresponZive'),
					"desc" => '',
					"id" => "ticker_post_no",					 
					"std" => "3",
					"type" => "text");


$of_options[] = array( "name" => __('HomePage Slider','themeapacific'),
					"desc" => __('Show HomePage slider Options','bresponZive'),
					"id" => "offline_feat_slide",
					"std" => 0,
          			"folds" => 1,
					"type" => "checkbox");  
 $of_options[] = array( "name" => __('Select a Category or use Theme Slider options','imagmag'),
					"desc" => __('Select category for slider. If you want to use theme slider options,then leave it to default option.','imagmag'),
					"id" => "feat_slide_category",
					"std" => "0",
					"fold" => "offline_feat_slide",  
					"type" => "select",
					"options" => $of_categories);

$of_options[] = array( "name" =>__('Theme Slider Options, use Page IDs','bresponZive'),
					"desc" => 'Enter Page IDs to show in slider Posts',
					"id" => "page_id_slider",					 
					"std" => "",
					"type" => "text");					

$of_options[] = array( "name" => __('Slider Transition ', 'bresponZive'),
					"desc" => __('Select Slider transition', 'bresponZive'),
					"id" => "feat_slide_trans",
					"std" => "random",
					"type" => "select",
					"options" => array(
					'random'=>'Random',
					'simplefade'=>'Simple Fade',
					'scrollLeft'=>'scrollLeft',
					'scrollRight'=>'scrollRight	',
					'mosaicSpiral'=>'mosaicSpiral',
					'curtainSliceLeft'=>'curtainSliceLeft',
					'curtainSliceRight'=>'curtainSliceRight',
					));
$of_options[] = array( "name" =>  __('Slider Loader Color','bresponZive'),
					"desc" => __('Pick a color for the loader.','bresponZive'),
					"id" => "feat_slide_loader",
					"std" => "#06AFE4",
					"type" => "color");
   					
$of_options[] = array( "name" =>__('Single Posts','bresponZive'),
					"type" => "heading");
$of_options[] = array( "name" => "General",
					"desc" => "",
					"id" => "introduction",
					"std" => __('<h3>Single Posts Settings</h3>','bresponZive'),
					"icon" => true,
					"type" => "info");
										
$of_options[] = array( "name" => __('Show Featured image on posts','bresponZive'),
					"desc" => '',
					"id" => "featured_image",
					"std" => "On",
					"type" => "radio",
					"options" => array(
						'On' => 'On',
						'Off' => 'Off'
						));
$of_options[] = array( "name" => __('Show Single Post Next/Prev navigation','bresponZive'),
					"desc" => '',
					"id" => "posts_navigation",
					"std" => "On",
					"type" => "radio",
					"options" => array(
						'On' => 'On',
						'Off' => 'Off'
						)); 
$of_options[] = array( "name" => __('Show Breadcrumbs','bresponZive'),
					"desc" => '',
					"id" => "posts_bread",
					"std" => "Off",
					"type" => "radio",
					"options" => array(
						'On' => 'On',
						'Off' => 'Off'
						));						
 						
$of_options[] = array( "name" => __('Show related posts on posts','bresponZive'),
					"desc" =>__('Check to Show related posts on posts','bresponZive'),
					"id" => "posts_related",
 					"std" => 1,
          			"folds" => 1,
					"type" => "checkbox"); 	
$of_options[] = array( "name" => __('Show related posts by Category Wise or Tag Wise','bresponZive'),
					"desc" =>'',
					"id" => "related_posts_tag",
					"std" => "category",
					"fold" =>"posts_related",
					"type" => "radio",
					"options" => array(
						'category' => 'Category',
						'tags' => 'Tags'
						));
$of_options[] = array( "name" =>__('Enter Number of Posts','bresponZive'),
					"desc" => '',
					"id" => "re_post_no",
					"fold" =>"posts_related",
					"std" => "3",
					"type" => "text");		
					
						
$of_options[] = array( "name" => __('Show Tags on posts','bresponZive'),
					"desc" =>'',
					"id" => "posts_tags",
					"std" => "On",
					"type" => "radio",
					"options" => array(
						'On' => 'On',
						'Off' => 'Off'
						)); 						
$of_options[] = array( "name" => __('Show About Author Box ', 'bresponZive'),
					"desc" => __('Select to show the Author Box in Single Posts', 'bresponZive'),
					"id" => "shw_auth_box",
					"std" => "yes",
					"type" => "select",
					"options" => array('yes'=>'Yes','no'=>'No'));
  

$of_options[] = array( "name" => "Social Shares",
					"type" => "heading");
$of_options[] = array( "name" => "General",
					"desc" => "",
					"id" => "introduction",
					"std" => __('<h3>Share Posts in Social Networks</h3>','bresponZive'),
					"icon" => true,
					"type" => "info");
										
$show_hide=array('show'=>'Show','hide'=>'Hide');				
$of_options[] = array( "name" => __('Social Shares in Single Posts', 'bresponZive'),
					"desc" => __('Select to enable/disable to show the social Shares in Single Posts.', 'bresponZive'),
					"id" => "social_shares",
					"std" => "enable",
					"type" => "select",
					"options" => array('enable'=>'Enable','disable'=>'Disable'));

$of_options[] = array( "name" => __('Twitter Consumer Key', 'bresponZive'),
					"desc" => __('For Social Counter Widget', 'bresponZive'),
					"id" => "twit_c_key",
					"std" => "",
					"type" => "text"
					 );
 $of_options[] = array( "name" => __('Twitter Consumer Secret', 'bresponZive'),
						"desc" => __('For Social Counter Widget', 'bresponZive'),
						"id" => "twit_c_secret",
						"std" => "",
						"type" => "text");
						
$of_options[] = array( "name" => __('Twitter Access Token', 'bresponZive'),
						"desc" => __('For Social Counter Widget', 'bresponZive'),
						"id" => "twit_a_token",
						"std" => "",
						"type" => "text");
						
 $of_options[] = array( "name" => __('Twitter Access Token secret', 'bresponZive'),
						"desc" => __('For Social Counter Widget', 'bresponZive'),
						"id" => "twit_a_token_secret",
						"std" => "",
						"type" => "text");
					
$of_options[] = array( "name" => __('Facebook Like', 'bresponZive'),
					"desc" => __('Select to enable/disable to show the Facebook Like in Single Posts.', 'bresponZive'),
					"id" => "share_fblike",
					"std" => "show",
					"type" => "select",
					"options" => $show_hide );
$of_options[] = array( "name" => __('Google +1 button', 'bresponZive'),
					"desc" => __('Select to enable/disable to show the Google +1 button in Single Posts.', 'bresponZive'),
					"id" => "share_gp",
					"std" => "show",
					"type" => "select",
					"options" => $show_hide);
$of_options[] = array( "name" => __('Twitter Tweet', 'bresponZive'),
					"desc" => __('Select to enable/disable to show the Twitter Tweet button in Single Posts.', 'bresponZive'),
					"id" => "share_tw",
					"std" => "show",
					"type" => "select",
					"options" => $show_hide);
$of_options[] = array( "name" => __('Pin it Button', 'bresponZive'),
					"desc" => __('Select to enable/disable to show the Pin it Button in Single Posts.', 'bresponZive'),
					"id" => "share_pin",
					"std" => "show",
					"type" => "select",
					"options" => $show_hide);	
$of_options[] = array( "name" => __('LinkedIn  Button', 'bresponZive'),
					"desc" => __('Select to enable/disable to show the LinkedIn in Single Posts.', 'bresponZive'),
					"id" => "share_in",
					"std" => "show",
					"type" => "select",
					"options" => $show_hide);						
$of_options[] = array( "name" => __('Stumble  Button', 'bresponZive'),
					"desc" => __('Select to enable/disable to show the Stumble Button in Single Posts.', 'bresponZive'),
					"id" => "share_stumble",
					"std" => "show",
					"type" => "select",
					"options" => $show_hide);	
 					
					
 
					
$of_options[] = array( "name" => __('Ad Banners','bresponZive'),
					"type" => "heading");
$of_options[] = array( "name" => "General",
					"desc" => "",
					"id" => "introduction",
					"std" => __('<h3>Advertisements Settings</h3>','bresponZive'),
					"icon" => true,
					"type" => "info");
										
$of_options[] = array( "name" => __('Header Ad',''),
					"desc" => __('Show Header Ad box','bresponZive'),
					"id" => "head_ad_sel",
					"std" => 0,
          			"folds" => 1,
					"type" => "checkbox"); 
$of_options[] = array( "name" => __('Upload Ad Banner Image','bresponZive'),
					"desc" => __('Upload a 468x60 or 728x90 Banner Ad image. </br></br><li>If you want to show Banner, Upload Banner Ad Image and enter the Banner URL Below.</li><li>If you want to show Adsense like ads, then remove the Banner image and add the code below</li>','bresponZive'),
					"id" => "head_ban_ad_img",
					"fold"=>"head_ad_sel",
					"std" => "",
					"type" => "media"); 					
$of_options[] = array( "name" => __('Enter AD Code or Banner AD URL','bresponZive'),
					"desc" => __('Enter your Ad code like Adsense Scripts here OR Enter Banner AD URL only.</br></br>
					<li>If you want to show Adsense like ads, then add Ad code.</li><li>If you want to show Banner ad,then remove the Ad Code and add Banner URL</li>','bresponZive'),
					"id" => "head_ad_code",
					"fold"=>"head_ad_sel",
					"std" => "",
					"type" => "textarea");  
 
 
 $of_options[] = array( "name" => __('Show Ad the in Beginning of Post','bresponZive'),
					"desc" =>__('Enter your Ad code here. This will be added into the Post after the Post title.','bresponZive'),
					"id" => "ptitle_below_ad_sel",
					"std" => 0,
          			"folds" => 1,
					"type" => "checkbox"); 
$of_options[] = array( "name" => __('Upload Ad Banner Image','bresponZive'),
					"desc" => __('Upload a Banner Ad image. </br></br><li>If you want to show Banner, Upload Banner Ad Image and enter the Banner URL Below.</li><li>If you want to show Adsense like ads, then remove the Banner image and add the code below</li>','bresponZive'),
					"id" => "ptitle_below_ad_img",
					"fold"=>"ptitle_below_ad_sel",
					"std" => "",
					"type" => "media"); 					
$of_options[] = array( "name" => __('Enter AD Code or Banner AD URL','bresponZive'),
					"desc" => __('Enter your Ad code like Adsense Scripts here OR Enter Banner AD URL only.</br></br>
					<li>If you want to show Adsense like ads, then add Ad code.</li><li>If you want to show Banner ad,then remove the Ad Code and add Banner URL</li>','bresponZive'),
					"id" => "ptitle_below_ad_code",
					"fold"=>"ptitle_below_ad_sel",
					"std" => "",
					"type" => "textarea");  
 
 
 
  $of_options[] = array( "name" => __('Show Ad in the End of Post','bresponZive'),
					"desc" => __('Enter your Ad code here. This will be added into the end of the post.','bresponZive'),
					"id" => "pend_below_ad_sel",
					"std" => 0,
          			"folds" => 1,
					"type" => "checkbox"); 
$of_options[] = array( "name" => __('Upload Ad Banner Image','bresponZive'),
					"desc" => __('Upload a Banner Ad image. </br></br><li>If you want to show Banner, Upload Banner Ad Image and enter the Banner URL Below.</li><li>If you want to show Adsense like ads, then remove the Banner image and add the code below</li>','bresponZive'),
					"id" => "pend_below_ad_img",
					"fold"=>"pend_below_ad_sel",
					"std" => "",
					"type" => "media"); 					
$of_options[] = array( "name" => __('Enter AD Code or Banner AD URL','bresponZive'),
					"desc" => __('Enter your Ad code like Adsense Scripts here OR Enter Banner AD URL only.</br></br>
					<li>If you want to show Adsense like ads, then add Ad code.</li><li>If you want to show Banner ad,then remove the Ad Code and add Banner URL</li>','bresponZive'),
					"id" => "pend_below_ad_code",
					"fold"=>"pend_below_ad_sel",
					"std" => "",
					"type" => "textarea"); 
					
 
$of_options[] = array( "name" => __('Sidebar Ads','bresponZive'),
					"desc" => __('To Show Sidebar Ad, Go to Widgets Page.','bresponZive'),
					"type" => "","id" => "","std" =>"");					


					
$of_options[] = array( "name" => __('Theme Skins','bresponZive'),
					"type" => "heading"); 	
$of_options[] = array( "name" => "General",
					"desc" => "",
					"id" => "introduction",
					"std" => __('<h3>Stylish Theme Skins</h3>','bresponZive'),
					"icon" => true,
					"type" => "info");


$urlskn =  ADMIN_DIR . 'assets/images/skins/';
$of_options[] = array( "name" =>__('Theme Skins','bresponZive'),
					"desc" =>'',
					"id" => "tptheme_skins",
					"std" => "default",
					"type" => "images",
					"options" => array(
						'default' => $urlskn . 'tpcrn_trans.png',
						'dark' => $urlskn . 'tpcrn_trans.png',
						'velvet' => $urlskn . 'tpcrn_trans.png',
						'darkblue' => $urlskn . 'tpcrn_trans.png',						
						'green' => $urlskn . 'tpcrn_trans.png',												
						'pink' => $urlskn . 'tpcrn_trans.png',
						'purple' => $urlskn . 'tpcrn_trans.png',
						'cappuccino' => $urlskn . 'tpcrn_trans.png',
						'turquoise' => $urlskn . 'tpcrn_trans.png',
						'orange' => $urlskn . 'tpcrn_trans.png'
					 )
					);							
$of_options[] = array( "name" => __('Typography','bresponZive'),
					"type" => "heading");
					
$of_options[] = array( "name" => __('Typography Settings','bresponZive'),
					"desc" => "",
					"id" => "typo_intro",
					"std" => "<h3>Typography Settings</h3>",
					"icon" => true,
					"type" => "info");					
$of_options[] = array( "name" => __('Body Font','bresponZive'),
					"desc" => __('Specify the body font properties','bresponZive'),
					"id" => "body_font",
					"std" => array('size' => '13px','face' =>'','style' => 'normal','color'=>'#666'),
					"type" => "typography");
$of_options[] = array( "name" => __('Top Menu Navigation','bresponZive'),
					"desc" => __('Specify the Header Top Menu font properties','bresponZive'),
					"id" => "headings_topmenu_font",
					"std" => array('size' => '13px','face' => 'Open Sans','style' => 'normal','color'=>''),
					"type" => "typography");
$of_options[] = array( "name" => __('Category Menu Navigation','bresponZive'),
					"desc" => __('Specify the Category Menu Navigation font properties','bresponZive'),
					"id" => "headings_catmenu_font",
					"std" => array('size' => '16px','face' => 'Oswald','style' => 'normal','color'=>''),
					"type" => "typography");	
$of_options[] = array( "name" => __('Blog and Magazine Category Title','bresponZive'),
					"desc" => __('Specify the Blog and Magazine Category Title font properties','bresponZive'),
					"id" => "blog_mag_title_font",
					"std" => array('size' => '20px','face' => 'default','style' => 'normal','color'=>''),
					"type" => "typography");
$of_options[] = array( "name" => __('HomePage Blog Post Title','bresponZive'),
					"desc" => __('Specify the Blog  Post Title font properties','bresponZive'),
					"id" => "blog_post_title_font",
					"std" => array('size' => '24px','face' => 'default','style' => 'normal','color'=>''),
					"type" => "typography");
$of_options[] = array( "name" => __('HomePage Magazine Post Title','bresponZive'),
					"desc" => __('Specify the Magazine Post Title font properties','bresponZive'),
					"id" => "mag_post_title_font",
					"std" => array('size' => '14px','face' => 'default','style' => 'normal','color'=>''),
					"type" => "typography");					
$of_options[] = array( "name" => __('Sidebar Widget Title','bresponZive'),
					"desc" => __('Specify the Sidebar Widget Title font properties','bresponZive'),
					"id" => "sb_widget_title_font",
					"std" => array('size' => '20px','face' => 'default','style' => 'normal','color'=>''),
					"type" => "typography");
$of_options[] = array( "name" => __('Footer Blocks Widget Title','bresponZive'),
					"desc" => __('Specify the Footet Blocks Widget Title font properties','bresponZive'),
					"id" => "fb_widget_title_font",
					"std" => array('size' => '21px','face' => 'default','style' => 'normal','color'=>''),
					"type" => "typography");					
					
$of_options[] = array( "name" => __('Typography Post elements Settings','bresponZive'),
					"desc" => "",
					"id" => "typo_intro",
					"std" => "<h3>Typography Post elements</h3>",
					"icon" => true,
					"type" => "info");	
					
 
$of_options[] = array( "name" => __('Post Title','bresponZive'),
					"desc" => __('Specify the Single Post Title font properties','bresponZive'),
					"id" => "post_title_font",
					"std" => array('size' => '40px','face' => 'default','style' => 'normal','color'=>''),
					"type" => "typography");						
$of_options[] = array( "name" => __('Post Meta','bresponZive'),
					"desc" => __('Specify the Single Post Title font properties','bresponZive'),
					"id" => "post_meta_font",
					"std" => array('size' => '10px','face' => 'default','style' => 'normal','color'=>''),
					"type" => "typography");
$of_options[] = array( "name" => __('Post Content','bresponZive'),
					"desc" => __('Specify the Post Content font properties','bresponZive'),
					"id" => "post_content_font",
					"std" => array('size' => '13px','face' => 'default','style' => 'normal','color'=>''),
					"type" => "typography");
 
$of_options[] = array( "name" => __('Single Post Navigation','bresponZive'),
					"desc" => __('Specify Single Post and comment navigation font properties','bresponZive'),
					"id" => "post_navigation_font",
					"std" => array('size' => '14px','face' => 'Bitter','style' => 'normal','color'=>''),
					"type" => "typography");					
$of_options[] = array( "name" => __('Comments Block',''),
					"desc" => __('Specify the Comments Block content font properties','bresponZive'),
					"id" => "post_comment_font",
					"std" => array('size' => '13px','face' => 'default','style' => 'normal','color'=>''),
					"type" => "typography");
$of_options[] = array( "name" => __('Typography Post Content Headings Settings','bresponZive'),
					"desc" => "",
					"id" => "typo_intro",
					"std" => "<h3>Typography Post Content Headings</h3>",
					"icon" => true,
					"type" => "info");	

$of_options[] = array( "name" => __('H1 Headings','bresponZive'),
					"desc" => __('Specify the H1 Headings font properties','bresponZive'),
					"id" => "post_h1_font",
					"std" => array('size' => '32px','face' => 'default','style' => 'normal','color'=>''),
					"type" => "typography");

$of_options[] = array( "name" => __('H2 Headings','bresponZive'),
					"desc" => __('Specify the H2 Headings font properties','bresponZive'),
					"id" => "post_h2_font",
					"std" => array('size' => '28px','face' => 'default','style' => 'normal','color'=>''),
					"type" => "typography");

$of_options[] = array("name" => __('H3 Headings','bresponZive'),
					"desc" => __('Specify the H3 Headings font properties','bresponZive'),
					"id" => "post_h3_font",
					"std" => array('size' => '24px','face' => 'default','style' => 'normal','color'=>''),
					"type" => "typography");
					
$of_options[] = array( "name" => __('H4 Headings','bresponZive'),
					"desc" => __('Specify the H4 Headings font properties','bresponZive'),
					"id" => "post_h4_font",
					"std" => array('size' => '20px','face' => 'default','style' => 'normal','color'=>''),
					"type" => "typography");
					
$of_options[] = array( "name" => __('H5 Headings','bresponZive'),
					"desc" => __('Specify the H5 Headings font properties','bresponZive'),
					"id" => "post_h5_font",
					"std" => array('size' => '16px','face' => 'default','style' => 'normal','color'=>''),
					"type" => "typography");
					
$of_options[] = array( "name" => __('H6 Headings','bresponZive'),
					"desc" => __('Specify the H6 Headings font properties','bresponZive'),
					"id" => "post_h6_font",
					"std" => array('size' => '12px','face' => 'default','style' => 'normal','color'=>''),
					"type" => "typography");					


 					
$of_options[] = array( "name" => __('Styling','bresponZive'),
					"type" => "heading");
$of_options[] = array( "name" => __('Site Styling Settings Page','bresponZive'),
					"desc" => "",
					"id" => "typo_intro",
					"std" => "<h3>Site Styling Settings </h3>",
					"icon" => true,
					"type" => "info");					

					
$of_options[] = array( "name" =>  __('Body Background Color',''),
					"desc" => __('Pick a background color for the theme.','bresponZive'),
					"id" => "body_background_color",
					"std" => "#fff",
					"type" => "color");
 
					
$of_options[] = array( "name" => __('Background Patterns','bresponZive'),
					"desc" => '',
					"id" => "custom_bg",
					//"std" => $bg_images_url."bg10.png",
					"std" => "",
 					"type" => "tiles",
					"options" => $bg_images,
					);
 $of_options[] = array( "name" => __('Upload Background Pattern','bresponZive'),
					"desc" => __('Upload a Custom background pattern.</br></br><b>Note:</b>If you want to show default background pattern then remove the custom Pattern','bresponZive'),
					"id" => "custom_bg_upload",
					"std" => "",
 					"type" => "media"
 					);	
$of_options[] = array( "name" => __('Custom Pattern as Full Screen background', 'bresponZive'),
					"desc" => __('To Show Custom Pattern as Full Screen background', 'bresponZive'),
					"id" => "custom_full_bg_img",
					"std" => "no",
					"type" => "select",
					"options" => array('yes'=>'Yes','no'=>'No'));					
					
$of_options[] = array( "name" => __('Custom CSS','bresponZive'),
                    "desc" => __('Quickly add some CSS to your theme by adding it to this block.','bresponZive'),
                    "id" => "custom_css",
                    "std" => "",
                    "type" => "textarea");
					
					
$of_options[] = array( "name" => __('Backup Options','bresponZive'),
					"type" => "heading");
$of_options[] = array( "name" => "General",
					"desc" => "",
					"id" => "introduction",
					"std" => __('<h3>Backup and Restore</h3>','bresponZive'),
					"icon" => true,
					"type" => "info");
										
$of_options[] = array( "name" => __('Backup and Restore Options','bresponZive'),
                    "id" => "of_backup",
                    "std" => "",
                    "type" => "backup",
					"desc" => __('You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.','bresponZive'),
					);
					
$of_options[] = array( "name" => __('Transfer Theme Options Data','bresponZive'),
                    "id" => "of_transfer",
                    "std" => "",
                    "type" => "transfer",
					"desc" => __('You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options".
						','bresponZive'),
					);
					
	}
}
?>
