<?php
/**
 * Woocommerce Compare page
 *
 * @author Your Inspiration Themes
 * @package YITH Woocommerce Compare
 * @version 1.1.4
 */

// remove the style of woocommerce
if( defined('WOOCOMMERCE_USE_CSS') && WOOCOMMERCE_USE_CSS ) wp_dequeue_style('woocommerce_frontend_styles');

$is_iframe = (bool)( isset( $_REQUEST['iframe'] ) && $_REQUEST['iframe'] );

wp_enqueue_script( 'jquery-fixedheadertable', YITH_WOOCOMPARE_ASSETS_URL . '/js/jquery.dataTables.min.js', array('jquery'), '1.3', true );
wp_enqueue_script( 'jquery-fixedcolumns', YITH_WOOCOMPARE_ASSETS_URL . '/js/FixedColumns.min.js', array('jquery', 'jquery-fixedheadertable'), '1.3', true );
wp_enqueue_script( 'jquery-fixedcolumns', YITH_WOOCOMPARE_ASSETS_URL . 'assets/js/jquery.colorbox-min.js', array('jquery', 'jquery-fixedheadertable'), '1.3', true );

$widths = array();
foreach( $products as $product ) $widths[] = '{ "sWidth": "205px", resizeable:true }';

/** FIX WOO 2.1 */
$wc_get_template = function_exists('wc_get_template') ? 'wc_get_template' : 'woocommerce_get_template';

$table_text = get_option( 'yith_woocompare_table_text' );
$localized_table_text = function_exists( 'icl_translate' ) ? icl_translate( 'Plugins', 'plugin_yit_compare_table_text', $table_text ) : $table_text;

?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if IE 9]>
<html id="ie9" class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if gt IE 9]>
<html class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if !IE]>
<html <?php language_attributes() ?>>
<![endif]-->

<!-- START HEAD -->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width" />
    <title><?php _e( 'Product Comparison', 'yith-wcmp' ) ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" />
    <link rel="stylesheet" href="<?php echo $this->stylesheet_url() ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo YITH_WOOCOMPARE_URL ?>assets/css/colorbox.css"/>
    <link rel="stylesheet" href="<?php echo YITH_WOOCOMPARE_URL ?>assets/css/jquery.dataTables.css"/>

    <?php wp_head() ?>

    <style type="text/css">
        body.loading {
            background: url("<?php echo YITH_WOOCOMPARE_URL ?>assets/images/colorbox/loading.gif") no-repeat scroll center center transparent;
        }
    </style>
</head>
<!-- END HEAD -->

<?php global $product; ?>

<!-- START BODY -->
<body <?php body_class('woocommerce') ?>>

<h1>
    <?php echo $localized_table_text ?>
    <?php if ( ! $is_iframe ) : ?><a class="close" href="#"><?php _e( 'Close window [X]', 'yith-wcmp' ) ?></a><?php endif; ?>
</h1>

<?php do_action( 'yith_woocompare_before_main_table' );?>
<?php  $product_num = count($products);
    if($product_num == 4){
        echo "<div style='color:red;font-weight:bold;background:pink; padding:3px 3px 3px 35%;'>Only 4 products can be added in compare list !</div>";
    }
?>
<div class="compare-course-wrapper">
<table class="compare-list" cellpadding="0" cellspacing="0"<?php if ( empty( $products ) ) echo ' style="width:100%"' ?>>
    <thead>
    <tr>
        <th>&nbsp;</th>
        <?php foreach( $products as $i => $product ) : ?>
            <td></td>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>&nbsp;</th>
        <?php foreach( $products as $i => $product ) : ?>
            <td></td>
        <?php endforeach; ?>
    </tr>
    </tfoot>
    <tbody>

    <?php if ( empty( $products ) ) : ?>

        <tr class="no-products">
            <td><?php _e( 'No products added in the compare table.', 'yith-wcmp' ) ?></td>
        </tr>

    <?php else : ?>
        <tr class="remove">
            <th>&nbsp;</th>
            <?php foreach( $products as $i => $product ) : $product_class = ( $i % 2 == 0 ? 'odd' : 'even' ) . ' product_' . $product->id ?>
                <td class="<?php echo $product_class; ?>">
                    <a href="<?php echo add_query_arg( 'redirect', 'view', $this->remove_product_url( $product->id ) ) ?>" data-product_id="<?php echo $product->id; ?>"><?php _e( 'Remove', 'yit' ) ?> <span class="remove">x</span></a>
                </td>
            <?php endforeach ?>
            <?php if($product_num > 0 && $product_num < 4){ ?>
            <td style="background: #F1F1F1;border-left: 1px solid #DDD;"></td>
            <?php }?>
        </tr>
