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

if ( !class_exists( 'YWRR_Schedule_Premium' ) ) {

    /**
     * Implements scheduling functions for YWRR plugin
     *
     * @class   YWRR_Schedule_Premium
     * @package Yithemes
     * @since   1.0.0
     * @author  Your Inspiration Themes
     *
     */
    class YWRR_Schedule_Premium {

        /**
         * Single instance of the class
         *
         * @var \YWRR_Schedule_Premium
         * @since 1.0.0
         */
        protected static $instance;

        /**
         * Returns single instance of the class
         *
         * @return \YWRR_Schedule_Premium
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

        }

        /**
         * Reschedule the mail sending
         *
         * @since   1.0.0
         *
         * @param   $order_id       int the order id
         * @param   $scheduled_date string the date of rescheduling
         * @param   $list           array optional list of items to request a review
         *
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function reschedule( $order_id, $scheduled_date, $list ) {
            global $wpdb;

            $wpdb->update(
                $wpdb->prefix . 'ywrr_email_schedule',
                array(
                    'scheduled_date' => $scheduled_date,
                    'mail_status'    => 'pending',
                    'request_items' => maybe_serialize( $list )
                ),
                array( 'order_id' => $order_id ),
                array(
                    '%s'
                ),
                array( '%d' )
            );
        }

        /**
         * Handles mass reschedule of email after options change
         *
         * @since   1.0.0
         *
         * @param   $option array plugin options
         *
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function mass_reschedule( $option ) {

            if ( 'ywrr_mail_reschedule' == $option['id'] && isset( $_POST[$option['id']] ) && '1' == $_POST[$option['id']] ) {

                if ( $_POST['ywrr_mail_schedule_day'] != get_option( 'ywrr_mail_schedule_day' ) ) {

                    global $wpdb;

                    $new_interval = $_POST['ywrr_mail_schedule_day'];

                    $orders = $wpdb->get_results( "
                    SELECT    order_id,
                              order_date,
                              request_items
                    FROM      {$wpdb->prefix}ywrr_email_schedule
                    WHERE     mail_status = 'pending'
                    " );

                    foreach ( $orders as $item ) {
                        $new_scheduled_date = date( 'Y-m-d', strtotime( $item->order_date . ' + ' . $new_interval . ' days' ) );

                        var_dump( $new_scheduled_date );

                        $wpdb->update(
                            $wpdb->prefix . 'ywrr_email_schedule',
                            array(
                                'scheduled_date' => $new_scheduled_date
                            ),
                            array( 'order_id' => $item->order_id ),
                            array(
                                '%s'
                            ),
                            array( '%d' )
                        );

                        if ( isset( $_POST['ywrr_mail_send_rescheduled'] ) && '1' == $_POST['ywrr_mail_send_rescheduled'] ) {

                            $list = maybe_unserialize( $item->request_items );

                            $today     = new DateTime( current_time( 'mysql' ) );
                            $send_date = new DateTime( $new_scheduled_date );
                            $pay_date  = new DateTime( $item->order_date );
                            $days      = $pay_date->diff( $today );

                            if ( $send_date <= $today ) {
                                YWRR_Emails()->send_email( $item->order_id, $days->days, array(), $list );
                                YWRR_Schedule()->change_schedule_status( $item->order_id, 'sent' );
                            }

                        }

                    }

                }

            }

        }

    }

    /**
     * Unique access to instance of YWRR_Schedule_Premium class
     *
     * @return \YWRR_Schedule_Premium
     */
    function YWRR_Schedule_Premium() {
        return YWRR_Schedule_Premium::get_instance();
    }

}