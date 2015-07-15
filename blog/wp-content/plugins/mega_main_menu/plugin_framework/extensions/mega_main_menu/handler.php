<?php
/**
 * @package MegaMain
 * @subpackage MegaMain
 * @since mm 1.0
 */

	/** 
	 * actions what we need for call all functions in this file.
	 * @return void
	 */
	if ( is_array( mmpm_get_option( 'coercive_styles' ) ) && in_array( 'true', mmpm_get_option( 'coercive_styles', array() ) ) ) {
		remove_all_filters( 'wp_nav_menu_items', 60 ); 
		remove_all_filters( 'wp_nav_menu_args', 60 ); 
	}
	add_filter( 'wp_nav_menu_items', 'mmpm_nav_woo_cart', 2016, 8 );
	add_filter( 'wp_nav_menu_items', 'mmpm_nav_search', 2015, 8 );
    add_filter( 'wp_nav_menu_args', 'mmpm_set_walkers', 2014, 8 ); 

    if ( function_exists( 'theme_get_menu' ) && function_exists( 'theme_get_list_menu' ) ) {
        $mega_menu_locations = mmpm_get_option( 'mega_menu_locations' );
        $indefinite_location_mode = ( is_array( mmpm_get_option( 'indefinite_location_mode' ) ) && in_array( 'true', mmpm_get_option( 'indefinite_location_mode' ) ) ) ? true : false;
        if ( isset( $mega_menu_locations[1] ) && ( $indefinite_location_mode === true ) ) {
        	$args['theme_location'] = $mega_menu_locations[ 1 ];
        	$args['echo'] = false;
			$GLOBALS['wp_nav_menu_html'] = wp_nav_menu( $args );
	    	function artisteer_nav_menu () {
	    		global $wp_nav_menu_html;
		    	return $wp_nav_menu_html;
	    	}
			add_filter( 'wp_nav_menu', 'artisteer_nav_menu', 50 );
        }
    }
	/** 
	 * Check current location and set args.
	 * @return $items
	 */
    function mmpm_set_walkers ( $args ){
        $args = (array)$args;
        $mega_menu_locations = mmpm_get_option( 'mega_menu_locations' );
        $indefinite_location_mode = ( is_array( mmpm_get_option( 'indefinite_location_mode' ) ) && in_array( 'true', mmpm_get_option( 'indefinite_location_mode' ) ) ) ? true : false;
        if ( $indefinite_location_mode === true && isset( $args['theme_location'] ) && $args['theme_location'] == '' && isset( $mega_menu_locations[1] ) ) {
        	$args['theme_location'] = $mega_menu_locations[ 1 ];
        }
        $original_theme_location_name = $args['theme_location'];
        $args['theme_location'] = str_replace( ' ', '-', $args['theme_location'] );
        if ( ( is_array( $mega_menu_locations ) && in_array( $args['theme_location'], $mega_menu_locations ) ) || ( $indefinite_location_mode === true ) ) {
            $args['items_wrap'] = '<ul id="%1$s" class="%2$s">%3$s</ul><!-- /class="%2$s" -->';
            $args['walker'] = new Mega_Main_Walker_Nav_Menu;
            $args['container'] = 'div';
            $args['container_id'] = 'mega_main_menu';
            $args['container_class'] = 'nav_menu ' . $args['theme_location'] . 
            ' icons-' . mmpm_get_option( $args['theme_location'] . '_first_level_icons_position', 'left' ) . 
           	' first-lvl-align-' . mmpm_get_option( $args['theme_location'] . '_first_level_item_align', 'left' ) . 
           	' first-lvl-separator-' . mmpm_get_option( $args['theme_location'] . '_first_level_separator', 'none' ) . 
           	' direction-' . mmpm_get_option( $args['theme_location'] . '_direction', 'horizontal' ) . 
           	' responsive-' . ( ( is_array( mmpm_get_option( 'responsive_styles' ) ) && in_array( 'true', mmpm_get_option( 'responsive_styles' ) ) ) ? 'enable' : 'disable' ) . 
           	' mobile_minimized-' . ( ( ( is_array( mmpm_get_option( $args['theme_location'] . '_mobile_minimized' ) ) && in_array( 'true', mmpm_get_option( $args['theme_location'] . '_mobile_minimized' ) ) ) || ( $indefinite_location_mode === true ) ) ? 'enable' : 'disable' ) . 
           	' dropdowns_animation-' . mmpm_get_option( $args['theme_location'] . '_dropdowns_animation', 'none' ) . 
            ' version-' . str_replace('.', '-', MMPM_PLUGIN_VERSION );
            $args['menu_id'] = 'mega_main_menu_ul';
            $args['menu_class'] = 'mega_main_menu_ul';
            $args['before'] = '';
            $args['after'] = '';
            $args['link_before'] = '';
            $args['link_after'] = '';
            $args['depth'] = 5;
			$items_wrap = '';
			$data_sticky = ( (is_array( mmpm_get_option( $args['theme_location'] . '_sticky_status' ) ) && in_array( 'true', mmpm_get_option( $args['theme_location'] . '_sticky_status', array() ) ) ) /*|| ( $indefinite_location_mode === true )*/ ) 
				? ' data-sticky="1"' 
				: '';
			$data_sticky .= ( ( mmpm_get_option( $args['theme_location'] . '_sticky_offset' ) && is_array( mmpm_get_option( $args['theme_location'] . '_sticky_status' ) ) && in_array( 'true', mmpm_get_option( $args['theme_location'] . '_sticky_status', array() ) ) ) || ( $indefinite_location_mode === true ) ) 
				? ' data-stickyoffset="' . mmpm_get_option( $args['theme_location'] . '_sticky_offset' ) . '"' 
				: '';
			$items_wrap .= mmpm_ntab(1) . '<div class="menu_holder"' . $data_sticky . '>';
			$items_wrap .= mmpm_ntab(2) . '<div class="menu_inner">';
			$items_wrap .= mmpm_ntab(3) . '<span class="nav_logo">';
			if( ( is_array( mmpm_get_option( $args['theme_location'] . '_included_components' ) ) && in_array( 'company_logo', mmpm_get_option( $args['theme_location'] . '_included_components' ) ) ) /*|| ( is_array( mmpm_get_option( 'indefinite_location_mode' ) ) && in_array( 'true', mmpm_get_option( 'indefinite_location_mode' ) ) ) */ && mmpm_get_option( 'logo_src' ) ) {
					$items_wrap .= mmpm_ntab(4) . '<a class="logo_link" href="' . home_url() . '" title="' . get_bloginfo( 'name' ) . '" tabindex="1"><img src="' . mmpm_get_option( 'logo_src' ) . '" alt="' . get_bloginfo( 'name' ) . '" /></a>';
				$args['container_class'] .= ' include-logo';
			} else {
				$args['container_class'] .= ' no-logo';
			}
			$items_wrap .= mmpm_ntab(4) . '<a class="mobile_toggle"><span class="mobile_button">' . __( 'Menu', MMPM_TEXTDOMAIN_ADMIN ) . '&nbsp; <span class="symbol_menu">&equiv;</span><span class="symbol_cross">&#x2573;</span></span></a>';  // &#x2573;   &equiv;
			$items_wrap .= mmpm_ntab(3) . '</span><!-- /class="nav_logo" -->';
			$items_wrap .= mmpm_ntab(4) . $args['items_wrap'];
			$items_wrap .= mmpm_ntab(2) . '</div><!-- /class="menu_inner" -->';
			$items_wrap .= mmpm_ntab(1) . '</div><!-- /class="menu_holder" -->';
			$args['items_wrap'] = $items_wrap;
			if( ( in_array( $args['theme_location'], $mega_menu_locations) ) && is_array( mmpm_get_option( $args['theme_location'] . '_included_components' ) ) && in_array( 'search_box', mmpm_get_option( $args['theme_location'] . '_included_components' ) ) ) {
				$args['container_class'] .= ' include-search';
			} else {
				$args['container_class'] .= ' no-search';
			}
        $args['theme_location'] = $original_theme_location_name;
        }
        return $args;
    }

	/** 
	 * Include search box in menu.
	 * @return $items
	 */
	function mmpm_nav_search( $items, $args ) {
		$args = (object) $args;
		if( isset( $args->theme_location ) ) {
	        $args->theme_location = str_replace( ' ', '-', $args->theme_location );
	        $mega_menu_locations = is_array( mmpm_get_option( 'mega_menu_locations' ) ) ? mmpm_get_option( 'mega_menu_locations' ) : array();
			if( ( in_array( $args->theme_location, $mega_menu_locations) ) && is_array( mmpm_get_option( $args->theme_location . '_included_components' ) ) && in_array( 'search_box', mmpm_get_option( $args->theme_location . '_included_components' ) ) ) {
				ob_start();
				include( MMPM_EXTENSIONS_DIR . '/html_templates/searchform.php' );
				$searchform = ob_get_contents();
				ob_end_clean();
				$items = mmpm_ntab(1) . '<li class="nav_search_box">' . mmpm_ntab(1) . $searchform . mmpm_ntab(1) . '</li><!-- class="nav_search_box" -->' . mmpm_ntab(1) . $items;
			}
		}
		return $items;
	}

	/** 
	 * Include woo_cart in menu.
	 * @return $items
	 */
	function mmpm_nav_woo_cart( $items, $args ) {
		$args = (object) $args;
		if( isset( $args->theme_location ) ) {
	        $args->theme_location = str_replace( ' ', '-', $args->theme_location );
	        $mega_menu_locations = is_array( mmpm_get_option( 'mega_menu_locations' ) ) ? mmpm_get_option( 'mega_menu_locations' ) : array();
			if( (in_array( $args->theme_location, $mega_menu_locations) ) && is_array( mmpm_get_option( $args->theme_location . '_included_components' ) ) && in_array( 'woo_cart', mmpm_get_option( $args->theme_location . '_included_components' ) ) ) {
				if ( class_exists( 'Woocommerce' ) ){
					global $woocommerce;
					ob_start();
					woocommerce_get_template( 'cart/mini-cart.php', array('list_class' => '') );
					$woo_cart = ob_get_contents();
					ob_end_clean();
				}
				$woo_cart_item = mmpm_ntab(1) . '<li class="nav_woo_cart multicolumn_dropdown drop_to_left submenu_default_width">';
				$woo_cart_item .= mmpm_ntab(2) . '<span class="item_link with_icon">'; 
				$woo_cart_item .= mmpm_ntab(3) . '<i class="im-icon-cart"></i>'; 
				$woo_cart_item .= mmpm_ntab(3) . '<span>'; 
				$woo_cart_item .= '(' . $woocommerce->cart->get_cart_contents_count() . ') - ' . $woocommerce->cart->get_cart_total(); 
				$woo_cart_item .= mmpm_ntab(3) . '</span>'; 
				$woo_cart_item .= mmpm_ntab(2) . '</span><!-- class="item_link" -->'; 
				$woo_cart_item .= mmpm_ntab(2) . '<ul class="mega_dropdown">';
				$woo_cart_item .= mmpm_ntab(3) . $woo_cart;
				$woo_cart_item .= mmpm_ntab(2) . '</ul><!-- class="mega_dropdown" -->';
				$woo_cart_item .= mmpm_ntab(1) . '</li><!-- class="nav_woo_cart" -->';
				$items = $woo_cart_item . $items;
			}
		}
		return $items;
	}
?>