<?php
/**
 * The template for displaying the footer.
 *
 * @package flatsome
 */

global $flatsome_opt;
?>


</div><!-- #main-content -->


<footer class="footer-wrapper clearfix" role="contentinfo">	
<?php if(isset($flatsome_opt['html_before_footer'])){
	// BEFORE FOOTER HTML BLOCK
	echo do_shortcode($flatsome_opt['html_before_footer']);
} ?>


<!-- FOOTER 1 -->
<?php if(!is_page(array('cart', 'checkout','referral'))){ ?>
    
<?php if ( is_active_sidebar( 'sidebar-footer-1' ) ) : ?>
<div class="footer footer-1 <?php echo $flatsome_opt['footer_1_color']; ?>"  style="background-color:<?php echo $flatsome_opt['footer_1_bg_color']; ?>">
	<div class="row">
   		<?php dynamic_sidebar('sidebar-footer-1'); ?>        
	</div><!-- end row -->
</div><!-- end footer 1 -->
<?php endif; ?>


<!-- FOOTER 2 -->
<?php if ( is_active_sidebar( 'sidebar-footer-2' ) ) : ?>
<div class="footer footer-2 <?php echo $flatsome_opt['footer_2_color']; ?>" style="background-color:<?php echo $flatsome_opt['footer_2_bg_color']; ?>">
	<div class="row">
 
   		<?php dynamic_sidebar('sidebar-footer-2'); ?>        
	</div><!-- end row -->
</div><!-- end footer 2 -->
<?php endif; ?>

<!-- FOOTER 3: Top Courses -->
<?php if ( is_active_sidebar( 'gh_footer_top_courses_widget_area' ) ) : ?>
<div class="footer" >
	<div class="row">
    <div class="edu_top_courses columns">
   		<?php dynamic_sidebar('gh_footer_top_courses_widget_area'); ?> 
        </div>       
	</div><!-- end row -->
</div><!-- end FOOTER 3: Top Courses -->
<?php endif; ?>
<?php } ?>
<!-- FOOTER 3: Payment Logos -->
<?php if ( is_active_sidebar( 'gh_footer_payment_logos_widget_area' ) ) : ?>
<div class="footer mob-footer" >
	<div class="clearfix">
            <?php if(is_page(array('cart', 'checkout'))){ ?>
            <ul class="footer_nav">
                <li><a href="<?php echo get_site_url()?>/privacy-policy">Privacy Policy</a></li>
                <li><a href="<?php echo get_site_url()?>/terms-and-conditions">Terms & Conditions</a></li>
                <li><a href="<?php echo get_site_url()?>/disclaimer">Disclaimer</a></li>
                <li><a href="<?php echo get_site_url()?>/cancellation-and-refund-policy">Cancellation & Refund Policy</a></li>
                <li><a href="<?php echo get_site_url()?>/shipping-and-delivery-policy">Shipping & Delievery Policy</a></li>
                
            </ul>
            <?php } ?>
            <?php if(is_front_page()){ ?>
            <ul class="footer_nav">
                <li><a href="<?php echo get_site_url()?>/privacy-policy">Privacy Policy</a></li>
                <li><a href="<?php echo get_site_url()?>/terms-and-conditions">Terms & Conditions</a></li>
                <li><a href="<?php echo get_site_url()?>/disclaimer">Disclaimer</a></li>
                <li><a href="<?php echo get_site_url()?>/cancellation-and-refund-policy">Cancellation & Refund Policy</a></li>
                <li><a href="<?php echo get_site_url()?>/shipping-and-delivery-policy">Shipping & Delievery Policy</a></li>
                
            </ul>
            <?php } ?>
     <div class="edu_footer_payment row">
   		<?php dynamic_sidebar('gh_footer_payment_logos_widget_area'); ?>  
        </div>      
	</div><!-- end row -->
</div><!-- end FOOTER 3: Payment Logos -->
<?php endif; ?>

