<?php
/**
 * Plugin Name:     Easy Digital Downloads - PayLane Gateway
 * Plugin URI:      https://wordpress.org/plugin/edd-paylane-gateway
 * Description:     Adds a payment gateway for PayLane to Easy Digital Downloads
 * Version:         1.0.2
 * Author:          Daniel J Griffiths
 * Author URI:      https://section214.com
 * Text Domain:     edd-paylane-gateway
 *
 * @package         EDD\Gateway\PayLane
 * @author          Daniel J Griffiths <dgriffiths@section214.com>
 * @copyright       Copyright (c) 2014, Daniel J Griffiths
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
    exit;
}


if( ! class_exists( 'EDD_PayLane_Gateway' ) ) {


    /**
     * Main EDD_PayLane_Gateway class
     *
     * @since       1.0.0
     */
    class EDD_PayLane_Gateway {


        /**
         * @var         EDD_PayLane_Gateway $instance The one true EDD_PayLane_Gateway
         * @since       1.0.0
         */
        private static $instance;


        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      self::$instance The one true EDD_PayLane_Gateway
         */
        public static function instance() {
            if( ! self::$instance ) {
                self::$instance = new EDD_PayLane_Gateway();
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->load_textdomain();
            }

            return self::$instance;
        }


        /**
         * Setup plugin constants
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function setup_constants() {
            // Plugin version
            define( 'EDD_PAYLANE_VER', '1.0.2' );

            // Plugin path
            define( 'EDD_PAYLANE_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'EDD_PAYLANE_URL', plugin_dir_url( __FILE__ ) );
        }


        /**
         * Include necessary files
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function includes() {
            require_once EDD_PAYLANE_DIR . 'includes/functions.php';
            require_once EDD_PAYLANE_DIR . 'includes/gateway.php';
        }


        /**
         * Internationalization
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function load_textdomain() {
            // Set filter for language directory
            $lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
            $lang_dir = apply_filters( 'edd_paylane_gateway_lang_dir', $lang_dir );

            // Traditional WordPress plugin locale filter
            $locale = apply_filters( 'plugin_locale', get_locale(), '' );
            $mofile = sprintf( '%1$s-%2$s.mo', 'edd-paylane-gateway', $locale );

            // Setup paths to current locale file
            $mofile_local   = $lang_dir . $mofile;
            $mofile_global  = WP_LANG_DIR . '/edd-paylane-gateway/' . $mofile;

            if( file_exists( $mofile_global ) ) {
                // Look in global /wp-content/languages/edd-paylane-gateway/ folder
                load_textdomain( 'edd-paylane-gateway', $mofile_global );
            } elseif( file_exists( $mofile_local ) ) {
                // Look in local /wp-content/plugins/edd-paylane-gateway/languages/ folder
                load_textdomain( 'edd-paylane-gateway', $mofile_local );
            } else {
                // Load the default language files
                load_plugin_textdomain( 'edd-paylane-gateway', false, $lang_dir );
            }
        }
    }
}


/**
 * The main function responsible for returning the one true EDD_PayLane_Gateway
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      EDD_PayLane_Gateway The one true EDD_PayLane_Gateway
 */
function edd_paylane_gateway_load() {
    if( ! class_exists( 'Easy_Digital_Downloads' ) ) {
        if( ! class_exists( 'EDD_Extension_Activation' ) ) {
            require_once EDD_PAYLANE_DIR . 'includes/class.extension-activation.php';
        }

        $activation = new EDD_Extension_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
        $activation = $activation->run;

        return EDD_PayLane_Gateway::instance();
    } else {
        return EDD_PayLane_Gateway::instance();
    }
}
add_action( 'plugins_loaded', 'edd_paylane_gateway_load' );
