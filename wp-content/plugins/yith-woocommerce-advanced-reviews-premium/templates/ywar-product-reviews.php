<?php
/**
 * Display single product reviews (comments)
 *
 * @author        Yithemes
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


global $product;
global $YWAR_AdvancedReview;


if ( ! comments_open( $product->id ) ) {
	return;
}

$reviews_count = count( $YWAR_AdvancedReview->get_product_reviews_by_rating( $product->id ) );
?>

<?php do_action( 'yith_advanced_reviews_before_reviews' ); ?>
<div id="reviews">
	<div id="comments">
		<h2><?php
			if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' && $reviews_count ) {
				printf( _n( '%s review for %s', '%s reviews for %s', $reviews_count, 'ywar' ), $reviews_count, get_the_title() );
			} else {
				_e( 'Reviews', 'ywar' );
			}
			?>
		</h2>

		<?php if ( $reviews_count ) : ?>

			<?php do_action( 'yith_advanced_reviews_before_review_list', $product ); ?>

			<ol class="commentlist">
				<?php $YWAR_AdvancedReview->reviews_list( $product->id,
					apply_filters( 'yith_advanced_reviews_reviews_list', null, $product->id ), true ); ?>
			</ol>

			<?php do_action( 'yith_advanced_reviews_after_review_list', $product ); ?>

		<?php else : ?>

			<p class="woocommerce-noreviews"><?php _e( 'There are no reviews yet.', 'ywar' ); ?></p>

		<?php endif; ?>
	</div>

	<?php 
	if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->id ) ) : 
		$args = array('user_id' => get_current_user_id(), 'post_id' => $product->id);
		//echo count(get_comments($args)).'    '.$product->id;
		if (count(get_comments($args)) <= 0 ) : 
	?>
		<div id="review_form_wrapper">
			<div id="review_form">
				<?php
				$commenter = wp_get_current_commenter();

				$comment_form = array(
					'title_reply'          => $reviews_count ? __( 'Add a review', 'ywar' ) : __( 'Be the first to review', 'ywar' ) . ' &ldquo;' . get_the_title() . '&rdquo;',
					'title_reply_to'       => __( 'Write a reply', 'ywar' ),
					'comment_notes_before' => '',
					'comment_notes_after'  => '',
					'fields'               => array(
						'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name', 'ywar' ) . ' <span class="required">*</span></label> ' .
						            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" /></p>',
						'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email', 'ywar' ) . ' <span class="required">*</span></label> ' .
						            '<input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" aria-required="true" /></p>',
					),
					'label_submit'         => __( 'Submit', 'ywar' ),
					'logged_in_as'         => '',
					'comment_field'        => ''
				);

				$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . __( 'Your Review', 'ywar' ) . ' <span style="  color: rgb(205, 25, 25);">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" maxlength="2000"></textarea></p>';

				$comment_form['comment_field'] .= '<input type="hidden" name="action" value="submit-form" />';
				comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
				?>
			</div>
		</div>
		<?php else : ?>
			<p class="woocommerce-verification-required"><?php _e( 'A customer can comment only once for specific product.', 'ywar' ); ?></p>
		<?php endif; ?>
	<?php else : ?>
		<p class="woocommerce-verification-required"><?php _e( 'Only logged in customers who have purchased this product may write a review.', 'ywar' ); ?></p>
	<?php endif; ?>

	<div class="clear"></div>
</div>