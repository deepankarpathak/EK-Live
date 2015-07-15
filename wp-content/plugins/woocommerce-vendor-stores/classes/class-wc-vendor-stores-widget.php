<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class WooCommerce_Product_Vendors_Widget extends WP_Widget {

	private $widget_cssclass;
	
	private $widget_description;
	
	private $widget_idbase;
	
	private $widget_title;


	public function __construct() {

		$this->widget_cssclass = 'widget_vendor_stores';
		
		$this->widget_description = __( 'Display selected or current vendor store info.', 'ignitewoo_vendor_stores' );
		
		$this->widget_idbase = 'vendor_stores';
		
		$this->widget_title = __( 'WooCommerce Vendor Stores', 'ignitewoo_vendor_stores' );

		$widget_ops = array( 'classname' => $this->widget_cssclass, 'description' => $this->widget_description );

		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => $this->widget_idbase );

		$this->WP_Widget( $this->widget_idbase, $this->widget_title, $widget_ops, $control_ops );
	} 


	public function widget( $args, $instance ) {
	
		extract( $args, EXTR_SKIP );

		$vendor_id = false;
		
		$vendors = false;

		// Only show current vendor store widget when showing a vendor's product(s)
		$show_widget = true;
		
		if ( $instance['vendor'] == 'current' ) {
		
			if ( is_singular( 'product' ) ) {
			
				global $post;
				
				$vendors = ign_get_product_vendors( $post->ID );
				
				if ( ! $vendors )
					$show_widget = false;

			}
			
			if ( is_archive() && ! is_tax( 'shop_vendor' ) ) 
				$show_widget = false;

			
		} else {
		
			$vendors = array(
				ign_get_vendor( $instance['vendor'] )
			);
		}

		if ( !$show_widget ) 
			return;
			

		if ( is_tax( 'shop_vendor' ) ) {
			$vendor_id = get_queried_object()->term_id;
			if ( $vendor_id ) {
				$vendors = array(
					ign_get_vendor( $vendor_id )
				);
			}
		}

		if ( $vendors ) {

			// Set up widget title
			if ( $instance['title'] )
				$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
			else
				$title = false;
			
			echo $before_widget;

			if ( $title )
				echo $before_title . $title . $after_title;

			$html = '';

			foreach( $vendors as $vendor ) {
			
				$html .= '<div class="widget_vendor_stores_wrap">';
			
				if ( 'yes' == $instance['logo'] ) { 
				
					$thumbnail_id = get_woocommerce_term_meta( $vendor->ID, 'thumbnail_id', true );
					
					if ( $thumbnail_id ) {

						$width = null;
						
						$height = null; 
						
						if ( !empty( $ignitewoo_vendors->settings['logo_width'] ) ) 
							$width = $ignitewoo_vendors->settings['logo_width'];
						
						if ( !empty( $ignitewoo_vendors->settings['logo_height'] ) ) 
							$height = $ignitewoo_vendors->settings['logo_height'];
						
						// If no size is specified in the plugin settings then use the full image size
						if ( !empty( $width ) && !empty( $height ) )
							$size = array( $width, $height );
						else 
							$size = 'large';
							
						$image = wp_get_attachment_image_src( $thumbnail_id, $size ); 

						if ( !empty( $image ) && is_array( $image ) )
							$image = $image[0];

					}
		
					if ( !empty( $thumbnail_id ) ) { 
					
						//$image = wp_get_attachment_thumb_url( $thumbnail_id );
						
						$html .= '<img class="widget_vendor_stores_logo" src="' . $image . '">';
						
					} 
					
					
					/*
					else
						$image = woocommerce_placeholder_img_src();
			
					}
					*/
					
				}
			
				$html .= '<h4>' . $vendor->title . '</h4>';
				
				$html .= '<p>' . wpautop( $vendor->description ) . '</p>';
				
				$html .= '<p><a href="' . esc_attr( $vendor->url ) . '" title"' . sprintf( __( apply_filters( 'ign_vendor_stores_widget_link_text', 'See more products from %1$s' ), 'ignitewoo_vendor_stores' ), $vendor->title ) . '">' . sprintf( __( apply_filters( 'ign_vendor_stores_widget_link_text', 'See more products from %1$s' ), 'ignitewoo_vendor_stores' ), $vendor->title ) . '</a></p>';
				
				$html .= '</div>';
			}

			do_action( $this->widget_cssclass . '_top' );

			echo $html;

			do_action( $this->widget_cssclass . '_bottom' );

			echo $after_widget;
		}
		
	}

	public function update ( $new_instance, $old_instance ) {
	
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		
		$instance['vendor'] = esc_attr( $new_instance['vendor'] );

		$instance['logo'] = esc_attr( $new_instance['logo'] );
		
		return $instance;
	}

	
	public function form( $instance ) {

		$defaults = array(
			'title' => '',
			'vendor' => 'current',
			'logo' => 'no',
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		// Set up vendor options
		$vendors = ign_get_vendors();
		
		$vendor_options = '<option value="current" ' . selected( $instance['vendor'], 'current', false ) . '>' . __( 'Current vendor(s)', 'ignitewoo_vendor_stores' ) . '</option>';
		
		if ( !empty( $vendors ) )
		foreach( $vendors as $vendor ) {
			$vendor_options .= '<option value="' . esc_attr( $vendor->ID ) . '" ' . selected( $instance['vendor'], $vendor->ID, false ) . '>' . esc_html( $vendor->title ) . '</option>';
		}
		
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (optional):', 'ignitewoo_vendor_stores' ); ?></label>
			
			<input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>"  value="<?php echo $instance['title']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'vendor' ); ?>"><?php _e( 'Vendor:', 'ignitewoo_vendor_stores' ); ?></label>
			
			<select name="<?php echo $this->get_field_name( 'vendor' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'vendor' ); ?>">
				<?php echo $vendor_options; ?>
			</select><br/><br/>
			
			<span class="description"><?php _e( '"Current vendor(s)" will display the details of the vendors whose product(s) are being viewed at the time. It will not show on other pages.', 'ignitewoo_vendor_stores' ); ?></span>
			
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'vendor' ); ?>"><?php _e( 'Display Logo', 'ignitewoo_vendor_stores' ); ?></label>
			
			<input type="checkbox" name="<?php echo $this->get_field_name( 'logo' ); ?>" value="yes" class="widefat" id="<?php echo $this->get_field_id( 'logo' ); ?>" <?php checked( $instance['logo'], 'yes', true ) ?>/><br/><br/>
			
			<span class="description"><?php _e( 'Show the vendor store logo when available', 'ignitewoo_vendor_stores' ); ?></span>
			
		</p>
		<?php
	} 
}


add_action( 'widgets_init', create_function( '', 'return register_widget("WooCommerce_Product_Vendors_Widget");' ), 1 );