<?php if(isset($flatsome_opt['html_after_footer'])){
	// AFTER FOOTER HTML BLOCK
	echo do_shortcode($flatsome_opt['html_after_footer']);
} ?>

<?php 
/*
<div class="absolute-footer <?php echo $flatsome_opt['footer_bottom_style']; ?>" style="background-color:<?php echo $flatsome_opt['footer_bottom_color']; ?>">
<div class="row">
	<div class="large-12 columns">
		<div class="left">
			 <?php if ( has_nav_menu( 'footer' ) ) : ?>
				<?php  
						wp_nav_menu(array(
							'theme_location' => 'footer',
							'menu_class' => 'footer-nav',
							'depth' => 1,
							'fallback_cb' => false,
						));
				?>						
			<?php endif; ?>
			<div class="copyright-footer"><?php if(isset($flatsome_opt['footer_left_text'])) {echo $flatsome_opt['footer_left_text'];} else{ echo 'Define left footer text / navigation in Theme Option Panel';} ?></div>
		</div><!-- .left -->
		<div class="right">
				<?php if(isset($flatsome_opt['footer_right_text'])){ echo do_shortcode($flatsome_opt['footer_right_text']);} else {echo 'Define right footer text in Theme Option Panel';} ?>
		</div>
	</div><!-- .large-12 -->
</div><!-- .row-->
</div><!-- .absolute-footer -->
*/

?>

</footer><!-- .footer-wrapper -->
</div><!-- #wrapper -->

<!-- back to top -->
<a href="#top" id="top-link"><span class="icon-angle-up"></span></a>

<?php if(isset($flatsome_opt['html_scripts_footer'])){
	// Insert footer scripts
	echo $flatsome_opt['html_scripts_footer'];
} ?>

<div class="mob-helper"></div>


<?php wp_footer(); ?>

<?php 
 global  $product;

global $time_end_footer_gh;
$time_end_footer_gh = microtime(true);
$time1 = $time_end_footer_gh - $time_start_header_gh;
echo "<div id='microtime_for_redis_gh_in_header' style='display:none;'>retrieve result in this time period ". $time1 ."seconds</div>";
// echo "<pre/>";
 //print_r($product->post->ID);
 //Exit;
 //$term_meta_slug = ;
//$term = get_term_meta(126, 'university_type', true);
//$term = wp_get_object_terms( $product->post->ID, 'product');
//echo $term;
//exit;
//if (!empty($term)){
?>


<div id='invtrflfloatbtn'></div>
<script> 
var invite_referrals = window.invite_referrals || {}; (function() { 
	invite_referrals.auth = { 
		bid_e : 'E1B12D0338B46F598D8123D7C78E9598', 
		bid : '1576', 
		t : '420', 
		email : '', 
		mobile : '', 
		userParams : {'fname': '', 'lname': '', 'birthday': '', 'gender': ''}, 
		referrerCode : '',
		orderID : '', 
		purchaseValue : '', 
		userCustomParams : {'customValue': '', 'shareLink': '', 'shareTitle': '', 'shareDesc': '', 'shareImg': ''}, 
		showWidget : '' 
	};
	var script = document.createElement('script');script.async = true; script.src = (document.location.protocol == 'https:' ? "//d11yp7khhhspcr.cloudfront.net" : "//cdn.invitereferrals.com") + '/js/invite-referrals-1.0.js'; var entry = document.getElementsByTagName('script')[0];entry.parentNode.insertBefore(script, entry); })(); 
