<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*
  public function price_filter_init() {
  if ( is_active_widget( false, false, 'woocommerce_price_filter', true ) && ! is_admin() ) {

  $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

  wp_register_script( 'wc-price-slider', WC()->plugin_url() . '/assets/js/frontend/price-slider' . $suffix . '.js', array( 'jquery-ui-slider' ), WC_VERSION, true );

  wp_localize_script( 'wc-price-slider', 'woocommerce_price_slider_params', array(
  'currency_symbol' 	=> get_woocommerce_currency_symbol(),
  // 'currency_symbol' 	=> gh_get_local_currency_symbol(),
  'currency_pos'      => get_option( 'woocommerce_currency_pos' ),
  'min_price'			=> isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '',
  'max_price'			=> isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : ''
  ) );

  add_filter( 'loop_shop_post_in', array( $this, 'price_filter' ) );
  }
  }

 */
// Location: this function exists in file woocommerce/includes/class-wc-query.php 
// Purpose: To change the currency according to the country.
// Action: get_woocommerce_currency_symbol() give the value of currency which value save in database. we calling our function in this which is commented
// above gh_get_currency_symbol() 
// Action for creating menu in wordpress admin

add_action('admin_menu', 'incomplete_order_page_menu');

add_filter('wc_price', 'gh_wc_price', 10, 2); // applying filter to add our custom currency symbol

add_filter('woocommerce_price_filter_widget_amount', 'gh_price_filter', 10, 2); // applying filter to add our custom currency symbol
// creating custom table in database for incomplete order

function incomplete_order() {
    global $wpdb;
    $table_name = $wpdb->prefix . "incomplete_orders";
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT  PRIMARY KEY, email VARCHAR(100) NOT NULL, items VARCHAR(100) NOT NULL, price VARCHAR(100) NOT NULL, queried_on DATETIME NOT NULL);";


    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

add_action('init', 'incomplete_order');

// class for getting custom wordpress table in wordpress admin  

class Incomplete_Order_List_Table extends WP_List_Table {

    /**

     * Constructor, we override the parent to pass our own arguments

     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.

     */
    function __construct() {

        parent::__construct(array(
            'singular' => 'incomplete_order_list', //Singular label
            'plural' => 'incomplete_order_lists' //plural label, also this well be one of the table css class
        ));

        add_action('admin_head', array(&$this, 'admin_header'));
    }

    function admin_header() {

        $page = ( isset($_GET['page']) ) ? esc_attr($_GET['page']) : false;

        if ('incomplete_order_bkap_top_menu' != $page)
            return;

        echo '<style type="text/css">';

        echo '.wp-list-table .column-id {width:20px; }';

        echo '</style>';
    }

    /**

     * Add extra markup in the toolbars before or after the list

     * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list

     */
    function extra_tablenav($which) {

        if ($which == "top") {

            //The code that goes before the table is here
            //echo"Hello, I'm before the table";
        }

        if ($which == "bottom") {

            //The code that goes after the table is there
            //echo"Hi, I'm after the table";
        }
    }

    /**

     * Define the columns that are going to be used in the table

     * @return array $columns, the array of columns to use with the table

     */
    function get_columns() {

        return $columns = array(
            'cb' => '<input type="checkbox" />',
            'id' => __('ID'),
            'email' => __('Email'),
            'items' => __('Course'),
            'price' => __('Price'),
            'queried_on' => __('Time')
        );
    }

    /**

     * Decide which columns to activate the sorting functionality on

     * @return array $sortable, the array of columns that can be sorted by the user

     */
    public function get_sortable_columns() {

        return $sortable = array(
            'id' => array('id', true),
                //'last_name'=>array('last_name',false)
        );
    }

