<?php
global $post, $product, $woocommerce;
$attachment_ids = $product->get_gallery_attachment_ids();

// run quick view hooks
do_action('wc_quick_view_before_single_product');
?> 

<div class="row collapse edu_quickview">

    <div class="large-6 columns">    
        <div class="product-image images">
            <div class="iosSlider product-gallery-slider" style="height:<?php $height = get_option('shop_single_image_size');
echo ($height['height'] - 60) . 'px!important'; ?>">

                <div class="slider" >

                    <?php if (has_post_thumbnail()) : ?>

                        <?php
                        //Get the Thumbnail URL
                        $src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false, '');
                        ?>

                        <div class="slide" >
                            <span itemprop="image"><?php echo get_the_post_thumbnail($post->ID, apply_filters('single_product_large_thumbnail_size', 'shop_single')) ?></span>
                        </div>

                    <?php endif; ?>	

                    <?php
                    if ($attachment_ids) {

                        $loop = 0;
                        $columns = apply_filters('woocommerce_product_thumbnails_columns', 3);

                        foreach ($attachment_ids as $attachment_id) {

                            $classes = array('zoom');

                            if ($loop == 0 || $loop % $columns == 0)
                                $classes[] = 'first';

                            if (( $loop + 1 ) % $columns == 0)
                                $classes[] = 'last';

                            $image_link = wp_get_attachment_url($attachment_id);

                            if (!$image_link)
                                continue;

                            $image = wp_get_attachment_image($attachment_id, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail'));
                            $image_class = esc_attr(implode(' ', $classes));
                            $image_title = esc_attr(get_the_title($attachment_id));

                            printf('<div class="slide"><span>%s</span></div>', wp_get_attachment_image($attachment_id, apply_filters('single_product_large_thumbnail_size', 'shop_single')), wp_get_attachment_url($attachment_id));

                            $loop++;
                        }
                    }
                    ?>

                </div>
                <div class="sliderControlls dark">
                    <div class="sliderNav small hide-for-small">
                        <a href="javascript:void(0)" class="nextSlide prev_product_slider"><span class="icon-angle-left"></span></a>
                        <a href="javascript:void(0)" class="prevSlide next_product_slider"><span class="icon-angle-right"></span></a>
                    </div>
                </div><!-- .sliderControlls -->

            </div><!-- .slider -->
        </div><!-- .product-image -->

    </div><!-- large-6 -->
<?php
$specialization = $product->get_attribute("specialization");
$show_specialization = $product->get_attribute("show-specialization");
$provider_object = wc_get_product_terms($product->id, 'shop_vendor')[0];
$university_name = wc_get_product_terms($product->id, 'university')[0];
$duration = $product->get_attribute("duration");
$labels = $product->get_attribute("labels");
$univ = get_option( "taxonomy_".$university_name->term_id );
$univ_shrt_name = get_term_meta($university_name->term_id, 'university_short_name', true);
$studycontent = $product->get_attribute("study-content");
?>
    <div class="large-6 columns">
        <div class="product-lightbox-inner product-info edu-quickview-info">
            <h1 itemprop="name" class="entry-title"><a href="<?php the_permalink(); ?>"><?php if($show_specialization =='Yes'){echo the_title() .' ('.$specialization.')'.' - '.$univ_shrt_name ; } else{ echo the_title() .( (trim($univ_shrt_name) != '') ? ' - '.$univ_shrt_name: '')  ;}  ?></a></h1>
            <p  class="edu-ins-name-detail" ><?php echo 'Institution : ' . $university_name->name; ?></p>
            <p class="duration_below_title">
                <span class="short-description_sapn" style="border-right:1px solid #CCC;padding-right: 5px;"><?php echo $duration; ?></span> 
                <span><?php echo "Provider : " . $provider_object->name; ?></span>
                <span class="edu-ins-name" style ="display:inline-block; padding-left:5px;border-left:1px solid #CCC;">Study Content : <?php echo $studycontent ; ?></span>
            </p>

<?php do_action('woocommerce_single_product_lightbox_summary'); ?>
        </div>
    </div>


