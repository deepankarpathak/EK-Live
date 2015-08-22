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
//echo get_site_url();
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
        global $wp_session;

        if (is_post_type_archive('product')) {
            if ($_GET['view'] == 'grid') {
                return;
            }
            if ((!isset($_GET)) OR ( $_GET['view'] == '')) {
                $catalog_view_type = (trim($wp_session['catalog_view_type']) != '') ? trim($wp_session['catalog_view_type']) : 'list';
                $new_query_string = array('view' => $catalog_view_type);
                // $new_query_string = array('view' => 'list');
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
        $fields2['billing']['billing_address_1'] = $fields['billing']['billing_address_1'];
        $fields2['billing']['billing_country'] = $fields['billing']['billing_country'];
        $fields2['billing']['billing_state'] = $fields['billing']['billing_state'];
        $fields2['billing']['billing_city'] = $fields['billing']['billing_city'];
        $fields2['billing']['billing_postcode'] = $fields['billing']['billing_postcode'];

        $fields['billing'] = $fields2['billing'];
        // Remove Last name field
        unset($fields['billing']['billing_last_name']);
        return $fields;
    }

    add_filter('posts_search', 'gh_posts_search');

    function gh_posts_search($q) {

        // $q = preg_replace("/post_content LIKE ('%[^%]+%')/", "post_excerpt LIKE $1 ", $q);
        global $wpdb;
        $q = preg_replace("/post_content LIKE ('%[^%]+%')/", "post_excerpt LIKE $1) OR {$wpdb->posts}.id IN (SELECT object_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id IN ( SELECT term_taxonomy_id FROM {$wpdb->term_taxonomy} WHERE taxonomy IN('pa_exam-center','university','pa_specialization','product_tag' ) AND term_id IN ( Select term_id from {$wpdb->terms} WHERE name LIKE $1 UNION Select taxonomy_id from {$wpdb->taxonomymeta} where meta_value LIKE $1 ))  ", $q);
//    echo "<p>LN".__LINE__.": Hook - posts_search :</p>";
//    echo "<p>$q</p>";
//    echo "<hr />";
        return $q;
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
            'label' => __('I want to signup for the selected course(s).'),
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
            wc_add_notice(__('You must accept signup field for this course  .'), 'error');
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
        $user_city = get_post_meta($order_id, '_billing_city', true);
        $user_address_1 = get_post_meta($order_id, '_billing_address_1', true);
        $user_state = get_post_meta($order_id, '_billing_state', true);
        $user_country = get_post_meta($order_id, '_billing_country', true);
        $user_phone = get_post_meta($order_id, '_billing_phone', true);
        $user_postcode = get_post_meta($order_id, '_billing_postcode', true);
        $user_pass = get_post_meta($order_id, '_billing_phone', true);
        $display_name = $billing_first_name . " " . $billing_last_name;
        $userdata = array(
            'user_login' => $user_email,
            'user_pass' => $user_pass,
            'user_nicename' => $billing_first_name,
            'user_email' => $user_email,
            'display_name' => $display_name,
            'first_name' => $billing_first_name,
            'last_name' => $billing_last_name,
            'role' => 'customer',
        );

        if (!email_exists($user_email) AND ! username_exists($user_email)) {

            //print_r($userdata);
            //exit;akhand.akhand123@yopmail.com
            wp_insert_user($userdata);
            $user_info = get_user_by('email', $user_email);
            $id = $user_info->ID;
            update_user_meta($id, 'billing_first_name', $billing_first_name);
            update_user_meta($id, 'billing_last_name', $billing_last_name);
            update_user_meta($id, 'billing_address_1', $user_address_1);
            update_user_meta($id, 'billing_country', $user_country);
            update_user_meta($id, 'billing_state', $user_state);
            update_user_meta($id, 'billing_phone', $user_phone);
            update_user_meta($id, 'billing_postcode', $user_postcode);
            update_user_meta($id, 'billing_city', $user_city);
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
            global $wpdb;
            // outputs the content of the widget
            echo '<div class= "filter">';
            echo $args['before_widget'];
            if (!empty($instance['title'])) {
                echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
            }
            $exammodes = get_terms("pa_exam-mode");
            $examcenters = get_terms("pa_exam-center");
            $studycontents = get_terms("pa_study-content");
            $coursetypes = get_terms("pa_certification-type");
            $specializations = get_terms("pa_specialization");
            $eligibilities = get_terms("pa_eligibility");
            $domains = get_terms("pa_domain");
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

            // $min_value= $wpdb->get_var('SELECT min(meta_value) AS min_value from edkwp_postmeta WHERE meta_key ="_max_variation_regular_price"'); 
            //$max_value= $wpdb->get_var('SELECT max(meta_value) AS max_value from edkwp_postmeta WHERE meta_key ="_max_variation_regular_price"'); 

            $min_max_value_ar = array();
            $result_ar = $wpdb->get_results('SELECT meta_value from edkwp_postmeta WHERE meta_key ="_max_variation_regular_price"');
            foreach ($result_ar as $k => $row) {
                array_push($min_max_value_ar, $row->meta_value);
            }
            // echo "<pre>";
            $min_value = min($min_max_value_ar);
            $max_value = max($min_max_value_ar);
            // print_r($min_max_value_ar);
            // echo "</pre>";
            // echo "<p>Max Value: ".max($min_max_value_ar)."</p>";
            // echo "<p>Max Value: ".min($min_max_value_ar)."</p>";
            // exit;

            if (isset($instance['price_slider'])) {
                ?>
                <script type ="text/javascript">
                    $(document).ready(function () {

                        var minvalues, maxvalues;
                        var minvalue = parseInt($("#min_amount").val());
                        var maxvalue = parseInt($("#max_amount").val());
            <?php if (isset($_GET['min_value'])) { ?>
                            minvalues = parseInt('<?php echo $_GET['min_value']; ?>');
            <?php } else { ?>
                            minvalues = parseInt($("#min_amount").val());
            <?php } ?>
            <?php if (isset($_GET['max_value'])) { ?>
                            maxvalues = parseInt('<?php echo $_GET['max_value']; ?>');
            <?php } else { ?>
                            maxvalues = parseInt($("#max_amount").val());
            <?php } ?>
                        $("#slider-range").slider({
                            range: true,
                            min: minvalue,
                            max: maxvalue,
                            values: [minvalues, maxvalues],
                            slide: function (event, ui) {
                                // $( "#amount" ).val( "Rs." + ui.values[ 0 ] + " - Rs." + ui.values[ 1 ] );
                                $("#amount1").val(ui.values[0]);
                                $("#amount2").val(ui.values[1]);
                            }
                        });
                        // $( "#amount" ).val( "Rs." + $( "#slider-range" ).slider( "values", 0 ) +
                        //        " - Rs" + $( "#slider-range" ).slider( "values", 1 ) );
                        $("#amount1").val($("#slider-range").slider("values", 0));
                        $("#amount2").val($("#slider-range").slider("values", 1));

                    });
                </script>

                <p>
                    <label for='amount'>Price range:</label>
                    <!-- <input type='text' id='amount' name="amount" readonly style='border:0; color:#f6931f; font-weight:bold;'><br/> -->
                <div style="width:100%;"><div class = "amount1"><span><?php echo gh_get_local_currency_symbol(); ?></span><input type="text" id="amount1" name="min_value" readonly /></div> <span style="line-height:27px;">-</span> <div class="amount2"><span><?php echo gh_get_local_currency_symbol(); ?></span><input type="text" id="amount2" name="max_value" readonly /></div></div>

            </p>
            <div id='slider-range'></div>

            <!--                    <span class="from"><?php //echo gh_get_local_currency_symbol() . gh_get_currency_updated_price($min_value); ?></span> &mdash; <span class="to"><?php echo gh_get_local_currency_symbol() . gh_get_currency_updated_price($max_value); ?></span> -->
            <input type="hidden" value ="<?php echo gh_get_currency_updated_price($min_value); ?>" id='min_amount'/> 
            <input type="hidden" value ="<?php echo gh_get_currency_updated_price($max_value); ?>" id="max_amount"/> 
            <?php
        }
        if (isset($instance['cousrse_type'])) {
            echo '<select class="filter_course_type js-course-type form-control" multiple="multiple" name="course_type[]" >';
            foreach ($coursetypes as $coursetype) {
                $selectcoursetype = isset($_GET['course_type']) ? (in_array($coursetype->term_id, $_GET['course_type']) ? ' selected' : null) : null;
                ?>
                <option value= "<?php echo $coursetype->term_id; ?>" <?php echo $selectcoursetype; ?>   > <?php echo $coursetype->name; ?> </option>
            <?php
            }
            echo '</select>';
        }
        if (isset($instance['specialization'])) {
            echo '<select class="filter_specialization js-specialization form-control" multiple="multiple" name="specialization[]">';
            foreach ($specializations as $specialization) {
                $selectspecialization = isset($_GET['specialization']) ? (in_array($specialization->term_id, $_GET['specialization']) ? ' selected' : null) : null;
                ?>
                <option value=" <?php echo $specialization->term_id; ?>" <?php echo $selectspecialization; ?> ><?php echo $specialization->name; ?></option>
            <?php
            }
            echo '</select>';
        }
        if (isset($instance['eligibility'])) {
            echo '<select class="filter_eligibility js-eligibility form-control" multiple="multiple" name="eligibility[]">';
            foreach ($eligibilities as $eligibility) {
                $selecteligibility = isset($_GET['eligibility']) ? (in_array($examcenter->term_id, $_GET['eligibility']) ? ' selected' : null) : null;
                ?>
                <option value="<?php echo $eligibility->term_id; ?>" <?php echo $selecteligibility; ?> ><?php echo $eligibility->name; ?> </option>
                <?php
            }
            echo '</select>';
        }
        if (isset($instance['domain'])) {
            echo '<select class="filter_domain js-domain form-control" multiple="multiple" name="domain[]">';
            foreach ($domains as $domain) {
                $selectdomain = isset($_GET['domain']) ? (in_array($domain->term_id, $_GET['domain']) ? ' selected' : null) : null;
                ?>
                <option value="<?php echo $domain->term_id; ?>" <?php echo $selectdomain; ?> ><?php echo $domain->name; ?> </option>
            <?php
            }
            echo '</select>';
        }
        if (isset($instance['exam_center'])) {
            echo '<select class="filter_exam_center js-center form-control" multiple="multiple" name="exam_center[]">';
            foreach ($examcenters as $examcenter) {
                $selectexamcenter = isset($_GET['exam_center']) ? (in_array($examcenter->term_id, $_GET['exam_center']) ? ' selected' : null) : null;
                ?>
                <option value="<?php echo $examcenter->term_id; ?>" <?php echo $selectexamcenter; ?> ><?php echo $examcenter->name; ?> </option>
            <?php
            }
            echo '</select>';
        }
        if (isset($instance['study_content'])) {
            echo '<select class="filter_study_content js-study-content form-control" multiple="multiple" name="study_content[]">';
            foreach ($studycontents as $studycontent) {
                $selectstudycontent = isset($_GET['study_content']) ? (in_array($studycontent->term_id, $_GET['study_content']) ? ' selected' : null) : null;
                ?>
                <option value="<?php echo $studycontent->term_id; ?>" <?php echo $selectstudycontent; ?>><?php echo $studycontent->name; ?></option>
            <?php
            }
            echo '</select>';
        }
        if (isset($instance['vendor_chk'])) {
            echo '<select class="filter_vendor js-vendor form-control" multiple="multiple"name="provider[]">';
            foreach ($vendors as $vendor) {
                $selectprovider = isset($_GET['provider']) ? (in_array($vendor->slug, $_GET['provider']) ? ' selected' : null) : null;
                ?>
                <option value="<?php echo $vendor->slug; ?>" <?php echo $selectprovider; ?> ><?php echo $vendor->name ?></option>
            <?php
            }
            echo'</select>';
        }
        if (isset($instance['univ_name'])) {
            echo '<select class="filter_university js-university form-control" multiple="multiple"name="institutions[]">';
            foreach ($universities as $university) {
                $selectinstitutions = isset($_GET['institutions']) ? (in_array($university->slug, $_GET['institutions']) ? ' selected' : null) : null;
                ?>
                <option value="<?php echo $university->slug; ?>" <?php echo $selectinstitutions; ?> ><?php echo $university->name; ?></option>
            <?php
            }
            echo'</select>';
        }
        if (isset($instance['exam_mode'])) {
            echo '<ul class="exam-mode gh_widget">';
            foreach ($exammodes as $exammode) {
                $exam_mode = isset($_GET['exam_mode']) ? (in_array($exammode->term_id, $_GET['exam_mode']) ? "checked='checked'" : '') : '';
                echo '<li><input type="checkbox" name="exam_mode[]" value="' . $exammode->term_id . '" ' . $exam_mode . '  /><label for="exam_mode[]">' . $exammode->name . '</label></li>';
            }
            echo '</ul>';
        }

        echo $args['after_widget'];
        echo '</div>';
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
        $study_content = $instance['study_content'];
        $vendor_chk = $instance['vendor_chk'];
        $univ_name = $instance['univ_name'];
        $course_type = $instance['cousrse_type'];
        $specialization = $instance['specialization'];
        $eligibility = $instance['eligibility'];
        $domain = $instance['domain'];
        $price_slider = $instance['price_slider'];
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
            <input class="widefat" id="<?php echo $this->get_field_id('study_content'); ?>" name="<?php echo $this->get_field_name('study_content'); ?>" type="checkbox"  <?php checked($instance['study_content'], 'on'); ?>/>
            <label for="<?php echo $this->get_field_id('study_content'); ?>"><?php _e('Study Content'); ?></label>
        </p>
        <p>
            <input class="widefat" id="<?php echo $this->get_field_id('vendor_chk'); ?>" name="<?php echo $this->get_field_name('vendor_chk'); ?>" type="checkbox"  <?php checked($instance['vendor_chk'], 'on'); ?>/>
            <label for="<?php echo $this->get_field_id('vendor_chk'); ?>"><?php _e('Show Vendor'); ?></label> 
        </p>
        <p>
            <input class="widefat" id="<?php echo $this->get_field_id('univ_name'); ?>" name="<?php echo $this->get_field_name('univ_name'); ?>" type="checkbox"  <?php checked($instance['univ_name'], 'on'); ?>/>
            <label for="<?php echo $this->get_field_id('univ_name'); ?>"><?php _e('University Name'); ?></label>
        </p>
        <p>
            <input class="widefat" id="<?php echo $this->get_field_id('cousrse_type'); ?>" name="<?php echo $this->get_field_name('cousrse_type'); ?>" type="checkbox"  <?php checked($instance['cousrse_type'], 'on'); ?>/>
            <label for="<?php echo $this->get_field_id('cousrse_type'); ?>"><?php _e('Course Type'); ?></label>
        </p>
        <p>
            <input class="widefat" id="<?php echo $this->get_field_id('specialization'); ?>" name="<?php echo $this->get_field_name('specialization'); ?>" type="checkbox"  <?php checked($instance['specialization'], 'on'); ?>/>
            <label for="<?php echo $this->get_field_id('specialization'); ?>"><?php _e('Specialization'); ?></label>
        </p>
        <p>
            <input class="widefat" id="<?php echo $this->get_field_id('eligibility'); ?>" name="<?php echo $this->get_field_name('eligibility'); ?>" type="checkbox"  <?php checked($instance['eligibility'], 'on'); ?>/>
            <label for="<?php echo $this->get_field_id('eligibility'); ?>"><?php _e('Eligibility'); ?></label>
        </p>
        <p>
            <input class="widefat" id="<?php echo $this->get_field_id('domain'); ?>" name="<?php echo $this->get_field_name('domain'); ?>" type="checkbox"  <?php checked($instance['domain'], 'on'); ?>/>
            <label for="<?php echo $this->get_field_id('domain'); ?>"><?php _e('Domain'); ?></label>
        </p>
        <p>
            <input class="widefat" id="<?php echo $this->get_field_id('price_slider'); ?>" name="<?php echo $this->get_field_name('price_slider'); ?>" type="checkbox"  <?php checked($instance['price_slider'], 'on'); ?>/>
            <label for="<?php echo $this->get_field_id('price_slider'); ?>"><?php _e('Price Slider'); ?></label>
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
        $instance['study_content'] = $new_instance['study_content'];
        $instance['vendor_chk'] = $new_instance['vendor_chk'];
        $instance['univ_name'] = $new_instance['univ_name'];
        $instance['cousrse_type'] = $new_instance['cousrse_type'];
        $instance['specialization'] = $new_instance['specialization'];
        $instance['eligibility'] = $new_instance['eligibility'];
        $instance['domain'] = $new_instance['domain'];
        $instance['price_slider'] = $new_instance['price_slider'];
        return $instance;
    }

}

add_action('widgets_init', 'vendor_widget');

function vendor_widget() {
    register_widget('Vendor_Widget');
}

function gh_courses_pagination_link($no_of_pagination_link, $paged) {

    $current_page_no = $paged;
    $n1 = $current_page_no - 5;
    $n2 = $current_page_no + 4;

    if ($no_of_pagination_link <= 15) {
        ?>
        <nav class="woocommerce-pagination">
            <div class="large-12 columns">
                <div class="pagination-centered">
                    <ul class="page-numbers">
        <?php
        for ($i = 1; $i <= $no_of_pagination_link; $i++) {
//                echo "<li><a class='page-numbers' data-value= ".$i.">".$i."</a></li>";    
            ?>
                            <li><a class='page-numbers <?php if ($i == $current_page_no) {
                echo 'selected';
            } ?>' data-value= "<?php echo $i; ?>"><?php echo $i; ?></a></li>                
            <?php
        }
        ?>
                    </ul>
                </div>
            </div>
        </nav>
                        <?php
                    } else {
                        if ($current_page_no == 1 OR $current_page_no < 13) {
                            ?>
            <nav class="woocommerce-pagination">
                <div class="large-12 columns">
                    <div class="pagination-centered">
                        <ul class="page-numbers">
            <?php
            for ($i = 1; $i <= 13; $i++) {
                ?>
                                <li><a class='page-numbers <?php if ($i == $current_page_no) {
                    echo 'selected';
                } ?>' data-value= "<?php echo $i; ?>"><?php echo $i; ?></a></li>                
                <?php
            }
            echo "<li><a class='page-numbers'>...</a></li>";
            ?>
                            <li><a class='page-numbers <?php if ($no_of_pagination_link == $current_page_no) {
                echo 'selected';
            } ?>' data-value= "<?php echo $no_of_pagination_link; ?>"><?php echo $no_of_pagination_link; ?></a></li>


                        </ul>
                    </div>
                </div>
            </nav>
                            <?php
                        } elseif ($current_page_no <= $no_of_pagination_link - 13) {
                            ?>
            <nav class="woocommerce-pagination">
                <div class="large-12 columns">
                    <div class="pagination-centered">
                        <ul class="page-numbers">


                            <li><a class='page-numbers <?php if ($current_page_no == 1) echo "selected"; ?>' data-value= '1'>1</a></li>
                            <li><a class='page-numbers'>...</a></li>
            <?php
            for ($i = $n1; $i <= $n2; $i++) {
                ?>
                                <li><a class='page-numbers <?php if ($i == $current_page_no) {
                    echo 'selected';
                } ?>' data-value= "<?php echo $i; ?>"><?php echo $i; ?></a></li>                
                <?php
            }
            echo "<li><a class='page-numbers'>...</a></li>";
            ?>
                            <li><a class='page-numbers <?php if ($no_of_pagination_link == $current_page_no) {
                echo 'selected';
            } ?>' data-value= "<?php echo $no_of_pagination_link; ?>"><?php echo $no_of_pagination_link; ?></a></li>

                        </ul>
                    </div>
                </div>
            </nav>
            <?php
        } elseif ($current_page_no > $no_of_pagination_link - 13) {
            ?>
            <nav class="woocommerce-pagination">
                <div class="large-12 columns">
                    <div class="pagination-centered">
                        <ul class="page-numbers">
                            <li><a class='page-numbers <?php if ($current_page_no == 1) echo "selected"; ?>' data-value= '1'>1</a></li>
                            <li><a class='page-numbers'>...</a></li>
            <?php
            for ($i = $no_of_pagination_link - 12; $i <= $no_of_pagination_link; $i++) {
                ?>
                                <li><a class='page-numbers <?php if ($i == $current_page_no) {
                    echo 'selected';
                } ?>' data-value= "<?php echo $i; ?>"><?php echo $i; ?></a></li>                
                <?php
            }
            ?>                            

                        </ul>
                    </div>
                </div>
            </nav>
            <?php
        }
    }
}