    function get_bulk_actions() {

        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    public function process_bulk_action() {

        global $wpdb;
        $table_name = $wpdb->prefix . 'incomplete_orders';
        $action = $this->current_action();
        switch ($action) {
            case 'delete':
                $counter = 0;
                $status = true;
                if (!empty($_POST['incomplete_order_lists'])) {
                    foreach ($_POST['incomplete_order_lists'] as $key => $val) {

                        $status = $wpdb->query($wpdb->prepare("DELETE FROM {$table_name} WHERE id = %d", $val));

                        if ($status) {

                            $counter++;
                        } else {

                            break;
                        }
                    }
                    if ($counter > 0) {
                        echo "<div class='sucess'><p>" . $counter . "Records Deleted Successfuly</p></div>";
                    } else {
                        echo "<div class='error'><p>No Record Deleted!</p></div>";
                    }
                }

                break;

            default:

                // do nothing or something else

                return;
                break;
        }
        return;
    }

    function column_default($item, $column_name) {

        switch ($column_name) {

            case 'id':
            case 'email':
            case 'items':
            case 'price':
            case 'queried_on':

                if ($column_name == 'queried_on') {

                    $app_timestamp = strtotime($item->{$column_name});

                    return date('F j, Y, g:i a', $app_timestamp);
                } else {

                    return $item->{$column_name};
                }

            default:

                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_first_name($item) {

        $actions = array(
            'delete' => sprintf('<a href="?page=%s&action=%s&id=%d">Delete</a>', $_REQUEST['page'], 'delete', $item->id));
        return sprintf('%1$s %2$s', $item->first_name, $this->row_actions($actions));
    }

    function column_cb($item) {

        return sprintf('<input type="checkbox" name="incomplete_order_lists[]" value="%s" />', $item->id);
    }

    /**

     * Prepare the table with different parameters, pagination, columns and table elements

     */
    function prepare_items() {

        global $wpdb, $_wp_column_headers;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->process_bulk_action();

        $user = get_current_user_id();

        $screen = get_current_screen();

        $screen_option = $screen->get_option('per_page', 5);

        $per_page = get_user_meta($user, 'links_per_page', true);

        if (empty($per_page) || $per_page < 1) {

            // get the default value if none is set

            $per_page = $screen->get_option('links_per_page', 'default');
        }

        //$per_page = 5;		  

        $current_page = $this->get_pagenum();



        /* -- Preparing your query -- */


        $query = "SELECT * FROM " . $wpdb->prefix . "incomplete_orders";



        /* -- Ordering parameters -- */

        //Parameters that are going to be used to order the result

        $orderby = empty($_GET["orderby"]) ? 'id' : $_GET["orderby"];

        $order = empty($_GET["order"]) ? ' DESC ' : $_GET["order"];

        /** filter the result as per the search condition  * */
        if (isset($_POST['s']) && !empty($_POST['s'])) {
            $query .= " WHERE email LIKE '%" . $_POST['s'] . "%'";
        }

        if (!empty($orderby) AND ! empty($order)) {
            $query.=' ORDER BY ' . $orderby . ' ' . $order;
        }

        echo "<p>$query</p>";
        // exit;
        //echo 'query:'.$query;

        /* -- Pagination parameters -- */

        //Number of elements in your table?

        $totalitems = $wpdb->query($query); //return the total number of affected rows
        //How many to display per page?

        $perpage = $per_page;

        $paged = !empty($_GET["paged"]) ? $_GET["paged"] : '';

        //Page Number

        if (empty($paged) || !is_numeric($paged) || $paged <= 0) {
            $paged = 1;
        }

        //How many pages do we have in total?
        //$totalpages = ceil($totalitems/$perpage);
        //adjust the query to take pagination into account

        if (!empty($paged) && !empty($perpage)) {
            $offset = ($paged - 1) * $perpage;
            $query.=' LIMIT ' . (int) $offset . ',' . (int) $perpage;
        }

        /* -- Register the pagination -- */

        $this->set_pagination_args(array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ));

        //The pagination links are automatically built according to those parameters
        /* -- Fetch the items -- */
        //$query = $wpdb->prepare($query);

        $this->items = $wpdb->get_results($query);
    }

    // for handling bulk actions 
}

// function for adding menu in in wordpress admin
function incomplete_order_page_menu() {

    add_menu_page('Incomplete Order', 'Incomplete Order', 'manage_options', 'incomplete_order_bkap_top_menu', 'incomplete_order_option_page');
}

// function for displaying data of custom table in wordpress admin

function incomplete_order_option_page() {

    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    //Prepare Table of elements
    $wp_list_incomplete_order = new Incomplete_Order_List_Table();
    $wp_list_incomplete_order->prepare_items();
    echo '<div class="wrap" style ="overflow:auto;">';
    echo '<h2>Incomplete Order Detail From Checkout Page</h2>';

    //Table of elements
    ?>
    <form method="post">
        <input type="hidden" name="page" value="ttest_list_table">
        <?php
        $wp_list_incomplete_order->search_box('search', 'search_id');
        $wp_list_incomplete_order->display();
        echo '</form>';
        echo "<div style='float:right;'>By Green Honchos Solutions PVT. LTD.</div> ";
        echo '</div>';
    }

    function gh_wc_price($html, $price, $arg = array()) {
        return gh_get_local_currency_symbol() . $price;
    }

    function gh_price_filter($price) {
        $price = gh_get_currency_updated_price($price);
        return $price;
    }

    /*     * ******* Custom Taxonomy University****************** */
    add_action('init', 'custom_taxonomy_university');

// Register Custom Taxonomy
    function custom_taxonomy_university() {

        $labels = array(
            'name' => 'Universities',
            'singular_name' => 'University',
            'menu_name' => 'University',
            'all_items' => 'All Universities',
            'parent_item' => 'Parent University',
            'parent_item_colon' => 'Parent University:',
            'new_item_name' => 'New University Name',
            'add_new_item' => 'Add New University',
            'edit_item' => 'Edit University',
            'update_item' => 'Update University',
            'separate_items_with_commas' => 'Separate University with commas',
            'search_items' => 'Search Universities',
            'add_or_remove_items' => 'Add or remove Universities',
            'choose_from_most_used' => 'Choose from the most used Universities',
        );
        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
        );
        register_taxonomy('university', 'product', $args);
        register_taxonomy_for_object_type('university', 'product');
    }