</script>
<!--<div id='invtrflfloatbtn'></div>
<script>	
var invite_referrals = window.invite_referrals || {}; (function() { 
	invite_referrals.auth = { 
		bid_e : '27296738CA663EAF0E219C01FEA8E38C',
		bid : '6044',
		t : '420',
		email : '',
		mobile : '',
		userParams : {'fname': '', 'lname': '', 'birthday': '', 'gender': ''},
		referrerCode : '',
		orderID : '', purchaseValue : '',
		userCustomParams : {'customValue': '', 'shareLink': '', 'shareTitle': '', 'shareDesc': '', 'shareImg': ''},
		showWidget : ''
	};	
var script = document.createElement('script');script.async = true;
script.src = (document.location.protocol == 'https:' ? "//d11yp7khhhspcr.cloudfront.net" : "//cdn.invitereferrals.com") + '/js/invite-referrals-1.0.js';
var entry = document.getElementsByTagName('script')[0];entry.parentNode.insertBefore(script, entry); })();
</script>-->




<script type="text/javascript">
setTimeout(function(){var a=document.createElement("script");
var b=document.getElementsByTagName("script")[0];
a.src=document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0028/1146.js?"+Math.floor(new Date().getTime()/3600000);
a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
</script>

<!-- Web engage Starts-->

<script id="_webengage_script_tag" type="text/javascript">
  var _weq = _weq || {};
  _weq['webengage.licenseCode'] = '~47b664c1';
  _weq['webengage.widgetVersion'] = "4.0";
  
  (function(d){
    var _we = d.createElement('script');
    _we.type = 'text/javascript';
    _we.async = true;
    _we.src = (d.location.protocol == 'https:' ? "https://ssl.widgets.webengage.com" : "http://cdn.widgets.webengage.com") + "/js/widget/webengage-min-v-4.0.js";
    var _sNode = d.getElementById('_webengage_script_tag');
    _sNode.parentNode.insertBefore(_we, _sNode);
  })(document);
</script>
<!-- Web engage ends-->


<!-- GA code starts -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-61230082-1', 'auto');
  ga('send', 'pageview');

</script>
<!-- GA code ends-->

<!-- Google Adwords Remarketing Tags - starts -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1011427157;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/1011427157/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<!-- Google Adwords Remarketing Tags - ends -->

<!-- AdRoll code starts -->
<script type="text/javascript">
adroll_adv_id = "KFQPYGFXBVFOVMVN7J3KS2";
adroll_pix_id = "MZT422LAGFGSDDRJV3RYWM";
(function () {
var oldonload = window.onload;
window.onload = function(){
   __adroll_loaded=true;
   var scr = document.createElement("script");
   var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com");
   scr.setAttribute('async', 'true');
   scr.type = "text/javascript";
   scr.src = host + "/j/roundtrip.js";
   ((document.getElementsByTagName('head') || [null])[0] ||
    document.getElementsByTagName('script')[0].parentNode).appendChild(scr);
   if(oldonload){oldonload()}};
}());
/*AdRoll code ends */	
var invite_referrals = window.invite_referrals || {}; (function() { 
	invite_referrals.auth = { bid_e : 'E1B12D0338B46F598D8123D7C78E9598', bid : '1576', t : '420', email : '', userParams : {'fname': ''}};	
var script = document.createElement('script');script.async = true;
script.src = (document.location.protocol == 'https:' ? "//d11yp7khhhspcr.cloudfront.net" : "//cdn.invitereferrals.com") + '/js/invite-referrals-1.0.js';
var entry = document.getElementsByTagName('script')[0];entry.parentNode.insertBefore(script, entry); })();
</script>
<input type="hidden" id="theme_dir" value="<?php echo get_site_url(); ?>"/>
<script>
// Added by gambheer
$(".search_home").keyup(function(){
	var theme_dir = $("#theme_dir").val();
	$("#mega-menu").slideUp();
	if($(".search_home").val().length == 0){
	    engine.helper.removeNumericRefinement("_price", ">=");
	    engine.helper.removeNumericRefinement("_price", "<=");
	    $.getScript( theme_dir+'/wp-content/themes/flatsome/js/after_algolia.js', function( data, textStatus, jqxhr ) {
	     });  
	    <?php if(is_front_page()) {?>
	    	if($( window ).width() > 768){
		        if($("#algolia_instant_selector").length <= 1)
		           $("#mega-menu").slideDown();
			}		
		<?php }?>
	}
});

$("#browse-cat").click(function(){
	$("#mega-menu").slideToggle();
});

jQuery(document).ready(function() {
    var offset = 100;
    jQuery(window).scroll(function() {
    	if($( window ).width() > 768){
	        if (jQuery(this).scrollTop() > offset) {
	                $("#mega-menu2").show();
	                $("#mega-menu2").css('position','absolute');
				    $("#mega-menu2").css('width','24%');
				    $("#mega-menu2").css('top','106px');
				    $("#mega-menu2").css('z-index','999');
				    $("#mega-menu2").css('left','8.5%');
				    if($( window ).width() > 768 && $( window ).width() < 1080){
				    	$("#mega-menu2").css('left','0%');
				    }
				    $("#mega-menu2 #mega_menu_and_slider").css("padding","0px");
				    $("#mega-menu2 .container").css("padding","0px");
	                $("#mega-menu").hide();
	        } 
	        else{
	        	<?php if(is_front_page()) {?>
		           $("#mega-menu2").hide();
		           if($("#algolia_instant_selector").length == 0)
		              $("#mega-menu").show();
		        <?php }?>
	        }
    	}
    });

     jQuery(window).resize(function(){
     	megamenu_adjust();
     });
     megamenu_adjust();
 });

function megamenu_adjust(){
	if($( window ).width() < 768){
		$("#mega-menu").hide();
		$("#mega-menu2").show();
	    $("#mega-menu2").css('position','inherit');
	    $("#mega-menu2").css('width','100%');
	    $("#mega-menu2 #mega_menu_and_slider").css("padding","0px");
	    $("#mega-menu2 .container").css("padding","0px");
	    $(".edu-nave ul.edu_mainnave li").css("background","#fff");
	    $(".fkr_nav").css("background","#fff");
	}
	else{
	    $("#mega-menu2").hide();
	}  
}

</script>
<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "item": {
      "@id": "https://edukart.com/about-us",
      "name": "About_Us"
    }
  },{
    "@type": "ListItem",
    "position": 2,
    "item": {
      "@id": "https://edukart.com/career",
      "name": "Career"
    }
  },{
    "@type": "ListItem",
    "position": 3,
    "item": {
      "@id": "https://edukart.com/blog",
      "name": "Blog"
    }
  }]
}
</script>

