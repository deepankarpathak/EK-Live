<?php

/**
 * YWAR_Review_Report
 *
 * @author  Your Inspiration Themes
 * @package YITH WooCommerce EU VAT
 * @version 1.0.0
 */
class YWAR_Review_Report extends WC_Admin_Report {

	public $data_to_show = '';

	public function __construct( $name ) {
		$data_to_show = $name;
	}

	/**
	 * Get the legend for the main chart sidebar
	 * @return array
	 */
	public function get_chart_legend() {
		return array();
	}

	/**
	 * Output an export link
	 */
	public function get_export_button() {

		$current_range = ! empty( $_GET['range'] ) ? sanitize_text_field( $_GET['range'] ) : 'last_month';
		?>
		<a
			href="#"
			download="report-<?php echo esc_attr( $current_range ); ?>-<?php echo date_i18n( 'Y-m-d', current_time( 'timestamp' ) ); ?>.csv"
			class="export_csv"
			data-export="table"
			>
			<?php _e( 'Export CSV', 'ywar' ); ?>
		</a>
	<?php
	}

	/**
	 * Output the report
	 */
	public function output_report() {
		global $report_name;

		$ranges = array(
			'year'       => __( 'Year', 'ywar' ),
			'last_month' => __( 'Last Month', 'ywar' ),
			'month'      => __( 'This Month', 'ywar' ),
		);

		$current_range = ! empty( $_GET['range'] ) ? sanitize_text_field( $_GET['range'] ) : 'last_month';

		if ( ! in_array( $current_range, array( 'custom', 'year', 'last_month', 'month', '7day' ) ) ) {
			$current_range = 'last_month';
		}

		$this->calculate_current_range( $current_range );

		$hide_sidebar = true;

		include( YITH_YWAR_TEMPLATES_DIR . '/reports/ywar-html-review-report.php' );
	}


	/**
	 * Get the main chart
	 *
	 * @return string
	 */
	public function get_main_chart() {


		$start_date = date( "Y-m-d", $this->start_date );
		$end_date   = date( "Y-m-d", $this->end_date );

		$query = "SELECT wp_posts.id, wp_posts.post_title, count(*) as total, min(rev_meta.meta_value) as min_rating, max(rev_meta.meta_value) as max_rating, avg(rev_meta.meta_value)  as avg_rating
			FROM wp_posts
			left JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.meta_value )
			left join wp_posts rev ON (rev.id = wp_postmeta.post_id)
			left join wp_postmeta rev_meta ON (rev.ID = rev_meta.post_id)
			left join wp_postmeta rev_meta2 ON (rev.ID = rev_meta2.post_id)
			where ( (wp_posts.post_type = 'product')
				AND (rev.post_type = 'ywar_reviews')
				AND (rev.post_parent = 0)
				AND (wp_posts.post_status = 'publish')
				AND (rev_meta.meta_key = '_ywar_rating')
				AND (rev_meta2.meta_key = '_ywar_product_id')
				AND (rev_meta2.meta_value = wp_posts.id)
				AND (rev.post_date >= '$start_date 00:00:00')
				AND (rev.post_date <= '$end_date 23:59:59') )
			group by wp_posts.id";

		global $report_name;

		switch ( $report_name ) {
			case 'most-commented' :
				$query .= " order by total DESC";
				break;
			case 'min-rating' :
				$query .= " order by min_rating ASC";
				break;
			case 'max-rating' :
				$query .= " order by max_rating DESC";
				break;
			case 'average-rating' :
				$query .= " order by avg_rating DESC";
				break;
			default :
				$query .= " order by wp_posts.post_title ASC";
				break;
		}

		global $wpdb;
		$results = $wpdb->get_results( $query );

		$most_commented_class = 'most-commented' == $report_name ? 'selected' : '';
		$min_rating_class     = 'min-rating' == $report_name ? 'selected' : '';
		$max_rating_class     = 'max-rating' == $report_name ? 'selected' : '';
		$avg_rating_class     = 'avg-rating' == $report_name ? 'selected' : '';

		?>
		<table class="widefat product-review-stats">
			<thead>
			<tr>
				<th class="product"><?php _e( 'Product', 'ywar' ); ?></th>
				<th class="stats <?php echo $most_commented_class; ?>"><?php _e( 'Reviews', 'ywar' ); ?></th>
				<th class="stats <?php echo $max_rating_class; ?>"><?php _e( 'Best rate', 'ywar' ); ?></th>
				<th class="stats <?php echo $min_rating_class; ?>"><?php _e( 'Worst rate', 'ywar' ); ?></th>
				<th class="stats <?php echo $avg_rating_class; ?>"><?php _e( 'Average rate', 'ywar' ); ?></th>
			</tr>
			</thead>
			<?php if ( $results ) : ?>
				<tbody>
				<?php
				foreach ( $results as $product_stats ) {
					?>
					<tr>
						<th scope="row"><?php echo $product_stats->post_title; ?></th>
						<td class="stats <?php echo $most_commented_class; ?>"><?php echo $product_stats->total; ?></td>
						<td class="stats <?php echo $max_rating_class; ?>"><?php echo $product_stats->max_rating; ?></td>
						<td class="stats <?php echo $min_rating_class; ?>"><?php echo $product_stats->min_rating; ?></td>
						<td class="stats <?php echo $avg_rating_class; ?>"><?php echo round( $product_stats->avg_rating, 2 ); ?></td>
					</tr>
				<?php
				}
				?>
				</tbody>
			<?php else : ?>
				<tbody>
				<tr>
					<td><?php _e( 'No reviews found in this period', 'ywar' ); ?></td>
				</tr>
				</tbody>
			<?php endif; ?>
		</table>
	<?php
	}
}