    add_action('admin_init', 'university_taxonomy_metadata_init');

    function university_taxonomy_metadata_init() {
        // Require the "Taxonomy Metadata" plugin
        if (!function_exists('update_term_meta') || !function_exists('get_term_meta'))
            return false;

        // Add fields to "add" and "edit" term pages
        add_action("university_add_form_fields", 'university_taxonomy_metadata_add', 10, 1);
        add_action("university_edit_form_fields", 'university_taxonomy_metadata_edit', 10, 1);
        // Process and save the data
        add_action("created_university", 'save_university_taxonomy_metadata', 10, 1);
        add_action("edited_university", 'save_university_taxonomy_metadata', 10, 1);
    }

    function university_taxonomy_metadata_add($tag) {
        // Only allow users with capability to publish content
        if (!current_user_can('publish_posts')) {
            return false;
        }
        ?>

        <div class="form-field">
            <label for="university_meta_type"><?php _e('University Type'); ?></label>
            <input name="university_meta_type" id="university_meta_type" type="text" value="" size="40" />
        </div>
        <div class="form-field">
            <label for="university_short_name"><?php _e('University Short-Name'); ?></label>
            <input name="university__short_name" id="university_short_name" type="text" value="" size="40" />
        </div>
        <?php
    }

    function university_taxonomy_metadata_edit($tag) {
        // Only allow users with capability to publish content
        if (!current_user_can('publish_posts')) {
            return false;
        }
        ?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="university_meta_type"><?php _e('University Type'); ?></label>
            </th>
            <td>
                <input name="university_meta_type" id="university_meta_type" type="text" value="<?php echo get_term_meta($tag->term_id, 'university_type', true); ?>" size="40" />
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="university_short_name"><?php _e('University Short-Name'); ?></label>
            </th>
            <td>
                <input name="university_short_name" id="university_short_name" type="text" value="<?php echo get_term_meta($tag->term_id, 'university_short_name', true); ?>" size="40" />
            </td>
        </tr>
        <?php
    }