//function vendor_univ_search() {
function gh_sidebar_filter_search() {

    $request_for_debug = print_r($_REQUEST, true);
    $client_ip = $_GET['client_ip'];

//	global $wp_session;	
    $conversion_rate = $_GET['Conversion_rate'];
    if (trim($conversion_rate) == '' OR ! is_int($conversion_rate)) {
        $conversion_rate = 1;
    }

    //echo "<p>LN".__LINE__.$wp_session['ip'].' | '.$wp_session['country'].' | '.$wp_session['currency'].' | '.$wp_session['conversion_rate']."</p>"; 

    $client_ip = $_GET['client_ip'];

    //echo "<pre>";
    //print_r($_REQUEST);
    //echo "</pre>";
    // echo '<p>LN '.__LINE__.'$client_ip = '.$client_ip.' <br/> $_GET[\'client_ip\'] = '.$_GET['client_ip'].'</p>';
    gh_set_ip_currency($client_ip);

    //echo "<p>LN".__LINE__.$wp_session['ip'].' | '.$wp_session['country'].' | '.$wp_session['currency'].' | '.$wp_session['conversion_rate']."</p>"; 

    $min = filter_var($_GET['minamount'], FILTER_SANITIZE_NUMBER_INT);
    $max = filter_var($_GET['maxamount'], FILTER_SANITIZE_NUMBER_INT);
    $min_price = isset($min) ? $min : '';
    $max_price = isset($max) ? $max : '';
    $provider = isset($_GET['provider']) ? explode(",", $_GET['provider']) : '';
    $university = isset($_GET['university']) ? explode(",", $_GET['university']) : '';
    $study_content = isset($_GET['studycontent']) ? $_GET['studycontent'] : '';
    $exam_mode = isset($_GET['exammode']) ? $_GET['exammode'] : '';
    $exam_center = isset($_GET['Center']) ? explode(",", $_GET['Center']) : '';
    $type_course = isset($_GET['typecourse']) ? explode(",", $_GET['typecourse']) : '';
    $specialization = isset($_GET['Specialization']) ? explode(",", $_GET['Specialization']) : '';
    $eligibility = isset($_GET['Eligibility']) ? explode(",", $_GET['Eligibility']) : '';
    $domain = isset($_GET['Domain']) ? explode(",", $_GET['Domain']) : '';
    $search = isset($_GET['Search']) ? $_GET['Search'] : '';
    $view = isset($_GET['View']) ? $_GET['View'] : '';
    $paged = isset($_GET['page']) ? $_GET['page'] : '';
    $result = isset($_GET['result']) ? $_GET['result'] : '';
    $order = isset($_GET['orderby']) ? $_GET['orderby'] : '';
    $conversion_rate = isset($_GET['Conversion_rate']) ? $_GET['Conversion_rate'] : 1;
    //set_transient( 'gh_order_by', $order, 12 * HOUR_IN_SECONDS );

    $get_for_debug = print_r($_GET, true);

    $ba_array = explode(" ", $search);
    array_walk($ba_array, function(&$v, $k) {
        $v = strtolower($v);
    });
    $ba_found = in_array("ba", $ba_array);

    if ($order == "price") {
        $order = "_max_variation_regular_price";
        $orderby = 'meta_value_num';
        $gh_order = "ASC";
    } elseif ($order == "price-desc") {
        $order = "_max_variation_regular_price";
        $orderby = 'meta_value_num';
        $gh_order = "DESC";
    } elseif ($order == "featured") {
        $order = "_featured";
        $orderby = 'meta_value';
        $gh_order = "DESC";
    } else {
        $order = "_max_variation_regular_price";
        $orderby = 'meta_value_num';
        $gh_order = "DESC";
    }
    //$minamount = isset($_GET['minamount']) ? explode(",", $_GET['minamount']) : '';
//        /$minamount = $amount[0];

    /*        if($wp_session['conversion_rate'] != 1){
      $max_price = $max_price/$wp_session['conversion_rate'];
      $min_price = $min_price/$wp_session['conversion_rate'];
      } */
    if ($conversion_rate != 1) {
        $max_price = $max_price / $conversion_rate;
        $min_price = $min_price / $conversion_rate;
    }

    $tax_query = array();

    if ($exam_center != '' AND $exam_center[0] != '') {

        //176 is the term id for anywhere.
        array_push($exam_center, 176);
        $t_ar_exam_center = array(
            'taxonomy' => 'pa_exam-center',
            'field' => 'term_id',
            'terms' => $exam_center,
            'operator' => 'IN'
        );

        array_push($tax_query, $t_ar_exam_center);
    }
    if ($type_course != '' AND $type_course[0] != '') {
        $t_ar_type_course = array(
            'taxonomy' => 'pa_certification-type',
            'field' => 'term_id',
            'terms' => $type_course,
            'operator' => 'IN'
        );
        array_push($tax_query, $t_ar_type_course);
    }
    if ($specialization != '' AND $specialization[0] != '') {
        $t_ar_specialization = array(
            'taxonomy' => 'pa_specialization',
            'field' => 'term_id',
            'terms' => $specialization,
            'operator' => 'IN'
        );
        array_push($tax_query, $t_ar_specialization);
    }
    if ($study_content != '' AND $study_content[0] != '') {
        $t_ar_study_content = array(
            'taxonomy' => 'pa_study-content',
            'field' => 'term_id',
            'terms' => $study_content,
            'operator' => 'IN'
        );
        array_push($tax_query, $t_ar_study_content);
    }

    if ($university != '' AND $university[0] != '') {
        $t_ar_university = array(
            'taxonomy' => 'university',
            'field' => 'slug',
            'terms' => $university,
            'operator' => 'IN'
        );
        array_push($tax_query, $t_ar_university);
    }

    if ($provider != '' AND $provider[0] != '') {
        $t_ar_provider = array(
            'taxonomy' => 'shop_vendor',
            'field' => 'slug',
            'terms' => $provider,
            'operator' => 'IN'
        );
        array_push($tax_query, $t_ar_provider);
    }
    if ($exam_mode != '' AND $exam_mode[0] != '') {
        $t_ar_exam_mode = array(
            'taxonomy' => 'pa_exam-mode',
            'field' => 'term_id',
            'terms' => $exam_mode,
            'operator' => 'IN'
        );
        array_push($tax_query, $t_ar_exam_mode);
    }
    if ($eligibility != '' AND $eligibility[0] != '') {
        $t_ar_eligibility = array(
            'taxonomy' => 'pa_eligibility',
            'field' => 'term_id',
            'terms' => $eligibility,
            'operator' => 'IN'
        );
        array_push($tax_query, $t_ar_eligibility);
    }
    if ($domain != '' AND $domain[0] != '') {
        $t_ar_domain = array(
            'taxonomy' => 'pa_domain',
            'field' => 'term_id',
            'terms' => $domain,
            'operator' => 'IN'
        );
        array_push($tax_query, $t_ar_domain);
    }
    if (count($tax_query) > 1) {
        $tax_query['relation'] = 'AND';
    }


    if ($min_price != '' AND $max_price != '') {
        $meta_query = array(
            'relation' => 'AND',
            array(
                'key' => '_max_variation_regular_price',
                'value' => $min_price,
                'type' => 'numeric',
                'compare' => '>='
            ),
            array(
                'key' => '_max_variation_regular_price',
                'value' => $max_price,
                'type' => 'numeric',
                'compare' => '<='
            )
        );
    }
//        echo "<p>".__LINE__.$order."</p>";
    // $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
    if (isset($result) AND $result == "all") {

//            $order = get_transient("gh_order_by");
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 12,
            'paged' => $paged,
            'meta_key' => $order,
            'orderby' => $orderby,
            'order' => $gh_order
        );
    } elseif (($_GET['provider'] == '') AND ( $_GET['university'] == '') AND ( $_GET['studycontent'] == '') AND ( $_GET['Specialization'] == '') AND ( $_GET['Center'] == '' OR $_GET['Center'] == 176 ) AND ( $_GET['typecourse'] == '')) {
        if (true === $ba_found) {
            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'meta_query' => $meta_query,
                's' => $search,
                'product_tag' => $search,
                'posts_per_page' => 12,
                'paged' => $paged,
                'meta_key' => $order,
                'orderby' => $orderby,
                'order' => $gh_order
            );
        } else {
            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'meta_query' => $meta_query,
                's' => $search,
                'posts_per_page' => 12,
                'paged' => $paged,
                'meta_key' => $order,
                'orderby' => $orderby,
                'order' => $gh_order
            );
        }
    } elseif ($tax_query) {

        if (true === $ba_found) {
            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'tax_query' => $tax_query,
                'meta_query' => $meta_query,
                's' => $search,
                'product_tag' => $search,
                'posts_per_page' => 12,
                'paged' => $paged,
                'meta_key' => $order,
                'orderby' => $orderby,
                'order' => $gh_order
            );
        } else {
            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'tax_query' => $tax_query,
                'meta_query' => $meta_query,
                's' => $search,
                'posts_per_page' => 12,
                'paged' => $paged,
                'meta_key' => $order,
                'orderby' => $orderby,
                'order' => $gh_order
            );
        }
    }

    $the_query = new WP_Query($args);


