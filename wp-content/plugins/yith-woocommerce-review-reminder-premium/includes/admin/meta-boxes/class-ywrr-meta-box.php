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
    exit; // Exit if accessed directly
}

/**
 * Shows Meta Box in order's details page
 *
 * @class   YWRR_Meta_Box
 * @package Yithemes
 * @since   1.0.0
 * @author  Your Inspiration Themes
 *
 */
class YWRR_Meta_Box {

    /**
     * Output Meta Box
     *
     * The function to be called to output the meta box in order details page.
     *
     * @since   1.0.0
     *
     * @param   $post object the current order
     *
     * @return  void
     * @author  Alberto Ruggiero
     */
    public static function output( $post ) {

        $customer_id    = get_post_meta( $post->ID, '_customer_user', true );
        $customer_email = get_post_meta( $post->ID, '_billing_email', true );

        if ( YWRR_Blocklist()->check_blocklist( $customer_id, $customer_email ) == true ) {
            $items        = get_option( 'ywrr_request_number' );
            $request_type = get_option( 'ywrr_request_type' );

            if ( $request_type == 'all' ) {
                $criteria = __( 'Reviews will be requested for all items in the order', 'ywrr' );
            }
            else {

                if ( $items > 1 ) {
                    $criteria = sprintf( __( 'Reviews will be requested for %d items in the order', 'ywrr' ), $items );
                }
                else {
                    $criteria = __( 'Reviews will be requested for 1 item in the order', 'ywrr' );
                }

            }

            ?>
            <div class="toolbar">
                <?php echo $criteria; ?>
                <br />
                <?php _e( 'You can override this setting by selecting the products from the list below.', 'ywrr' ) ?>
                <p class="buttons">
                    <button type="button" class="button-primary do-send-email"><?php _e( 'Send Email', 'ywrr' ); ?></button>
                    <button type="button" class="button-secondary do-reschedule-email"><?php _e( 'Reschedule Email', 'ywrr' ); ?></button>
                    <button type="button" class="button-secondary do-cancel-email"><?php _e( 'Cancel Email', 'ywrr' ); ?></button>
                </p>
                <div class="clear"></div>
            </div>
        <?php
        }
        else {
            ?>
            <div class="toolbar">
                <?php _e( 'This customer doesn\'t want to receive any more review requests', 'ywrr' ) ?>
            </div>
        <?php
        }
    }

}