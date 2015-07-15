<?php
/**

Reports available to vendors

*/

/**
Copyright (c) 2013 - IgniteWoo.com
*/

if ( ! defined( 'ABSPATH' ) ) exit;

class IgniteWoo_Vendor_Store_Reports { 


	function __construct() {
		global $user_ID, $wpdb;
		
		if ( empty( $user_ID ) || !ign_is_vendor() )
			return;

		add_filter( 'woocommerce_reports_charts', array( &$this, 'filter_tabs' ), 99 );

		add_filter( 'woocommerce_json_search_found_products', array( &$this, 'filter_products_json' ) );

		add_filter( 'woocommerce_reports_product_sales_order_items', array( &$this, 'filter_products_sales' ), 9999, 2 );
		
		add_filter( 'woocommerce_reports_top_sellers_order_items', array( &$this, 'filter_products_top_sellers' ), 9999, 3 );
		
		add_filter( 'woocommerce_reports_top_earners_order_items', array( &$this, 'filter_products_top_earners' ), 9999, 3 );


	}
	
	
	function charts() { 

		$charts = array();
		
		$charts['sales'] = array(
			'title' => __( 'Sales', 'ignitewoo_vendor_stores' ),
			'charts' => array(
					array( 
						'title'       => __( 'Overview', 'ignitewoo_vendor_stores' ),
						'description' => '',
						'hide_title'  => true,
						'function'    => 'ignitewoo_vendor_stores_report_overview'
					)
			)
		);
		
		return $charts; 
	}

	
	function ign_get_vendor_products() { 
		global $user_ID;
		
		if ( empty( $user_ID ) )
			return array();
		
		
		$vendor_data = ign_get_user_vendor( $user_ID );
		
		if ( !empty( $vendor_data->ID ) )
			return ign_get_vendor_products( $vendor_data->ID );
		
		/*
		if ( empty( $user_ID ) )
			return array();
			
		$sql = 'select ID, post_title from ' . $wpdb->posts . ' where post_status="publish" and post_author=' . $user_ID;
		
		$res = $wpdb->get_results( $sql );
	
		if ( !empty( $res ) )
			return $res;

		return array();
		*/
	}
	
	/*
	function calculate_commission( $line_total, $product_id ) { 
		global $user_ID;
		
		if ( empty( $this->vendor_id ) ) { 
		
			$this->vendor_id = ign_get_product_vendors( $product_id );

			$this->vendor_id = $this->vendor_id[0]->ID;
			
		}

		if ( empty( $this->commission ) )
			$this->commission = ign_get_commission_percent( $product_id, $this->vendor_id );

		if ( empty( $this->commission ) || false == $this->commission )
			return $line_total;
			
		$percent = strpos( $this->commission, '%' );
		
		$commission = $this->commission;
		
		// if commission is percentage then calculate against line total
		// otherwise commission is a fixed amount so subtract that from the line total
		if ( false !== $percent ) {
		
			$commission = str_replace( '%', '', $commission );
			
			$commission = ( $commission / 100 );
			
			$commission = ( $commission * $line_total );
		
			return $commission;
		}

		return $line_total - $commission;
		
	}
	*/

