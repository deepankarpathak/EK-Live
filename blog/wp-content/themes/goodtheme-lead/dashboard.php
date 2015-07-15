<?php



$themename = "Good Theme";
$shortname = "pov";
$mx_categories_obj = get_categories('hide_empty=0');
$mx_categories = array();
foreach ($mx_categories_obj as $mx_cat) {
	$mx_categories[$mx_cat->cat_ID] = $mx_cat->cat_name;
}
$categories_tmp = array_unshift($mx_categories, "Select a category:");	
$number_entries = array("Select a Number:","1","2","3","4","5","6","7","8","9","10" );
$colorscheme = array("Default", "White", "Blue", "Red", "Purple");

$options = array (




	

array(  "name" => "Main Set Up",
            "type" => "heading",
			"desc" => "Set your logo and color scheme.",
       ),

array( 	"name" => "Logo Display",
			"desc" => "The URL address of your logo (best is 400px x 65px). (Leaving it empty will display your blog title)",
			"id" => $shortname."_logo",
			"type" => "text",
			"std" => ""),		

array(   "name" => "Blog Color Scheme",
            "id" => $shortname."_color",
            "type" => "select",
            "std" => "Default",
            "options" => $colorscheme),



	
array(	"name" => "Navigation Settings",
					"type" => "heading"),	


array(	"name" => "Exclude Categories",
					"desc" => "Enter a comma-separated list of the <a href='http://support.wordpress.com/pages/8/'>Category ID's</a> that you'd like to exclude from the main category navigation. (e.g. 1,2,3,4)",
					"id" => $shortname."_cat_ex",
					"std" => "",
					"type" => "text"),	

			
			
array(  "name" => "Featured section",
            "type" => "heading",
			"desc" => "This section customizes the featured area on the top of the content and the number of stories displayed there.",
       ),		
array( 	"name" => "Featured section category",
			"desc" => "Select the category that you would like to have displayed in Featured list on your homepage.",
			"id" => $shortname."_story_category",
			"std" => "Uncategorized",
			"type" => "select",
			"options" => $mx_categories),
			
array(	"name" => "Number of highlight reel posts",
			"desc" => "Select the number of posts to display ( Upto 5 is good).",
			"id" => $shortname."_story_count",
			"std" => "1",
			"type" => "select",
			"options" => $number_entries),
			
array(  "name" => "Sidebar Set Up",
	"type" => "heading",
	"desc" => "Set your sidebar layout.",
       ),

array( "name" => "Disable Tabs box?",
	"desc" => "Tick to disable Tabs box.",
	"id" => $shortname."_distabs",
	"type" => "checkbox",
	"std" => "false"),

array( "name" => "Disable Search box?",
	"desc" => "Tick to disable Search box.",
	"id" => $shortname."_search",
	"type" => "checkbox",
	"std" => "false"),
	
array( "name" => "Disable About box?",
	"desc" => "Tick to disable About box.",
	"id" => $shortname."_dispop",
	"type" => "checkbox",
	"std" => "false"),	
/*	
array( "name" => "Disable Ads box?",
	"desc" => "Tick to disable Ads box.",
	"id" => $shortname."_disads",
	"type" => "checkbox",
	"std" => "false"),
*/	
array( "name" => "Disable Flickr box?",
	"desc" => "Tick to disable Flickr box.",
	"id" => $shortname."_disflickr",
	"type" => "checkbox",
	"std" => "false"),

array( "name" => "Disable Follow Me box?",
	"desc" => "Tick to disable Follow Me box.",
	"id" => $shortname."_disfollow",
	"type" => "checkbox",
	"std" => "false"),

array( "name" => "Disable Video box?",
	"desc" => "Tick to disable Video Me box.",
	"id" => $shortname."_disvideo",
	"type" => "checkbox",
	"std" => "false"),
	
	
array(  "name" => "About Me Settings",
            "type" => "heading",
			"desc" => "Set your About me image and text from here .",
       ),			
/*		
array("name" => "About me Image",
			"desc" => "Enter your avatar image url here.",
            "id" => $shortname."_img",
            "std" => "",
            "type" => "text"),    
*/	   
array("name" => "About me text",
			"desc" => "Enter some descriptive text about you, or your site.",
            "id" => $shortname."_about",
            "std" => "Integer eget dui ante, a vestibulum augue. Suspendisse lorem diam, viverra a interdum in, facilisis eget mauris. Etiam cursus ligula at dolor ultrices adipiscing sodales metus lacinia. Etiam id justo consectetur lorem auctor scelerisque nec varius ante. Ut condimentum nisl nec enim porttitor ut auctor neque adipiscing. Praesent ac eleifend nunc.",
            "type" => "textarea"),    


			
array(  "name" => "Featured Video Settings",
            "type" => "heading",
			"desc" => "Displays a featured video on the homepage .",
       ),	
	   	
	
array( 	"name" => "Featured Video category",
			"desc" => "Select the category that you would like to have displayed in the videos section on your homepage.",
			"id" => $shortname."_video_category",
			"std" => "Select a category:",
			"type" => "select",
			"options" => $mx_categories),
			
		
array(  "name" => "Twitter, Facebook, Flickr account",
            "type" => "heading",
			"desc" => "",
			),	
array(  "name" => "Your Twitter account",
        	"desc" => "Enter your Twitter account name",
        	"id" => $shortname."_twitter_user_name",
        	"type" => "text",
        	"std" => ""),
array(  "name" => "Your Facebook account",
        	"desc" => "Enter your Facebook account name",
        	"id" => $shortname."_facebook_user_name",
        	"type" => "text",
        	"std" => ""),	
array(  "name" => "Your Flickr account",
        	"desc" => "Enter your Flickr account name",
        	"id" => $shortname."_flickr_user_name",
        	"type" => "text",
        	"std" => ""),	
	
/*	
array(	"name" => "Header Banner Ad  (468x60px)",
			"desc" => "Enter your AdSense code, or your banner url and destination, or disable header ad.",
					"type" => "heading"),

	

array(	"name" => "Adsense code",
					"desc" => "Enter your adsense code here.",
					"id" => $shortname."_ad_head_adsense",
					"std" => "",
					"type" => "textarea"),

array(	"name" => "Banner Ad Header - Image Location",
					"desc" => "Enter the URL for this banner ad.",
					"id" => $shortname."_ad_head_image",
					"std" => "wp-content/themes/GoodThemeLead/images/ad-big.gif",
					"type" => "text"),

array(	"name" => "Banner Ad Header - Destination",
					"desc" => "Enter the URL where this banner ad points to.",
					"id" => $shortname."_ad_head_url",
					"std" => "#",
					"type" => "text"),


array(	"name" => "Disable Ad",
					"desc" => "Disable the ad space",
					"id" => $shortname."_ad_head_disable",
					"std" => "false",
					"type" => "checkbox"),
	
	
	
	
	
	
	


	
array(	"name" => "Content Banner Ad  (468x60px)",
			"desc" => "Enter your AdSense code, or your banner url and destination, or disable content ad.",
					"type" => "heading"),

	

array(	"name" => "Adsense code",
					"desc" => "Enter your adsense code here.",
					"id" => $shortname."_ad_content_adsense",
					"std" => "",
					"type" => "textarea"),

array(	"name" => "Banner Ad Content - Image Location",
					"desc" => "Enter the URL for this banner ad.",
					"id" => $shortname."_ad_content_image",
					"std" => "wp-content/themes/GoodThemeLead/images/ad-big.gif",
					"type" => "text"),

array(	"name" => "Banner Ad Content - Destination",
					"desc" => "Enter the URL where this banner ad points to.",
					"id" => $shortname."_ad_content_url",
					"std" => "#",
					"type" => "text"),


array(	"name" => "Disable Ad",
					"desc" => "Disable the ad space",
					"id" => $shortname."_ad_content_disable",
					"std" => "false",
					"type" => "checkbox"),
	
	
			      		
			
	array(  "name" => "Banner Ads Settings",
            "type" => "heading",
			"desc" => "You can setup four 125x125 banners for your blog from here",
       ), 
	   
	array("name" => "Banner-1 Image",
			"desc" => "Enter your 125x125 banner image url here.",
            "id" => $shortname."_banner1",
            "std" => "wp-content/themes/GoodThemeLead/images/ad-small.gif",
            "type" => "text"),     
	   
	array("name" => "Banner-1 Url",
			"desc" => "Enter the banner-1 url here.",
            "id" => $shortname."_url1",
            "std" => "#",
            "type" => "text"),    
	      
	 
	array("name" => "Banner-2 Image",
			"desc" => "Enter your 125x125 banner image url here.",
            "id" => $shortname."_banner2",
            "std" => "wp-content/themes/GoodThemeLead/images/ad-small.gif",
            "type" => "text"),    
	   
	array("name" => "Banner-2 Url",
			"desc" => "Enter the banner-2 url here.",
            "id" => $shortname."_url2",
            "std" => "#",
            "type" => "text"), 

	array("name" => "Banner-3 Image",
			"desc" => "Enter your 125x125 banner image url here.",
            "id" => $shortname."_banner3",
            "std" => "wp-content/themes/GoodThemeLead/images/ad-small.gif",
            "type" => "text"),    
	   
	array("name" => "Banner-3 Url",
			"desc" => "Enter the banner-3 url here.",
            "id" => $shortname."_url3",
            "std" => "#",
            "type" => "text"),
			
	array("name" => "Banner-4 Image",
			"desc" => "Enter your 125x125 banner image url here.",
            "id" => $shortname."_banner4",
            "std" => "wp-content/themes/GoodThemeLead/images/ad-small.gif",
            "type" => "text"),    
	   
	array("name" => "Banner-4 Url",
			"desc" => "Enter the banner-4 url here.",
            "id" => $shortname."_url4",
            "std" => "#",
            "type" => "text"),
			
*/		
	 
	   
	
   
);