    function save_university_taxonomy_metadata($term_id) {
        if (isset($_POST['university_meta_type']))
            update_term_meta($term_id, 'university_type', esc_attr($_POST['university_meta_type']));
        if (isset($_POST['university_short_name']))
            update_term_meta($term_id, 'university_short_name', esc_attr($_POST['university_short_name']));
    }

// function is defined to set default view as list view and it is called on the archive-product.php 
    function gh_catalog_default_view() {
        if (is_post_type_archive('product')) {
            if ($_GET['view'] == 'grid') {
                return;
            }
            if ((!isset($_GET)) OR ( $_GET['view'] == '')) {
                $new_query_string = array('view' => 'list');
                $url = add_query_arg($new_query_string, $_SERVER['REQUEST_URI']);
                wp_redirect($url);
            }
        }
    }

    add_filter('woocommerce_checkout_fields', 'gh_reorder_woo_fields');

    function gh_reorder_woo_fields($fields) {
        $fields2['billing']['billing_first_name'] = $fields['billing']['billing_first_name'];
        $fields2['billing']['billing_last_name'] = $fields['billing']['billing_last_name'];
        $fields2['billing']['billing_email'] = $fields['billing']['billing_email'];
        $fields2['billing']['billing_phone'] = $fields['billing']['billing_phone'];
        $fields2['billing']['billing_country'] = $fields['billing']['billing_country'];
        $fields2['billing']['billing_state'] = $fields['billing']['billing_state'];
        $fields2['billing']['billing_city'] = $fields['billing']['billing_city'];
        $fields2['billing']['billing_address_1'] = $fields['billing']['billing_address_1'];
        $fields2['billing']['billing_postcode'] = $fields['billing']['billing_postcode'];

        $fields['billing'] = $fields2['billing'];

        return $fields;
    }

    add_action('pre_get_posts', 'gh_add_custom_product_taxonomies');

    function gh_add_custom_product_taxonomies($query) {

        if ($query->is_post_type_archive('product') && !is_admin()) {

            if (isset($_GET['university'])) {
                $univ_array = explode(",", $_GET['university']);
            }
            if (isset($_GET['provider'])) {
                $prov_array = explode(",", $_GET['provider']);
            }
            if (!isset($univ_array) AND ! isset($prov_array)) {
                return;
            }
            $taxonomy_query = array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'university',
                    'field' => 'slug',
                    // 'terms'    => array( 'karnatka-state-open-university', 'uttrakhand-open-university' ),
                    'terms' => $univ_array,
                ),
                array(
                    'taxonomy' => 'shop_vendor',
                    'field' => 'slug',
                    'terms' => $prov_array,
                ),
            );

