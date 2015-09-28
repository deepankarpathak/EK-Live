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

if ( !class_exists( 'YWRR_Ajax' ) ) {

    /**
     * Implements AJAX for YWRR plugin
     *
     * @class   YWRR_Ajax
     * @package Yithemes
     * @since   1.0.0
     * @author  Your Inspiration Themes
     *
     */
    class YWRR_Ajax {

        /**
         * Single instance of the class
         *
         * @var \YWRR_Ajax
         * @since 1.0.0
         */
        protected static $instance;

        /**
         * Returns single instance of the class
         *
         * @return \YWRR_Ajax
         * @since 1.0.0
         */
        public static function get_instance() {

            if ( is_null( self::$instance ) ) {

                self::$instance = new self( $_REQUEST );

            }

            return self::$instance;
        }

        /**
         * Constructor
         *
         * @since   1.0.0
         * @return  mixed
         * @author  Alberto Ruggiero
         */
        public function __construct() {

            add_action( 'wp_ajax_ywrr_send_request_mail', array( $this, 'send_request_mail' ) );
            add_action( 'wp_ajax_ywrr_reschedule_mail', array( $this, 'reschedule_mail' ) );
            add_action( 'wp_ajax_ywrr_cancel_mail', array( $this, 'cancel_mail' ) );
            add_action( 'wp_ajax_ywrr_send_test_mail', array( $this, 'send_test_mail' ) );

        }

        /**
         * Send a request mail from order details page
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function send_request_mail() {
            ob_start();
            $order_id        = $_POST['order_id'];
            $items_to_review = json_decode( sanitize_text_field( stripslashes( $_POST['items_to_review'] ) ), true );

            $today    = new DateTime( current_time( 'mysql' ) );
            $pay_date = new DateTime( $_POST['order_date'] );
            $days     = $pay_date->diff( $today );

            try {

                YWRR_Emails()->send_email( $order_id, $days->days, $items_to_review );

                if ( YWRR_Schedule()->check_exists_schedule( $order_id ) != 0 ) {
                    YWRR_Schedule()->change_schedule_status( $order_id, 'sent' );
                }

                wp_send_json( true );

            } catch ( Exception $e ) {

                wp_send_json( array( 'error' => $e->getMessage() ) );

            }
        }

        /**
         * Reschedule mail from order details page
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function reschedule_mail() {
            ob_start();
            $order_id        = $_POST['order_id'];
            $items_to_review = json_decode( sanitize_text_field( stripslashes( $_POST['items_to_review'] ) ), true );
            $scheduled_date  = date( 'Y-m-d', strtotime( current_time( 'mysql' ) . ' + ' . get_option( 'ywrr_mail_schedule_day' ) . ' days' ) );
            $list            = null;

            if ( $items_to_review != null ) {
                $list = YWRR_Emails_Premium()->get_review_list_forced( $items_to_review, $order_id );
            }

            try {

                if ( YWRR_Schedule()->check_exists_schedule( $order_id ) != 0 ) {
                    YWRR_Schedule_Premium()->reschedule( $order_id, $scheduled_date, $list );
                }
                else {
                    YWRR_Schedule()->schedule_mail( $order_id, $list );
                }

                wp_send_json( true );

            } catch ( Exception $e ) {

                wp_send_json( array( 'error' => $e->getMessage() ) );

            }
        }

        /**
         * Cancel schedule mail from order details page
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function cancel_mail() {
            ob_start();
            $order_id = $_POST['order_id'];

            try {

                if ( YWRR_Schedule()->check_exists_schedule( $order_id ) != 0 ) {
                    YWRR_Schedule()->change_schedule_status( $order_id );

                    wp_send_json( true );
                }
                else {
                    wp_send_json( 'notfound' );
                }

            } catch ( Exception $e ) {

                wp_send_json( array( 'error' => $e->getMessage() ) );

            }
        }

        /**
         * Send a test mail from option panel
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function send_test_mail() {
            ob_start();

            $total_products = wp_count_posts( 'product' );

            if ( !$total_products->publish ) {

                wp_send_json( array( 'error' => __( 'In order to send the test email, at least one product has to be published', 'ywrr' ) ) );

            }
            else {

                $args = array(
                    'posts_per_page' => 2,
                    'orderby'        => 'rand',
                    'post_type'      => 'product'
                );

                $random_products = get_posts( $args );

                $test_items = array();

                foreach ( $random_products as $item ) {

                    $test_items[$item->ID]['id']   = $item->ID;
                    $test_items[$item->ID]['name'] = $item->post_title;

                }

                $days       = get_option( 'ywrr_mail_schedule_day' );
                $test_email = $_POST['email'];
                $template   = $_POST['template'];

                try {

                    $wc_email = WC_Emails::instance();
                    $email    = $wc_email->emails['YWRR_Request_Mail'];
                    $email->trigger( 0, $test_items, $days, $test_email, $template );

                    wp_send_json( true );

                } catch ( Exception $e ) {

                    wp_send_json( array( 'error' => $e->getMessage() ) );

                }

            }

        }

    }

    /**
     * Unique access to instance of YWRR_Ajax class
     *
     * @return \YWRR_Ajax
     */
    function YWRR_Ajax() {

        return YWRR_Ajax::get_instance();

    }

}

