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

/**
 * Displays the schedule table in YWRR plugin admin tab
 *
 * @class   YWRR_Schedule_Table
 * @package Yithemes
 * @since   1.0.0
 * @author  Your Inspiration Themes
 *
 */
class YWRR_Schedule_Table {

    /**
     * Outputs the schedule list template
     *
     * @since   1.0.0
     * @return  void
     * @author  Alberto Ruggiero
     */
    public static function output() {

        global $wpdb;

        $table = new YITH_Custom_Table( array(
                                            'singular' => __( 'reminder', 'ywrr' ),
                                            'plural'   => __( 'reminders', 'ywrr' )
                                        ) );

        $table->options = array(
            'select_table'     => $wpdb->prefix . 'ywrr_email_schedule',
            'select_columns'   => array(
                'id',
                'order_id',
                'order_date',
                'scheduled_date',
                'request_items',
                'mail_status',
            ),
            'select_where'     => '',
            'select_group'     => '',
            'select_order'     => 'scheduled_date',
            'select_order_dir' => 'DESC',
            'per_page_option'  => 'mails_per_page',
            'count_table'      => $wpdb->prefix . 'ywrr_email_schedule',
            'count_where'      => '',
            'key_column'       => 'id',
            'view_columns'     => array(
                'cb'             => '<input type="checkbox" />',
                'order_id'       => __( 'Order', 'ywrr' ),
                'request_items'  => __( 'Items to review', 'ywrr' ),
                'order_date'     => __( 'Date of Order Completed', 'ywrr' ),
                'scheduled_date' => __( 'E-mail Scheduled Date', 'ywrr' ),
                'mail_status'    => __( 'Status', 'ywrr' )
            ),
            'hidden_columns'   => array(),
            'sortable_columns' => array(
                'order_id'       => array( 'order_id', false ),
                'order_date'     => array( 'order_date', false ),
                'scheduled_date' => array( 'scheduled_date', false ),
            ),
            'custom_columns'   => array(
                'column_mail_status'   => function ( $item, $me ) {

                    switch ( $item['mail_status'] ) {

                        case 'sent':
                            $class = 'sent';
                            $tip   = __( 'Sent', 'ywrr' );
                            break;
                        case 'cancelled':
                            $class = 'cancelled';
                            $tip   = __( 'Cancelled', 'ywrr' );
                            break;
                        default;
                            $class = 'on-hold';
                            $tip   = __( 'On Hold', 'ywrr' );

                    }

                    return sprintf( '<mark class="%s tips" data-tip="%s">%s</mark>', $class, $tip, $tip );

                },
                'column_order_id'      => function ( $item, $me ) {

                    $the_order = wc_get_order( $item['order_id'] );

                    $customer_tip = '';

                    if ( $address = $the_order->get_formatted_billing_address() ) {
                        $customer_tip .= __( 'Billing:', 'ywrr' ) . ' ' . $address . '<br/><br/>';
                    }

                    if ( $the_order->billing_phone ) {
                        $customer_tip .= __( 'Phone:', 'ywrr' ) . ' ' . $the_order->billing_phone;
                    }

                    if ( $the_order->billing_first_name || $the_order->billing_last_name ) {
                        $username = trim( $the_order->billing_first_name . ' ' . $the_order->billing_last_name );
                    }
                    else {
                        $username = __( 'Guest', 'ywrr' );
                    }

                    $order_query_args = array(
                        'post'   => absint( $item['order_id'] ),
                        'action' => 'edit'
                    );
                    $order_url        = esc_url( add_query_arg( $order_query_args, admin_url( 'post.php' ) ) );
                    $order_number     = '<a href="' . $order_url . '"><strong>#' . esc_attr( $the_order->get_order_number() ) . '</strong></a>';

                    $customer_email = '<a href="' . esc_url( 'mailto:' . $the_order->billing_email ) . '">' . esc_html( $the_order->billing_email ) . '</a>';

                    $query_args = array(
                        'page'   => $_GET['page'],
                        'tab'    => $_GET['tab'],
                        'action' => 'delete',
                        'id'     => $item['id']
                    );
                    $delete_url = esc_url( add_query_arg( $query_args, admin_url( 'admin.php' ) ) );
                    $actions    = array(
                        'delete' => '<a href="' . $delete_url . '">' . __( 'Cancel Schedule', 'ywrr' ) . '</a>',
                    );

                    return '<div class="tips" data-tip="' . esc_attr( wc_sanitize_tooltip( $customer_tip ) ) . '">' . sprintf( _x( '%s by %s', 'Order number by X', 'ywrr' ), $order_number, $username ) . ' - ' . $customer_email . '</div>' . $me->row_actions( $actions );

                },
                'column_request_items' => function ( $item, $me ) {

                    if ( $item['request_items'] == '' ) {

                        return __( 'As general settings', 'ywrr' );

                    }
                    else {
                        $items        = 0;
                        $items_tip    = '';
                        $review_items = maybe_unserialize( $item['request_items'] );

                        foreach ( $review_items as $item ) {
                            $items_tip .= $item['name'] . '<br />';
                            $items ++;
                        }

                        return '<div class="tips" data-tip="' . $items_tip . '">' . sprintf( _n( '%s item to review', '%s items to review', $items, 'ywrr' ), $items ) . '</div>';

                    }

                }
            ),
            'bulk_actions'     => array(
                'actions'   => array(
                    'delete' => __( 'Cancel Schedule', 'ywrr' ),
                ),
                'functions' => array(
                    'function_delete' => function () {
                        global $wpdb;

                        $ids = isset( $_GET['id'] ) ? $_GET['id'] : array();
                        if ( is_array( $ids ) ) {
                            $ids = implode( ',', $ids );
                        }

                        if ( !empty( $ids ) ) {
                            $wpdb->query( "UPDATE {$wpdb->prefix}ywrr_email_schedule SET mail_status = 'cancelled' WHERE id IN ( $ids )" );

                        }
                    },
                )
            ),
        );

        $table->prepare_items();

        $message = '';
        $notice  = '';

        $query_args       = array(
            'page' => $_GET['page'],
            'tab'  => $_GET['tab']
        );
        $schedulelist_url = esc_url( add_query_arg( $query_args, admin_url( 'admin.php' ) ) );

        if ( 'delete' === $table->current_action() ) {
            $message = sprintf( __( 'Email unscheduled: %d', 'ywrr' ), count( $_GET['id'] ) );
        }

        ?>
        <div class="wrap">
            <h2>
                <?php _e( 'Scheduled Reminders', 'ywrr' ); ?>
            </h2>
            <?php

            if ( !empty( $notice ) ) : ?>
                <div id="notice" class="error below-h2"><p><?php echo $notice; ?></p></div>
            <?php endif;

            if ( !empty( $message ) ) : ?>
                <div id="message" class="updated below-h2"><p><?php echo $message; ?></p></div>
            <?php endif; ?>

            <form id="custom-table" method="GET" action="<?php echo $schedulelist_url; ?>">
                <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
                <input type="hidden" name="tab" value="<?php echo $_GET['tab'] ?>" />

                <?php $table->display(); ?>
            </form>
        </div>
    <?php
    }

    /**
     * Add screen options for schedule table template
     *
     * @since   1.0.0
     * @return  void
     * @author  Alberto Ruggiero
     */
    public static function add_options() {
        if ( 'yit-plugins_page_yith_ywrr_panel' == get_current_screen()->id && isset( $_GET['tab'] ) && $_GET['tab'] == 'schedule' ) {

            $option = 'per_page';

            $args = array(
                'label'   => __( 'Reminders', 'ywrr' ),
                'default' => 10,
                'option'  => 'mails_per_page'
            );

            add_screen_option( $option, $args );
        }
    }

    /**
     * Set screen options for schedule table template
     *
     * @since   1.0.0
     *
     * @param   $status
     * @param   $option
     * @param   $value
     *
     * @return  mixed
     * @author  Alberto Ruggiero
     */
    public static function set_options( $status, $option, $value ) {

        return ( 'mails_per_page' == $option ) ? $value : $status;

    }

}