            $query->set('tax_query', $taxonomy_query);
            print_r($query);
        }
    }

    add_action('woocommerce_review_order_before_submit', 'my_custom_checkout_field');

    function my_custom_checkout_field($checkout) {
        echo '<div id="my-new-field">';
        woocommerce_form_field('my_checkbox1', array(
            'type' => 'checkbox',
            'class' => array('input-checkbox'),
            'label' => __('I have read and agreed the disclaimer.'),
            'required' => true,
            'value' => true,
            'default' => 1
        ));
        woocommerce_form_field('my_checkbox2', array(
            'type' => 'checkbox',
            'class' => array('input-checkbox'),
            'label' => __('fhffdksbbvdfbvf fdfvbhdkbkj ckbnkbvkbvfs lsnnvs.'),
            'required' => true,
            'value' => true,
            'default' => 1
        ));
        echo '</div>';
    }

    add_action('woocommerce_checkout_process', 'gh_my_custom_checkout_field_process');

    function gh_my_custom_checkout_field_process() {
        // Check if set, if its not set add an error.
        if (!$_POST['my_checkbox1']) {
            wc_add_notice(__('You must accept the Disclaimer.'), 'error');
        }
        if (!$_POST['my_checkbox2']) {
            wc_add_notice(__('You must accept this field .'), 'error');
        }
    }

    add_action('woocommerce_order_status_pending', 'gh_create_account_customer');
    add_action('woocommerce_order_status_failed', 'gh_create_account_customer');
    add_action('woocommerce_order_status_on-hold', 'gh_create_account_customer');
    add_action('woocommerce_order_status_processing', 'gh_create_account_customer');
    add_action('woocommerce_order_status_completed', 'gh_create_account_customer');
    add_action('woocommerce_order_status_refunded', 'gh_create_account_customer');
    add_action('woocommerce_order_status_cancelled', 'gh_create_account_customer');

    function gh_create_account_customer($order_id) {

        $billing_first_name = get_post_meta($order_id, '_billing_first_name', true);
        $billing_last_name = get_post_meta($order_id, '_billing_last_name', true);
        $user_email = get_post_meta($order_id, '_billing_email', true);
        $user_pass = get_post_meta($order_id, '_billing_phone', true);
        $display_name = $billing_first_name . " " . $billing_last_name;
        $userdata = array(
            'user_login' => $user_email,
            'user_pass' => $password,
            'user_nicename' => $billing_first_name,
            'user_email' => $user_email,
            'display_name' => $display_name,
            'first_name' => $billing_first_name,
            'last_name' => $billing_last_name,
            'role' => 'customer',
        );
        if (!email_exists($user_email) AND ! username_exists($user_email)) {

            //print_r($userdata);
            //exit;
            wp_insert_user($userdata);
            // wp_update_user( array( 'first_name ' => $billing_first_name,'last_name'=> $billing_last_name,'email'=>$user_name, 'role' => 'customer' ) );
        } else {
            wp_update_user(array('first_name ' => $billing_first_name, 'last_name' => $billing_last_name, 'role' => 'customer'));
        }

        //$billing_last_name = get_post_meta($order_id,'_billing_last_name',true);
    }

    /*
     * adding custom widget in admin for filtering courses by vendor store and university name  
     * 
     */

    class Vendor_Widget extends WP_Widget {

        /**
         * Sets up the widgets name etc
         */
        public function __construct() {
            // widget actual processes
            parent::__construct(
                    'vendor_widget', // Base ID
                    __('Vendor Filter', 'text_domain'), // Name
                    array('description' => __('filter courses by Vendor Store', 'text_domain'),) // Args
            );
        }

        /**
         * Outputs the content of the widget
         *
         * @param array $args
         * @param array $instance
         */
        public function widget($args, $instance) {
            // outputs the content of the widget
            echo $args['before_widget'];
            if (!empty($instance['title'])) {
                echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
            }
            $exammodes = get_terms("pa_exam-mode");
            $examcenters = get_terms("pa_exam-center");
            $studymodes = get_terms("pa_study-mode");
            $vendors = get_categories($prod_cat_args = array(
                'taxonomy' => 'shop_vendor', //woocommerce
                'orderby' => 'name',
                'empty' => 0)
            );

            $universities = get_categories($prod_cat_args = array(
                'taxonomy' => 'university', //woocommerce
                'orderby' => 'name',
                'empty' => 0)
            );
            //$vendors = get_term('university', 'name');
            if (isset($instance['exam_mode'])) {
                echo '<select class="filter_exam_mode"><option value ="">Any Exam Mode</option>';
                foreach ($exammodes as $exammode) {
                    echo "<option value=" . $exammode->term_id . ">" . $exammode->name . "</option>";
                }
                echo '</select>';
            }
            if (isset($instance['exam_center'])) {
                echo '<select class="filter_exam_center js-states form-control" multiple="multiple">';
                foreach ($examcenters as $examcenter) {
                    echo "<option value=" . $examcenter->term_id . ">" . $examcenter->name . "</option>";
                }
                echo '</select>';
            }
            if (isset($instance['study_mode'])) {
                echo '<select class="filter_study_mode"><option value ="">Any Study Mode</option>';
                foreach ($studymodes as $studymode) {
                    echo "<option value=" . $studymode->term_id . ">" . $studymode->name . "</option>";
                }
                echo '</select>';
            }
            if (isset($instance['vendor_chk'])) {

                echo '<ul class="vendor">';
                foreach ($vendors as $vendor) {

                    echo '<li><input type="checkbox" name="vendor" value="' . $vendor->slug . '" /><label for="vendor_name">' . $vendor->name . '</label></li>';
                }
                echo'</ul>';
            }
            if (isset($instance['univ_name'])) {


                echo '<ul class="university">';
                foreach ($universities as $university) {

                    echo '<li><input type="checkbox" name="university[]" value="' . $university->slug . '" /><label for="univ_name">' . $university->name . '</label></li>';
                }
                echo'</ul>';
            }
            echo $args['after_title'];
        }

        /**
         * Outputs the options form on admin
         *
         * @param array $instance The widget options
         */
        public function form($instance) {
            // outputs the options form on admin
            $title = !empty($instance['title']) ? $instance['title'] : __('Title', 'text_domain');
            $exam_center = $instance['exam_center'];
            $exam_mode = $instance['exam_mode'];
            $study_mode = $instance['study_mode'];
            $vendor_chk = $instance['vendor_chk'];
            $univ_name = $instance['univ_name'];
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            <p>
                <input class="widefat" id="<?php echo $this->get_field_id('exam_center'); ?>" name="<?php echo $this->get_field_name('exam_center'); ?>" type="checkbox"  <?php checked($instance['exam_center'], 'on'); ?>/>
                <label for="<?php echo $this->get_field_id('exam_center'); ?>"><?php _e('Exam Center'); ?></label>
            </p>
            <p>
                <input class="widefat" id="<?php echo $this->get_field_id('exam_mode'); ?>" name="<?php echo $this->get_field_name('exam_mode'); ?>" type="checkbox"  <?php checked($instance['exam_mode'], 'on'); ?>/>
                <label for="<?php echo $this->get_field_id('exam_mode'); ?>"><?php _e('Exam Mode'); ?></label>
            </p>
            <p>
                <input class="widefat" id="<?php echo $this->get_field_id('study_mode'); ?>" name="<?php echo $this->get_field_name('study_mode'); ?>" type="checkbox"  <?php checked($instance['study_mode'], 'on'); ?>/>
                <label for="<?php echo $this->get_field_id('study_mode'); ?>"><?php _e('Study Mode'); ?></label>
            </p>
            <p>
                <input class="widefat" id="<?php echo $this->get_field_id('vendor_chk'); ?>" name="<?php echo $this->get_field_name('vendor_chk'); ?>" type="checkbox"  <?php checked($instance['vendor_chk'], 'on'); ?>/>
                <label for="<?php echo $this->get_field_id('vendor_chk'); ?>"><?php _e('Show Vendor'); ?></label> 
            </p>
            <p>
                <input class="widefat" id="<?php echo $this->get_field_id('univ_name'); ?>" name="<?php echo $this->get_field_name('univ_name'); ?>" type="checkbox"  <?php checked($instance['univ_name'], 'on'); ?>/>
                <label for="<?php echo $this->get_field_id('univ_name'); ?>"><?php _e('University Name'); ?></label>
            </p>
            <?php
        }

        /**
         * Processing widget options on save
         *
         * @param array $new_instance The new options
         * @param array $old_instance The previous options
         */
        public function update($new_instance, $old_instance) {
            // processes widget options to be saved
            $instance = array();
            $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
            $instance['exam_center'] = $new_instance['exam_center'];
            $instance['exam_mode'] = $new_instance['exam_mode'];
            $instance['study_mode'] = $new_instance['study_mode'];
            $instance['vendor_chk'] = $new_instance['vendor_chk'];
            $instance['univ_name'] = $new_instance['univ_name'];
            return $instance;
        }

    }

    add_action('widgets_init', 'vendor_widget');

    function vendor_widget() {
        register_widget('Vendor_Widget');
    }

    //function vendor_univ_search() {
    function gh_sidebar_filter_search() {

        global $wpdb;

        $min = filter_var($_GET['Min_price'], FILTER_SANITIZE_NUMBER_INT);
        $max = filter_var($_GET['Max_price'], FILTER_SANITIZE_NUMBER_INT);
        $min_price = isset($min) ? $min : '';
        $max_price = isset($max) ? $max : '';
        $provider = isset($_GET['provider']) ? explode(",", $_GET['provider']) : '';
        $university = isset($_GET['university']) ? explode(",", $_GET['university']) : '';
        $study_mode = isset($_GET['studymode']) ? $_GET['studymode'] : '';
        //$exam_mode = isset($_GET['Exammode']) ? $_GET['Exammode'] : '';
        $exam_center = isset($_GET['Center']) ? explode(",", $_GET['Center']) : '';

        /*        $tax_query = array(
          'relation' => 'OR',
          array(
          'taxonomy' => 'shop_vendor',
          'field' => 'slug',
          'terms' => $provider,
          ),
          array(
          'taxonomy' => 'university',
          'field' => 'slug',
          'terms' => $university,
          ),
          array(
          'taxonomy' => 'pa_exam-center',
          'field' => 'term_id',
          'terms' => $exam_center,
          ),

          array(
          'taxonomy' => 'pa_study-mode',
          'field' => 'term_id',
          'terms' => $study_mode,
          ),
          );
         */

        $tax_query = array();

        if ($exam_center != '' AND $exam_center[0] != '') {
            $t_ar_exam_center = array(
                'taxonomy' => 'pa_exam-center',
                'field' => 'term_id',
                'terms' => $exam_center,
            );
            array_push($tax_query, $t_ar_exam_center);
        }
        if ($study_mode != '' AND $study_mode[0] != '') {
            $t_ar_study_mode = array(
                'taxonomy' => 'pa_study-mode',
                'field' => 'term_id',
                'terms' => $study_mode,
            );
            array_push($tax_query, $t_ar_study_mode);
        }

        if ($university != '' AND $university[0] != '') {
            $t_ar_university = array(
                'taxonomy' => 'university',
                'field' => 'slug',
                'terms' => $university,
            );
            array_push($tax_query, $t_ar_university);
        }

        if ($provider != '' AND $provider[0] != '') {
            $t_ar_provider = array(
                'taxonomy' => 'shop_vendor',
                'field' => 'slug',
                'terms' => $provider,
            );
            array_push($tax_query, $t_ar_provider);
        }

        if (count($tax_query) > 1) {
            $tax_query['relation'] = 'AND';
        }



        /*        $meta_query = array(
          'key' => '_price',
          // 'value' => array($min_price,$max_price),
          'value' => array(5000, 10000),
          'type'    => 'numeric',
          'compare' => 'BETWEEN',
          ); */
        $meta_query = array(
            'relation' => 'AND',
            array(
                'key' => '_max_variation_regular_price',
                'value' => $min_price,
                'compare' => '>'
            ),
            array(
                'key' => '_max_variation_regular_price',
                'value' => $max_price,
                'compare' => '<'
            )
        );


//        echo "<pre>";
//        print_r($meta_query);
//        echo "</pre>";
//        // exit;
//        echo "<pre>";
//        var_dump($_GET);
//        echo "</pre>";

        if (($_GET['provider'] == '') AND ( $_GET['university'] == '') AND ( $_GET['studymode'] == '') AND ( $_GET['Center'] == '')) {
            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'meta_query' => $meta_query
            );
        } elseif ($tax_query) {
            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'meta_query' => $meta_query,
                'tax_query' => $tax_query
            );
        }

        /*        if(empty($provider[0]) AND empty($university[0])  AND empty($exam_center[0])  AND !isset($study_mode) AND !isset($min_price) AND !isset($max_price)){
          echo "<p>".__LINE__."</p>";
          $args = array(
          'post_type' => 'product',
          'post_status' => 'publish',
          );
          }
          else{
          echo "<p>".__LINE__."</p>";
          $args = array(
          'post_type' => 'product',
          'post_status' => 'publish',
          'meta_query' => $meta_query,
          'tax_query' => $tax_query
          );
          } */