//	echo "<pre>";
//         print_r($the_query);
////        echo '<p>$the_query->max_num_pages = '.$the_query->max_num_pages.'</p>';
//	echo "<pre />";
//	exit;

    $args_for_debug = print_r($args, true);
    $the_query_for_debug = print_r($the_query, true);
//	$result_for_debug = print_r($result, true);


    $result = $the_query->posts;
    $pf = new WC_Product_Factory();
    ?>  
    <p class="woocommerce-result-count">
        <?php
        $paged = max(1, $the_query->get('paged'));
        $per_page = $the_query->get('posts_per_page');
        $total = $the_query->found_posts;
        $first = ( $per_page * $paged ) - $per_page + 1;
        $last = min($total, $the_query->get('posts_per_page') * $paged);

        if (1 == $total) {
            _e('Showing the single result', 'woocommerce');
        } elseif ($total <= $per_page || -1 == $per_page) {
            printf(__('Showing all %d results', 'woocommerce'), $total);
        } else {
            printf(_x('Showing %1$d&ndash;%2$d of %3$d results', '%1$d = first, %2$d = last, %3$d = total', 'woocommerce'), $first, $last, $total);
        }
        ?>
    </p>
    <div class="row"><div class="large-12 columns">
            <ul class="products small-block-grid-2 large-block-grid-4">
                <?php
                global $woocommerce, $flatsome_opt;

                if ($result) {

                    $imgplaceholder = site_url() . '/wp-content/plugins/woocommerce/assets/images/placeholder.png';
                    //echo $imgplaceholder;
                    foreach ($result as $post) {
                        $id = $post->ID;
                        $img1 = wp_get_attachment_thumb_url(get_post_thumbnail_id($post->ID));
                        $product = $pf->get_product($id);
                        $url = get_permalink($id);
                        $title = $product->post->post_title;
                        $scholarship = $product->get_attribute("scholarship");
                        $trending = $product->get_attribute("trending");
                        $hot = $product->get_attribute("hot");
                        $specialization = $product->get_attribute("specialization");
                        $studycontent = $product->get_attribute("study-content");
                        $show_specialization = $product->get_attribute("show-specialization");
                        $label_new = $product->get_attribute("new");
                        $duration = $product->get_attribute("duration");
                        $cashback = $product->get_attribute("referral-cashback");

                        if ($product->product_type == 'variable') {
                            $max_variation_sale_price = '';
                            if ($product->is_on_sale()) {
                                $max_variation_sale_price = round(gh_get_currency_updated_price($product->max_variation_sale_price), 2);
                                // $price = gh_variation_sale_price($product->price, $product);
                            } else {
                                $price = round(gh_get_currency_updated_price($product->get_variation_regular_price('max')), 2);
                                //$the_query $price = gh_variation_price( $product->price, $product );
                            }
                            // $variation_sale_price = gh_variation_sale_price($product->price, $product);
                            // $price = ($variation_sale_price != '') ? $variation_sale_price : gh_variation_price( $product->price, $product );
                        } else {
                            if ($product->is_on_sale()) {
                                $price = round($product->get_sale_price(), 2);
                            } else {
                                $price = round($product->price, 2);
                            }
                        }


                        $provider_object = wc_get_product_terms($product->id, 'shop_vendor')[0];
                        $university_name = wc_get_product_terms($product->id, 'university')[0];

                        $fee_type = $product->get_attribute("fee-type");
                        $product_cat = wc_get_product_terms($product->id, 'product_cat')[0];

                        $univ = get_option("taxonomy_" . $university_name->term_id);
                        $univ_shrt_name = get_term_meta($university_name->term_id, 'university_short_name', true);
                        ?>
                        <li class="product-small  grid1 <?php if ($view == "list") {
                    echo "edu-list-product";
                } ?>">
                            <?php
                            echo '<a href="' . $url . '"><div class="inner-wrap">';
                            echo '<div class="product-image hover_fade_in_back"><div class="image-cent">';
                            if ($img1) {
                                ?><img src='<?php echo $img1; ?>' title ='<?php echo $title; ?>'>
                            <?php } else {
                                ?><img src="<?php echo $imgplaceholder; ?>" title ="<?php echo $title; ?>" width="150">
                            <?php
                            }
                            echo '</div><div data-prod=' . $product->id . ' class="quick-view">+ Quick View</div></div>';
                            echo '<div class="info text-center">';

                            if ($show_specialization == 'Yes') {
                                echo '<p class="name">' . $title . ( (trim($specialization) != '') ? ' (' . $specialization . ')' : '' ) . ( (trim($univ_shrt_name) != '') ? ' - ' . $univ_shrt_name : '') . '</p>';
                            } else {
                                echo '<p class="name">' . $title . ( (trim($univ_shrt_name) != '') ? ' - ' . $univ_shrt_name : '') . '</p>';
                            }
                            echo '<div class="duration_below_title-list"><span class="short-description_sapn"><p class="edu-duration-list">' . $duration . '</p></span> <span class="edu-ins-name"><b>Institution: </b>' . $university_name->name . '</span><div class="clear"></div><span class="short-description_sapn"><p class="provider">Provider: ' . $provider_object->name . '</p></span><span class="edu-study-mode"><p class="exam-mode"><b>Study Content: </b>' . $studycontent . '</p></span></div>';
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
                            ?> </span>
                            <span class="product_trending"><?php
                                if ($trending == "Yes") {
                                    echo "Trending";
                                }
                                ?> </span>
                            <span class="edu_new product_label"><?php
                                if ($hot == "Yes") {
                                    echo "Hot";
                                }
                                ?> </span><span class="learn_more">Learn More</span>
                                <?php
                                echo '<span class="price">' . gh_get_local_currency_symbol() . '<span class="gh_max_price_in_loop">' . ($max_variation_sale_price != '' ? '<del>' . $max_variation_sale_price . '</del>' : '') . '' . $price . '</span></span>';

                                if ($product_cat->term_id == "338" || $product_cat->term_id == "181" || $product_cat->term_id == "5912") {
                                    if (strlen($fee_type) < 24) {
                                        echo '<span class="feetype">(' . $fee_type . ')</span>';
                                    }
                                }
                                if ($cashback) {
                                    ?>
                                <div class="referral"><span class="cashback">Cashback</span> <?php echo'Rs.' . number_format_i18n((int) $cashback); ?></div>
                        <?php
                    }
                    echo '</div></div></a></li>';
                }
                ?>
                </ul>

                <?php
                $no_of_pagination_link = $the_query->max_num_pages;

                if ($no_of_pagination_link > 1) {
                    gh_courses_pagination_link($no_of_pagination_link, $paged);
                }
                ?>      


    <?php
    } else {
        echo '<p style="margin: 0px 20px 10px 20px;">Sorry, You’re looking for something which isn\'t here.</p><br> <p>However, we have wide range of courses which will help you enhance your skills, <a href="' . site_url() . '/courses/?view=list">click here to explore.</a></p>';
    }
    ?>
        </div></div>
    <?php
    die();
}

