<?php

/**
Copyright (c) 2013 - IgniteWoo.com
*/

if ( ! defined( 'ABSPATH' ) ) exit;

class IgniteWoo_Vendor_Stores_Products {


	function __construct() {
		global $ignitewoo_vendors;

		if ( current_user_can( 'vendor' ) && ( !current_user_can( 'manage_woocommerce' ) && !current_user_can( 'administrator' ) ) ) { 
				
			add_action( 'admin_menu', array( &$this, 'change_post_menu_label' ), 9999 );
			
			return;
		}

		if ( !current_user_can( 'manage_woocommerce' ) && !current_user_can( 'administrator' ) ) 
			return;

		add_post_type_support( 'product', 'author' );

		add_action( 'add_meta_boxes', array( $this, 'author_meta_box_title' ), 50 );

		add_action( 'wp_dropdown_users', array( $this, 'vendor_roles' ), 0, 1 );

		// Handle commission setting on product edit page
		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_product_settings' ) );
		
		add_action( 'init', array( $this, 'save_vendor_id' ), -99 );
		add_action( 'save_post', array( $this, 'process_product_settings' ), 1, 2 );
		
		add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'add_variation_settings' ), 10, 2 );
		
		add_action( 'woocommerce_process_product_meta_variable', array( $this, 'process_variation_settings' ) );

		// Switch non-hierarchical "vendors" tag metabox style to checkbox style
		add_action( 'admin_menu', array( $this, 'remove_meta_box' ), 25 );
		
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ), 49 );
		
		add_action( 'wp_ajax_add-' . $ignitewoo_vendors->token , array( &$this, '_wp_ajax_add_non_hierarchical_term' ) );
		
		add_action( 'admin_head', array( &$this, 'admin_head' ) );
		
		// Not implemented yet	
		//add_action('quick_edit_custom_box',  array( &$this, 'add_quick_edit' ), 10, 2);
		

	}

	function admin_head() { 
		?>
		<script>
		jQuery( document ).ready( function( $ ) { 
			$( '.tax_input_shop_vendor' ).parent().remove();
		})
		</script>
		<style>
		#taxonomy-shop_vendor .chosen-container { 
			width: 250px !important; 
		}
		</style>
		<?php

	}
	
	
	/** Not implemented yet */
	function add_quick_edit( $column_name, $post_type ) {

		if ( $column_name != 'taxonomy-shop_vendor') 
			return;
								
		if ( !empty( $this->quick_edit_posts ) && in_array( $post->ID, $this->quick_edit_posts ) )
			return;

		$this->quick_edit_posts[] = $post->ID;

		$p = new stdClass();
		
		$p->ID = $post->ID;
		
		$out = $this->metabox_content( $p, null, true );
		
		echo $out;
		
		return;

	}

	
	function change_post_menu_label() {
		global $menu;

		if ( !current_user_can( 'vendor' ) )
			return;
		
		foreach( $menu as $key => $parms ) 
			if ( 'WooCommerce' == $parms[0] ) {
				$menu[ $key ][0] = __( 'Tools', 'ignitewoo_vendor_stores' );
				$menu[ $key ][3] = __( 'Tools', 'ignitewoo_vendor_stores' );
			}

	}


	function add_product_settings() {
		global $post, $_product, $woocommerce, $vs_fields_added;

		
		if ( isset( $vs_fields_added ) )
			return;
		else
			$vs_fields_added = 1;

		if ( current_user_can( 'vendor' ) && !current_user_can( 'administrator' ) )
			return;

		if ( !has_term( 'simple', 'product_type', $post->ID ) )
			return;
		
		if ( !ign_vendor_access() ) {

			$commission = get_post_meta( $post->ID , '_product_vendors_commission', true );

			$commission_for = get_post_meta( $post->ID , '_product_vendors_commission_for', true );

			$html = '<div class="options_group">
					<p class="form-field _product_vendors_commission_field">
					<label for="_product_vendors_commission">' . __( 'Commission', 'ignitewoo_vendor_stores' ) . '</label>
					<input class="short" size="6" placeholder="" type="number" name="_product_vendors_commission" id="_product_vendors_commission" value="' . $commission . '" />&nbsp;
					<span class="description">' . __( 'OPTIONAL: Enter the commission override for this product. If no value is entered then the vendor\'s default commission will be used.', 'ignitewoo_vendor_stores' ) . '</span>
					</p>
					<p class="form-field _product_vendors_commission_field">
						<label for="_product_vendors_commission_for">' . __( 'Commission For', 'ignitewoo_vendor_stores' ) . ':</label>
						<select name="_product_vendors_commission_for" id="_product_vendors_commission_for">
							<option value="vendor" ' . selected( $commission_for, 'vendor', false ) . '>'. __( 'Vendor', 'ignitewoo_vendor_stores' ) . '</option>
							<option value="store" ' . selected( $commission_for, 'store', false ) .'>' . __( 'Store', 'ignitewoo_vendor_stores' ) . '</option>
							
						</select>
					</p>
				</div>';

			echo $html;
		}
	}

	
	function save_vendor_id() { 
		global $shop_vendor_id_saved;

		if ( isset( $_POST['tax_input']['shop_vendor'] ) ) {

			$shop_vendor_id_saved = $_POST['tax_input']['shop_vendor'];
			
			unset( $_POST['tax_input']['shop_vendor'] );

		}
		
	}
	

	function process_product_settings( $post_id, $post ) {
		global $shop_vendor_id_saved;
		
		// for quick editor action
		if ( !empty( $_POST['action'] ) && 'inline-save' == $_POST['action'] )
			return;
			
		$commission = 0;

		if ( isset( $_POST['_product_vendors_commission'] ) )
			$commission = $_POST['_product_vendors_commission'];
		
		if ( !empty( $commission ) )
			update_post_meta( $post_id , '_product_vendors_commission' , $commission );
		
		if ( isset( $_POST['_product_vendors_commission_for'] ) )
			$commission_for = $_POST['_product_vendors_commission_for'];
		
		if ( !empty( $commission_for ) )
			update_post_meta( $post_id , '_product_vendors_commission_for' , $commission_for );

		if ( !empty( $shop_vendor_id_saved ) ) {
		
			// Clear all out
			wp_set_object_terms( $post_id, '', 'shop_vendor' );
			
			// Set new
			wp_set_object_terms( $post_id, absint( $shop_vendor_id_saved ), 'shop_vendor' );
			
		} else { 
		
			wp_set_object_terms( $post_id, '', 'shop_vendor' );
			
		}
	}


	function add_variation_settings( $loop, $variation_data ) {
		global $var_loop_ids;
		
		if ( current_user_can( 'vendor' ) && !current_user_can( 'administrator' ) )
			return;

		if ( ign_vendor_access() )
			return;
		
		if ( isset( $var_loop_ids[ $loop ] ) )
			return;
		else
			$var_loop_ids[ $loop ] = 1;
		
		$commission = isset( $variation_data['_product_vendors_commission'] ) ? $variation_data['_product_vendors_commission'] : '';

		if ( isset( $commission[0] ) )
			$commission = $commission[0];
			
		$commission_for = isset( $variation_data['_product_vendors_commission_for'] ) ? $variation_data['_product_vendors_commission_for'] : 'vendor';

		if ( isset( $commission_for[0] ) )
			$commission_for = $commission_for[0];

		$html = '<tr>
				<td>
					<div class="_product_vendors_commission">
						<label for="_product_vendors_commission_' . $loop . '">' . __( 'Commission', 'ignitewoo_vendor_stores' ) . ':</label>
						<input size="4" type="text" name="variable_product_vendors_commission[' . $loop . ']" id="_product_vendors_commission_' . $loop . '" value="' . $commission . '" />
					</div>
				</td>
				<td>
					<div class="_product_vendors_commission">
						<label for="_product_vendors_commission_' . $loop . '">' . __( 'Commission For', 'ignitewoo_vendor_stores' ) . ':</label>
						<label>
						<select name="variable_product_vendors_commission_for[' . $loop . ']" >
							<option value="vendor" ' . selected( $commission_for, 'vendor', false ) . '>' . __( 'Vendor', 'ignitewoo_vendor_stores' ) . '</option>
							<option value="store" ' . selected( $commission_for, 'store', false ) .'>' . __( 'Store', 'ignitewoo_vendor_stores' ) . '</option>
							
						</select>
					</div>
				</td>
			</tr>';

		echo $html;
		
	}


	function process_variation_settings() {
		if ( empty( $_POST['variable_post_id'] ) )
			return;
			
		foreach( $_POST['variable_post_id'] as $k => $id ) {
		
			$commission = $_POST['variable_product_vendors_commission'][$k];
			
			update_post_meta( $id , '_product_vendors_commission' , $commission );
			
			$commission_for = $_POST['variable_product_vendors_commission_for'][$k];
			
			update_post_meta( $id , '_product_vendors_commission_for' , $commission_for );
		}
	}
	
		
	function author_meta_box_title() {
		global $wp_meta_boxes, $post;
		
		if ( 'product' !== $post->post_type )
			return;

		$wp_meta_boxes['product']['normal']['core']['authordiv']['title'] = __( 'Vendor User', 'ignitewoo_vendor_stores' ); 


	}
	
	function remove_meta_box() {
		global $ignitewoo_vendors;
	
		//remove_meta_box( 'authordiv', 'product', 'normal' );
		
		remove_meta_box('tagsdiv-' . $ignitewoo_vendors->token, 'product', 'normal');
		//remove_meta_box( $ignitewoo_vendors->token . 'div', 'product', 'side' );
	}

	
	function add_meta_box() {
		global $ignitewoo_vendors, $post;
		
		if ( 'product' !== $post->post_type )
			return;
			
		if ( ! ign_vendor_access() ) {
		
			$tax = get_taxonomy( $ignitewoo_vendors->token );
		
			add_meta_box( 'authordiv', __( 'Vendor User', 'ignitewoo_vendor_stores' ), 'post_author_meta_box', 'product', 'side', 'core' );
			
			add_meta_box( 'tagdiv-' . $ignitewoo_vendors->token, __( 'Vendor Store', 'ignitewoo_vendor_stores' ), array( $this, 'metabox_content' ), 'product', 'side', 'core' );
		
		}
	}


	function metabox_content( $post, $box, $return = false ) {
		global $ignitewoo_vendors;

		$taxonomy = $ignitewoo_vendors->token;

		$item_vendor = get_the_terms( $post->ID, $taxonomy );

		if ( !empty( $item_vendor ) && !is_wp_error( $item_vendor ) ) {
			$item_vendor = array_values( $item_vendor );
			$this_vendor = $item_vendor[0]->term_id;
		} else {
			$this_vendor = 9999999999;
		}

		$all_vendors = get_terms( $taxonomy, array( 'hide_empty' => false ) );

		if ( !$return ) 
			$class = 'chosen';
		else 
			$class = '';
 
		ob_start();
		
		?>
		<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">

			<select name="tax_input[shop_vendor]" class="<?php echo $class ?>" style="width:200px !important">
				<option value=""> &nbsp; </option>
				<?php 


				if ( !empty( $all_vendors ) && !is_wp_error( $all_vendors ) )
				foreach( $all_vendors as $v ) { 
					?>
					<option value="<?php echo $v->term_id ?>" <?php selected( $v->term_id, $this_vendor, true ) ?>>
						<?php echo $v->name ?>
					</option>
					<?php 
				}
				
				?>
			</select>
			
		</div>
		
		<?php
		
		$res = ob_get_clean();

		if ( !$return )
			echo $res;
		else
			return $res;
	}
	

	function vendor_roles( $output ) {
		global $post;

		if ( empty( $post ) ) 
			return $output;

		if ( 'product' != $post->post_type ) 
			return $output;

		// Return if this isn't the vendor author override dropdown
		if ( false === strpos( $output, 'post_author_override' ) ) 
			return $output;

		$args = array(
			'selected' => $post->post_author,
			'id' => 'post_author_override',
		);

		$output = $this->get_dropdown( $args );

		return $output;
	}


	function get_dropdown( $args ) {
	
		$default_args = array(
			'placeholder',
			'id',
			'class',
		);

		foreach ( $default_args as $key ) {
			
			if ( !is_array( $key ) && empty( $args[$key] ) ) 
				$args[$key] = '';
				
			else if ( is_array( $key ) )
				foreach ( $key as $val ) 
					$args[$key][$val] = esc_attr( $args[$key][$val] );
		}
		
		extract( $args );

		$roles = array( 'vendor', 'administrator', 'shop_manager' );
		
		$user_args = array( 'fields' => array( 'ID', 'user_login' ) );

		$output = "<select style='width:200px;' name='$id' id='$id' class='$class' data-placeholder='$placeholder'>\n";
		
		$output .= "<option value=''> &nbsp; </option>";

		foreach ( $roles as $role ) {

			$new_args = $user_args;
			
			$new_args['role'] = $role;
			
			$users = get_users( $new_args );

			if ( empty( $users ) ) 
				continue;
			
			foreach ( (array)$users as $user ) {
			
				$select = selected( $user->ID, $selected, false );
				
				$output .= "<option value='{$user->ID}' {$select}>{$user->user_login}</option>";
				
			}

		}
		
		$output .= "</select>";

		$output .= '<script type="text/javascript">jQuery(function() {jQuery("#'.$id.'").chosen();});</script>';

		return $output;
		
	}
	
	/**
	 * Modified version of _wp_ajax_add_hierarchical_term to handle non-hierarchical taxonomies
	 */
	function _wp_ajax_add_non_hierarchical_term() {
	
		$action = $_POST['action'];
		
		$taxonomy = get_taxonomy( substr( $action, 4 ) );
		
		check_ajax_referer( $action, '_ajax_nonce-add-' . $taxonomy->name );
		
		if ( !current_user_can( $taxonomy->cap->edit_terms ) )
			wp_die( -1 );
			
		$names = explode( ',', $_POST['new'.$taxonomy->name] );
		
		$parent = 0;
		
		if ( $taxonomy->name == 'category' )
			$post_category = isset( $_POST['post_category'] ) ? (array) $_POST['post_category'] : array();
		else
			$post_category = ( isset( $_POST['tax_input'] ) && isset( $_POST['tax_input'][$taxonomy->name] ) ) ? (array) $_POST['tax_input'][$taxonomy->name] : array();
			
		$checked_categories = array_map( 'absint', (array) $post_category );

		foreach ( $names as $tax_name ) {
		
			$tax_name = trim( $tax_name );
			
			$category_nicename = sanitize_title( $tax_name );
			
			if ( '' === $category_nicename )
				continue;
				
			if ( ! $cat_id = term_exists( $tax_name, $taxonomy->name, $parent ) )
				$cat_id = wp_insert_term( $tax_name, $taxonomy->name, array( 'parent' => $parent ) );
				
			if ( is_wp_error( $cat_id ) )
				continue;
				
			else if ( is_array( $cat_id ) )
				$cat_id = $cat_id['term_id'];
				
			$checked_categories[] = $cat_id;
			
			if ( $parent ) // Do these all at once in a second
				continue;
				
			$new_term = get_term( $cat_id, $taxonomy->name );
			
			$data = "\n<li id='{$taxonomy->name}-{$cat_id}'>" . '<label class="selectit"><input value="' . $new_term->slug . '" type="checkbox" name="tax_input['.$taxonomy->name.'][]" id="in-'.$taxonomy->name.'-' . $new_term->term_id . '"' . checked( in_array( $new_term->term_id, $checked_categories ), true, false ) . ' /> ' . esc_html( apply_filters('the_category', $new_term->name )) . '</label>';
			
			$add = array(
				'what' => $taxonomy->name,
				'id' => $cat_id,
				'data' => str_replace( array("\n", "\t"), '', $data ),
				'position' => -1
			);
		}

		$x = new WP_Ajax_Response( $add );
		
		$x->send();
	}


}