//        if(isset($min_price) AND isset($max_price)){
//
//            $args = array(
//                'post_type' =>  'product_variation',
//                'post_status' => 'publish',
//                'meta_query' => $meta_query
//            );
//
//        }
        echo "<pre>";
        print_r($args);
        echo "</pre>";
       // exit;
        $the_query = new WP_Query($args);

        $result = $the_query->posts;
        $pf = new WC_Product_Factory();

        if ($result) {
            foreach ($result as $post) {
                $id = $post->ID;
                $imgplaceholder = site_url() . '/wp-content/plugins/woocommerce/assets/images/placeholder.png';
                $img1 = wp_get_attachment_thumb_url(get_post_thumbnail_id($post->ID));
                $product = $pf->get_product($id);
                $url = $product->post->guid;
                $title = $product->post->post_title;
                $scholarship = $product->get_attribute("scholarship");
                $label_new = $product->get_attribute("new");
                $duration = $product->get_attribute("duration");

                if ($product->product_type == 'variable') {
                    $max_variation_sale_price = '';
                    if ($product->is_on_sale()) {
                        $max_variation_sale_price = gh_get_currency_updated_price($product->max_variation_sale_price);
                        // $price = gh_variation_sale_price($product->price, $product);
                    } else {
                        $price = gh_get_currency_updated_price($product->get_variation_regular_price('max'));
                        // $price = gh_variation_price( $product->price, $product );
                    }
                    // $variation_sale_price = gh_variation_sale_price($product->price, $product);
                    // $price = ($variation_sale_price != '') ? $variation_sale_price : gh_variation_price( $product->price, $product );
                } else {
                    if ($product->is_on_sale()) {
                        $price = $product->get_sale_price();
                    } else {
                        $price = $product->price;
                    }
                }


                $provider_object = wc_get_product_terms($product->id, 'shop_vendor')[0];
                $university_name = wc_get_product_terms($product->id, 'university')[0];
                echo '<li class="product-small  grid1 edu-list-product">';
                echo '<div class="inner-wrap"><a href=' . $url . '/>';
                echo '<div class="product-image hover_fade_in_back">';
                if (isset($img1)) {
                    echo '<img src=' . $img1 . ' title =' . $title . '>';
                } else {
                    echo '<img src=' . $imgplaceholder . ' title =' . $title . '>';
                }
                echo '</div>';
                echo '<div class="info text-center"><p class="name">' . $title . '</p><div class="duration_below_title-list"><span class="short-description_sapn"> <p class="edu-duration-list">' . $duration . '</p></span> <span><p class="edu-ins-name"><b>University: </b>' . $university_name->name . '</p></span><p>Provider: ' . $provider_object->name . '</p></div>';
                ?> 
                <span class="edu_new product_label"><?php
                    if ($label_new == "yes") {
                        echo "New";
                    }
                    ?></span>
                <span class="product_label"><?php
                    if ($scholarship == "yes") {
                        echo "Scholarship";
                    }
                    ?> </span><span class="learn_more">Learn More</span></div>
            <?php
            echo '<span class="price"><span class="gh_max_price_in_loop">' . ($max_variation_sale_price != '' ? '<del>' . $max_variation_sale_price . '</del>' : '') . $price . '</span></span>';
            echo '</a></div></li>';
        }
    } else {
        echo '<p>OOPS... No courses matching your search criteria could be found.</p>';
    }
    die();
}

add_action('wp_ajax_filter_search', 'gh_sidebar_filter_search');
add_action('wp_ajax_nopriv_filter_search', 'gh_sidebar_filter_search');