add_action('wp_ajax_filter_search', 'gh_sidebar_filter_search');
add_action('wp_ajax_nopriv_filter_search', 'gh_sidebar_filter_search');

add_action('wp_ajax_test_api', 'gh_test_api');
add_action('wp_ajax_nopriv_test_api', 'gh_test_api');

function gh_test_api() {
    echo "<p>LN" . __LINE__ . ": hi</p>";
}

function gh_connecto_integration_form() {

    $data = $_POST['Data'];
    $name = $data[0]['value'];
    $email = $data[1]['value'];
    $phone = $data[2]['value'];
    $course = $data[3]['value'];
    $university = $data[4]['value'];

    $data = "<html><head></head><body><h3 style='background-color:ligh-blue;padding:10px,5px;'>Connecto Form Query</h3>";
    $data .="<table border='0' cellspacing ='1' cellpadding: '10' background='light-blue'><tbody><tr><td><strong>Name :</strong></td><td>" . $name . "</td></tr>";
    $data .="<tr><td><strong>Email :</strong></td><td>" . $email . "</td></tr>";
    $data .="<tr><td><strong>Phone :</strong></td><td>" . $phone . "</td></tr>";
    $data .="<tr><td><strong>Course :</strong></td><td>" . $course . "</td></tr>";
    $data .="<tr><td><strong>University :</strong></td><td>" . $university . "</td></tr>";
    $data .="</tbody></table></body></html>";
    $mailto = get_option('admin_email');
    $subject = "Connecto Form Query";
    add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
    wp_mail($mailto, $subject, $data);
    die();
}

