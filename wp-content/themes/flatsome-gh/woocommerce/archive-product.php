<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if(isset($_GET['s']) AND trim($_GET['s'] == '') ){
    $url = site_url()."/courses/";
    header($url);
}

gh_catalog_default_view();

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
global $flatsome_opt;
get_header('shop');

// Connect to Redis
$redisClient = new Redis();
$redisClient->connect('127.0.0.1');
?>


<script type="text/javascript">
  
    $(document).ready(function() {        
        jQuery('.js-center').select2({
            placeholder: "e.g. Delhi, Mumbai, etc.",
            allowClear: true
        });
        jQuery('.js-course-type').select2({
            placeholder: "e.g. Degree, Diploma, etc.",
            allowClear: true
        });
        jQuery('.js-study-content').select2({
            placeholder: "e.g. Books, Online etc.",
            allowClear: true
        });
        jQuery('.js-vendor').select2({
            placeholder: "Select Provider",
            allowClear: true
        });
        jQuery('.js-university').select2({
            placeholder: "Select Institution",
            allowClear: true
        });
        jQuery('.js-specialization').select2({
            placeholder: "e.g. Marketing, Finance, etc.",
            allowClear: true
        });
        jQuery('.js-eligibility').select2({
            placeholder: "Select Eligibility",
            allowClear: true
        });
        jQuery('.js-domain').select2({
            placeholder: "Select Domain",
            allowClear: true
        });
        
        function gh_connecto_integration(data){
            $.ajax({
                url: "<?php echo get_site_url() . "/wp-admin/admin-ajax.php" ?>",
                type: 'POST',
                data: {action: 'connect_form', Data:data},
                success: function() {

                }
            });
        }
        function gh_call_sidebar_filter_ajax(){
        
            var search = '<?php echo $_GET['s'] ; ?>';
            var filter_string = $(".filter_form").serializeArray();
            var vendor = [];
            var university = [];
            var exam_mode = [];
            var study_content = [];
            var center = [];
            var coursetype = [];
            var specialization = [];
            var eligibility = [];
            var domain = [];
            var min = $('.from').html();
            var max = $('.to').html();
            var conversion = "<?php echo $wp_session['conversion_rate'] ; ?>";
            $.each( $(".filter_vendor option:selected"), function() {
                vendor.push($(this).val());
            });
            $.each( $(".exam-mode input[type=checkbox]:checked"), function() {
                exam_mode.push($(this).val());
            });
            $.each( $(".filter_university option:selected"), function() {
                university.push($(this).val());
            });
            $.each( $(".filter_exam_center option:selected"), function() {
                center.push($(this).val());
            });
            $.each( $(".filter_course_type option:selected"), function() {
                coursetype.push($(this).val());
            });
            $.each( $(".filter_specialization option:selected"), function() {
                specialization.push($(this).val());
            });
            $.each( $(".filter_eligibility option:selected"), function() {
                eligibility.push($(this).val());
            });
            $.each( $(".filter_study_content option:selected"), function() {
                study_content.push($(this).val());
            });
            var paged = '<?php ( get_query_var('paged') ) ? get_query_var('paged') : 1;?>';
	    var client_ip_value = encodeURIComponent('<?php echo $wp_session['ip'] ?>');
	    var view = encodeURIComponent('<?php echo $_GET['view']; ?>');
	    var order = encodeURIComponent('<?php echo $_GET['orderby']; ?>');
            var vendorjoin = vendor.join(",");
            var universityjoin = university.join(",");
            var exam_center_join = center.join(",");
            var course_type= coursetype.join(",");
            var specializationjoin= specialization.join(",");
            var eligibilityjoin= eligibility.join(",");
            var domainjoin= domain.join(",");
            var exam_mode_join = exam_mode.join(",");
            var study_content_join = study_content.join(",");

            var min_amount = $("#amount1").val();
            var max_amount = $("#amount2").val();            
            formquerystring = $.param(filter_string);
            //var urlstring = "?"+formquerystring+"&view=list";
	    var urlstring = "?"+formquerystring+"&view="+view+"&orderby="+order ;

            $.ajax({
                url: "<?php echo get_site_url() . "/wp-admin/admin-ajax.php" ?>",
                type: 'GET',
                dataType: 'html',                
                
                data: {action: 'filter_search', provider: vendorjoin, minamount:min_amount, maxamount:max_amount, university: universityjoin, studycontent:study_content_join, exammode:exam_mode_join, Center:exam_center_join, typecourse:course_type, Specialization:specializationjoin, Eligibility:eligibilityjoin, Domain:domainjoin, Conversion_rate:conversion,client_ip:client_ip_value, Search:search, View:view,page:paged,orderby:order},
                success: function(response) {
                    //$(".products").html(response);   //ajax calling by filter result withhout page load        
                    location.href = urlstring;
                }
            });
            
         
        }
        $("input[type=checkbox], select").change(function() {
            gh_call_sidebar_filter_ajax();
            $(".woocommerce-result-count").html("");
        }); 

        $(".price_slider, .ui-slider-handle, #slider-range, .ui-slider-range").mouseup(function() {
            gh_call_sidebar_filter_ajax();
            $(".woocommerce-result-count").html("");
        });
        $('.page-numbers a').click(function(){
            
                var pagenum = $(this).attr('data-value');
               var currentpage;
               currentpage = "<?php echo $_GET['paged']; ?>";
//            alert(pagenum);
        if(currentpage === '' ){
            window.location = location.href+'&paged='+pagenum;
        }
        else{
            
            var newUrl = location.href.replace("paged="+currentpage, "paged="+pagenum);
            window.location  = newUrl;
        }
           
		//var paged =  $('.page-numbers a').click(val);
                //alert(paged);
	});
    });