$ignitewoo_vendor_stores_product_admin = new IgniteWoo_Vendor_Stores_Products();


if ( ! class_exists( 'IgniteWoo_Walker_Tag_Checklist' ) ) {
	/**
	 * Modified version of WP's Walker_Category_Checklist class
	 */
	class IgniteWoo_Walker_Tag_Checklist extends Walker {
	
		var $tree_type = 'tag';
		
		var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

		function start_lvl( &$output, $depth = 0, $args = array() ) {
		
			$indent = str_repeat("\t", $depth);
			
			$output .= "$indent<ul class='children'>\n";
			
		}

		function end_lvl( &$output, $depth = 0, $args = array() ) {
		
			$indent = str_repeat("\t", $depth);
			
			$output .= "$indent</ul>\n";
		}

		
		function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
		
			extract($args);
			
			if ( empty($taxonomy) )
				$taxonomy = 'tag';

			if ( $taxonomy == 'tag' )
				$name = 'post_tag';
			else
				$name = 'tax_input['.$taxonomy.']';

			$class = in_array( $object->term_id, $popular_cats ) ? ' class="popular-category"' : '';
			$output .= "\n<li id='{$taxonomy}-{$object->term_id}'$class>" . '<label class="selectit"><input value="' . $object->term_id . '" type="radio" name="'.$name.'[]" id="in-'.$taxonomy.'-' . $object->term_id . '"' . checked( in_array( $object->term_id, $selected_cats ), true, false ) /*. disabled( empty( $args['disabled'] ), false, false ) */ . ' /> ' . esc_html( apply_filters('the_category', $object->name )) . '</label>';
			
		}

		
		function end_el( &$output, $object, $depth = 0, $args = array() ) {
		
			$output .= "</li>\n";
			
		}
	}
}

$ignitewoo_vendor_stores_products = new IgniteWoo_Vendor_Stores_Products();