add_action('wp_ajax_connect_form', 'gh_connecto_integration_form');
add_action('wp_ajax_nopriv_connect_form', 'gh_connecto_integration_form');


//function woocommerce_remove_related_products(){
//  remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
//}
// add_action('woocommerce_after_single_product_summary', 'woocommerce_remove_related_products');

add_filter('woocommerce_related_products_args', function( $args ) {
    unset($args['post__in']);

    global $product;

    $specializations = $product->get_attribute("Specialization");
    $specialization = isset($specializations) ? explode(",", $specializations) : '';
    $tax_rel_query = array();

    if ($specialization != '' AND $specialization[0] != '') {
        $t_rel_specialization = array(
            'taxonomy' => 'pa_specialization',
            'field' => 'slug',
            'terms' => $specialization,
        );
        array_push($tax_rel_query, $t_rel_specialization);
    }

    $product_title = $product->post->post_title;

    if ("ba" == strtolower($product_title)) {
        $ba_found = true;
    } else {
        $ba_array = explode(" ", $product_title);
        $ba_found = in_array("ba", $ba_array);
    }

    $args['tax_query'] = $tax_rel_query;
    $args['s'] = $product_title;

    if (true === $ba_found) {
        $args['product_tag'] = $product_title;
    }
    return $args;
});

