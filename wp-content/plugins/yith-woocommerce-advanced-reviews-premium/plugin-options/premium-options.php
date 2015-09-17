<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$custom_attributes = defined( 'YITH_YWAR_PREMIUM' ) ? array() : array( 'disabled' => 'disabled' );
return array(

	'premium' => array(

		'section_vote_system_settings'     => array(
			'name' => __( 'Voting system', 'ywar' ),
			'type' => 'title',
			'id'   => 'ywar_section_general'
		),
		'vote_system_enable'               => array(
			'name'              => __( 'Show vote section', 'ywar' ),
			'type'              => 'checkbox',
			'desc'              => __( 'Allow user to upvote or downvote a review.', 'ywar' ),
			'id'                => 'ywar_enable_vote_system',
			'custom_attributes' => $custom_attributes,
			'default'           => 'yes'
		),
		'vote_system_show_peoples_choice'  => array(
			'name'              => __( 'Show review votes', 'ywar' ),
			'type'              => 'checkbox',
			'desc'              => __( 'Add a string stating how many people found the review useful.', 'ywar' ),
			'id'                => 'ywar_show_peoples_vote',
			'custom_attributes' => $custom_attributes,
			'default'           => 'yes'
		),
		'enable_visitors_vote'             => array(
			'name'    => __( 'Enable vote for all', 'ywar' ),
			'type'    => 'checkbox',
			'desc'    => __( 'Allow unregistered users to vote the reviews.', 'ywar' ),
			'id'      => 'ywar_enable_visitors_vote',
			'default' => 'no'
		),
		'section_vote_system_settings_end' => array(
			'type' => 'sectionend',
			'id'   => 'ywar_section_general_end'
		),
		'section_reviews_settings'         => array(
			'name'              => __( 'Review settings', 'ywar' ),
			'type'              => 'title',
			'custom_attributes' => $custom_attributes,
			'id'                => 'ywar_section_reviews'
		),
		'review_moderation'                => array(
			'name'    => __( 'Manual review approval', 'ywar' ),
			'type'    => 'checkbox',
			'desc'    => __( 'When a user submits a review, it should be manually approved to be showed.', 'ywar' ),
			'id'      => 'ywar_review_moderation',
			'default' => 'no'
		),
		'show_how_many_reviews'            => array(
			'name'              => __( 'Number of reviews to display', 'ywar' ),
			'type'              => 'number',
			'desc'              => __( 'Limit the maximum number of reviews to display, 0 for unlimited.', 'ywar' ),
			'id'                => 'ywar_review_per_page',
			'custom_attributes' => $custom_attributes + array(
					'min'      => 0,
					'step'     => 1,
					'required' => 'required'
				),
			'default'           => '0'
		),
		'show_load_more'                   => array(
			'name'              => __( '"Load more"', 'ywar' ),
			'type'              => 'select',
			'desc'              => __( 'Choose if reviews should be shown grouped and which link style should be applied.', 'ywar' ),
			'id'                => 'ywar_show_load_more',
			'options'           => array(
				'1' => __( 'Don\'t group reviews', 'ywar' ),
				'2' => __( 'Group reviews and show a textual link on bottom', 'ywar' ),
				'3' => __( 'Group reviews and show a button link on bottom', 'ywar' )
			),
			'custom_attributes' => $custom_attributes,
			'default'           => '1'
		),
		'show_reviews_dialog'              => array(
			'name'              => __( 'Modal window', 'ywar' ),
			'type'              => 'checkbox',
			'desc'              => __( 'Display reviews filtered by rate in a modal window.', 'ywar' ),
			'id'                => 'ywar_reviews_dialog',
			'custom_attributes' => $custom_attributes,
			'default'           => 'no'
		),
		'reply_to_review'                  => array(
			'name'              => __( 'Reply to review', 'ywar' ),
			'type'              => 'select',
			'desc'              => __( 'Choose who can reply to a review.', 'ywar' ),
			'id'                => 'ywar_reply_to_review',
			'options'           => array(
				'1' => __( 'No one can reply', 'ywar' ),
				'2' => __( 'Only administrators can reply', 'ywar' ),
				'3' => __( 'Everyone can reply', 'ywar' )
			),
			'custom_attributes' => $custom_attributes,
			'default'           => '2'
		),
		'report_inappropriate_review'      => array(
			'name'              => __( 'Inappropriate reviews', 'ywar' ),
			'type'              => 'select',
			'desc'              => __( 'Let users report a review as inappropriate content.', 'ywar' ),
			'id'                => 'ywar_report_inappropriate_review',
			'options'           => array(
				'0' => __( 'Not enabled', 'ywar' ),
				'1' => __( 'Only registered users can report an inappropriate content', 'ywar' ),
				'2' => __( 'Everyone can report an inappropriate content', 'ywar' ),
			),
			'custom_attributes' => $custom_attributes,
			'default'           => '2'
		),
		'hide_inappropriate_review'      => array(
			'name'              => __( 'Hiding threshold', 'ywar' ),
			'type'              => 'number',
			'desc'              => __( 'Hide temporarily a review when a specific number of users has flagged it as inappropriate. Set this value to 0 to never hide automatically the reviews.', 'ywar' ),
			'id'                => 'ywar_hide_inappropriate_review',
			'custom_attributes' => $custom_attributes + array(
					'min'      => 0,
					'step'     => 1,
					'required' => 'required'
				),
			'default'           => '0'
		),
		'show_featured_review'      => array(
			'name'              => __( 'Featured reviews', 'ywar' ),
			'type'              => 'number',
			'desc'              => __( 'Number of reviews to show as featured items. Set this value to 0 to never show featured reviews.', 'ywar' ),
			'id'                => 'ywar_featured_review',
			'custom_attributes' => $custom_attributes + array(
					'min'      => 0,
					'step'     => 1,
					'required' => 'required'
				),
			'default'           => '0'
		),
		'load_more_text'                   => array(
			'name'              => __( '"Load more" text', 'ywar' ),
			'type'              => 'text',
			'desc'              => __( 'Text to show in the textual link or button.', 'ywar' ),
			'id'                => 'ywar_load_more_text',
			'custom_attributes' => $custom_attributes,
			'default'           => __( 'Load more', 'ywar' )
		),
		'section_reviews_settings_end'     => array(
			'type' => 'sectionend',
			'id'   => 'ywar_section_reviews_end'
		)
	)
);