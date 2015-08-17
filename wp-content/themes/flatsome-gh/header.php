<?php
global $woo_options;
global $woocommerce;
global $flatsome_opt;
global $gh_country_currency;
global $wp_session;

?>
<!DOCTYPE html>
<!--[if lte IE 9 ]><html class="ie lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title><?php wp_title( '|', true, 'right' ); ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<!-- Custom favicon-->
	<link rel="shortcut icon" href="<?php if ($flatsome_opt['site_favicon']) { echo $flatsome_opt['site_favicon']; ?>
	<?php } else { ?><?php echo get_template_directory_uri(); ?>/favicon.png<?php } ?>" />

	<!-- Retina/iOS favicon -->
	<link rel="apple-touch-icon-precomposed" href="<?php if ($flatsome_opt['site_favicon_large']) { echo $flatsome_opt['site_favicon_large']; ?>
	<?php } else { ?><?php echo get_template_directory_uri(); ?>/apple-touch-icon-precomposed.png<?php } ?>" />
        
	
            <?php wp_head(); ?>
        <?php 
        if(is_product()){
            $product = get_product( get_the_ID() );
?>
<script type="text/javascript">
  window.vizLayer = {
    geo: "sg",
    account_id: "VIZVRM3503",
    vertical: "ecommerce",
    type: "product_page",
    pid: <?php echo $product->id;?> 
 };

(function(){try{var viz = document.createElement("script"); viz.type = "text/javascript";viz.async = true; viz.src = ("https:" == document.location.protocol ?"https://in-tags.vizury.com" : "http://in-tags.vizury.com")+ "/analyze/pixel.php?account_id=vst";var s = document.getElementsByTagName("script")[0];s.parentNode.insertBefore(viz, s);viz.onload = function() {try {pixel.parse();} catch(i){}};viz.onreadystatechange = function() {if (viz.readyState == "complete" || viz.readyState == "loaded"){try {pixel.parse();}catch(i){}}};}catch(i){}})();

</script>

<?php
            if($product){
                $showspecialization = $product->get_attribute("show-specialization");
                $specialization1 = $product->get_attribute("specialization");
                $university1 = wc_get_product_terms($product->id, 'university')[0];
                if($showspecialization == 'Yes'){
                   $course_name = $product->post->post_title . ( (trim($specialization1) != '') ? ' ('.$specialization1.')' : '' );
                }
                else{
                    $course_name = $product->post->post_title ;
                }
                $university = $university1->name; 
            }
        }
        ?>

<meta name="google-site-verification" content="3MT2zagYFUMEvpQLX6ArMfpeip_Zt0ogB8dzKIMiWyw" />
<!--Start of Connecto Script-->
<script type="text/javascript">
var _TConnecto = _TConnecto || {};
_TConnecto.licenseKey = 'XPWF0WQYET09JP1V';

_TConnecto.initConnecto = function() {
    _TConnecto.addVariable("course", "<?php echo $course_name ;?>");
    _TConnecto.addVariable("university", "<?php echo $university ;?>");
};

(function() {
  var con = document.createElement('script'); con.type = 'text/javascript';
  var host = (document.location.protocol === 'http:') ? 'http://cdn' : 'https://server';
  con.src = host + '.connecto.io/javascripts/connect.prod.min.js';
  var s = document.getElementsByTagName('script')[0];
  s.parentNode.insertBefore(con, s);
})();
</script>
<!--End of Connecto Script-->

        <script type="text/javascript">
 //   $(document).ready(function() {    
        function submitLead_gh(data){
            $.ajax({
                url: "<?php echo get_site_url() . "/wp-admin/admin-ajax.php" ?>",
                type: 'POST',
                data: {action: 'connect_form', Data:data},
                success: function() {

                }
            });
        }
   //     });