//add_action('wp_head', 'gh_redirect_to_couse_page', 1);// used for not going to cart page and checkout page
//
//function gh_redirect_to_couse_page(){
//
//    if(is_page(7)){
//
//        $url = site_url()."/courses/";
//        wp_safe_redirect($url);
//    }
//}


function my_woocommerce_catalog_orderby($orderby) {
    unset($orderby["date"]);
    unset($orderby["popularity"]);
    unset($orderby["rating"]);
    $orderby['featured'] = 'Sort by featured courses';
    //set($orderby["featured"]);
    return $orderby;
}

add_filter("woocommerce_catalog_orderby", "my_woocommerce_catalog_orderby", 20);

function gh_woocommerce_add_error($error) {
    if ('<strong>First Name</strong> is a required field.' == $error) {
        $error = '';
    }
    if ('<strong>Last Name</strong> is a required field.' == $error) {
        $error = '';
    }
    if ('<strong>Phone</strong> is a required field.' == $error) {
        $error = '';
    }
    if ('<strong>State / County</strong> is a required field.' == $error) {
        $error = '';
    }
    if ('<strong>Town / City</strong> is a required field.' == $error) {
        $error = '';
    }
    if ('<strong>Address</strong> is a required field.' == $error) {
        $error = '';
    }
    if ('<strong>Postcode / Zip</strong> is a required field.' == $error) {
        $error = '';
    }
    return $error;
}

add_filter('woocommerce_add_error', 'gh_woocommerce_add_error', 20);

// add shortcode is used for adding shortcode on paynow page 
add_shortcode('gh_paynow_form', 'gh_paynow');

function gh_paynow() {
    ob_start();
    $output = include_once('paynowform.php');
    $output = ob_get_clean();
    return $output;
}

global $time_start_header_gh;
global $time_end_footer_gh;
add_action('init', 'gh_time_interval');

function gh_time_interval() {
    global $time_start_header_gh;
    $time_start_header_gh = microtime(true);
}

function gh_paytm_kit_mail_to_customer($mail_details) {

    $content = '<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    $content .='<title>Untitled Document</title></head><body><div style="" class=""><table width="100%" cellspacing="0" cellpadding="10" border="0" align="center" ><tbody style="" class=""><tr style="" class=""><td style="" class="">Dear User, <br style="" class=""><br style="" class="">Thank you for choosing to learn with <a href="http://EduKart.com" target="_blank" style="color:#000; font-weight: bold;" class="">EduKart.com</a>. We are delighted to know that you have taken an informed decision to become more employable by enrolling for one of the industry relevant courses on <a href="http://EduKart.com" target="_blank" style="color:#000; font-weight: bold;" class="">EduKart.com</a>. You are now a step ahead of your peers! </td>';
    $content .='<td width="47%" align="right" style="" class=""><a href="'.esc_url(home_url("/")).'" title="'.esc_attr(get_bloginfo("name", "display")) - bloginfo("description").'" rel="home"><img style= "dislay:inline-block;" src="'.get_site_url().'/wp-content/themes/flatsome-gh/images/logo.jpg" title="edukart" /> </a></td></tr>';
    $content .='<tr bgcolor="white" style="" class=""><td style="" class=""><h3 style="" class=""><b style="" class="">Invoice</b></h3></td></tr></tbody></table>';
    $content .='<table width="100%" cellspacing="0" cellpadding="2" border="0" ><tbody style="" class=""><tr bgcolor="#CCCCCC" style="" class=""><td colspan="2" style="" class=""><b style="" class="">Order Information</b></td></tr><tr style="" class=""><td style="" class="">Order Number:</td><td style="" class="">'. $mail_details["ORDERID"] .'</td></tr>';
    $content .='<tr style="" class=""><td style="" class="">Order Date:</td><td style="" class="">'. $mail_details["TXNDATE"] .'</td></tr>';
    $content .='<tr style="" class=""><td style="" class="">Order Status:</td><td style="" class=""><b style="" class=""><b>Successful</b></b></td></tr>';
    $content .='<tr style="" class=""><td colspan="2" style="" class=""> </td></tr><tr bgcolor="#CCCCCC" style="" class=""><td colspan="2" style="" class=""><b style="" class="">Amount</b></td><td style="" class="">'.$mail_details["TXNAMOUNT"].'</td></tr>';
    $content .='<tr bgcolor="#CCCCCC" style="" class=""><td colspan="2" style="" class=""><b style="" class="">Currency</b></td><td style="" class="">'.$mail_details["CURRENCY"].'</td> </tr>';
    $content .='<tr bgcolor="#CCCCCC" style="" class=""><td colspan="2" style="" class=""><b style="" class="">Bank Name</b></td><td style="" class=""> '.$mail_details["BANKNAME"].'</td></tr>';
    $content .='<tr bgcolor="#CCCCCC" style="" class=""><td colspan="2" style="" class=""><b style="" class="">Transaction ID</b></td><td style="" class="">'.$mail_details["TXNID"].'</td></tr>';
    $content .='<tr bgcolor="#CCCCCC" style="" class=""><td colspan="2" style="" class=""><b style="" class="">Payment Mode</b></td><td style="" class="">'.$mail_details["PAYMENTMODE"].'</td></tr>';
    $content .='<tr style="" class=""><td colspan="2" style="" class=""> </td></tr><tr bgcolor="#CCCCCC" style="" class=""><td style="" class=""><b style="" class="">Payment Information</b></td><td style="" class=""><b>Successful</b></td></tr></tbody></table><br style="" class=""><br style="" class="">';
    $content .='<table style="" class=""><tbody style="" class=""> <tr valign="top" style="" class=""><td width="53%" align="left" style="" class=""><div style="" class="">Please feel free to contact us for your queries related to the course by calling us on +91-11-49323333 or email us at <a href="mailto:support@EduKart.com" style="" class="">support@EduKart.com</a> . We look forward to being with you as you go through the course on <a href="http://EduKart.com" target="_blank" style="" class="">EduKart.com</a> .</div><br style="" class="">Enjoy your learning! <br style="" class=""><br style="" class="">Thank you,<br style="" class="">Customer Relations<br style="" class=""><a href="http://EduKart.com" target="_blank" style="" class="">EduKart.com</a><br style="" class="">Telephonic Support:+91-11-49323333<br style="" class="">Email Support: <a href="mailto:support@EduKart.com" style="" class="">support@EduKart.com</a><br style="" class=""></td></tr><tr style="" class=""><td style="" class=""><hr style="" class=""><div style="font-size: 9px;" class=""> Declaration:<br style="" class=""><a href="http://EduKart.com" target="_blank" style="" class="">EduKart.com</a> is a trademark of Earth Education Valley Pvt. Ltd. This invoice shows the final selling price of the course and is inclusive of all taxes, shipping and handling charges. <br style="" class="">Company&#39;s PAN: AACCE6152C <br style="" class="">Company&#39;s Service Tax No: AACCE6152CSD001 <br style="" class="">            CIN: U80302DL2011PTC213971 </div><div style="font-size: 9px;" class=""> Terms and Conditions:<br style="" class=""> 1)The sale is governed by the Cancellation and Refund Policy as mentioned on <a target="_blank" href="http://www.edukart.com" style="" class="">www.edukart.com</a>.<br style="" class="">2)Interest @ 24% per annum will be charged on overdue accounts.<br style="" class="">3)Subject to Delhi jurisdiction only.<br style="" class=""></div><hr style="" class=""></td></tr></tbody></table><style>table h3 {  display: none !important; } </style> </div></body></html>';

    $adminmail = get_option('admin_email');
    //array_push($mailto, $adminmail);
    //$customermail =
	$MAILTO = array("lokesh.garg@greenhonchos.com","garglokesh123@yopmail.com"); 
    $subject = "Payment Confiramtion mail";
    add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
    wp_mail($adminmail, $subject, $content);
}

