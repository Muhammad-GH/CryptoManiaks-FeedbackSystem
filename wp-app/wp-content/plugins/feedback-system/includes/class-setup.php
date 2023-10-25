<?php
/**
 * Setup Class File
 *
*/

namespace FeedbackSystem\Includes;

use FeedbackSystem\Includes\User;
use FeedbackSystem\Includes\Vote;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Setup {

    private $user_obj;
    private $vote_obj;
    private $plugin_name;
	private $version;

	public function __construct() {
        // Path to the plugin directory
        if ( !defined( 'FEEDBACK_SYSTEM_PLUGIN_DIR' ) ) {
            define( 'FEEDBACK_SYSTEM_PLUGIN_DIR', trailingslashit( plugin_dir_path( dirname( __FILE__ ) ) ) );
        }

        // Path to the plugin directory url
        if ( ! defined( 'FEEDBACK_SYSTEM_PLUGIN_URL' ) ) {
			define( 'FEEDBACK_SYSTEM_PLUGIN_URL', trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) );
		}

        // Define Constants

        if ( !defined( 'FEEDBACK_SYSTEM_VERSION' ) ) {
            define( 'FEEDBACK_SYSTEM_VERSION', '1.0.0' );
        }

        if ( !defined( 'FEEDBACK_SYSTEM_NAME' ) ) {
            define( 'FEEDBACK_SYSTEM_NAME', 'feedback-system' );
        }

        $this->plugin_name = FEEDBACK_SYSTEM_NAME;
        $this->version = FEEDBACK_SYSTEM_VERSION;
        $this->user_obj = new User();
        $this->vote_obj = new Vote();
    }

	public function init() {
        // Add Javascript and CSS for front-end display
        add_action( 'wp_enqueue_scripts', array($this, 'enqueue') );

        // Add shortcode for front-end display
        add_shortcode( 'feedback_system', array($this->vote_obj, 'vote_html') );

        add_filter( 'the_content', array($this, 'content_after_post'), 11 );

        add_action( 'wp_ajax_nopriv_feedback_action_post', array($this->vote_obj, 'vote_action_post') );
        add_action( 'wp_ajax_feedback_action_post', array($this->vote_obj, 'vote_action_post') );

        // Add Metabox
        add_action( 'load-post.php', [$this, 'add_metaboxes'] );
        add_action( 'load-post-new.php', [$this, 'add_metaboxes'] );

        $this->user_obj->init();
	}

    /**
     * enqueuing a JavaScript file and a CSS file for use on the front end display
     */
    public function enqueue() {
        wp_enqueue_style( $this->plugin_name, FEEDBACK_SYSTEM_PLUGIN_URL . "public/css/style.css", null, $this->version );

        wp_enqueue_script( $this->plugin_name, FEEDBACK_SYSTEM_PLUGIN_URL . "public/js/script.js", array('jquery'), $this->version, true );

        wp_localize_script( $this->plugin_name, 'feedback_system', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce("feedback_system_nonce"),
            'post_id' => get_the_ID()
        ));
    }

     // Appends Voting Form to bottom of the_content()
    public function content_after_post($content) {
        if( is_single() ){
            $content .= do_shortcode( '[feedback_system]' ); 
        }
    
        return $content;
    }

    // Add Voting Metabox to Pages/Posts
    public function add_metaboxes()
    {
        add_meta_box(
            $this->plugin_name,
            esc_html__( 'Post Votes', $this->plugin_name ),
            [$this->vote_obj, 'view_metabox'], null, 'side'       
        );
    }
}