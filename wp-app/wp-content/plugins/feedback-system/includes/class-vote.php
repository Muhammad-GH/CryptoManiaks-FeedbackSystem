<?php
/**
 * Vote Class File
 *
*/

namespace FeedbackSystem\Includes;

use FeedbackSystem\Includes\User;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Vote {

    private $user_obj;

	public function __construct() {
        $this->user_obj = new User();
    }

    // loading voting system html in front-end
    public function vote_html() {
        // Process output
        ob_start();
        include( FEEDBACK_SYSTEM_PLUGIN_DIR . "templates/feedback-system-display.php" );
        $html = ob_get_clean();
        return $html;
    }

    // Returns an array of Yes and No vote percentages ( [ "Yes" => $yes_value_percent, "No" => $no_value_percent ] )
    public function get_vote_percentages($post_id = "")
    {
        if(!$post_id) {
            $post_id = get_the_ID();
        }

        $yes_votes = $this->has_vote( $post_id, 'yes' );
        $no_votes = $this->has_vote( $post_id, 'no' );
        
        // Percentage Calculation
        if( $yes_votes || $no_votes )
        {
            $total_votes = ( $yes_votes + $no_votes );
            $yes_votes = $yes_votes ? round(($yes_votes / $total_votes) * 100) : 0;
            $no_votes = $no_votes ? round(($no_votes / $total_votes) * 100) : 0;
        }

        return [ 
            "yes" => $yes_votes,
            "no" => $no_votes
        ]; 
    }

    // AJAX Callback called when Vote Button is Clicked
    public function vote_action_post() {
        if ( !wp_verify_nonce( $_POST['nonce'], "feedback_system_nonce")) {
            exit("No naughty business please");
        }

        $feedback_action = $_POST["feedback_action"];
        $post_id = $_POST["post_id"];

        if($feedback_action && $post_id) {
            $this->save_vote($post_id, $feedback_action);
        }

        $user_token = $this->user_obj->get_user_token();
        $is_user_voted = $this->is_user_voted($post_id, $user_token);

        $response = [];
        $response['success'] = true;
        $response['votes'] = $this->get_vote_percentages($post_id);
        $response['is_user_voted'] = $is_user_voted;
        echo json_encode( $response, true );
        wp_die();
    }

    // Save the vote and token in the database if the user has not voted
    public function save_vote( $post_id, $feedback_action ) {
        $user_token = $this->user_obj->get_user_token();
        $is_user_voted = $this->is_user_voted($post_id, $user_token);

        if( !$is_user_voted ) {
            $vote_val = $this->has_vote( $post_id, $feedback_action );
            $vote_val += 1;

            $this->add_vote( $post_id, $feedback_action, $vote_val );
            $this->add_user_token( $post_id, $user_token );
        }
    }

    // Check if the user's token exists for a particular post
    public function is_user_voted( $post_id, $user_token ) {
        $tokens = get_post_meta( $post_id, "user_token" );
        return in_array( $user_token, $tokens );
    }

    // Check if post has votes and returns the value or 0
    public function has_vote( $post_id, $vote_str )
    {
        $vote = get_post_meta( $post_id, "vote_$vote_str", true );
        return $vote ? $vote : 0;
    }

    // Save the yes/no vote in the post meta data.
    public function add_vote( $post_id, $vote_str, $vote_val )
    {
        $_SESSION["vote_" . $post_id] = $vote_str;
        update_post_meta($post_id, "vote_$vote_str", $vote_val);
    }

    // save user token in the post meta
    public function add_user_token( $post_id, $user_token )
    {
        add_post_meta($post_id, "user_token", $user_token);
    }

    // Returns an array of Yes and No votes ( [ "Yes" => $yes_value, "No" => $no_value ] )
    public function get_votes()
    {
        $yes_votes = $this->has_vote( get_the_ID(), 'yes' );
        $no_votes = $this->has_vote( get_the_ID(), 'no' );

        return [ 
            "yes" => $yes_votes ? $yes_votes : 0, 
            "no" => $no_votes ? $no_votes : 0
        ];
    }

    // loading html for post in admin
    public function view_metabox() {
        // Process output
        ob_start();
        include( FEEDBACK_SYSTEM_PLUGIN_DIR . "templates/feedback-system-admin-display.php" );
        $html = ob_get_clean();
        echo $html;
    }
}