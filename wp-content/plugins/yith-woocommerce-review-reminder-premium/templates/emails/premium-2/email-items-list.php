<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

$review_list = '';

$site_url   = get_option( 'siteurl' );
$assets_url = untrailingslashit( YWRR_ASSETS_URL );

if ( strpos( $assets_url, $site_url ) === false ) {
    $assets_url = $site_url . $assets_url;
}

$items_css       = 'display: block; padding: 20px 0; color:#429889; height: 135px; font-size: 16px; font-weight: bold; text-decoration: none; border-bottom: 1px solid #dbdbdb;';
$items_img_css   = 'display: block; float:left; height: 135px; margin-right: 20px;';
$items_name_span = 'display: block; margin: 25px 0 0 0;';
$items_vote_span = 'display: inline-block; font-size: 11px; color: #6e6e6e; line-height: 40px; text-transform: uppercase; background: url(' . $assets_url . '/images/rating-stars.png) no-repeat bottom left; padding: 0 0 22px 0; width: 150px;';

foreach ( $item_list as $item ) {

    $image = '';

    if ( has_post_thumbnail( $item['id'] ) ) {

        $product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $item['id'] ), 'ywrr_picture' );
        list( $src, $width, $height ) = $product_image;


        if ( strpos( $src, $site_url ) === false ) {
            $src = $site_url . $src;
        }

        $image = $src;

    }
    elseif ( wc_placeholder_img_src() ) {

        $src = wc_placeholder_img_src();

        if ( strpos( $src, $site_url ) === false ) {
            $src = $site_url . $src;
        }

        $image = $src;

    }

    $product_link = apply_filters( 'ywrr_product_permalink', get_permalink( $item['id'] ) );

    $review_list .= '<a class="items" style="' . $items_css . '" href="' . $product_link . '" ><img src="' . $image . '" style="' . $items_img_css . '" /><span style="' . $items_name_span . '">' . $item['name'] . ' &gt;</span><span style="' . $items_vote_span . '">' . __( 'Your Vote', 'ywrr' ) . '</span></a>';

}

return $review_list;