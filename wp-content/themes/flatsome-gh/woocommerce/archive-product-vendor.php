<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
Template name: Vendor Page 
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

gh_catalog_default_view();

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $flatsome_opt;
get_header('shop'); ?>

<div class="cat-header">
<?php 
// GET CUSTOM HEADER CONTENT FOR CATEGORY
if(function_exists('get_term_meta')){
	$queried_object = get_queried_object();
	
	if (isset($queried_object->term_id)){

		$term_id = $queried_object->term_id;  
		$content = get_term_meta($term_id, 'cat_meta');

		if(isset($content[0]['cat_header'])){
			echo do_shortcode($content[0]['cat_header']);
		}
	}
}
?>
<?php if(isset($flatsome_opt['html_shop_page']) && is_shop()) {
	// Add Custom HTML for shop page header
	if($wp_query->query_vars['paged'] == 1 || $wp_query->query_vars['paged'] < 1){
		echo do_shortcode($flatsome_opt['html_shop_page']);
	}
} ?>
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
        'delimiter'  => '<span>/</span>',
        'wrap_before'  => '<h3 class="breadcrumb">',
        'wrap_after' => '</h3>',
        'before'   => '',
        'after'   => '',
        'home'    => 'Home'
    );
    $args = wp_parse_args(  $defaults  );
    woocommerce_get_template( 'global/breadcrumb.php', $args );
    ?>
    </div><!-- .left -->

    <!--<div class="large-9 small-12 columns ">
    <div class="edu-grid-list">
         <div id="edu-grid" class="edu-grid active" onclick="change_grid_view_url()">  </div>
        <div id="edu-list" class="edu-list" onclick="change_list_view_url()">  </div>
        </div>	
    	<?php //do_action( 'ux_woocommerce_navigate_products'); ?>    
        
    </div><!-- .right -->
</div><!-- .breadcrumb-row -->
</div><!-- .large-12 breadcrumb -->


<script type="text/javascript">
    jQuery(document).ready(function(){   
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
		if(view == 'list'){
			jQuery(".grid1").attr("class","product-small  grid1 edu-list-product");
			jQuery(".edu-list").attr("class","edu-list active");
			jQuery(".edu-grid").attr("class", "edu-grid");
		}else if(view == 'grid'){
			jQuery(".grid1").attr("class","product-small  grid1");
			jQuery(".edu-grid").attr("class","edu-grid active");
			 jQuery(".edu-list").attr("class", "edu-list");
		} 
		
    });
</script>


<?php if($flatsome_opt['category_sidebar'] == 'right-sidebar') { ?>
       <div class="large-9 columns right edu-catgery_list">
<?php } else if ($flatsome_opt['category_sidebar'] == 'left-sidebar') { ?>
		<div class="large-9 columns left edu-catgory-list">
<?php } else { ?>
		<div class="large-12 columns">
<?php } ?>
    <?php
        /* GH Comments: 
         * We are modifying the default product search LOOP here. Hooks and Functions used here are as following:
         * Hook: pre_get_posts
         * Function: gh_add_custom_product_taxonomies
         * File: functions-lk.php
         */
        
    ?>
	<?php if ( have_posts() ) : do_action( 'woocommerce_before_shop_loop' ); ?><?php endif; ?>
    <?php do_action( 'woocommerce_archive_description' ); ?>

		<?php if ( have_posts() ) : ?>

			<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_product_subcategories(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php woocommerce_get_template_part( 'content', 'product' ); ?>
				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php woocommerce_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('woocommerce_after_main_content');
	?>

  <?php if( $flatsome_opt['search_result'] && get_search_query() ) : ?>
    <?php
      /**
       * Include pages and posts in search
       */
      query_posts( array( 'post_type' => array( 'post', 'page' ), 's' => get_search_query() ) );

      if(have_posts()){ echo '<div class="row"><div class="large-12 columns"><hr/>'; }

      while ( have_posts() ) : the_post();
        $wc_page = false;
        if($post->post_type == 'page'){
          foreach (array('myaccount', 'edit_address', 'change_password', 'lost_password', 'shop', 'cart', 'checkout', 'pay', 'view_order', 'thanks', 'terms') as $wc_page_type) {
            if( $post->ID == woocommerce_get_page_id($wc_page_type) ) $wc_page = true;
          }
        }
        if( !$wc_page ) get_template_part( 'content', get_post_format() );
      endwhile;

      if(have_posts()){ echo '</div></div>'; }

      wp_reset_query();
    ?>
  <?php endif; ?>


                      
 </div><!-- .large-12 -->

<?php if($flatsome_opt['category_sidebar'] == 'right-sidebar') { ?>
<!-- Right Shop sidebar -->
        <div class="large-3 right columns edu-catgery_list">
        	<div class="sidebar-inner">
            	<?php dynamic_sidebar('shop-sidebar'); ?>
       		</div>
        </div>            
<?php } else if ($flatsome_opt['category_sidebar'] == 'left-sidebar') { ?>
<style>


 
</style>
<div class="block block-layered-nav flt_nav">
<div class="flt_nav-list">
<!-- Left Shop sidebar -->
		<div class="large-3 left columns edu-catgory-filter">
			<div class="sidebar-inner">
           		<?php dynamic_sidebar('sidebar-main'); ?>
            </div>
        </div>
         </div>
         </div>
<?php } ?>


</div><!-- end row -->

<?php 
// GET CUSTOM HEADER CONTENT FOR CATEGORY
if(function_exists('get_term_meta')){
	$queried_object = get_queried_object();
	
	if (isset($queried_object->term_id)){

		$term_id = $queried_object->term_id;  
		$content = get_term_meta($term_id, 'cat_meta');

		if(isset($content[0]['cat_footer'])){
			echo '<div class="row"><div class="large-12 column"><div class="cat-footer"><hr/>';
			echo do_shortcode($content[0]['cat_footer']);
			echo '</div></div></div>';
		}
	}
}
?>

<?php get_footer('shop'); ?>