</script>
<div class="cat-header">
    <?php
// GET CUSTOM HEADER CONTENT FOR CATEGORY
    if (function_exists('get_term_meta')) {
        $queried_object = get_queried_object();

        if (isset($queried_object->term_id)) {

            $term_id = $queried_object->term_id;
            $content = get_term_meta($term_id, 'cat_meta');

            if (isset($content[0]['cat_header'])) {
                echo do_shortcode($content[0]['cat_header']);
            }
        }
    }
    ?>
    <?php
    if (isset($flatsome_opt['html_shop_page']) && is_shop()) {
        // Add Custom HTML for shop page header
        if ($wp_query->query_vars['paged'] == 1 || $wp_query->query_vars['paged'] < 1) {
            echo do_shortcode($flatsome_opt['html_shop_page']);
        }
    }
    ?>
</div>


<div class="row category-page edu-catgery-page">

    <?php
    /**
     * woocommerce_before_main_content hook
     * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
     */
    do_action('woocommerce_before_main_content');
    ?>
    <div class="large-12 columns edu_filter_option">
        <div class="breadcrumb-row">
            <div class="large-3 small-4 columns">
                <?php
                /** Output the WooCommerce Breadcrumb  */
                $defaults = array(
                    'delimiter' => '<span>/</span>',
                    'wrap_before' => '<h3 class="breadcrumb">',
                    'wrap_after' => '</h3>',
                    'before' => '',
                    'after' => '',
                    'home' => 'Home'
                );
                $args = wp_parse_args($defaults);
                woocommerce_get_template('global/breadcrumb.php', $args);
                ?>
            </div><!-- .left -->

            <div class="large-9 small-12 columns ">
                <div class="edu-grid-list">
                    <div id="edu-grid" class="edu-grid active" onclick="change_grid_view_url()">  </div>
                    <div id="edu-list" class="edu-list" onclick="change_list_view_url()">  </div>
                </div>	
