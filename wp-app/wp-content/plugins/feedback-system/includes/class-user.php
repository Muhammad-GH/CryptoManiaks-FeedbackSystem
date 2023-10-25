<?php
/**
 * User Class File
 *
*/

namespace FeedbackSystem\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class User {
	public function init() {
        $this->set_user_cookie();
	}

    // Check if cookie exists. If not then set new cookie.
    public function set_user_cookie() {
        if( !isset($_COOKIE['visited']) ) {
            list($usec, $sec) = explode(" ", microtime()); // Micro time!
            $expire = time() +60 * 60 * 24 * 30; // expiration after 30 day
            setcookie("visited", "".md5("".$sec.".".$usec."")."", $expire, "/", "", "0"); 
        }
    }

    // Get IP address of user
    public function get_user_ip() { 
        if ( !empty($_SERVER['HTTP_CLIENT_IP']) ) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    // get user cookie
    public function get_user_cookie() {
        return $_COOKIE['visited'];
    }

    // create user unique access token based on ip and cookie
    public function get_user_token() {
        $ip = $this->get_user_ip();
        $user_cookie = $this->get_user_cookie();
        return md5( $ip . '_' . $user_cookie );
    }

}