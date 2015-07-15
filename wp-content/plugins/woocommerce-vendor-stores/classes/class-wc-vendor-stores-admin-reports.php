<?php

/**
* Reports for admins to review vendor store sales
*/

if ( ! defined( 'ABSPATH' ) ) exit;

class IgniteWoo_Vendor_Store_Admin_Reports { 


	function __construct() { 
	
	}


	// Only shows orders that have commissions so total reflect only vendor store items
	function ignitewoo_vendor_stores_report_overview() {
		global $start_date, $end_date, $woocommerce, $wp_locale, $wpdb;

		if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ) {
			
			$args = array(
				'post_type' => 'shop_order',
				'post_status' => apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ),
				'meta_query' => array(
					array(
						'key' => '_commissions_processed',
						'value' => 'yes',
						'compare' => '='
					)
				),
				'posts_per_page' => -1
			);
			
		} else { 
				
			$args = array(
				'post_type' => 'shop_order',
				'post_status' => 'publish',
				'meta_query' => array(
					array(
						'key' => '_commissions_processed',
						'value' => 'yes',
						'compare' => '='
					)
				),
				'tax_query' => array( 
					array( 
						'taxonomy' => 'shop_order_status',
						'field' => 'slug',
						'terms' => apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) )
					)
				),
				'posts_per_page' => -1
			);

		}
		
		$orders = get_posts( $args );
		
		$total_sales = $total_orders = $total_earnings = $total_vendor_earnings = 0;
		
		foreach( $orders as $order ) {

			$order_obj = new WC_Order( $order->ID );

			$items = $order_obj->get_items( 'line_item' );

			$product_vendor_earnings = 0;
			
			foreach( $items as $item_id => $item ) {

				$product_id = $order_obj->get_item_meta( $item_id, '_product_id', true );
				
				$line_total = $order_obj->get_item_meta( $item_id, '_line_total', true );
				
				if ( empty( $product_id ) || empty( $line_total ) )
					continue;
					
					$product_vendors = ign_get_product_vendors( $product_id );

					if ( empty( $product_vendors ) ) 
						continue;
					
					$total_sales += $line_total;
					
					foreach( $product_vendors as $vendor ) {

						//$comm_percent = ign_get_commission_percent( $product_id, $vendor->ID );
						
						$comm = ign_calculate_product_commission( $item['line_total'], $item['product_id'], $item['variation_id'], $vendor->ID );
						
						if ( empty( $comm ) || $comm <= 0 )
							continue; 
						
						$product_vendor_earnings += $comm;
						
						$total_vendor_earnings += $comm;
						
						$earnings = ( $line_total - $comm );
					
						$total_earnings += $earnings;
					
					}

					
				
			}
			
		++$total_orders;

		}

		
		?>
		<div id="poststuff" class="woocommerce-reports-wrap">
			<div class="woocommerce-reports-sidebar">
				<div class="postbox">
					<h3><span><?php _e( 'Total sales', 'ignitewoo_vendor_stores' ); ?></span></h3>
					<div class="inside">
						<p class="stat"><?php if ( $total_sales > 0 ) echo woocommerce_price( $total_sales ); else _e( 'n/a', 'ignitewoo_vendor_stores' ); ?></p>
					</div>
				</div>
				<div class="postbox">
					<h3><span><?php _e( 'Total orders', 'ignitewoo_vendor_stores' ); ?></span></h3>
					<div class="inside">
						<p class="stat"><?php if ( $total_orders > 0 ) echo $total_orders; else _e( 'n/a', 'ignitewoo_vendor_stores' ); ?></p>
					</div>
				</div>
				<div class="postbox">
					<h3><span><?php _e( 'Average order total', 'ignitewoo_vendor_stores' ); ?></span></h3>
					<div class="inside">
						<p class="stat"><?php if ( $total_orders > 0 ) echo woocommerce_price( $total_sales / $total_orders ); else _e( 'n/a', 'ignitewoo_vendor_stores' ); ?></p>
					</div>
				</div>
				<div class="postbox">
					<h3><span><?php _e( 'Total store revenue', 'ignitewoo_vendor_storess' ); ?></span></h3>
					<div class="inside">
						<p class="stat"><?php if ( $total_earnings > 0 ) echo woocommerce_price( $total_earnings ); else _e( 'n/a', 'ignitewoo_vendor_stores' ); ?></p>
					</div>
				</div>
				<div class="postbox">
					<h3><span><?php _e( 'Total vendor revenue', 'ignitewoo_vendor_storess' ); ?></span></h3>
					<div class="inside">
						<p class="stat"><?php if ( $total_vendor_earnings > 0 ) echo woocommerce_price( $total_vendor_earnings ); else _e( 'n/a', 'ignitewoo_vendor_stores' ); ?></p>
					</div>
				</div>
			</div>
			<div class="woocommerce-reports-main">
				<div class="postbox">
					<h3><span><?php _e( 'This month\'s sales', 'ignitewoo_vendor_stores' ); ?></span></h3>
					<div class="inside chart">
						<div id="placeholder" style="width:100%; overflow:hidden; height:568px; position:relative;"></div>
						<div id="cart_legend"></div>
					</div>
				</div>
			</div>
		</div>
		<?php

		$chart_data = array();

		$start_date = strtotime( date('Ymd', strtotime( date('Ym', current_time('timestamp') ) . '01' ) ) );
		
		$end_date = strtotime( date('Ymd', current_time( 'timestamp' ) ) );

		for ( $date = $start_date; $date <= $end_date; $date = strtotime( '+1 day', $date ) ) {
		
			$year = date( 'Y', $date );
			
			$month = date( 'n', $date );
			
			$day = date( 'j', $date );
			
			$total_vendor_earnings = $total_earnings = $order_count = $day_total_vendors = $day_total = 0;

			if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ) {
				
				$args = array(
					'post_type' => 'shop_order',
					'post_status' => apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ),
					'posts_per_page' => -1,
					'meta_query' => array(
						array(
							'key' => '_commissions_processed',
							'value' => 'yes',
							'compare' => '='
						)
					),
					'year' => $year,
					'monthnum' => $month,
					'day' => $day,
					'orderby' => 'date',
					'order' => 'ASC'
				);
				
			} else { 
			
				$args = array(
					'post_type' => 'shop_order',
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'meta_query' => array(
						array(
							'key' => '_commissions_processed',
							'value' => 'yes',
							'compare' => '='
						)
					),
					'tax_query' => array( 
						array( 
							'taxonomy' => 'shop_order_status',
							'field' => 'slug',
							'terms' => apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) )
						)
					),
					'year' => $year,
					'monthnum' => $month,
					'day' => $day,
					'orderby' => 'date',
					'order' => 'ASC'
				);
				
			
			}
			$comms = new WP_Query( $args );

			$total_vendor_earnings = $total_earnings = 0;
			
			if ( !$comms->have_posts() )
				continue; 
				
			while ( $comms->have_posts() ) { 
			
				$comms->the_post();
				
				$order = new WC_Order( get_the_ID() );
				
				$items = $order_obj->get_items( 'line_item' );
				
				foreach( $items as $item_id => $item ) {
					
					$product_id = $order_obj->get_item_meta( $item_id, '_product_id', true );
					
					$line_total = $order_obj->get_item_meta( $item_id, '_line_total', true );
					
					if ( empty( $product_id ) || empty( $line_total ) )
						continue;
						
					$product_vendors = ign_get_product_vendors( $product_id );
					
					if ( empty( $product_vendors ) )
						continue;
					

					foreach( $product_vendors as $vendor ) {
					
						$comm = ign_calculate_product_commission( $item['line_total'], $item['product_id'], $item['variation_id'], $vendor->ID );
					
						//$comm_percent = ign_get_commission_percent( $product_id, $vendor->ID );
						
						if ( empty( $comm ) || $comm <= 0 )
							continue;
							
						//$comm_amount = (int) $line_total * ( $comm_percent / 100 );
						
						$total_vendor_earnings += $comm;
						
					}
					
					$earnings = ( $line_total - $total_vendor_earnings );
					
					$total_earnings += $earnings;

					
				}
				
				$day_total += $total_earnings;
				
				$day_total_vendors += $total_vendor_earnings;
				
				++$order_count;
			}

			wp_reset_postdata();

			$chart_data[ __( 'Total earned', 'ignitewoo_vendor_storess' ) ][] = array(
				$date . '000',
				$day_total
			);

			$chart_data[ __( 'Total earned by vendors', 'ignitewoo_vendor_storess' ) ][] = array(
				$date . '000',
				$day_total_vendors
			);

			$chart_data[ __( 'Number of orders', 'ignitewoo_vendor_storess' ) ][] = array(
				$date . '000',
				$order_count
			);
		}

		?>
		<script type="text/javascript">
			jQuery(function(){

				<?php
				foreach ( $chart_data as $name => $data ) {
					$varname = str_replace( '-', '_', sanitize_title( $name ) ) . '_data';
					echo 'var ' . $varname . ' = jQuery.parseJSON( \'' . json_encode( $data ) . '\' );';
				}
				?>

				var placeholder = jQuery("#placeholder");

				var plot = jQuery.plot(placeholder, [
					<?php
					$labels = array();

					foreach ( $chart_data as $name => $data ) {
						if ( $name == 'Number of orders' ) {
							$labels[] = '{ label: "' . esc_js( $name ) . '", data: ' . str_replace( '-', '_', sanitize_title( $name ) ) . '_data, yaxis: 2 }';
						} else {
							$labels[] = '{ label: "' . esc_js( $name ) . '", data: ' . str_replace( '-', '_', sanitize_title( $name ) ) . '_data }';
						}
					}

					echo implode( ',', $labels );
					?>
				], {
					legend: {
						container: jQuery('#cart_legend'),
						noColumns: 2
					},
					series: {
						lines: { show: true, fill: true },
						points: { show: true }
					},
					grid: {
						show: true,
						aboveData: false,
						color: '#aaa',
						backgroundColor: '#fff',
						borderWidth: 2,
						borderColor: '#aaa',
						clickable: false,
						hoverable: true
					},
					xaxis: {
						mode: "time",
						timeformat: "%d %b %y",
						monthNames: <?php echo json_encode( array_values( $wp_locale->month_abbrev ) ) ?>,
						tickLength: 1,
						minTickSize: [1, "day"]
					},
					yaxes: [ { min: 0, tickSize: 10, tickDecimals: 2 }, { position: "right", min: 0, tickDecimals: 2 } ]
				});

				placeholder.resize();

				<?php woocommerce_tooltip_js(); ?>
			});
		</script>

		<?php

			$sql = 'select p.ID, p.post_date, m2.meta_value as amount from ' . $wpdb->posts . ' p
				left join ' . $wpdb->postmeta . ' m1 on m1.post_id = ID
				left join ' . $wpdb->postmeta . ' m2 on m2.post_id = ID
				left join ' . $wpdb->usermeta . ' u1 on u1.meta_value = m1.meta_value
				where 
				post_type = "vendor_commission"
				and 
				u1.meta_key = "product_vendor" 
				and
				m2.meta_key = "_commission_amount" 
				order by p.ID DESC limit 10
			';

			$count = 0;
			
			$res = $wpdb->get_results( $sql );

			if ( !empty( $res ) ) {
			
				foreach( $res as $r ) {
			
					$count++;
					
					$meta = get_post_meta( $r->ID );
					
					$amount = empty( $meta['_amount'][0] ) ? 0 : round( $meta['_amount'][0], 2 );
					
					// items + shipping + tax
					$order_commission = empty( $meta['_commission_amount'][0] ) ? 0 : round( $meta['_commission_amount'][0], 2 );
					
					$shipping = empty( $meta['_shipping'][0] ) ? 0 : round( $meta['_shipping'][0], 2 );
					
					$tax = empty( $meta['_tax'][0] ) ? 0 : round( $meta['_tax'][0], 2 );
					
					if ( 'paid' == $meta['_paid_status'][0] )
						$amount_paid = $order_commission;
					else 
						$amount_paid = 0;
					
					$vendor = $meta['_commission_vendor'][0];

					$vendor_data = get_term( $vendor, 'shop_vendor' );

					if ( !empty( $vendor_data ) && !is_wp_error( $vendor_data ) )
						$vendor_name = $vendor_data->name;
					else
						$vendor_name = __( 'ERROR FINDING VENDOR NAME', 'ignitewoo_vendor_stores' );

					$count++;

					$commissions[ $count ]['order'] = '<a href="' . admin_url( 'post.php?post=' . $r->ID . '&action=edit' ) . '" title=" ' . __( 'View Order', 'ignitewoo_vendor_stores' ). ' " target="_blank">' . $r->ID . '</a>';
				
					$commissions[ $count ]['date'] = date( 'D M j Y H:i:s', strtotime( $r->post_date ) );
				
					$commissions[ $count ]['vendor'] = $vendor_name . ' [ <a href="' . get_term_link( $vendor_data, 'shop_vendor' ) . '" title=" ' . __( 'View Vendor Store', 'ignitewoo_vendor_stores' ). ' " target="_blank">' . __( 'Frontend', 'ignitewoo_vendor_stores' ) . '</a> | <a href="' . admin_url( 'edit-tags.php?action=edit&taxonomy=shop_vendor&tag_ID=' . $vendor . '&post_type=product' ) . '" title=" ' . __( 'View Vendor Settings', 'ignitewoo_vendor_stores' ). ' " target="_blank">' . __( 'Backend', 'ignitewoo_vendor_stores' ) . '</a> ]'; 
					
					$commissions[ $count ]['product'] = '<a href="' . admin_url( 'post.php?post=' . $meta['_commission_product'][0] . '&action=edit' ) . '" title=" ' . __( 'View Product', 'ignitewoo_vendor_stores' ). ' " target="_blank">' . get_the_title( $meta['_commission_product'][0] ) . '</a>';
					
					$commissions[ $count ]['total'] = $meta['_commission_amount'][0];

					$commissions[ $count ]['status'] = $meta['_paid_status'][0];
						
					
				}
			}


		if ( empty( $commissions) ) {
		
			_e( 'No recent commission recorded yet', 'ignitewoo_vendor_stores' );
			
			return;
			
		} 
		
		?>

		<div class="woocommerce-reports-main">
		
			<?php 
			
				echo '<h3>' . __( 'Recent Commissions' , 'ignitewoo_vendor_stores' ) . '</h3>';
			
			?>
			
			<table class="widefat">
				<thead>
					<tr>