add_action('wp_ajax_referral_validation', 'gh_referral_validation');
add_action('wp_ajax_nopriv_referral_validation', 'gh_referral_validation');

add_action('wp_ajax_rmove_cashback', 'gh_referral_validation');
add_action('wp_ajax_nopriv_remove_cashback', 'gh_referral_validation');

function gh_referral_validation() {

    global $woocommerce;
    //$prod_id = get_product($_REQUEST['product_id']);
    //print_r($prod_id);
    $cart_subtotal = $_REQUEST['cart_subtotal'];
    $cashback = $_REQUEST['cashback_amount'];
//	$bid = '6044'; // GH credential
    $bid = '1576'; // Edukart Credential
//	$secretKey = '6BB82499A0D5835F2049C06BD80C52B4'; // GH Credential
    $secretKey = 'C3B7F29C28C4209D267C168B6A7CA7B8'; // Edukart Credential
//	$campaignID = '5581'; // GH Credential
    $campaignID = '5184'; // Edukart Credential - Campaign name: "Edukart sale"
    $referCode = $_REQUEST['coupon_code']; //referral code passed by customer
    $_SESSION['cash_back'] = '';
    $_SESSION['effective_total'] = '';
    //echo $_REQUEST['cashback_amount'];
    //exit;
    if ((int) str_replace('Rs.', '', str_replace(',', '', $cart_subtotal)) > (int) $_REQUEST['cashback_amount']) {
        $effecivetotal = (int) str_replace('Rs.', '', str_replace(',', '', $cart_subtotal)) - (int) $_REQUEST['cashback_amount'];
    } else {
        $effecivetotal = 0;
    }
    //$referal_arr = array('cashback'=>$cashback, 'effectivetotal'=>$effecivetotal);
    $http_val = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
    $result = file_get_contents($http_val . 'www.ref-r.com/campaign/t1/confirmReferCode?secretKey=' . $secretKey . '&bid=' . $bid . '&referCode=' . $referCode . '&campaignID=' . $campaignID);
    $res = json_decode($result);
    $referal_arr = array('refercode' => $_REQUEST['coupon_code'], 'cashback' => $cashback, 'effectivetotal' => $effecivetotal, 'referral_details' => $res->referrer_details);
    //array_push($referal_arr, $res->referrer_details	);
    //exit;
    if ($res->response == 'success') {
        $_SESSION['cash_back'] = (int) $cashback;
        $_SESSION['ir_coupon_code'] = $_REQUEST['coupon_code'];
        $_SESSION['effective_total'] = $effecivetotal;
        $_SESSION['referral_details'] = $res->referrer_details;
        //$_SESSION['order_custom_meta_data'] = $referal_arr;
        //echo $_SESSION['effective_total'];
        echo json_encode($referal_arr);
    } else {
        echo 'failed';
        //session_destroy();
        unset($_SESSION['cash_back']);
        unset($_SESSION['ir_coupon_code']);
        unset($_SESSION['effective_total']);
        unset($_SESSION['referral_details']);
        $cookie_name = 'referee';
        $cookie_value = '';
        setcookie($cookie_name, $cookie_value, time() + (86400 * 30), '/');
    }
    //$_SESSION['coupon'] = $_REQUEST['coupon_code'];
    die();
}

add_action('init', 'gh_validate_referral_link');

function gh_validate_referral_link() {

    if (isset($_GET['ir_co'])) {
        $referCode = $_GET['ir_co'];
        $bid = '1576';
        $secretKey = 'C3B7F29C28C4209D267C168B6A7CA7B8';
        $campaignID = 5184;
        $http_val = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
        $res = file_get_contents($http_val . 'www.ref-r.com/campaign/t1/confirmReferCode?secretKey=' . $secretKey . '&bid=' . $bid . '&referCode=' . $referCode . '&campaignID=' . $campaignID);
        $result = json_decode($res);
        //echo $result->response;
        if ($result->response == 'success') {
            //echo "hi ";
            $cookie_name = 'referee';
            $result->referrer_details->code = $_GET['ir_co'];
            //print_r($result->referrer_details);
            $cookie_value = json_encode($result->referrer_details);
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), '/');
        }
        //$_SESSION['coupon'] = $_GET['ir_co'];
        //echo $_COOKIE['referee'];
    }
}

add_action('woocommerce_checkout_update_order_meta', 'custom_checkout_field_update');

function custom_checkout_field_update($order_id) {
    if ($_POST['cash_back']) {
        update_post_meta($order_id, 'cash_back', esc_attr($_POST['cash_back']));
    }
    if ($_POST['refree_name']) {
        update_post_meta($order_id, 'refree_name', esc_attr($_POST['refree_name']));
    }
    if ($_POST['refree_email']) {
        update_post_meta($order_id, 'refree_email', esc_attr($_POST['refree_email']));
    }
    if ($_POST['ir_coupon_code']) {
        update_post_meta($order_id, 'ir_coupon_code', esc_attr($_POST['ir_coupon_code']));
    }
}

function gh_my_custom_tracking($order_id) {
    $order = new WC_Order($order_id);

    global $wpdb;
    $table_name = $wpdb->prefix . "invite_referal";
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT  PRIMARY KEY,order_id VARCHAR(20)NOT NULL, referee_name VARCHAR(50), referee_email VARCHAR(100), refer_to VARCHAR(100), refer_to_email VARCHAR(100), coupon_code VARCHAR(100), coupon_value VARCHAR(100));";


    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);


    //$referto = $order->billing_first_name . $order->billing_last_name ;
    $order_no = $order_id;
    $first_name = get_post_meta($order_id, '_billing_first_name', true);
    $last_name = get_post_meta($order_id, '_billing_last_name', true);
    $refer_to_email = get_post_meta($order_id, '_billing_email', true);
    $refer_to = $first_name . " " . $last_name;
    $referee_name = get_post_meta($order_id, 'refree_name', true);
    $referee_email = get_post_meta($order_id, 'refree_email', true);
    $coupon_code = get_post_meta($order_id, 'ir_coupon_code', true);
    $coupon_value = get_post_meta($order_id, 'cash_back', true);
    $table_name = $wpdb->prefix . 'invite_referal';
    if ($referee_name != '') {
        $sql = "INSERT INTO $table_name ( `order_id`,`referee_name`,`referee_email`,`refer_to`,`refer_to_email`,`coupon_code`,`coupon_value`) VALUES ( %s,%s,%s,%s,%s,%s,%s ) ";
        $wpdb->query($wpdb->prepare($sql, $order_no, $referee_name, $referee_email, $refer_to, $refer_to_email, $coupon_code, $coupon_value));
    }
    session_destroy();
    $cookie_name = 'referee';
    $cookie_value = '';
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), '/');
}

add_action('woocommerce_checkout_order_processed', 'gh_my_custom_tracking');

add_action('admin_menu', 'invite_referal_menu');

// function for adding menu in in wordpress admin
function invite_referal_menu() {

    add_menu_page('GH Invite Referal', 'GH Invite Referal', 'manage_options', 'invite_referal_bkap_top_menu', 'invite_referal_option_page');
}

class Inivte_Referal_List_Table extends WP_List_Table {

    /**

     * Constructor, we override the parent to pass our own arguments

     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.

     */
    function __construct() {

        parent::__construct(array(
            'singular' => 'invite_referal_list', //Singular label
            'plural' => 'invite_referal_lists' //plural label, also this well be one of the table css class
        ));

        add_action('admin_head', array(&$this, 'admin_header'));
    }

