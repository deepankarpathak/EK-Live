<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( !class_exists( 'YWRR_Review_Reminder_Premium' ) ) {

    /**
     * Implements features of YWRR plugin
     *
     * @class   YWRR_Review_Reminder_Premium
     * @package Yithemes
     * @since   1.0.0
     * @author  Your Inspiration Themes
     */
    class YWRR_Review_Reminder_Premium extends YWRR_Review_Reminder {

        /**
         * Constructor
         *
         * Initialize plugin and registers actions and filters to be used
         *
         * @since   1.0.0
         * @return  mixed
         * @author  Alberto Ruggiero
         */
        public function __construct() {

            if ( !function_exists( 'WC' ) ) {
                return;
            }

            parent::__construct();

            $this->_email_templates = array(
                'premium-1' => array(
                    'folder' => '/emails/premium-1',
                    'path'   => YWRR_TEMPLATE_PATH
                ),
                'premium-2' => array(
                    'folder' => '/emails/premium-2',
                    'path'   => YWRR_TEMPLATE_PATH
                ),
                'premium-3' => array(
                    'folder' => '/emails/premium-3',
                    'path'   => YWRR_TEMPLATE_PATH
                )
            );

            // register plugin to licence/update system
            add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
            add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );
            add_action( 'init', array( $this, 'ywrr_image_sizes' ) );

            // Include required files
            $this->includes();

            add_filter( 'yith_wcet_email_template_types', array( $this, 'add_yith_wcet_template' ) );
            add_filter( 'ywrr_product_permalink', array( $this, 'ywrr_product_permalink' ) );

            if ( is_admin() ) {

                YWRR_Ajax();

                add_filter( 'set-screen-option', 'YWRR_Schedule_Table::set_options', 10, 3 );

                add_action( 'admin_notices', array( $this, 'ywrr_admin_notices' ) );
                add_action( 'woocommerce_admin_field_custom-select', 'YWRR_Custom_Select::output' );
                add_action( 'woocommerce_update_option', array( YWRR_Schedule_premium(), 'mass_reschedule' ), 10, 1 );
                add_action( 'admin_enqueue_scripts', array( $this, 'ywrr_admin_scripts' ) );
                add_action( 'ywrr_schedulelist', 'YWRR_Schedule_Table::output' );
                add_action( 'current_screen', 'YWRR_Schedule_Table::add_options' );
                add_action( 'add_meta_boxes', array( $this, 'ywrr_add_metabox' ) );

            }
            else {

                remove_action( 'template_redirect', array( YWRR_Form_Handler(), 'unsubscribe_review_request' ) );
                add_action( 'template_redirect', array( YWRR_Form_Handler_Premium(), 'unsubscribe_review_request' ) );
                add_action( 'wp_enqueue_scripts', array( $this, 'ywrr_scripts' ) );

            }

        }

        /**
         * Files inclusion
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        private function includes() {

            include_once( 'includes/class-ywrr-emails-premium.php' );
            include_once( 'includes/class-ywrr-schedule-premium.php' );
            include_once( 'includes/emails/class-ywrr-mandrill-premium.php' );


            if ( is_admin() ) {
                include_once( 'includes/admin/class-ywrr-ajax.php' );
                include_once( 'includes/admin/meta-boxes/class-ywrr-meta-box.php' );
                include_once( 'templates/admin/schedule-table.php' );
                include_once( 'templates/admin/custom-select.php' );
            }

            if ( !is_admin() || defined( 'DOING_AJAX' ) ) {
                include_once( 'includes/class-ywrr-form-handler-premium.php' );
            }

        }

        /**
         * Set image sizes for email
         *
         * @since   1.0.4
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function ywrr_image_sizes() {

            add_image_size( 'ywrr_picture', 135, 135, true );

        }

        /**
         * If is active YITH WooCommerce Email Templates, add YWRR to list
         *
         * @since   1.0.0
         *
         * @param   $templates
         *
         * @return  array
         * @author  Alberto Ruggiero
         */
        public function add_yith_wcet_template( $templates ) {

            $templates[] = array(
                'id'   => 'yith-review-reminder',
                'name' => 'YITH WooCommerce Review Reminder',
            );

            return $templates;

        }

        /**
         * Add a metabox on order page
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function ywrr_add_metabox() {
            foreach ( wc_get_order_types( 'order-meta-boxes' ) as $type ) {
                add_meta_box( 'ywrr-metabox', __( 'Ask for a review', 'ywrr' ), 'YWRR_Meta_Box::output', $type, 'normal', 'high' );
            }
        }

        /**
         * Set the link to the product
         *
         * @since   1.0.4
         *
         * @param   $permalink
         *
         * @return  string
         * @author  Alberto Ruggiero
         */
        public function ywrr_product_permalink( $permalink ) {

            $link_type = get_option( 'ywrr_mail_item_link' );

            switch ( $link_type ) {
                case 'custom':
                    $link_hash = get_option( 'ywrr_mail_item_link_hash' );

                    if ( !empty( $link_hash ) ) {

                        if ( substr( $link_hash, 0, 1 ) === '#' ) {

                            $permalink .= $link_hash;

                        }
                        else {

                            $permalink .= '#' . $link_hash;

                        }

                    }

                    break;

                case 'review':

                    $permalink .= '#tab-reviews';

                    break;

                default:

            }

            return $permalink;

        }

        /**
         * ADMIN FUNCTIONS
         */

        /**
         * Advise if the plugin cannot be performed
         *
         * @since   1.0.3
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function ywrr_admin_notices() {
            if ( get_option( 'ywrr_mandrill_enable' ) == 'yes' && get_option( 'ywrr_mandrill_apikey' ) == '' ) : ?>
                <div class="error">
                    <p>
                        <?php _e( 'Please enter Mandrill API Key for YITH Woocommerce Review Reminder', 'ywrr' ); ?>
                    </p>
                </div>
            <?php
            endif;
        }

        /**
         * Initializes Javascript with localization
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function ywrr_admin_scripts() {
            global $post;

            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

            wp_enqueue_style( 'ywrr-premium', YWRR_ASSETS_URL . 'css/ywrr-premium.css' );

            wp_enqueue_script( 'ywrr-meta-boxes', YWRR_ASSETS_URL . 'js/admin/meta-boxes' . $suffix . '.js' );

            $params = array(
                'post_id'                => isset( $post->ID ) ? $post->ID : '',
                'order_date'             => isset( $post->post_modified ) ? $post->post_modified : '',
                'ajax_url'               => admin_url( 'admin-ajax.php' ),
                'do_send_email'          => __( 'Do you want to send remind email?', 'ywrr' ),
                'after_send_email'       => __( 'Reminder email has been sent successfully!', 'ywrr' ),
                'do_reschedule_email'    => __( 'Do you want to reschedule reminder email?', 'ywrr' ),
                'after_reschedule_email' => __( 'Reminder email has been rescheduled successfully!', 'ywrr' ),
                'do_cancel_email'        => __( 'Do you want to cancel reminder email?', 'ywrr' ),
                'after_cancel_email'     => __( 'Reminder email has been cancelled!', 'ywrr' ),
                'not_found_cancel'       => __( 'There is no email to unschedule', 'ywrr' ),
                'after_send_test_email'  => __( 'Test email has been sent successfully!', 'ywrr' ),
                'test_mail_wrong'        => __( 'Please insert a valid email address', 'ywrr' ),
            );

            wp_localize_script( 'ywrr-meta-boxes', 'ywrr_meta_boxes', $params );

        }

        /**
         * FRONTEND FUNCTIONS
         */

        /**
         * Initializes Javascript
         *
         * @since   1.0.4
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function ywrr_scripts() {

            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

            wp_enqueue_script( 'ywrr-footer', YWRR_ASSETS_URL . 'js/admin/ywrr-footer' . $suffix . '.js', array(), false, true );

        }

        /**
         * YITH FRAMEWORK
         */

        /**
         * Register plugins for activation tab
         *
         * @return void
         * @since    2.0.0
         * @author   Andrea Grillo <andrea.grillo@yithemes.com>
         */
        public function register_plugin_for_activation() {
            if ( !class_exists( 'YIT_Plugin_Licence' ) ) {
                require_once 'plugin-fw/licence/lib/yit-licence.php';
                require_once 'plugin-fw/licence/lib/yit-plugin-licence.php';
            }
            YIT_Plugin_Licence()->register( YWRR_INIT, YWRR_SECRET_KEY, YWRR_SLUG );
        }

        /**
         * Register plugins for update tab
         *
         * @return void
         * @since    2.0.0
         * @author   Andrea Grillo <andrea.grillo@yithemes.com>
         */
        public function register_plugin_for_updates() {
            if ( !class_exists( 'YIT_Upgrade' ) ) {
                require_once( 'plugin-fw/lib/yit-upgrade.php' );
            }
            YIT_Upgrade()->register( YWRR_SLUG, YWRR_INIT );
        }

    }

}