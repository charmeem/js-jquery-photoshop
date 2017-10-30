<?php
/*Plugin Name:  */

// reference the php library we downloaded from GitHub
require (dirname(__FILE__) . "/../lib/twitteroauth/vendor/autoload.php");
use Abraham\TwitterOAuth\TwitterOAuth;

//session_start();

// include config file
$config = require_once (dirname(__FILE__) . "/twitter_config.php");

$oauth_verifier = filter_input(INPUT_GET, 'oauth_verifier');
 
if (empty($oauth_verifier) ||
    empty($_SESSION['oauth_token']) ||
    empty($_SESSION['oauth_token_secret'])
) {
    // something's missing, go and login again
    header('Location: ' . $config['url_login']);
}

// connect with application token
$connection = new TwitterOAuth(
    $config['consumer_key'],
    $config['consumer_secret'],
    $_SESSION['oauth_token'],
    $_SESSION['oauth_token_secret']
);
 
// request user token
$token = $connection->oauth(
    'oauth/access_token', [
        'oauth_verifier' => $oauth_verifier
    ]
);

//you've got the user token stored in the $token variable
//We can use this token to act on behalf of the user's account7

//To connect to the Twitter API with the user token:
$twitter = new TwitterOAuth(
    $config['consumer_key'],
    $config['consumer_secret'],
    $token['oauth_token'],
    $token['oauth_token_secret']
);

/* Search tweets
$q = 'RESTful';
$search = $twitter->get( 'search/tweets', array( 'q' => $q ) );
echo "<pre>";
print_r( $search );
echo "</pre>";
//////exit();
*/

//Or, Create new Tweet
$status = $twitter->post(
    "statuses/update", [
        "status" => "Thank you @nedavayruby, now I know how to authenticate users with Twitter because of this tutorial https://goo.gl/N2Znbb"
    ]
);
 
echo ('Created new status with #' . $status->id . PHP_EOL);