function mytheme_add_admin() {

    global $themename, $shortname, $options;

    if ( $_GET['page'] == basename(__FILE__) ) {
    
        if ( 'save' == $_REQUEST['action'] ) {

                foreach ($options as $value) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

                foreach ($options as $value) {
                    if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }

                header("Location: themes.php?page=dashboard.php&saved=true");
                die;

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($options as $value) {
                delete_option( $value['id'] ); 
                update_option( $value['id'], $value['std'] );}

            header("Location: themes.php?page=dashboard.php&reset=true");
            die;

        }
    }

      add_theme_page($themename." Options", "$themename Options", 'edit_themes', basename(__FILE__), 'mytheme_admin');

}

function mytheme_admin() {

    global $themename, $shortname, $options;

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';
    
    
?>
<div class="wrap">
<h2><b><?php echo $themename; ?> theme options</b></h2>

<form method="post">

<table class="optiontable" >

<?php foreach ($options as $value) { 
    
	
if ($value['type'] == "text") { ?>
        
<tr align="left"> 
    <th scope="row"><?php echo $value['name']; ?>:</th>
    <td>
        <input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" size="40" />
				
    </td>
	
</tr>
<tr><td colspan=2> <small><?php echo $value['desc']; ?> </small> <hr /></td></tr>

<?php } elseif ($value['type'] == "textarea") { ?>
<tr align="left"> 
    <th scope="row"><?php echo $value['name']; ?>:</th>
    <td>
                   <textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" cols="40" rows="5"/><?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>
</textarea>

				
    </td>
	
</tr>
<tr><td colspan=2> <small><?php echo $value['desc']; ?> </small> <hr /></td></tr>

<?php } elseif ($value['type'] == "select") { ?>

    <tr align="left"> 
        <th scope="top"><?php echo $value['name']; ?>:</th>
	        <td>
            <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
                <?php foreach ($value['options'] as $option) { ?>
                <option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; }?>><?php echo $option; ?></option>
                <?php } ?>
            </select>
			
        </td>
	
</tr>
<tr><td colspan=2> <small><?php echo $value['desc']; ?> </small> <hr /></td></tr>






<?php } elseif ($value['type'] == "checkbox") { ?>
		
            <tr>
            <td style="width: 40%"><strong><?php echo $value['name']; ?></strong><br /><small><?php echo $value['desc']; ?></small></td>
                <td><?php if(get_settings($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = ""; } ?>
                        <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
                        </td>
            </tr>
                        

       










<?php } elseif ($value['type'] == "heading") { ?>

   <tr valign="top"> 
		    <td colspan="2" style="text-align: left;"><h2 style="color:grey;"><?php echo $value['name']; ?></h2></td>
		</tr>
<tr><td colspan=2> <small> <p style="color:green; margin:0 0;" > <?php echo $value['desc']; ?> </P> </small> <hr /></td></tr>

<?php } ?>
<?php 
}
?>
</table>






<p class="submit">
<input name="save" type="submit" value="Save changes" />    
<input type="hidden" name="action" value="save" />
</p>
</form>
<form method="post">
<p class="submit">
<input name="reset" type="submit" value="Reset" />
<input type="hidden" name="action" value="reset" />
</p>
</form>

<p>For support visit us: <a href="http://goodtheme.org/" >GoodTheme.org</a>.</p>
<?php
}
add_action('admin_menu', 'mytheme_add_admin'); ?>
