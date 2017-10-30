<?php
/*Plugin Name: BWAwWP - Twitter */
// reference the php library we downloaded from GitHub

require (dirname(__FILE__) . "/../lib/twitteroauth/vendor/autoload.php");
use Abraham\TwitterOAuth\TwitterOAuth;

// Copy over credentials from my Twitter app 'WP_REST'

define( 'C_KEY', 'MuZ5dbKL7SO5Ifk6tB0gGL3oG' );
define( 'C_SECRET', 'LzBg7hPVPS5gYNow9OWNnc3bsElXEGMG2CCXdOGpilXPXvDwaO' );
define( 'A_TOKEN', '896731622493564928-YNuWOXQ16xQ4XVjwK9CWYm2kL6xKhBj' );
define( 'A_TOKEN_SECRET', 'cscflpEeg9OT2Z4FuQge7ckrBN3WdNCaIHg6O854i7aOV' );

add_action( 'init', 'sp_twitter_search' );
function sp_twitter_search() {
    
    // call TwitterOAuth and pass in Twitter credentials.
    $toa = new TwitterOAuth( 'C_KEY', 'C_SECRET', 'A_TOKEN', 'A_TOKEN_SECRET' );
	//var_dump($toa);
	// Solution for expired or invalid token.. taken from stackoverflow
	//$access_token = $toa->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
	//var_dump($access_token);
    //$toa = new TwitterOAuth('C_KEY', 'C_SECRET', $access_token['A_TOKEN'], $access_token['A_TOKEN_SECRET']);
    
	// call the search tweets method
	// our search term
	/*
    $q = 'RESTful';
    $search = $toa->get( 'search/tweets', array( 'q' => $q ) );
    echo "<pre>";
    print_r( $search );
    echo "</pre>";
    exit();
	*/
	
	$result = $toa->get('statuses/user_timeline', ["count" => 25, "exclude_replies" => true]);
	print_r( $result);
}