    function admin_header() {

        $page = ( isset($_GET['page']) ) ? esc_attr($_GET['page']) : false;

        if ('invite_referal_bkap_top_menu' != $page)
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
            'order_id' => __('Order ID'),
            'referee_name' => __('Referee Name'),
            'referee_email' => __('Referee Email'),
            'refer_to' => __('Refer To'),
            'refer_to_email' => __('Refer To Email'),
            'coupon_code' => __('Coupon Code'),
            'coupon_value' => __('Coupon Value'),
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
        $table_name = $wpdb->prefix . 'invite_referal';
        $action = $this->current_action();
        switch ($action) {
            case 'delete':
                $counter = 0;
                $status = true;
                if (!empty($_POST['invite_referal_lists'])) {
                    foreach ($_POST['invite_referal_lists'] as $key => $val) {


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
            case 'referee_name':
            case 'referee_email':
            case 'refer_to':
            case 'refer_to_email':
            case 'coupon_code':
            case 'coupon_value':

            default:

                return print_r($item->$column_name, true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_first_name($item) {

        $actions = array(
            'delete' => sprintf('<a href="?page=%s&action=%s&id=%d">Delete</a>', $_REQUEST['page'], 'delete', $item->id));
        return sprintf('%1$s %2$s', $item->first_name, $this->row_actions($actions));
    }

    function column_cb($item) {

        return sprintf('<input type="checkbox" name="invite_referal_lists[]" value="%s" />', $item->id);
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

        $per_page = 10;

        $current_page = $this->get_pagenum();



        /* -- Preparing your query -- */


        $query = "SELECT * FROM " . $wpdb->prefix . "invite_referal GROUP BY order_id";



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

        //echo "<p>$query</p>";
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

// function for displaying data of custom table in wordpress admin

function invite_referal_option_page() {

    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    //Prepare Table of elements
    $wp_list_invite_referal = new Inivte_Referal_List_Table();
    $wp_list_invite_referal->prepare_items();
    echo '<div class="wrap" style ="overflow:auto;">';
    echo '<h2>GH Invite Referral Page</h2>';

    //Table of elements
    ?>
    <form method="post">
        <input type="hidden" name="page" value="ttest_list_table">
        <?php
        $wp_list_invite_referal->search_box('search', 'search_id');
        $wp_list_invite_referal->display();
        echo '</form>';
        echo "<div style='float:right;'>By Green Honchos Solutions PVT. LTD.</div> ";
        echo '</div>';
    }

    add_filter('woocommerce_email_subject_new_order', 'gh_change_admin_email_subject', 1, 2);
    add_filter('woocommerce_email_subject_customer_processing_order', 'gh_change_admin_email_subject', 1, 2);

    function gh_change_admin_email_subject($subject, $order) {
        global $woocommerce;

        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

        $subject = sprintf('[%s] New Customer Order (# %s)', $blogname, $order->id);

        return $subject;
    }

    add_filter('woocommerce_email_headers', 'gh_mycustom_headers_filter_function');

    function gh_mycustom_headers_filter_function($headers, $object) {
        if ($object == 'customer_process_order') {
            $headers .= 'BCC: NAME <garglokesh12@gmail.com>' . "\r\n";
        }
        return $headers;
    }
    
	function paynow_paytm() {
	    global $wpdb;
	    $table_name = $wpdb->prefix . "paynow_payments";
	    $sql2 = "CREATE TABLE IF NOT EXISTS $table_name (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT  PRIMARY KEY,order_id VARCHAR(20)NOT NULL, first_name VARCHAR(50), mobile_no VARCHAR(50), customer_email VARCHAR(100), transaction_id VARCHAR(100), payment_mode VARCHAR(100), bank_name VARCHAR(100), amount VARCHAR(100), payment_status VARCHAR(100), order_date DATETIME)";


	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	    dbDelta($sql2);
}

add_action('init', 'paynow_paytm');


    function gh_paynow_page_payment($order_details) {

    global $wpdb;
    //$referto = $order->billing_first_name . $order->billing_last_name ;
    $order_no = $order_details["ORDERID"];
    $first_name = '';
    $customer_email = '';
    $phone = '';
    $txnid = $order_details["TXNID"];
    $bank_name = $order_details["BANKNAME"];
    $amount = $order_details["TXNAMOUNT"];
    $txndate = $order_details["TXNDATE"];
    $paymentmode = $order_details["PAYMENTMODE"];
    $status = ( ($order_details['STATUS'] == 'TXN_SUCCESS') ? 'Successful' : 'NOT Successful');    
    $table_name = $wpdb->prefix . 'paynow_payments';
    $sql1 = "INSERT INTO $table_name ( `order_id`,`first_name`,`mobile_no`,`customer_email`,`transaction_id`,`payment_mode`,`bank_name`,`amount`,`payment_status`,`order_date`) VALUES ( '$order_no', '$first_name', '$phone', '$customer_email', '$txnid', '$paymentmode','$bank_name','$amount', '$status', '$txndate') ";
	//echo $sql1;
	$wpdb->query($sql1);
	
   
}

add_action('admin_menu', 'gh_paynow_payment');

// function for adding menu in in wordpress admin
function gh_paynow_payment() {

    add_menu_page('GH Paynow Payment', 'GH Paynow Payment', 'manage_options', 'paynow_payment_bkap_top_menu', 'paynow_payment_option_page');
}

class Paynow_Payment_List_Table extends WP_List_Table {

    /**

     * Constructor, we override the parent to pass our own arguments

     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.

     */
    function __construct() {

        parent::__construct(array(
            'singular' => 'paynow_payment_list', //Singular label
            'plural' => 'paynow_payment_lists' //plural label, also this well be one of the table css class
        ));

        add_action('admin_head', array(&$this, 'admin_header'));
    }

    function admin_header() {

        $page = ( isset($_GET['page']) ) ? esc_attr($_GET['page']) : false;

        if ('paynow_payment_bkap_top_menu' != $page)
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
            'order_id' => __('Order ID'),
            'first_name' => __('First Name'),
            'mobile_no' => __('Phone No:'),
            'customer_email' => __('Email'),
            'transaction_id' => __('Transaction ID'),
            'amount' => __('Amount'),
            'order_date' => __('Date'),
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
        $table_name = $wpdb->prefix . 'paynow_payments';
        $action = $this->current_action();
        switch ($action) {
            case 'delete':
                $counter = 0;
                $status = true;
                if (!empty($_POST['paynow_payments_lists'])) {
                    foreach ($_POST['paynow_payments_lists'] as $key => $val) {


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
            case 'first_name':
            case 'mobile_no':
            case 'customer_email':
            case 'transaction_id':
            case 'amount':
            case 'order_date':

            default:

                return print_r($item->$column_name, true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_first_name($item) {

        $actions = array(
            'delete' => sprintf('<a href="?page=%s&action=%s&id=%d">Delete</a>', $_REQUEST['page'], 'delete', $item->id));
        return sprintf('%1$s %2$s', $item->first_name, $this->row_actions($actions));
    }

    function column_cb($item) {

        return sprintf('<input type="checkbox" name="paynow_payments_lists[]" value="%s" />', $item->id);
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

        $per_page = 10;

        $current_page = $this->get_pagenum();



        /* -- Preparing your query -- */


        $query = "SELECT * FROM " . $wpdb->prefix . "paynow_payments";



        /* -- Ordering parameters -- */

        //Parameters that are going to be used to order the result

        $orderby = empty($_GET["orderby"]) ? 'id' : $_GET["orderby"];

        $order = empty($_GET["order"]) ? ' DESC ' : $_GET["order"];

        /** filter the result as per the search condition  * */
        if (isset($_POST['s']) && !empty($_POST['s'])) {
            $query .= " WHERE first_name LIKE '%" . $_POST['s'] . "%'";
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

// function for displaying data of custom table in wordpress admin

function paynow_payment_option_page() {

    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    //Prepare Table of elements
    $wp_list_paynow_payment = new Paynow_Payment_List_Table();
    $wp_list_paynow_payment->prepare_items();
    echo '<div class="wrap" style ="overflow:auto;">';
    echo '<h2>GH Paynow Payment Page</h2>';

    //Table of elements
    ?>
    <form method="post">
        <input type="hidden" name="gh_paynow_page" value="ttest_list_table">
        <?php
        $wp_list_paynow_payment->search_box('search', 'search_id');
        $wp_list_paynow_payment->display();
        echo '</form>';
        echo "<div style='float:right;'>By Green Honchos Solutions PVT. LTD.</div> ";
        echo '</div>';
    }