<?php do_action('ux_woocommerce_navigate_products'); ?>    

            </div><!-- .right -->
        </div><!-- .breadcrumb-row -->
    </div><!-- .large-12 breadcrumb -->

    <script type="text/javascript">
        jQuery(document).ready(function() {
            /*** Add attribute ***/
            function GetURLParameter(sParam)
            {
                var sPageURL = window.location.search.substring(1);
                var sURLVariables = sPageURL.split('&');
                for (var i = 0; i < sURLVariables.length; i++)
                {
                    var sParameterName = sURLVariables[i].split('=');
                    if (sParameterName[0] == sParam)
                    {
                        return sParameterName[1];
                    }
                }
            }
            var view = GetURLParameter('view');
            if (view == 'list') {
                jQuery(".grid1").attr("class", "product-small  grid1 edu-list-product");
                jQuery(".edu-list").attr("class", "edu-list active");
                jQuery(".edu-grid").attr("class", "edu-grid");
            } else if (view == 'grid') {
                jQuery(".grid1").attr("class", "product-small  grid1");
                jQuery(".edu-grid").attr("class", "edu-grid active");
                jQuery(".edu-list").attr("class", "edu-list");
            }

        });
    </script>


            <?php if ($flatsome_opt['category_sidebar'] == 'right-sidebar') { ?>
        <div class="large-9 columns left edu-catgery_list">
                <?php } else if ($flatsome_opt['category_sidebar'] == 'left-sidebar') { ?>
            <div class="large-9 columns right edu-catgory-list">
                <?php } else { ?>
                <div class="large-12 columns">
                <?php } ?>
                    
                <?php if( (isset($_GET['s']) AND trim($_GET['s']) != '') OR ($_GET['gh_filter_search']=='yes') ){
                ?>     <ul class="products small-block-grid-2 large-block-grid-4">
                        <?php 
                            $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
                            $conversion = $wp_session['conversion_rate'];
                            $client_ip = $wp_session['ip'];

                            if(isset($_GET['s'])){
                                $s =  urlencode($_GET['s']);
                            }
     			    if(isset($_GET['view'])){
                                $view = urlencode($_GET['view']);
                            }
                            if(isset($_GET['orderby'])){
                                $order = urlencode($_GET['orderby']);
                            }
                            if(isset($_GET['min_value'])){
                                $min =  urlencode($_GET['min_value']);
                            }
                            if(isset($_GET['max_value'])){
                                $max =  urlencode($_GET['max_value']);
                            }
                            if(isset($_GET['exam_center'])){
                                $examcenter = urlencode(implode("," , $_GET['exam_center']));
                            }
                            if(isset($_GET['course_type'])){
                                $coursetype =  urlencode(implode("," , $_GET['course_type']));
                            }
                            if(isset($_GET['study_content'])){
                                $studycontent =  urlencode(implode("," , $_GET['study_content']));
                            }
                            if(isset($_GET['exam_mode'])){
                                $exammode =  urlencode(implode("," , $_GET['exam_mode']));
                            }
                            if(isset($_GET['provider'])){
                                $provider =  urlencode(implode("," , $_GET['provider']));
                            }
                            if(isset($_GET['institutions'])){
                                $institutions =  urlencode(implode("," , $_GET['institutions']));
                            }
			    			if(isset($_GET['specialization'])){
                                $specialization =  urlencode(implode("," , $_GET['specialization']));
                            }			
			   				$wp_session['catalog_view_type'] = $view;

                            //print_r($_GET);
                            $curl = curl_init();
                            $url =  get_site_url()."/wp-admin/admin-ajax.php";
                            $url = $url.'?action=filter_search&Search='.$s.'&minamount='.$min.'&maxamount='.$max.'&Center='.$examcenter.'&typecourse='.$coursetype.'&Specialization='.$specialization.'&studycontent='.$studycontent.'&exammode='.$exammode.'&provider='.$provider.'&university='.$institutions.'&Conversion_rate='.$conversion.'&client_ip='.$client_ip.'&View='.$view.'&page='.$paged.'&orderby='.$order ;
							$search_cache_key = md5($url);
							
							$time_start = microtime(true);
							if ($redisClient -> exists($search_cache_key )){
								$resp = $redisClient -> get($search_cache_key);;
							}else{
								curl_setopt_array($curl, array(
									CURLOPT_RETURNTRANSFER => 1,
									CURLOPT_URL => $url,
								));
								$resp = curl_exec($curl);
	//				echo "<p>E:".curl_error($curl)."</p>";
								curl_close($curl);
								 $redisClient -> setex($search_cache_key, 604800, $resp );
							}
                            $time_end = microtime(true);
							$time = $time_end - $time_start;
							echo "<div id='microtime_for_redis' style='display:none;'>". $time ."seconds</div>";
                            echo $resp;
							 $redisClient -> flushall() ;    //to drop the redis cache 
				if($client_ip == '122.160.51.238'){
					$email_msg = "<p>\$client_ip = $client_ip</p><p>Position: 1</p><p>\$url = $url</p><p>\$resp = $resp</p>";
				}
                        ?>
                        
                     </ul>
                <?php } else{ 
//                            echo "<pre>";
//                            print_r($_GET);
//                            echo "<pre/>";
//                            exit;
                    
                            $conversion = $wp_session['conversion_rate'];
                            $client_ip = $wp_session['ip'];
                            if(isset($_GET['orderby'])){
                                $order = $_GET['orderby'];
                            }
                            if(isset($_GET['view'])){
                                $view = urlencode($_GET['view']);
                            }else{
                                $view = 'list';
                            }
                            $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
//                            echo "<p>".__LINE__." ". $paged ."</p>";



//                            $curl = curl_init();
                            $test_url =  get_site_url()."/wp-admin/admin-ajax.php";
                            $test_url = $test_url.'?action=test_api&x=test';
//                            curl_setopt_array($curl, array(
//                                CURLOPT_RETURNTRANSFER => 1,
//                                CURLOPT_URL => $test_url,
//                            ));
//                            $test_resp = curl_exec($curl);
//			    curl_close($curl);
//				echo "<p>$test_url</p>";
				$test_resp = wp_remote_get( $test_url, array( 'timeout' => 120, 'httpversion' => '1.1' ) );
//				echo "<p>".__LINE__."</p>";
//			    echo "<p>$test_resp</p>";
//				echo "<pre>";
//				var_dump($test_resp);


                            $curl = curl_init();
                            $url =  get_site_url()."/wp-admin/admin-ajax.php";
                            $url = $url.'?action=filter_search&result=all&page='.$paged.'&orderby='.$order.'&Conversion_rate='.$conversion.'&client_ip='.$client_ip.'&view='.$view ;
							$search_cache_key = md5($url);
							
							//$time_start = microtime(true);
							if ($redisClient -> exists($search_cache_key )){
								$resp = $redisClient -> get($search_cache_key);;
							}else{
								curl_setopt_array($curl, array(
									CURLOPT_RETURNTRANSFER => 1,
									CURLOPT_URL => $url,
								));
                            	$resp = curl_exec($curl);
								$redisClient -> setex($search_cache_key, 604800, $resp );
							}
//				echo "<p>\$url = $url</p>";
//				$resp_return = wp_remote_get( $url, array( 'timeout' => 120, 'httpversion' => '1.1' ) );
//				echo "<pre>";
//				print_r($resp);
//				print_r($resp_return);
//				print_r($resp_return);
//				$resp = $resp_return['body'];
//				$resp = wp_remote_retrieve_body($resp_return);

				//echo "<div id='debug_div' style='display:none;'>$url</div>";
//				echo "<p>$url</p>";
//				echo "<pre>";
//				var_dump($curl);
//				echo "</pre>";
//				echo var_dump($resp);

//				echo "<p>E:".curl_error($curl)."</p>";

//					$curl_error_for_debug = curl_error($curl);
//					$curl_errorno_for_debug = curl_errno($curl);

//                            curl_close($curl);
                            echo $resp;
							 $redisClient -> flushall() ; 		//to drop the redis cache
				if($client_ip == '122.160.51.238'){
					$curl_for_debug = print_r($curl, true);

					$email_msg = "<p>\$client_ip = $client_ip</p><p>Position: 2</p><p>\$curl_for_debug = $curl_for_debug</p><p>\$url = $url</p><p>\$resp = $resp</p>  \$curl_error_for_debug = $curl_error_for_debug | \$curl_errorno_for_debug = $curl_errorno_for_debug";

				}

                        ?>

                <?php
                /**
                 * woocommerce_after_main_content hook
                 *
                 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
                 */
                do_action('woocommerce_after_main_content');
                ?>

                <?php if ($flatsome_opt['search_result'] && get_search_query()) : ?>
                    <?php
                    /**
                     * Include pages and posts in search
                     */
                    query_posts(array('post_type' => array('post', 'page'), 's' => get_search_query()));

                    if (have_posts()) {
                        echo '<div class="row"><div class="large-12 columns"><hr/>';
                    }

                    while (have_posts()) : the_post();
                        $wc_page = false;
                        if ($post->post_type == 'page') {
                            foreach (array('myaccount', 'edit_address', 'change_password', 'lost_password', 'shop', 'cart', 'checkout', 'pay', 'view_order', 'thanks', 'terms') as $wc_page_type) {
                                if ($post->ID == woocommerce_get_page_id($wc_page_type))
                                    $wc_page = true;
                            }
                        }
                        if (!$wc_page)
                            get_template_part('content', get_post_format());
                    endwhile;

                    if (have_posts()) {
                        echo '</div></div>';
                    }

                    wp_reset_query();
                    ?>
                    <?php endif; ?>


                <?php } ?>
            </div><!-- .large-12 -->