	function filter_tabs( $tabs ) {

		if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
	
			$allow = array(
				'product_sales',
				'top_sellers',
				'top_earners',
			);

			if ( empty( $tabs['sales'] ) )
				$tabs['sales'] = array();
				
			$charts = $tabs['sales']['charts'];

			foreach ( $charts as $key => $chart ) {
				if ( !in_array( $key, $allow ) ) {
					unset( $tabs['sales']['charts'][$key] );
				}
			}
			
			return array(
				'sales' => $tabs['sales']
			);
			
		} else {

			
			$reports = array(
				'orders'     => array(
					'title'  => __( 'Sales', 'woocommerce' ),
					'reports' => array(
						"product_sales"    => array(
							'title'       => __( 'Product Sales', 'woocommerce' ),
							'description' => '',
							'hide_title'  => true,
							'callback'    => array( $this, 'get_report' )
						),
						"top_sellers"     => array(
							'title'       => __( 'Top Sellers', 'woocommerce' ),
							'description' => '',
							'hide_title'  => true,
							'callback'    => array( $this, 'get_report' )
						),
						"top_earners" => array(
							'title'       => __( 'Top Earners', 'woocommerce' ),
							'description' => '',
							'hide_title'  => true,
							'callback'    => array( $this, 'get_report' )
						),
					)
				),
			);

			return $reports;

		}
	}
	
	
	public function get_report( $name ) {

		switch( $name ) { 
			case 'product_sales' : 
				$this->product_sales();
				break;
			case 'top_sellers' : 
				$this->top_sellers();
				break;
			case 'top_earners' : 
				$this->top_earners();
				break;
					
		}

	}
	

	function product_sales() {

		global $wpdb, $woocommerce;

		$chosen_product_ids = ( isset( $_POST['product_ids'] ) ) ? array_map( 'absint', (array) $_POST['product_ids'] ) : '';

		if ( $chosen_product_ids && is_array( $chosen_product_ids ) ) {

			$start_date = date( 'Ym', strtotime( '-12 MONTHS', current_time('timestamp') ) ) . '01';
			$end_date 	= date( 'Ymd', current_time( 'timestamp' ) );

			$max_sales = $max_totals = 0;
			$product_sales = $product_totals = array();

			// Get titles and ID's related to product
			$chosen_product_titles = array();
			$children_ids = array();

			foreach ( $chosen_product_ids as $product_id ) {
				$children = (array) get_posts( 'post_parent=' . $product_id . '&fields=ids&post_status=any&numberposts=-1' );
				$children_ids = $children_ids + $children;
				$chosen_product_titles[] = get_the_title( $product_id );
			}

			if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ) { 
			
				// Get order items
				$order_items = apply_filters( 'woocommerce_reports_product_sales_order_items', $wpdb->get_results( "
					SELECT order_item_meta_2.meta_value as product_id, posts.post_date, SUM( order_item_meta.meta_value ) as item_quantity, SUM( order_item_meta_3.meta_value ) as line_total
					FROM {$wpdb->prefix}woocommerce_order_items as order_items

					LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
					LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_2 ON order_items.order_item_id = order_item_meta_2.order_item_id
					LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_3 ON order_items.order_item_id = order_item_meta_3.order_item_id
					LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID


					WHERE 	posts.post_type 	= 'shop_order'
					AND 	order_item_meta_2.meta_value IN ('" . implode( "','", array_merge( $chosen_product_ids, $children_ids ) ) . "')
					AND	posts.post_status IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
					AND 	order_items.order_item_type = 'line_item'
					AND 	order_item_meta.meta_key = '_qty'
					AND 	order_item_meta_2.meta_key = '_product_id'
					AND 	order_item_meta_3.meta_key = '_line_total'
					GROUP BY order_items.order_id
					ORDER BY posts.post_date ASC
				" ), array_merge( $chosen_product_ids, $children_ids ) );
				
			} else { 
				
				// Get order items
				$order_items = apply_filters( 'woocommerce_reports_product_sales_order_items', $wpdb->get_results( "
					SELECT order_item_meta_2.meta_value as product_id, posts.post_date, SUM( order_item_meta.meta_value ) as item_quantity, SUM( order_item_meta_3.meta_value ) as line_total
					FROM {$wpdb->prefix}woocommerce_order_items as order_items

					LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
					LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_2 ON order_items.order_item_id = order_item_meta_2.order_item_id
					LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_3 ON order_items.order_item_id = order_item_meta_3.order_item_id
					LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
					LEFT JOIN {$wpdb->term_relationships} AS rel ON posts.ID = rel.object_ID
					LEFT JOIN {$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
					LEFT JOIN {$wpdb->terms} AS term USING( term_id )

					WHERE 	posts.post_type 	= 'shop_order'
					AND 	order_item_meta_2.meta_value IN ('" . implode( "','", array_merge( $chosen_product_ids, $children_ids ) ) . "')
					AND 	posts.post_status 	= 'publish'
					AND 	tax.taxonomy		= 'shop_order_status'
					AND		term.slug			IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) . "')
					AND 	order_items.order_item_type = 'line_item'
					AND 	order_item_meta.meta_key = '_qty'
					AND 	order_item_meta_2.meta_key = '_product_id'
					AND 	order_item_meta_3.meta_key = '_line_total'
					GROUP BY order_items.order_id
					ORDER BY posts.post_date ASC
				" ), array_merge( $chosen_product_ids, $children_ids ) );

			}
			
			
			$found_products = array();

			if ( $order_items ) {
				foreach ( $order_items as $order_item ) {

					if ( $order_item->line_total == 0 && $order_item->item_quantity == 0 )
						continue;

					// Get date
					$date 	= date( 'Ym', strtotime( $order_item->post_date ) );

					// Set values
					$product_sales[ $date ] 	= isset( $product_sales[ $date ] ) ? $product_sales[ $date ] + $order_item->item_quantity : $order_item->item_quantity;
					$product_totals[ $date ] 	= isset( $product_totals[ $date ] ) ? $product_totals[ $date ] + $order_item->line_total : $order_item->line_total;

					if ( $product_sales[ $date ] > $max_sales )
						$max_sales = $product_sales[ $date ];

					if ( $product_totals[ $date ] > $max_totals )
						$max_totals = $product_totals[ $date ];
				}
			}
			?>
			<style>
			table.bar_chart thead th {
				color: #000;
			}
			</style>
			<h4><?php printf( __( 'Sales for %s:', 'woocommerce' ), implode( ', ', $chosen_product_titles ) ); ?></h4>
			<table class="bar_chart">
				<thead>
					<tr>
						<th><?php _e( 'Month', 'woocommerce' ); ?></th>
						<th colspan="2"><?php _e( 'Sales', 'woocommerce' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
						if ( sizeof( $product_sales ) > 0 ) {
							foreach ( $product_sales as $date => $sales ) {
								$width = ($sales>0) ? (round($sales) / round($max_sales)) * 100 : 0;
								$width2 = ($product_totals[$date]>0) ? (round($product_totals[$date]) / round($max_totals)) * 100 : 0;

								if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ) { 
								
									$orders_link = admin_url( 'edit.php?post_type=shop_order&action=-1&s=' . urlencode( implode( ' ', $chosen_product_titles ) ) . '&m=' . date( 'Ym', strtotime( $date . '01' ) ) );
									
								} else { 
								
									$orders_link = admin_url( 'edit.php?s&post_status=all&post_type=shop_order&action=-1&s=' . urlencode( implode( ' ', $chosen_product_titles ) ) . '&m=' . date( 'Ym', strtotime( $date . '01' ) ) . '&shop_order_status=' . implode( ",", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) );
								}
								
								$orders_link = apply_filters( 'woocommerce_reports_order_link', $orders_link, $chosen_product_ids, $chosen_product_titles );

								echo '<tr><th><a href="' . esc_url( $orders_link ) . '">' . date_i18n( 'F', strtotime( $date . '01' ) ) . '</a></th>
								<td width="1%"><span>' . esc_html( $sales ) . '</span><span class="alt">' . woocommerce_price( $product_totals[ $date ] ) . '</span></td>
								<td class="bars">
									<span style="width:' . esc_attr( $width ) . '%">&nbsp;</span>
									<span class="alt" style="width:' . esc_attr( $width2 ) . '%">&nbsp;</span>
								</td></tr>';
							}
						} else {
							echo '<tr><td colspan="3">' . __( 'No sales :(', 'woocommerce' ) . '</td></tr>';
						}
					?>
				</tbody>
			</table>
			<?php

		} else {
			?>
			<form method="post" action="">
				<p><select id="product_ids" name="product_ids[]" class="ajax_chosen_select_products" multiple="multiple" data-placeholder="<?php _e( 'Search for a product&hellip;', 'woocommerce' ); ?>" style="width: 400px;"></select> <input type="submit" style="vertical-align: top;" class="button" value="<?php _e( 'Show', 'woocommerce' ); ?>" /></p>
				<script type="text/javascript">
					jQuery(function(){

						// Ajax Chosen Product Selectors
						jQuery("select.ajax_chosen_select_products").ajaxChosen({
						method: 	'GET',
						url: 		'<?php echo admin_url('admin-ajax.php'); ?>',
						dataType: 	'json',
						afterTypeDelay: 100,
						data:		{
							action: 		'woocommerce_json_search_products',
								security: 		'<?php echo wp_create_nonce("search-products"); ?>'
						}
						}, function (data) {

							var terms = {};

						jQuery.each(data, function (i, val) {
							terms[i] = val;
						});

						return terms;
						});

					});
				</script>
			</form>
			<?php
		}
	}

		
	function top_sellers() {

		global $start_date, $end_date, $woocommerce, $wpdb;

		$start_date = isset( $_POST['start_date'] ) ? $_POST['start_date'] : '';
		$end_date	= isset( $_POST['end_date'] ) ? $_POST['end_date'] : '';

		if ( ! $start_date )
			$start_date = date( 'Ymd', strtotime( date( 'Ym', current_time( 'timestamp' ) ) . '01' ) );
		if ( ! $end_date )
			$end_date = date( 'Ymd', current_time( 'timestamp' ) );

		$start_date = strtotime( $start_date );
		$end_date = strtotime( $end_date );

		if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ) {
		
			// Get order ids and dates in range
			$order_items = apply_filters( 'woocommerce_reports_top_sellers_order_items', $wpdb->get_results( "
				SELECT order_item_meta_2.meta_value as product_id, SUM( order_item_meta.meta_value ) as item_quantity FROM {$wpdb->prefix}woocommerce_order_items as order_items

				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_2 ON order_items.order_item_id = order_item_meta_2.order_item_id
				LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
				WHERE 	posts.post_type 	= 'shop_order'
				AND 	posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
				AND 	post_date > '" . date('Y-m-d', $start_date ) . "'
				AND 	post_date < '" . date('Y-m-d', strtotime('+1 day', $end_date ) ) . "'
				AND 	order_items.order_item_type = 'line_item'
				AND 	order_item_meta.meta_key = '_qty'
				AND 	order_item_meta_2.meta_key = '_product_id'
				GROUP BY order_item_meta_2.meta_value
			" ), $start_date, $end_date );
			
		} else { 
		
			
			// Get order ids and dates in range
			$order_items = apply_filters( 'woocommerce_reports_top_sellers_order_items', $wpdb->get_results( "
				SELECT order_item_meta_2.meta_value as product_id, SUM( order_item_meta.meta_value ) as item_quantity FROM {$wpdb->prefix}woocommerce_order_items as order_items

				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_2 ON order_items.order_item_id = order_item_meta_2.order_item_id
				LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
				LEFT JOIN {$wpdb->term_relationships} AS rel ON posts.ID = rel.object_ID
				LEFT JOIN {$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
				LEFT JOIN {$wpdb->terms} AS term USING( term_id )

				WHERE 	posts.post_type 	= 'shop_order'
				AND 	posts.post_status 	= 'publish'
				AND 	tax.taxonomy		= 'shop_order_status'
				AND		term.slug			IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) . "')
				AND 	post_date > '" . date('Y-m-d', $start_date ) . "'
				AND 	post_date < '" . date('Y-m-d', strtotime('+1 day', $end_date ) ) . "'
				AND 	order_items.order_item_type = 'line_item'
				AND 	order_item_meta.meta_key = '_qty'
				AND 	order_item_meta_2.meta_key = '_product_id'
				GROUP BY order_item_meta_2.meta_value
			" ), $start_date, $end_date );
		}
		
		$found_products = array();

		if ( $order_items ) {
			foreach ( $order_items as $order_item ) {
				$found_products[ $order_item->product_id ] = $order_item->item_quantity;
			}
		}

		asort( $found_products );
		$found_products = array_reverse( $found_products, true );
		$found_products = array_slice( $found_products, 0, 25, true );
		reset( $found_products );
		?>
		<style>
			table.bar_chart thead th {
				color: #000;
			}
		</style>
		<form method="post" action="">
			<p><label for="from"><?php _e( 'From:', 'woocommerce' ); ?></label> <input type="text" name="start_date" id="from" readonly="readonly" value="<?php echo esc_attr( date('Y-m-d', $start_date) ); ?>" /> <label for="to"><?php _e( 'To:', 'woocommerce' ); ?></label> <input type="text" name="end_date" id="to" readonly="readonly" value="<?php echo esc_attr( date('Y-m-d', $end_date) ); ?>" /> <input type="submit" class="button" value="<?php _e( 'Show', 'woocommerce' ); ?>" /></p>
		</form>
		<table class="bar_chart">
			<thead>
				<tr>
					<th><?php _e( 'Product', 'woocommerce' ); ?></th>
					<th><?php _e( 'Sales', 'woocommerce' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$max_sales = current( $found_products );
					foreach ( $found_products as $product_id => $sales ) {
						$width = $sales > 0 ? ( $sales / $max_sales ) * 100 : 0;
						$product_title = get_the_title( $product_id );

						if ( $product_title ) {
						
							$product_name = '<a href="' . get_permalink( $product_id ) . '">'. __( $product_title ) .'</a>';
							
							if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ) {
							
								$orders_link = admin_url( 'edit.php?s&post_status=all&post_type=shop_order&action=-1&s=' . urlencode( $product_title ) );
							
							} else { 
							
								$orders_link = admin_url( 'edit.php?s&post_status=all&post_type=shop_order&action=-1&s=' . urlencode( $product_title ) . '&shop_order_status=' . implode( ",", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) );
							}
							
						} else {
						
							$product_name = __( 'Product does not exist', 'woocommerce' );
							
							if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ) {
							
								$orders_link = admin_url( 'edit.php?s&post_status=all&post_type=shop_order&action=-1&s=' );
								
								
							} else { 
							
								$orders_link = admin_url( 'edit.php?s&post_status=all&post_type=shop_order&action=-1&s=&shop_order_status=' . implode( ",", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) );
								
							}
						}

						$orders_link = apply_filters( 'woocommerce_reports_order_link', $orders_link, $product_id, $product_title );

						echo '<tr><th>' . $product_name . '</th><td width="1%"><span>' . esc_html( $sales ) . '</span></td><td class="bars"><a href="' . esc_url( $orders_link ) . '" style="width:' . esc_attr( $width ) . '%">&nbsp;</a></td></tr>';
					}
				?>
			</tbody>
		</table>
		<script type="text/javascript">
			jQuery(function(){
				<?php woocommerce_datepicker_js(); ?>
			});
		</script>
		<?php
	}


	/**
	* Output the top earners chart.
	*
	* @access public
	* @return void
	*/
	function top_earners() {

		global $start_date, $end_date, $woocommerce, $wpdb;

		$start_date = isset( $_POST['start_date'] ) ? $_POST['start_date'] : '';
		$end_date	= isset( $_POST['end_date'] ) ? $_POST['end_date'] : '';

		if ( ! $start_date )
			$start_date = date( 'Ymd', strtotime( date('Ym', current_time( 'timestamp' ) ) . '01' ) );
		if ( ! $end_date )
			$end_date = date( 'Ymd', current_time( 'timestamp' ) );

		$start_date = strtotime( $start_date );
		$end_date = strtotime( $end_date );

		if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ) {
		
			// Get order ids and dates in range
			$order_items = apply_filters( 'woocommerce_reports_top_earners_order_items', $wpdb->get_results( "
				SELECT order_item_meta_2.meta_value as product_id, SUM( order_item_meta.meta_value ) as line_total FROM {$wpdb->prefix}woocommerce_order_items as order_items

				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_2 ON order_items.order_item_id = order_item_meta_2.order_item_id
				LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID

				WHERE 	posts.post_type 	= 'shop_order'
				AND 	posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
				AND 	post_date > '" . date('Y-m-d', $start_date ) . "'
				AND 	post_date < '" . date('Y-m-d', strtotime('+1 day', $end_date ) ) . "'
				AND 	order_items.order_item_type = 'line_item'
				AND 	order_item_meta.meta_key = '_line_total'
				AND 	order_item_meta_2.meta_key = '_product_id'
				GROUP BY order_item_meta_2.meta_value
			" ), $start_date, $end_date );
			
		} else { 
			
			// Get order ids and dates in range
			$order_items = apply_filters( 'woocommerce_reports_top_earners_order_items', $wpdb->get_results( "
				SELECT order_item_meta_2.meta_value as product_id, SUM( order_item_meta.meta_value ) as line_total FROM {$wpdb->prefix}woocommerce_order_items as order_items

				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_2 ON order_items.order_item_id = order_item_meta_2.order_item_id
				LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
				LEFT JOIN {$wpdb->term_relationships} AS rel ON posts.ID = rel.object_ID
				LEFT JOIN {$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
				LEFT JOIN {$wpdb->terms} AS term USING( term_id )

				WHERE 	posts.post_type 	= 'shop_order'
				AND 	posts.post_status 	= 'publish'
				AND 	tax.taxonomy		= 'shop_order_status'
				AND		term.slug			IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) . "')
				AND 	post_date > '" . date('Y-m-d', $start_date ) . "'
				AND 	post_date < '" . date('Y-m-d', strtotime('+1 day', $end_date ) ) . "'
				AND 	order_items.order_item_type = 'line_item'
				AND 	order_item_meta.meta_key = '_line_total'
				AND 	order_item_meta_2.meta_key = '_product_id'
				GROUP BY order_item_meta_2.meta_value
			" ), $start_date, $end_date );
		}
		
		$found_products = array();

		if ( $order_items ) {
			foreach ( $order_items as $order_item ) {
				$found_products[ $order_item->product_id ] = $order_item->line_total;
			}
		}

		asort( $found_products );
		$found_products = array_reverse( $found_products, true );
		$found_products = array_slice( $found_products, 0, 25, true );
		reset( $found_products );
		?>
		<form method="post" action="">
			<p><label for="from"><?php _e( 'From:', 'woocommerce' ); ?></label> <input type="text" name="start_date" id="from" readonly="readonly" value="<?php echo esc_attr( date('Y-m-d', $start_date) ); ?>" /> <label for="to"><?php _e( 'To:', 'woocommerce' ); ?></label> <input type="text" name="end_date" id="to" readonly="readonly" value="<?php echo esc_attr( date('Y-m-d', $end_date) ); ?>" /> <input type="submit" class="button" value="<?php _e( 'Show', 'woocommerce' ); ?>" /></p>
		</form>
		<style>
			table.bar_chart thead th {
				color: #000;
			}
		</style>
		<table class="bar_chart">
			<thead>
				<tr>
					<th><?php _e( 'Product', 'woocommerce' ); ?></th>
					<th colspan="2"><?php _e( 'Sales', 'woocommerce' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$max_sales = current( $found_products );
					foreach ( $found_products as $product_id => $sales ) {
						$width = $sales > 0 ? ( round( $sales ) / round( $max_sales ) ) * 100 : 0;

						$product_title = get_the_title( $product_id );

						if ( $product_title ) {
						
							$product_name = '<a href="'.get_permalink( $product_id ).'">'. __( $product_title ) .'</a>';
						
							if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ) {
						
								$orders_link = admin_url( 'edit.php?s&post_status=all&post_type=shop_order&action=-1&s=' . urlencode( $product_title ) );
						
							} else { 
							
								$orders_link = admin_url( 'edit.php?s&post_status=all&post_type=shop_order&action=-1&s=' . urlencode( $product_title ) . '&shop_order_status=' . implode( ",", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) );
							
							}
							
						} else {
						
							$product_name = __( 'Product no longer exists', 'woocommerce' );
							
							if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ) {
							
								$orders_link = admin_url( 'edit.php?s&post_status=all&post_type=shop_order&action=-1&s=' );
							
							} else { 
							
								$orders_link = admin_url( 'edit.php?s&post_status=all&post_type=shop_order&action=-1&s=&shop_order_status=' . implode( ",", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) );
							}
						}

						$orders_link = apply_filters( 'woocommerce_reports_order_link', $orders_link, $product_id, $product_title );

						echo '<tr><th>' . $product_name . '</th><td width="1%"><span>' . woocommerce_price( $sales ) . '</span></td><td class="bars"><a href="' . esc_url( $orders_link ) . '" style="width:' . esc_attr( $width ) . '%">&nbsp;</a></td></tr>';
					}
				?>
			</tbody>
		</table>
		<script type="text/javascript">
			jQuery(function(){
				<?php woocommerce_datepicker_js(); ?>
			});
		</script>
		<?php
	}

	
	function filter_products( $orders ) { 
		global $wpdb;
		
		if ( empty( $orders ) )
			return $orders;
			
		$products = $this->ign_get_vendor_products();

		$ids = array();
	
		foreach ( $products as $product )
			$ids[] = ( $product->ID );

		foreach ( $orders as $key => $order ) {

			if ( !in_array( $order->product_id, $ids ) ) {
			
				unset( $orders[$key] );
				
				continue;
				
			} else {
			
				$sql = 'select meta_value from ' . $wpdb->prefix . 'woocommerce_order_itemmeta where meta_key="_variation_id" and order_item_id=' . $order->order_item_id;

				$variation_id = $wpdb->get_var( $sql );

				if ( !empty( $order->line_total ) )
					$orders[$key]->line_total = ign_calculate_product_commission( $order->line_total, $order->product_id, $variation_id );
				
			}

		}

		return $orders;
		
	}
	
	function filter_products_sales( $orders, $chosen_product_ids ) { 
		global $wpdb;

		if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ) {
		
			$res = $wpdb->get_results( "
				SELECT order_item_meta_2.meta_value as product_id, posts.post_date, SUM( order_item_meta.meta_value ) as item_quantity, SUM( order_item_meta_3.meta_value ) as line_total, order_item_meta_2.order_item_id as order_item_id
				FROM {$wpdb->prefix}woocommerce_order_items as order_items

				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_2 ON order_items.order_item_id = order_item_meta_2.order_item_id
				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_3 ON order_items.order_item_id = order_item_meta_3.order_item_id
				LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID

				WHERE 	posts.post_type 	= 'shop_order'
				AND 	order_item_meta_2.meta_value IN ('" . implode( "','", $chosen_product_ids ) . "')
				AND 	posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
				AND 	order_items.order_item_type = 'line_item'
				AND 	order_item_meta.meta_key = '_qty'
				AND 	order_item_meta_2.meta_key = '_product_id'
				AND 	order_item_meta_3.meta_key = '_line_total'
				GROUP BY order_items.order_id
				ORDER BY posts.post_date ASC
			" );
			
		} else { 
		
			$res = $wpdb->get_results( "
				SELECT order_item_meta_2.meta_value as product_id, posts.post_date, SUM( order_item_meta.meta_value ) as item_quantity, SUM( order_item_meta_3.meta_value ) as line_total, order_item_meta_2.order_item_id as order_item_id
				FROM {$wpdb->prefix}woocommerce_order_items as order_items

				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_2 ON order_items.order_item_id = order_item_meta_2.order_item_id
				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_3 ON order_items.order_item_id = order_item_meta_3.order_item_id
				LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
				LEFT JOIN {$wpdb->term_relationships} AS rel ON posts.ID = rel.object_ID
				LEFT JOIN {$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
				LEFT JOIN {$wpdb->terms} AS term USING( term_id )

				WHERE 	posts.post_type 	= 'shop_order'
				AND 	order_item_meta_2.meta_value IN ('" . implode( "','", $chosen_product_ids ) . "')
				AND 	posts.post_status 	= 'publish'
				AND 	tax.taxonomy		= 'shop_order_status'
				AND		term.slug			IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) . "')
				AND 	order_items.order_item_type = 'line_item'
				AND 	order_item_meta.meta_key = '_qty'
				AND 	order_item_meta_2.meta_key = '_product_id'
				AND 	order_item_meta_3.meta_key = '_line_total'
				GROUP BY order_items.order_id
				ORDER BY posts.post_date ASC
			" );
		
		}
		
		if ( empty( $res ) )
			return $orders;
			
		return $this->filter_products( $res );
		
	}
	
	
	function filter_products_top_sellers( $orders, $start_date, $end_date ) {
		global $wpdb; 

		if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ) {
			
			$res = $wpdb->get_results( "
				SELECT order_item_meta_2.meta_value as product_id, SUM( order_item_meta.meta_value ) as item_quantity, order_item_meta_2.order_item_id as order_item_id FROM {$wpdb->prefix}woocommerce_order_items as order_items

				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_2 ON order_items.order_item_id = order_item_meta_2.order_item_id
				LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID

				WHERE 	posts.post_type 	= 'shop_order'
				AND 	posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
				AND 	post_date > '" . date('Y-m-d', $start_date ) . "'
				AND 	post_date < '" . date('Y-m-d', strtotime('+1 day', $end_date ) ) . "'
				AND 	order_items.order_item_type = 'line_item'
				AND 	order_item_meta.meta_key = '_qty'
				AND 	order_item_meta_2.meta_key = '_product_id'
				GROUP BY order_item_meta_2.meta_value
			" );

		} else { 
		
			$res = $wpdb->get_results( "
				SELECT order_item_meta_2.meta_value as product_id, SUM( order_item_meta.meta_value ) as item_quantity, order_item_meta_2.order_item_id as order_item_id FROM {$wpdb->prefix}woocommerce_order_items as order_items

				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_2 ON order_items.order_item_id = order_item_meta_2.order_item_id
				LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
				LEFT JOIN {$wpdb->term_relationships} AS rel ON posts.ID = rel.object_ID
				LEFT JOIN {$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
				LEFT JOIN {$wpdb->terms} AS term USING( term_id )

				WHERE 	posts.post_type 	= 'shop_order'
				AND 	posts.post_status 	= 'publish'
				AND 	tax.taxonomy		= 'shop_order_status'
				AND		term.slug			IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) . "')
				AND 	post_date > '" . date('Y-m-d', $start_date ) . "'
				AND 	post_date < '" . date('Y-m-d', strtotime('+1 day', $end_date ) ) . "'
				AND 	order_items.order_item_type = 'line_item'
				AND 	order_item_meta.meta_key = '_qty'
				AND 	order_item_meta_2.meta_key = '_product_id'
				GROUP BY order_item_meta_2.meta_value
			" );
			
		}
		
		if ( empty( $res ) )
			return $orders;
			
		return $this->filter_products( $res ); 
		
	}


	function filter_products_top_earners( $orders, $start_date, $end_date ) {
		global $wpdb;
		
		if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ) {
		
			$res = $wpdb->get_results( "
				SELECT order_item_meta_2.meta_value as product_id, SUM( order_item_meta.meta_value ) as line_total, order_item_meta_2.order_item_id as order_item_id FROM {$wpdb->prefix}woocommerce_order_items as order_items

				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_2 ON order_items.order_item_id = order_item_meta_2.order_item_id
				LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
				WHERE 	posts.post_type 	= 'shop_order'
				AND 	posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
				AND 	post_date > '" . date('Y-m-d', $start_date ) . "'
				AND 	post_date < '" . date('Y-m-d', strtotime('+1 day', $end_date ) ) . "'
				AND 	order_items.order_item_type = 'line_item'
				AND 	order_item_meta.meta_key = '_line_total'
				AND 	order_item_meta_2.meta_key = '_product_id'
				GROUP BY order_item_meta_2.meta_value
			" );

		
		} else { 
		
			$res = $wpdb->get_results( "
				SELECT order_item_meta_2.meta_value as product_id, SUM( order_item_meta.meta_value ) as line_total, order_item_meta_2.order_item_id as order_item_id FROM {$wpdb->prefix}woocommerce_order_items as order_items

				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_2 ON order_items.order_item_id = order_item_meta_2.order_item_id
				LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
				LEFT JOIN {$wpdb->term_relationships} AS rel ON posts.ID = rel.object_ID
				LEFT JOIN {$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
				LEFT JOIN {$wpdb->terms} AS term USING( term_id )

				WHERE 	posts.post_type 	= 'shop_order'
				AND 	posts.post_status 	= 'publish'
				AND 	tax.taxonomy		= 'shop_order_status'
				AND		term.slug			IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) . "')
				AND 	post_date > '" . date('Y-m-d', $start_date ) . "'
				AND 	post_date < '" . date('Y-m-d', strtotime('+1 day', $end_date ) ) . "'
				AND 	order_items.order_item_type = 'line_item'
				AND 	order_item_meta.meta_key = '_line_total'
				AND 	order_item_meta_2.meta_key = '_product_id'
				GROUP BY order_item_meta_2.meta_value
			" );
		}
		
		if ( empty( $res ) )
			return $orders;
			
		return $this->filter_products( $res ); 
		
	}
	
	
	function filter_products_json( $products ) {
		
		$vendor_products = $this->ign_get_vendor_products();

		$ids = array();
		
		foreach ( $vendor_products as $vendor_product )
			$ids[$vendor_product->ID] = $vendor_product->post_title;

		return array_intersect_key( $products, $ids );
	}


}