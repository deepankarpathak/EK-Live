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

return array(
    'settings' => array(
        'review_reminder_request_section_title' => array(
            'name' => __( 'General Settings', 'ywrr' ),
            'type' => 'title',
            'desc' => '',
            'id'   => 'ywrr_request_settings_title',
        ),
        'review_reminder_schedule_day'          => array(
            'name'              => __( 'Days to elapse', 'ywrr' ),
            'type'              => 'number',
            'desc'              => __( 'Type here the number of days that have to pass after the order has been set as "completed" before sending an email for reminding users to review the item(s)purchased. Defaults to 7 <br/> Note: Changing this WILL NOT re-schedule currently scheduled emails. If you would like to reschedule emails to this new date, make sure you check the \'Reschedule emails\' checkboxes below.', 'ywrr' ),
            'default'           => 7,
            'id'                => 'ywrr_mail_schedule_day',
            'custom_attributes' => array(
                'min'      => 1,
                'required' => 'required'
            )
        ),
        'review_reminder_request_type'          => array(
            'name'    => __( 'Request a review for', 'ywrr' ),
            'type'    => 'select',
            'desc'    => __( 'Select the products you want to aks for a review', 'ywrr' ),
            'options' => array(
                'all'       => __( 'All products in order', 'ywrr' ),
                'selection' => __( 'Specific products', 'ywrr' )
            ),
            'default' => 'all',
            'id'      => 'ywrr_request_type'
        ),
        'review_reminder_request_number'        => array(
            'name'              => __( 'Number of products for review request', 'ywrr' ),
            'type'              => 'number',
            'desc'              => __( 'Set the number of products from the order to include in the review reminder email. Default: 1', 'ywrr' ),
            'default'           => 1,
            'id'                => 'ywrr_request_number',
            'custom_attributes' => array(
                'min'      => 1,
                'required' => 'required'
            )
        ),
        'review_reminder_request_criteria'      => array(
            'name'    => __( 'Send review reminder for', 'ywrr' ),
            'type'    => 'select',
            'desc'    => '',
            'options' => array(
                'first'               => __( 'First products(s) bought', 'ywrr' ),
                'last'                => __( 'Last products(s) bought', 'ywrr' ),
                'highest_quantity'    => __( 'Products with highest number of items bought', 'ywrr' ),
                'lowest_quantity'     => __( 'Products with lowest number of items bought', 'ywrr' ),
                'most_reviewed'       => __( 'Products with highest number of reviews', 'ywrr' ),
                'least_reviewed'      => __( 'Products with lowest number of reviews', 'ywrr' ),
                'highest_priced'      => __( 'Products with highest price', 'ywrr' ),
                'lowest_priced'       => __( 'Products with lowest price', 'ywrr' ),
                'highest_total_value' => __( 'Products with highest total value', 'ywrr' ),
                'lowest_total_value'  => __( 'Products with lowest total value', 'ywrr' ),
                'random'              => __( 'Random', 'ywrr' ),
            ),
            'default' => 'first',
            'id'      => 'ywrr_request_criteria'
        ),
        'review_reminder_reschedule'            => array(
            'name'          => __( 'Reschedule emails', 'ywrr' ),
            'type'          => 'checkbox',
            'desc'          => __( 'Reschedule all currently scheduled emails to the new date defined above', 'ywrr' ),
            'id'            => 'ywrr_mail_reschedule',
            'default'       => 'no',
            'checkboxgroup' => 'start'
        ),
        'review_reminder_send_rescheduled'      => array(
            'name'          => __( 'Reschedule emails', 'ywrr' ),
            'type'          => 'checkbox',
            'desc'          => __( 'Send emails if rescheduled date has already passed', 'ywrr' ),
            'id'            => 'ywrr_mail_send_rescheduled',
            'default'       => 'no',
            'checkboxgroup' => 'end'
        ),
        'review_reminder_request_section_end'   => array(
            'type' => 'sectionend',
            'id'   => 'ywrr_request_settings_end'
        ),
    )
);