'						<th class="total_row"><?php _e( 'Order', 'ignitewoo_vendor_stores' ); ?></th>
						<th class="total_row"><?php _e( 'Date', 'ignitewoo_vendor_stores' ); ?></th>
						<th class="total_row"><?php _e( 'Vendor', 'ignitewoo_vendor_stores' ); ?></th>
						<th class="total_row"><?php _e( 'Product', 'ignitewoo_vendor_stores' ); ?></th>
						<th class="total_row"><?php _e( 'Total', 'ignitewoo_vendor_stores' ); ?></th>
						<th class="total_row"><?php _e( 'Status', 'ignitewoo_vendor_stores' ); ?></th>
					</tr>
				</thead>

				<tbody>
					<?php
						foreach ( $commissions as $month => $commission ) {
							$alt = ( isset( $alt ) && $alt == 'alt' ) ? '' : 'alt';

							echo '<tr class="' . $alt . '">';

							foreach ($commission as $key => $value) {
							
								if ( 'total' == $key ) 
									$value = woocommerce_price( $value );
									
								echo '<td class="total_row">' . $value  . '</td>';
							}

							echo '</tr>';
						}
					?>
				</tbody>
			</table>
		</div>
		
		<?php
	}


	
	function ignitewoo_vendor_stores_report_vendor_sales() {
		global $wpdb, $woocommerce;

		$chosen_product_ids = $vendor_id = $vendor = false;
		
		if ( isset( $_POST['vendor'] ) ) {
		
			$vendor_id = $_POST['vendor'];
			
			$vendor = ign_get_vendor( $vendor_id );
			
			$products = ign_get_vendor_products( $vendor_id );
			
			foreach( $products as $product )
				$chosen_product_ids[] = $product->ID;
			
		}

		if ( $vendor_id && $vendor ) {
			$option = '<option value="' . $vendor_id. '" selected="selected">' . $vendor->title . '</option>';
		} else {
			$option = '<option></option>';
		}

		?>
		<form method="post" action="">
			<p><select id="vendor" name="vendor" class="ajax_chosen_select_vendor" data-placeholder="<?php _e( 'Search for a vendor&hellip;', 'ignitewoo_vendor_storess' ); ?>" style="width: 400px;"><?php echo $option; ?></select> <input type="submit" style="vertical-align: top;" class="button" value="<?php _e( 'Show', 'ignitewoo_vendor_stores' ); ?>" /></p>
			<script type="text/javascript">
				jQuery(function(){

					// Ajax Chosen Vendor Selectors
					jQuery('select.ajax_chosen_select_vendor').ajaxChosen({
					method: 		'GET',
					url: 			'<?php echo admin_url( "admin-ajax.php" ); ?>',
					dataType: 		'json',
					afterTypeDelay: 100,
					minTermLength: 	1,
					data:		{
						action: 	'ignitewoo_json_search_vendors',
							security: 	'<?php echo wp_create_nonce( "search-vendors" ); ?>'
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

		if ( $chosen_product_ids && is_array( $chosen_product_ids ) ) {

			$start_date = date( 'Ym', strtotime( '-12 MONTHS', current_time('timestamp') ) ) . '01';
			
			$end_date = date( 'Ymd', current_time( 'timestamp' ) );

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
					SELECT order_item_meta_2.meta_value as product_id, order_item_meta_2.order_item_id as order_item_id, posts.post_date, SUM( order_item_meta.meta_value ) as item_quantity, SUM( order_item_meta_3.meta_value ) as line_total
					FROM {$wpdb->prefix}woocommerce_order_items as order_items

					LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
					LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_2 ON order_items.order_item_id = order_item_meta_2.order_item_id
					LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_3 ON order_items.order_item_id = order_item_meta_3.order_item_id
					LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID

					WHERE 	posts.post_type 	= 'shop_order'
					AND ( order_item_meta_2.meta_value IN ('" . implode( "','", array_merge( $chosen_product_ids, $children_ids ) ) . "') AND 	order_item_meta_2.meta_key = '_product_id' )
					AND 	posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
					AND 	order_items.order_item_type = 'line_item'
					AND 	order_item_meta.meta_key = '_qty'
					
					AND 	order_item_meta_3.meta_key = '_line_total'
					GROUP BY order_items.order_id
					ORDER BY posts.post_date ASC
				" ), array_merge( $chosen_product_ids, $children_ids ) );
				
			} else { 
			
				// Get order items
				$order_items = apply_filters( 'woocommerce_reports_product_sales_order_items', $wpdb->get_results( "
					SELECT order_item_meta_2.meta_value as product_id, order_item_meta_2.order_item_id as order_item_id, posts.post_date, SUM( order_item_meta.meta_value ) as item_quantity, SUM( order_item_meta_3.meta_value ) as line_total
					FROM {$wpdb->prefix}woocommerce_order_items as order_items

					LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
					LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_2 ON order_items.order_item_id = order_item_meta_2.order_item_id
					LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_3 ON order_items.order_item_id = order_item_meta_3.order_item_id
					LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
					LEFT JOIN {$wpdb->term_relationships} AS rel ON posts.ID = rel.object_ID
					LEFT JOIN {$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
					LEFT JOIN {$wpdb->terms} AS term USING( term_id )

					WHERE 	posts.post_type 	= 'shop_order'
					AND ( order_item_meta_2.meta_value IN ('" . implode( "','", array_merge( $chosen_product_ids, $children_ids ) ) . "') AND 	order_item_meta_2.meta_key = '_product_id' )
					AND 	posts.post_status 	= 'publish'
					AND 	tax.taxonomy		= 'shop_order_status'
					AND		term.slug			IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) . "')
					AND 	order_items.order_item_type = 'line_item'
					AND 	order_item_meta.meta_key = '_qty'
					
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
					$date = date( 'Ym', strtotime( $order_item->post_date ) );

					$variation_id = woocommerce_get_order_item_meta( $order_item->order_item_id, '_variation_id', true );
					
					// Calculate vendor earnings from sale

					$comm = ign_calculate_product_commission( $order_item->line_total, $order_item->product_id, $variation_id, $vendor->ID );

					$vendor_earnings = $comm;

					// Set values
					$product_sales[ $date ] = isset( $product_sales[ $date ] ) ? $product_sales[ $date ] + $order_item->item_quantity : $order_item->item_quantity;
					
					$product_totals[ $date ] = isset( $product_totals[ $date ] ) ? $product_totals[ $date ] + $vendor_earnings : $vendor_earnings;

					if ( $product_sales[ $date ] > $max_sales )
						$max_sales = $product_sales[ $date ];

					if ( $product_totals[ $date ] > $max_totals )
						$max_totals = $product_totals[ $date ];
				}
				
			}

			?>
			<h4><?php printf( __( 'Sales and earnings for %s:', 'ignitewoo_vendor_storess' ), $vendor->title ); ?></h4>
			<table class="bar_chart">
				<thead>
					<tr>
						<th><?php _e( 'Month', 'ignitewoo_vendor_stores' ); ?></th>
						<th colspan="2"><?php _e( 'Sales', 'ignitewoo_vendor_stores' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
						if ( sizeof( $product_sales ) > 0 ) {
							foreach ( $product_sales as $date => $sales ) {
								$width = ($sales>0) ? (round($sales) / round($max_sales)) * 100 : 0;
								$width2 = ($product_totals[$date]>0) ? (round($product_totals[$date]) / round($max_totals)) * 100 : 0;

								$orders_link = admin_url( 'edit.php?s&post_status=all&post_type=shop_order&action=-1&s=' . urlencode( implode( ' ', $chosen_product_titles ) ) . '&m=' . date( 'Ym', strtotime( $date . '01' ) ) . '&shop_order_status=' . implode( ",", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) );
								$orders_link = apply_filters( 'woocommerce_reports_order_link', $orders_link, $chosen_product_ids, $chosen_product_titles );

								echo '<tr><th><a href="' . esc_url( $orders_link ) . '">' . date_i18n( 'F', strtotime( $date . '01' ) ) . '</a></th>
								<td width="7%"><span>' . esc_html( $sales ) . ' ' . __( 'items', 'ignitewoo_vendor_stores' ) . '</span><span class="alt">' . woocommerce_price( $product_totals[ $date ] ) . '</span></td>
								<td class="bars">
									<span style="width:' . esc_attr( $width ) . '%">&nbsp;</span>
									<span class="alt" style="width:' . esc_attr( $width2 ) . '%">&nbsp;</span>
								</td></tr>';
							}
						} else {
							echo '<tr><td colspan="3">' . __( 'No sales :(', 'ignitewoo_vendor_stores' ) . '</td></tr>';
						}
					?>
				</tbody>
			</table>
			<?php

		}
	}
	
	
	function ignitewoo_vendor_stores_report_vendor_commission() { 
		global $start_date, $end_date, $woocommerce, $wpdb;

		$first_year = $wpdb->get_var( "SELECT post_date FROM {$wpdb->prefix}posts where post_type='vendor_commission' ORDER BY post_date ASC LIMIT 1;" );
		
		$first_year = $first_year ? date( 'Y', strtotime( $first_year ) ) : date( 'Y' );
		
		$current_year = isset( $_POST['show_year'] ) ? $_POST['show_year'] : date( 'Y', current_time( 'timestamp', false ) );
		
		$start_date = strtotime( $current_year . '0101' );

		$vendors = get_users( array( 'role' => 'vendor' ) );

		$vendors = apply_filters( 'ignitewoo_commission_vendors_list', $vendors );
		
		$selected_vendor = !empty($_POST['show_vendor']) ? (int) $_POST['show_vendor'] : false;
		
		?>

		<form method="post" action="" class="report_filters">
			<label for="show_year"><?php _e( 'Show:', 'ignitewoo_vendor_stores' ); ?></label>
			<select name="show_year" id="show_year">
				<?php
					for ( $i = $first_year; $i <= date( 'Y' ); $i++ )
						printf( '<option value="%s" %s>%s</option>', $i, selected( $current_year, $i, false ), $i );
				?>
			</select>


			<select class="chosen_select" id="show_vendor" name="show_vendor" style="width: 300px;" data-placeholder="<?php _e( 'Select a vendor&hellip;', 'ignitewoo_vendor_stores' ); ?>">
				<option></option>
				<?php 
				foreach ($vendors as $key => $vendor) {
				
					$vendor_store_id = get_user_meta( $vendor->ID, 'product_vendor', true );
					
					if ( empty( $vendor_store_id ) )
						continue;
						
					$term = get_term( $vendor_store_id, 'shop_vendor' );
					

					if ( !empty( $term ) && !is_wp_error( $term ) )
						$shop_name = $term->name;
					else
						$shop_name = '?';

					printf( '<option value="%s" %s>%s</option>', $vendor_store_id, selected( $selected_vendor, $vendor_store_id, false ), $vendor->display_name . ' ( ' . $shop_name . ' )' ); 
				
				
				}
				?>
			</select>
		
			<script type="text/javascript">
				jQuery(document).ready( function(){
					jQuery("select.chosen_select").chosen({allow_single_deselect: 'true'});
				});
			</script>
			
			<input type="submit" class="button" value="<?php _e( 'Show', 'ignitewoo_vendor_stores' ); ?>" /></p>
		</form>

		<?php

		if ( !empty( $selected_vendor ) ) { 
		
			$sql = 'select p.ID, p.post_date from ' . $wpdb->posts . ' p
				left join ' . $wpdb->postmeta . ' m1 on m1.post_id = ID
				left join ' . $wpdb->postmeta . ' m2 on m2.post_id = ID
				left join ' . $wpdb->usermeta . ' u1 on u1.meta_value = m1.meta_value
				where 
				post_type = "vendor_commission"
				and 
				u1.meta_key = "product_vendor" 
				and 
				( m1.meta_key = "_commission_vendor" and m1.meta_value = "' . $selected_vendor . '" )
				and
				m2.meta_key = "_commission_amount" 
				and "'. $current_year .'" = date_format( p.post_date ,"%Y")
				order by post_date ASC
				';

			$count = 0;
			
			$res = $wpdb->get_results( $sql );

			if ( !empty( $res ) ) {
			
				foreach( $res as $r ) {
			
					$count++;
					
					$meta = get_post_meta( $r->ID );
					
					$amount = empty( $meta['_amount'][0] ) ? 0 : round( $meta['_amount'][0], 2 );
					
					// items + shipping + tax
					$order_commission = empty( $meta['_commission_amount'][0] ) ? 0 : round( $meta['_commission_amount'][0], 2 );
					
					$shipping = empty( $meta['_shipping'][0] ) ? 0 : round( $meta['_shipping'][0], 2 );
					
					$tax = empty( $meta['_tax'][0] ) ? 0 : round( $meta['_tax'][0], 2 );
					
					if ( 'paid' == $meta['_paid_status'][0] )
						$amount_paid = $order_commission;
					else 
						$amount_paid = 0;
					
						
					$month = date( 'M', strtotime( $r->post_date ) );
					
					if ( !empty( $commissions[ $month ] ) ) { 

						$commissions[ $month ]['items'] = $count ;
					
						$commissions[ $month ]['commission'] += $amount;
					
						$commissions[ $month ]['paid'] += $amount_paid;
						
						$commissions[ $month ]['shipping'] += $shipping;
						
						$commissions[ $month ]['tax'] += $tax;

						$commissions[ $month ]['total'] += $order_commission;

					
					} else { 

						$commissions[ $month ] = array(
							'items'	     => $count,
							'commission' => $amount,
							'tax'        => $tax,
							'shipping'   => $shipping,
							'total'      => $order_commission,
							'paid'       => $amount_paid,
							
						);
						
					}
				}
			}
		}

		if ( !empty( $selected_vendor ) && empty( $commissions) ) {
		
			_e( 'No sales found for', 'ignitewoo_vendor_stores' );
			
			$term = get_term( $selected_vendor, 'shop_vendor' );
					
			echo ' ' . $term->name . '</h3>';
				
			return;
			
		} else if ( empty( $commissions ) )
			return;
		?>

		<div class="woocommerce-reports-main">
		
			<?php 
			
				echo '<h3>' . $current_year . ' ' . __( 'Sales For' , 'ignitewoo_vendor_stores' );
				
				$term = get_term( $selected_vendor, 'shop_vendor' );
					
				echo ' ' . $term->name . '</h3>';
			
			?>
			
			<table class="widefat">
				<thead>
					<tr>
						<th><?php _e( 'Month', 'ignitewoo_vendor_stores' ); ?></th>
						<th class="total_row"><?php _e( 'Items', 'ignitewoo_vendor_stores' ); ?></th>
						<th class="total_row"><?php _e( 'Commission', 'ignitewoo_vendor_stores' ); ?></th>
						<th class="total_row"><?php _e( 'Tax', 'ignitewoo_vendor_stores' ); ?></th>
						<th class="total_row"><?php _e( 'Shipping', 'ignitewoo_vendor_stores' ); ?></th>	<th class="total_row"><strong><?php _e( 'Total Earned', 'ignitewoo_vendor_stores' ); ?></strong></th>
						<th class="total_row"><strong><?php _e( 'Amount Paid', 'ignitewoo_vendor_stores' ); ?></strong></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<?php
							$total = array(
								'items'      => 0,
								'commission' => 0,
								'tax'        => 0,
								'shipping'   => 0,
								'total'      => 0,
								'paid'       => 0,
							);

							foreach ( $commissions as $month => $commission ) {
								$total['items']	     += $commission['items'];
								$total['commission'] += $commission['commission'];
								$total['tax']        += $commission['tax'];
								$total['shipping']   += $commission['shipping'];
								$total['total']      += $commission['total'];
								$total['paid']       += $commission['paid'];
							}

							echo '<td>' . __( 'Total', 'ignitewoo_vendor_stores' ) . '</td>';

							foreach ($total as $key => $value) {
								
								if ( 'items' != $key ) 
									$value = woocommerce_price( $value );
									
								echo '<td class="total_row">' . $value . '</td>';
									
								
							}
						?>
					</tr>
				</tfoot>
				<tbody>
					<?php
						foreach ( $commissions as $month => $commission ) {
							$alt = ( isset( $alt ) && $alt == 'alt' ) ? '' : 'alt';

							echo '<tr class="' . $alt . '"><td>' . $month . '</td>';

							foreach ($commission as $key => $value) {
							
								if ( 'items' != $key ) 
									$value = woocommerce_price( $value );
									
								echo '<td class="total_row">' . $value  . '</td>';
							}

							echo '</tr>';
						}
					?>
				</tbody>
			</table>
		</div>

		<?php
	}
	
	
	function ignitewoo_vendor_stores_report_vendor_product_commission() { 
		global $start_date, $end_date, $woocommerce, $wpdb;

		$first_year = $wpdb->get_var( "SELECT post_date FROM {$wpdb->prefix}posts where post_type='vendor_commission' ORDER BY post_date ASC LIMIT 1;" );
		
		$first_year = $first_year ? date( 'Y', strtotime( $first_year ) ) : date( 'Y' );
		
		$current_year = isset( $_POST['show_year'] ) ? $_POST['show_year'] : date( 'Y', current_time( 'timestamp', false ) );
		
		$start_date = strtotime( $current_year . '0101' );

		$products = !empty($_POST['product_ids']) ? (array) $_POST['product_ids'] : false;
		?>

		<form method="post" action="" class="report_filters">
			<label for="show_year"><?php _e( 'Show:', 'ignitewoo_vendor_stores' ); ?></label>
			
			<select name="show_year" id="show_year">
				<?php
					for ( $i = $first_year; $i <= date( 'Y' ); $i++ )
						printf( '<option value="%s" %s>%s</option>', $i, selected( $current_year, $i, false ), $i );
				?>
			</select>

			<select id="product_ids" name="product_ids[]" class="ajax_chosen_select_products" multiple="multiple" data-placeholder="<?php _e( 'Search for a product&hellip;', 'woocommerce' ); ?>" style="width: 400px;"></select>
			
			<script type="text/javascript">
				jQuery(function(){

					// Ajax Chosen Product Selectors
					jQuery("select.ajax_chosen_select_products").ajaxChosen({
						method: 'GET',
						url: '<?php echo admin_url('admin-ajax.php'); ?>',
						dataType: 'json',
						afterTypeDelay: 100,
						data:	{
							action: 'woocommerce_json_search_products',
							security: '<?php echo wp_create_nonce("search-products"); ?>'
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


			<input type="submit" class="button" value="<?php _e( 'Show', 'ignitewoo_vendor_stores' ); ?>" /></p>
		</form>

		<?php

		if ( !empty( $products ) ) { 
		
			$product_ids = implode( ',', $products );
			
			$sql = 'select p.ID, p.post_date from ' . $wpdb->posts . ' p
				left join ' . $wpdb->postmeta . ' m1 on m1.post_id = ID
				left join ' . $wpdb->postmeta . ' m2 on m2.post_id = ID
				where 
				post_type = "vendor_commission"
				and
				m1.meta_key = "_commission_amount" 
				and 
				( m2.meta_key = "_commission_product" and FIND_IN_SET ( m2.meta_value, "' . $product_ids . '" ) )
				and "'. $current_year .'" = date_format( p.post_date ,"%Y")
				order by post_date ASC
				';

			$res = $wpdb->get_results( $sql );

			$count = 0;
			
			if ( !empty( $res ) ) {
			
				foreach( $res as $r ) {
			
					++$count;
					
					$meta = get_post_meta( $r->ID );
					
					$amount = empty( $meta['_amount'][0] ) ? 0 : round( $meta['_amount'][0], 2 );
					
					// items + shipping + tax
					$order_commission = empty( $meta['_commission_amount'][0] ) ? 0 : round( $meta['_commission_amount'][0], 2 );
					
					$shipping = empty( $meta['_shipping'][0] ) ? 0 : round( $meta['_shipping'][0], 2 );
					
					$tax = empty( $meta['_tax'][0] ) ? 0 : round( $meta['_tax'][0], 2 );
					
					if ( 'paid' == $meta['_paid_status'][0] )
						$amount_paid = $order_commission;
					else 
						$amount_paid = 0;
					
						
					$month = date( 'M', strtotime( $r->post_date ) );
					
					if ( !empty( $commissions[ $month ] ) ) { 

						$commissions[ $month ]['items'] += $count;
						
						$commissions[ $month ]['commission'] += $amount;
					
						$commissions[ $month ]['paid'] += $amount_paid;
						
						$commissions[ $month ]['shipping'] += $shipping;
						
						$commissions[ $month ]['tax'] += $tax;

						$commissions[ $month ]['total'] += $order_commission;

					
					} else { 

						$commissions[ $month ] = array(
							'items'	     => $count,
							'commission' => $amount,
							'tax'        => $tax,
							'shipping'   => $shipping,
							'total'      => $order_commission,
							'paid'       => $amount_paid,
							
						);
						
					}
				}
			}
		}

		if ( !empty( $products ) && empty( $commissions) ) {
		
			_e( 'No sales found for', 'ignitewoo_vendor_stores' );
			
			foreach( $products as $p ) 
				$titles[] = get_the_title( $p );
					
			echo ' ' . implode( ', ', $titles ) . '</h3>';
				
			return;
			
		} else if ( empty( $commissions ) )
			return;
		?>

		<div class="woocommerce-reports-main">
		
			<?php 
			
				echo '<h3>' . $current_year . ' ' . __( 'Sales For' , 'ignitewoo_vendor_stores' );
				
				foreach( $products as $p ) 
					$titles[] = get_the_title( $p );
					
				echo ' ' . implode( ', ', $titles ) . '</h3>';
			
			?>
		
			<table class="widefat">
				<thead>
					<tr>
						<th><?php _e( 'Month', 'ignitewoo_vendor_stores' ); ?></th>
						<th class="total_row"><?php _e( 'Items', 'ignitewoo_vendor_stores' ); ?></th>
						<th class="total_row"><?php _e( 'Commission', 'ignitewoo_vendor_stores' ); ?></th>
						<th class="total_row"><?php _e( 'Tax', 'ignitewoo_vendor_stores' ); ?></th>
						<th class="total_row"><?php _e( 'Shipping', 'ignitewoo_vendor_stores' ); ?></th>	<th class="total_row"><strong><?php _e( 'Total Earned', 'ignitewoo_vendor_stores' ); ?></strong></th>
						<th class="total_row"><strong><?php _e( 'Amount Paid', 'ignitewoo_vendor_stores' ); ?></strong></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<?php
							$total = array(
								'items' => 0,
								'commission' => 0,
								'tax'        => 0,
								'shipping'   => 0,
								'total'      => 0,
								'paid'       => 0,
								
							);

							foreach ( $commissions as $month => $commission ) {
								$total['items']      += $commission['items'];
								$total['commission'] += $commission['commission'];
								$total['tax']        += $commission['tax'];
								$total['shipping']   += $commission['shipping'];
								$total['total']      += $commission['total'];
								$total['paid']       += $commission['paid'];
								
							}

							echo '<td>' . __( 'Total', 'ignitewoo_vendor_stores' ) . '</td>';

							foreach ($total as $key => $value) {
							
								if ( 'items' != $key ) 
									$value = woocommerce_price( $value );
									
								echo '<td class="total_row">' . $value . '</td>';
							}
						?>
					</tr>
				</tfoot>
				<tbody>
					<?php
						foreach ( $commissions as $month => $commission ) {
							$alt = ( isset( $alt ) && $alt == 'alt' ) ? '' : 'alt';

							echo '<tr class="' . $alt . '"><td>' . $month . '</td>';

							foreach ($commission as $key => $value) {
							
								if ( 'items' != $key ) 
									$value = woocommerce_price( $value );
									
								echo '<td class="total_row">' . $value . '</td>';
							}

							echo '</tr>';
						}
					?>
				</tbody>
			</table>
		</div>

		<?php
	}
}