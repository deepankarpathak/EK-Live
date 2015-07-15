<?php
/**
 * Single Product title
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
	<?php 
	/** Output the WooCommerce Breadcrum  */
        // GH: Commenting out the default breadcrumb on Single Product page and adding the breadcrumb as per the given design.
        $defaults = array(
            'delimiter'  => '<span>/</span>',
            'wrap_before'  => '<h4 class="breadcrumb">',
            'wrap_after' => '</h4>',
            'before'   => '',
            'after'   => '',
            'home'    => true
        );
        $args = wp_parse_args(  $defaults  );
        woocommerce_get_template( 'global/breadcrumb-single-product.php', $args );  
        
       global $product;
        $specialization = $product->get_attribute("specialization");
        $show_specialization = $product->get_attribute("show-specialization");
        $university_name = wc_get_product_terms($product->id, 'university')[0];
        //$univ_shrt_name = get_tax_meta($university_name->term_id,'university_short_name');
        $univ = get_option( "taxonomy_".$university_name->term_id );
        $univ_shrt_name = get_term_meta($university_name->term_id, 'university_short_name', true);
        //$univ_shrt_name = $term_meta[ 'university_short_name' ];
        //echo "Short Name".$univ_shrt_name;
        //$product_title = the_title();
        ?>
<!--<h4><a href="<?php// echo site_url(); ?>">Home</a> <span>/</span> <span class="course_category"><?php //echo $product->get_attribute("Course Category");?></span></h4>-->
<h1 itemprop="name" class="entry-title"><?php if($show_specialization =='Yes'){echo the_title() .'('.$specialization .')' . ( (trim($univ_shrt_name) != '') ? ' - '.$univ_shrt_name: '') ; } else{ echo the_title() . ( (trim($univ_shrt_name) != '') ? ' - '.$univ_shrt_name: '');} ?></h1>