</script>
<script type="text/javascript">
setTimeout(function(){var a=document.createElement("script");
var b=document.getElementsByTagName("script")[0];
a.src=document.location.protocol+"//script.crazyegg.com/pages/scripts/0028/1146.js?"+Math.floor(new Date().getTime()/3600000);
a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
</script> 

<!--<link href="modalPopLite1.3.1/modalPopLite.css" rel="stylesheet" type="text/css" /> 
<script type="text/javascript" src="modalPopLite1.3.1/modalPopLite.min.js"></script>

<script type="text/javascript">
	 $(function () {     $('#popup-wrapper').modalPopLite({ openButton: '#popup-wrapper', closeButton: '#close-btn' }); }); 
</script>-->
<script type="text/javascript">
$(function() {
$('.product_detail_referral a, .coupon_successful a').click(function() {
$(".referal_tc").dialog({
title: "Terms & Conditions",
width: 500,
height: 400,
top:200,
modal: true,
});
});
});
</script>
</head>

<body <?php body_class(); ?>>
<div id="popup-wrapper" class="referal_tc" style="font-family:robotolight,arial,sans-serif !important; display:none;">
	<ol type="decimal" style="padding-left:20px;">
		<li>EduKart's Referral Program is an initiative to spread awareness &amp; importance of education in today's competitive world</li>
		<li>No discount or cashback is offered by any of the course providers on any of the courses listed on EduKart</li>
		<li>The student is required to pay the prescribed fee set by the given course providers</li><li>Cashback is valid only on confirmed orders through online transactions</li>
		<li>Cashback will only be credited to your Paytm wallet</li>
		<li>Each email id will be associated with only one referral code</li>
		<li>No refund on courses bought with a cashback option</li>
		<li>The student is eligible for cashback on every subsequent fee installment</li>
		<li>The referrer gets cashback only on the first fee installment by the student</li>
		<li>EduKart reserves the right to change the terms and conditions of this offer at any time without prior notice</li>
	</ol>
</div>
<?php
if(isset($_COOKIE['referee']) && $_COOKIE['referee']!='') { 
	$referrer = json_decode(stripslashes($_COOKIE['referee']), true);
	$referee_name = $referrer['referrer_name'];
	$referee_code = $referrer['code'];
	//print_r($_SESSION);
    if(!isset($_GET['utm_source'])){
	 if(!is_page('checkout')) { 
?>
	<div class="th_referral_link " style= "<?php if($_SESSION['cash_back'] != ''){ echo 'display:block;';}?>"><div style="text-align: center;"><span style="font-family: helvetica; font-size: 1em;">You have been referred by <?php echo $referee_name; ?> Use referral code <b><?php echo $referee_code; ?></b> to get cash back</span></div></div>
		<?php }
		}
	} ?>
<?php
echo '<div id="ses_de_gh" style="display:none;">'.$wp_session['ip'].' | '.$wp_session['country'].' | '.$wp_session['currency'].' | '.$wp_session['conversion_rate'].' | '.$wp_session['ip_type'].' | '.$wp_session['all_ip'].'</div>';
//    echo $wp_session['ip'].' | '.$wp_session['country'].' | '.$wp_session['currency'].' | '.$wp_session['conversion_rate']; 
?>
<?php 

// HTML Homepage Before Header // Set in Theme Option > HTML Blocks
if($flatsome_opt['html_intro'] && is_front_page()) echo '<div class="home-intro">'.do_shortcode($flatsome_opt['html_intro']).'</div>' ?>

	<div id="wrapper"<?php if($flatsome_opt['box_shadow']) echo ' class="box-shadow"';?>>
		<div class="header-wrapper before-sticky">

		<?php do_action( 'before' ); ?>
		<?php if(!isset($flatsome_opt['topbar_show']) || $flatsome_opt['topbar_show']){ ?>
                    <?php if (!is_page(array('cart', 'checkout'))){ ?>
		<div id="top-bar">
			<div class="row">
			   <div class="large-12 columns">
				<?php 
					$args = array(
					       'order'                  => 'ASC',
					       'orderby'                => 'menu_order',
					       'post_type'              => 'nav_menu_item',
					       'post_status'            => 'publish',
					       'output'                 => ARRAY_A,
					       'output_key'             => 'menu_order',
					       'nopaging'               => true,
					       'update_post_term_cache' => false ); 
					$i = 0;
					$raw = wp_get_nav_menu_items("Topper Menu",$args);
					foreach($raw as $menu){
						$menus[$i]['menu_name'] = $menu->post_title;
						$i++;
					}
					echo "<ul class='topper-menu large-9 columns  hide-for-small'>";
					for($i=0; $i<count($menus); $i++){
						if($menus[$i]['menu_name'] == "GET REWARD POINTS"){
				?>
							<li class='reward-points'><a><img src="<?php echo get_site_url(); ?>/wp-content/themes/flatsome-gh/images/grade_icon.png" title="grade reward points"/>
				<?php
							echo $menus[$i]['menu_name']; 
						}
						else{
						 echo "<li class=''><a>".$menus[$i]['menu_name']."</a></li>";
						}
					}
				   echo "</ul>"; 
				?>
				<ul class="minicart  small-2 large-3 columns">
					<!-- Show mini cart if Woocommerce is activated -->
					<?php if(!isset($flatsome_opt['myaccount_dropdown']) || $flatsome_opt['myaccount_dropdown']) { ?>
							<li class="account-dropdown">
								<?php
								if ( is_user_logged_in() ) { ?> 
								<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" class="nav-top-link nav-top-login">
									<img src="<?php echo get_site_url(); ?>/wp-content/themes/flatsome-gh/images/myaccount.png" title="my account"/>
									<span class="my-account-title hide-for-small">My Account<img src="<?php echo get_site_url(); ?>/wp-content/themes/flatsome-gh/images/down-arrow.png" title="my account"/></span>
								</a>
								<div class="nav-dropdown">
									<ul>
										<?php if ( has_nav_menu( 'my_account' ) ) : ?>
										<?php  
										wp_nav_menu(array(
											'theme_location' => 'my_account',
											'container'       => false,
											'items_wrap'      => '%3$s',
											'depth'           => 0,
										));
										?>
				                        <?php else: ?>
				                            <li>Define your My Account dropdown menu in <b>Apperance > Menus</b></li>
				                        <?php endif; ?>	
									</ul>
								</div><!-- end account dropdown -->
								<?php } else { ?>
								<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" class="nav-top-link nav-top-not-logged-in"><img src="<?php echo get_site_url(); ?>/wp-content/themes/flatsome-gh/images/myaccount.PNG" title="login"/>									<span class="my-account-title hide-for-small">My Account<img src="<?php echo get_site_url(); ?>/wp-	content/themes/flatsome-gh/images/down-arrow.png" title="my account"/></span></a>
								<?php }  ?>						
							</li>
					<?php } ?>
					<?php if(!isset($flatsome_opt['show_cart']) || $flatsome_opt['show_cart'] == 1) { 
							if(function_exists('wc_print_notices')) { ?> 
								<li class="mini-cart">
									<div class="cart-inner">
										<?php // Edit this content in inc/template-tags.php. Its gets relpaced with Ajax! ?>
										<a href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" class="cart-link">
											<strong class="cart-name hide-for-small"><?php _e('Cart', 'woocommerce'); ?></strong> 
											<span class="cart-price hide-for-small">/ <?php echo $woocommerce->cart->get_cart_total(); ?></span> 
												<!-- cart icon -->
												<div class="cart-image">
							                        <?php if ($flatsome_opt['custom_cart_icon']){ ?> 
							                        <div class="custom-cart-inner">
								                        <div class="custom-cart-count">
								                        	<?php echo $woocommerce->cart->cart_contents_count; ?>
								                        </div>
								                        <img class="custom-cart-icon" src="<?php echo $flatsome_opt['custom_cart_icon']?>"/> 
							                        </div><!-- .custom-cart-inner -->
							                        <?php } else { ?> 
							                        <strong>
							                         	<?php echo $woocommerce->cart->cart_contents_count; ?>
							                        </strong>
							                        <span class="cart-icon-handle"></span>
							                        <?php }?>
												</div><!-- end cart icon -->
										</a>
										<div class="nav-dropdown">
										  	<div class="nav-dropdown-inner">
											<!-- Add a spinner before cart ajax content is loaded -->
												<?php if ($woocommerce->cart->cart_contents_count == 0) {
													echo '<p class="empty">'.__('No products in the cart.','woocommerce').'</p>';
												?> 
												<?php } else { //add a spinner ?> 
													<div class="loading"><i></i><i></i><i></i><i></i></div>
												<?php } ?>
											</div><!-- nav-dropdown-innner -->
										</div><!-- .nav-dropdown -->
									</div><!-- .cart-inner -->
								</li><!-- .mini-cart -->
					  <?php } ?>
					<?php } ?>
				</ul>	
			</div><!-- .large-12 columns -->




			</div><!-- .row -->
		</div><!-- .#top-bar -->
                    <?php } ?>
		<?php }?>


		<header id="masthead" class="site-header" role="banner">
			<div class="row"> 
				<div class="large-12 header-container">
					<?php /*<div class="mobile-menu show-for-small"><a href="#open-menu"><span class="icon-menu"></span></a></div><!-- end mobile menu --> */?>
					
					<?php if($flatsome_opt['logo_position'] == 'left') : ?> 
                    <div class="header-logo">
					<div id="logo" class="logo-left">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?> - <?php bloginfo( 'description' ); ?>" rel="home">
							<?php if($flatsome_opt['site_logo']){
								$site_title = esc_attr( get_bloginfo( 'name', 'display' ) );
								echo '<img src="'.$flatsome_opt['site_logo'].'" class="header_logo" alt="'.$site_title.'"/>';
								if ( is_page_template( 'page-transparent-header-light.php' )) {
								  if($flatsome_opt['site_logo_dark']){
								  	echo '<img src="'.$flatsome_opt['site_logo_dark'].'" class="header_logo_dark" alt="'.$site_title.'"/>';
								  }
								}
							} else {bloginfo( 'name' );}?>
						</a>
					</div><!-- .logo -->
                    </div>
					<?php endif; ?>
            <?php if(is_page(array('cart', 'checkout'))) { ?>
                <div class="small-12 large-6  columns cart_page">
                    <?php 
                        wp_nav_menu(array(
								'theme_location' => 'gh_logged_out_top_bar_menu_location',
								'menu_class' => 'top-bar-nav',
								'before' => '',
								'after' => '',
								'link_before' => '',
								'link_after' => '',
								'depth' => 1,
								'fallback_cb' => false,
								'walker' => new FlatsomeNavDropdown
		                                            ));          
			?>       
                </div>
            <div class="cart_page_call_icon">    
	            <?php 
					dynamic_sidebar("gh_header_contact_widget_area");
				?>
			</div>
	<?php } else{ ?>
    <!-- Sticky search hidden -->
<div class="search-menu-container sticky-hidden">
	<div class="search-box-row">
		<div class="row collapse search-wrapper">
			<form method="GET" id="searchform" class="searchform" action="<?php echo site_url();?>/courses/">
  				<div class="large-10 small-10 columns">
   					<input type="search" class="field" name="s" id="s" value="<?php echo $_GET['s']; ?>" placeholder="<?php echo _e( 'Search the courses e.g. MBA, BA, BBA, ', 'woocommerce' ); ?>&hellip;" />
  				</div><!-- input -->
	  			<div class="large-2 small-2 columns">
              		<input type="submit" class="button secondary postfix gh_search_form" value="GO">
	  			</div><!-- button -->
			</form>
		</div><!-- row -->
	</div>
</div>
<!-- End Sticky search hidden -->

    <div class="menu-features">
		<div class="right-text right edu_topbar">
		     <?php 
	               if ( has_nav_menu( 'top_bar_nav' )  ) { 
					     wp_nav_menu(array(
						'theme_location' => 'gh_logged_out_top_bar_menu_location',
						'menu_class' => 'top-bar-nav',
						'before' => '',
						'after' => '',
						'link_before' => '',
						'link_after' => '',
						'depth' => 1,
						'fallback_cb' => false,
						'walker' => new FlatsomeNavDropdown
				    ));                                                    
	                }else{ ?>
	                  Define your top bar navigation in <b>Apperance > Menus</b>
	                <?php } ?>
        
		<?php if($flatsome_opt['logo_position'] == 'center') { ?> 
				<div id="logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?> - <?php bloginfo( 'description' ); ?>" rel="home">
						<?php if($flatsome_opt['site_logo']){
							$site_title = esc_attr( get_bloginfo( 'name', 'display' ) );
							echo '<img src="'.$flatsome_opt['site_logo'].'" class="header_logo" alt="'.$site_title.'"/>';
							if ( is_page_template( 'page-transparent-header-light.php' )) {
							  if($flatsome_opt['site_logo_dark']){
							  	echo '<img src="'.$flatsome_opt['site_logo_dark'].'" class="header_logo_dark" alt="'.$site_title.'"/>';
							  }
							}
						} else {bloginfo( 'name' );}?>
					</a>
				</div><!-- .logo -->
		<?php } ?>
	    </div><!-- .large-12 -->
    </div>
    <?php 
    	dynamic_sidebar("gh_header_contact_widget_area");
	?>
    <?php } ?> 
</div>	
<div class="large-12 search-menu-container">
	<?php if (!is_page(array('cart', 'checkout'))){ ?>
				<div class="search-box-row search-div large-8 small-12">
					<div class="row collapse search-wrapper">
						<form method="GET" id="searchform" class="searchform" action="<?php echo site_url();?>/courses/">
			  				<div class="large-10 small-10 columns">
			   					<input type="search" class="field" name="s" id="s" value="<?php echo $_GET['s']; ?>" placeholder="<?php echo _e( 'Search the courses e.g. MBA, BA, BBA, ', 'woocommerce' ); ?>&hellip;" />
			  				</div><!-- input -->
				  			<div class="large-2 small-2 columns">
			              		<input type="submit" class="button secondary postfix gh_search_form" value="GO">
				  			</div><!-- button -->
						</form>
					</div><!-- row -->
				</div>
				<div class="no-padding menu-container large-4">
					<div class="browse-cat hide-for-small" id="browse-cat">Browse Course Categories
						<span style="float:right"><img src="<?php echo get_site_url(); ?>/wp-content/themes/flatsome-gh/images/detail-tab-icon.png" style="width:20px"/></span>
					</div>
					<div id="mega-menu" <?php if (!is_front_page()){echo "style='display:none';";}?> >
						<?php 
							echo do_shortcode('[block id="mega-menu"]'); 
						?>
					</div>
				</div>
	<?php }?>	
</div>
<!-- .row -->


</header><!-- .header -->

<?php if($flatsome_opt['nav_position'] == 'bottom' || $flatsome_opt['nav_position'] == 'bottom_center') { ?>
<!-- Main navigation - Full width style -->
<div class="wide-nav <?php echo $flatsome_opt['nav_position_color']; ?> <?php if($flatsome_opt['nav_position'] == 'bottom_center') {echo 'nav-center';} else {echo 'nav-left';} ?>">
	<div class="row">
		<div class="large-12 columns">
		<div class="nav-wrapper">
		<ul id="site-navigation" class="header-nav">
				<?php if ( has_nav_menu( 'primary' ) ) : ?>
					<?php  
					wp_nav_menu(array(
						'theme_location' => 'primary',
						'container'       => false,
						'items_wrap'      => '%3$s',
						'depth'           => 3,
						'walker'          => new FlatsomeNavDropdown
					));
				?>

				<?php if($flatsome_opt['search_pos'] == 'right' && $flatsome_opt['nav_position'] == 'bottom_center'){ ?>
					<li class="search-dropdown">
					<a href="#" class="nav-top-link icon-search" onClick="return false;"></a>
					<div class="nav-dropdown">
						<?php if(function_exists('get_product_search_form')) {
							get_product_search_form();
						} else {
							get_search_form();
						} ?>	
					</div><!-- .nav-dropdown -->
				</li><!-- .search-dropdown -->
				<?php } ?>
              <?php else: ?>
                  <li>Define your main navigation in <b>Apperance > Menus</b></li>
              <?php endif; ?>								
		</ul>
		<?php if($flatsome_opt['nav_position'] == 'bottom') { ?>
		<div class="right hide-for-small">
			<div class="wide-nav-right">
				<div>
				<?php echo do_shortcode($flatsome_opt['nav_position_text']); ?>
			</div>
				<?php if($flatsome_opt['search_pos'] == 'right'){ ?>
							<div>
									<?php if(function_exists('get_product_search_form')) {
											get_product_search_form();
										} else {
											get_search_form();
										} ?>		
							</div>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
		</div><!-- .nav-wrapper -->
		</div><!-- .large-12 -->
	</div><!-- .row -->
</div><!-- .wide-nav -->
<?php } ?>
</div><!-- .header-wrapper -->

<div id="main-content" class="site-main  <?php echo $flatsome_opt['content_color']; ?>">
<?php 
//adds a border line if header is white
if (strpos($flatsome_opt['header_bg'],'#fff') !== false && $flatsome_opt['nav_position'] == 'top') {
		  echo '<div class="row"><div class="large-12 columns"><div class="top-divider"></div></div></div>';
} ?>

<?php if($flatsome_opt['html_after_header']){
	// AFTER HEADER HTML BLOCK
	echo '<div class="block-html-after-header" style="position:relative;top:-1px;">';
	echo do_shortcode($flatsome_opt['html_after_header']);
	echo '</div>';
} ?>

<!-- woocommerce message -->
<?php  if(function_exists('wc_print_notices')) {wc_print_notices();}?>