<?php if ($flatsome_opt['category_sidebar'] == 'right-sidebar') { ?>
                <!-- Right Shop sidebar -->
                <div class="large-3 right columns edu-catgery_list">
                    <div class="sidebar-inner">
    <?php dynamic_sidebar('shop-sidebar'); ?>
                    </div>
                </div>            
<?php } else if ($flatsome_opt['category_sidebar'] == 'left-sidebar') { ?>
                <style>



                </style>
                <div class="filter-bg"> </div>
                <div class="block block-layered-nav flt_nav">
                    <div class="flt_nav-list">
                        <!-- Left Shop sidebar -->
                        <div class="large-3 left columns edu-catgory-filter">
                            <form class="filter_form"><div class="sidebar-inner">
    <?php dynamic_sidebar('shop-sidebar'); ?>
                                </div>
                                <?php if(isset($_GET['s']) AND trim($_GET['s'])!='') { ?>
                                <input type="hidden" name="s" value="<?php echo $_GET['s']; ?>" />
                                <?php } ?>
                                <input type="hidden" name="gh_filter_search" value="yes" />
                            </form>
                        </div>
                    </div>
                </div>

        <?php } ?>


        </div><!-- end row -->

        <?php
// GET CUSTOM HEADER CONTENT FOR CATEGORY
        if (function_exists('get_term_meta')) {
            $queried_object = get_queried_object();

            if (isset($queried_object->term_id)) {

                $term_id = $queried_object->term_id;
                $content = get_term_meta($term_id, 'cat_meta');

                if (isset($content[0]['cat_footer'])) {
                    echo '<div class="row"><div class="large-12 column"><div class="cat-footer"><hr/>';
                    echo do_shortcode($content[0]['cat_footer']);
                    echo '</div></div></div>';
                }
            }
        }
        ?>

<?php get_footer('shop'); ?>