<?php //echo "<pre>"; print_r($fields);  die;
    $fields = array();
    $fields['image'] = 'Image';
    $fields['title'] = 'Title';
    $fields['price'] = 'Fee';
    $fields['pa_referral-cashback'] = 'Referral Cashback';
    $fields['institute'] = 'Institute';
    $fields['description'] = 'Description';
    $fields['pa_certification-type'] = 'Certification Type';
    $fields['pa_specialization'] = 'Specialization';
    $fields['pa_fee-type'] = 'Fee Type';
    $fields['pa_exam-center'] = 'Exam Center';
    $fields['pa_duration'] = 'Duration';
    $fields['pa_exam-mode'] = 'Exam Mode';
    $fields['pa_eligibility'] = 'Eligibility';
    $fields['pa_show-specialization'] = 'Show specialization';
    $fields['pa_domain'] = 'Domain';
    $fields['pa_product-category'] = 'Product Category';
    $fields['pa_study-content'] = 'Study Content';
    $fields['add-to-cart'] = 'Explore More';
?>
        <?php foreach ( $fields as $field => $name ) : ?>

            <tr class="<?php echo $field ?>">

                <th>
                    <?php echo $name ?>
                    <?php if ( $field == 'image' ) echo '<div class="fixed-th"></div>'; ?>
                </th>

                <?php foreach( $products as $i => $product ) : $product_class = ( $i % 2 == 0 ? 'odd' : 'even' ) . ' product_' . $product->id; ?>
                    <td class="<?php echo $product_class; ?>"><?php
                        switch( $field ) {

                            case 'image':
                                echo '<div class="image-wrap">' . wp_get_attachment_image( $product->fields[$field], 'yith-woocompare-image' ) . '</div>';
                                break;

                            case 'add-to-cart':
                                $wc_get_template( 'loop/add-to-cart.php' );
                                break;

                            case 'pa_referral-cashback':
                                if(empty($product->fields[$field])){
                                    $product->fields[$field] = "NA";
                                }
                                echo '<div class="referral custom-hide-small"><span class="cashback">Cashback</span> Rs.'.$product->fields[$field].'</div>';
                            break;

                            default:
                                echo empty( $product->fields[$field] ) ? '&nbsp;' : $product->fields[$field];
                                break;
                        }
                        ?>
                    </td>
                <?php endforeach ?>
                <?php if($product_num > 0 && $product_num < 4){ ?>
                    <td class="add_more_course" style="background: #F1F1F1;border-left: 1px solid #DDD;"><img class="add_more_compare" src="<?php echo get_site_url()?>/wp-content/themes/flatsome-gh/images/add_more_compare.png"/></td>
                <?php }?>
            </tr>

        <?php endforeach; ?>

        <?php if ( $repeat_price == 'yes' && isset( $fields['price'] ) ) : ?>
            <tr class="price repeated">
                <th><?php echo $fields['price'] ?></th>

                <?php foreach( $products as $i => $product ) : $product_class = ( $i % 2 == 0 ? 'odd' : 'even' ) . ' product_' . $product->id ?>
                    <td class="<?php echo $product_class ?>"><?php echo $product->fields['price'] ?></td>
                <?php endforeach; ?>

            </tr>
        <?php endif; ?>

        <?php if ( $repeat_add_to_cart == 'yes' && isset( $fields['add-to-cart'] ) ) : ?>
            <tr class="add-to-cart repeated">
                <th><?php echo $fields['add-to-cart'] ?></th>

                <?php foreach( $products as $i => $product ) : $product_class = ( $i % 2 == 0 ? 'odd' : 'even' ) . ' product_' . $product->id ?>
                    <td class="<?php echo $product_class ?>"><?php $wc_get_template( 'loop/add-to-cart.php' ); ?></td>
                <?php endforeach; ?>

            </tr>
        <?php endif; ?>

    <?php endif; ?>

    </tbody>