<script type="text/javascript">
	$(document).ready(function(){
	    $(".side-menu-icon").click(function(){
	        $("#masthead").addClass("slide-menu");
	        $(".header-wrapper").css("position","initial");
	        $("html").css("overflow","hidden");
	    });
   	    $(".overlay").click(function(){
	        $("#masthead").removeClass("slide-menu");
	        $(".header-wrapper").css("position","relative");
	        $("html").css("overflow","initial");
	    });

	});
</script>

<script type="text/javascript">
/*	$(document).ready(function(){  
		$(".mobile-side-menu .edu_mainnave > li").click(function(e){
			$(".edu_mainnave > li > div").removeClass("fkr_nav-active");	
		    $(".edu_mainnave > li > span > i").removeClass("fkr_nav-rotate");   
		});
	});
*/

$(document).ready(function(){  
	$(".mobile-side-menu .edu_mainnave > li").click(function(e){
		if($(e.target).parents().hasClass("menu_drop") && $(e.target).parents().hasClass("fkr_nav-active")) return;
		$(".edu_mainnave > li > div").removeClass("fkr_nav-active");
		$(".edu_mainnave > li > span > i").removeClass("fkr_nav-rotate");
	});
	$(".mobile-side-menu .edu_subnave > li").click(function(e){
		if($(e.target).parents().hasClass("edu_subnavebar")) return;
		$(".edu_subnave > li > div").removeClass("fkr_nav-active");
		$(".edu_subnave > li > span > i").removeClass("fkr_nav-rotate");
	});
});


</script>


</body>
</html>
