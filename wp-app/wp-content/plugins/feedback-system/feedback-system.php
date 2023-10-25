<?php
session_start();
/**
 * Plugin Name: Feedback Voting System for posts
 * Description: Engage your website visitors and gather valuable feedback with FeedbackSystem, the ultimate visitor voting system for WordPress.
 * Version: 1.0
 * Author: Muhammad Usama Mazhar
*/

/**
 * Define your namespaces here by use keyword
*/

use FeedbackSystem\Includes\Setup;

/**
 * If this file is called directly, then abort execution.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'FeedbackSystem' ) ) {
    final class FeedbackSystem {

        /**
         * Instance property of FeedbackSystem Class.
         *
         * @access private
         * @var    FeedbackSystem $instance create only one instance from plugin primary class
         * @static
        */
        private static $instance;

        protected $setup_object;

        /**
         * Constructor
        */
        public function __construct() {
            /*Define Autoloader class for plugin*/
            $autoloader_path = 'includes/class-autoloader.php';
            /**
             * Include autoloader class to load all of classes inside this plugin
             */
            require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . $autoloader_path;
        }

        /**
         * Create an instance from FeedbackSystem class.
         *
         * @access public
         * @since  1.0.2
         * @return FeedbackSystem
         */
        public static function instance() {
            if ( is_null( ( self::$instance ) ) ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Load Core plugin class.
         *
         * @access public
        */
        public function run_feedback_system() {
            $this->setup_object = new Setup();
            $this->setup_object->init();
        }
    }

    $feedback_system = FeedbackSystem::instance();
    $feedback_system->run_feedback_system();
}
?>