</table>
</div>
<?php do_action( 'yith_woocompare_after_main_table' ); ?>

<?php if( wp_script_is( 'responsive-theme', 'enqueued' ) ) wp_dequeue_script( 'responsive-theme' ) ?><?php if( wp_script_is( 'responsive-theme', 'enqueued' ) ) wp_dequeue_script( 'responsive-theme' ) ?>
<?php do_action('wp_print_footer_scripts'); ?>

<script type="text/javascript">

    jQuery(document).ready(function($){
        <?php if ( $is_iframe ) : ?>$('a').attr('target', '_parent');<?php endif; ?>

        var oTable;
        $('body').on( 'yith_woocompare_render_table', function(){
            if( $( window ).width() > 767 ) {
                oTable = $('table.compare-list').dataTable( {
                    "sScrollX": "100%",
                    //"sScrollXInner": "150%",
                    "bScrollInfinite": true,
                    "bScrollCollapse": true,
                    "bPaginate": false,
                    "bSort": false,
                    "bInfo": false,
                    "bFilter": false,
                    "bAutoWidth": false
                } );

                new FixedColumns( oTable );
                $('<table class="compare-list" />').insertAfter( $('h1') ).hide();
            }
        }).trigger('yith_woocompare_render_table');

        // add to cart
        var button_clicked;
        $(document).on('click', 'a.add_to_cart_button', function(){
            button_clicked = $(this);
            button_clicked.block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6}});
        });

        // remove add to cart button after added
        $('body').on('added_to_cart', function( ev, fragments, cart_hash, button ){
            button_clicked.hide();

            <?php if ( $is_iframe ) : ?>
            $('a').attr('target', '_parent');

            // Replace fragments
            if ( fragments ) {
                $.each(fragments, function(key, value) {
                    $(key, window.parent.document).replaceWith(value);
                });
            }
            <?php endif; ?>
        });

        // close window
        $(document).on( 'click', 'a.close', function(e){
            e.preventDefault();
            window.close();
        });

        $(window).on( 'yith_woocompare_product_removed', function(){
            if( $( window ).width() > 767 ) {
                oTable.fnDestroy(true);
            }
            $('body').trigger('yith_woocompare_render_table');
        });
    });

// remove from table
    $(document).on( 'click', '.remove a', function(e){
        e.preventDefault();
        var button = $(this),
            data = {
                _yitnonce_ajax: yith_woocompare.nonceremove,
                action: yith_woocompare.actionremove,
                id: button.data('product_id'),
                context: 'frontend'
            },
            product_cell = $( 'td.product_' + data.id + ', th.product_' + data.id );

        // add ajax loader
        if( typeof woocommerce_params != 'undefined' ) {
            button.block({
                message: null,
                overlayCSS: {
                    background: '#fff url(' + woocommerce_params.ajax_loader_url + ') no-repeat center',
                    backgroundSize: '16px 16px',
                    opacity: 0.6
                }
            });
        }

        $.ajax({
            type: 'post',
            url: yith_woocompare.ajaxurl,
            data: data,
            dataType:'html',
            success: function(response){

                if( typeof woocommerce_params != 'undefined' ) {
                    button.unblock();
                }

                // in compare table
                var table = $(response).filter('table.compare-list');
                $('body > table.compare-list').replaceWith( table );

                // removed trigger
                $(window).trigger('yith_woocompare_product_removed');
            }
        });
    });
$(".add_more_compare").hide();
$(".add_more_compare:first").show();
jQuery(document).on( 'click', '.add_more_compare', function(e){
    console.log(jQuery().colorbox);
    jQuery().colorbox.close();
});
</script>

</body>
</html>