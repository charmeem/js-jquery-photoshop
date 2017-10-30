<?php
// reference the php library we downloaded from GitHub
require (dirname(__FILE__) . "/../lib/twitteroauth/vendor/autoload.php");
use Abraham\TwitterOAuth\TwitterOAuth;

//session_start();

// include config file
$config = require_once (dirname(__FILE__) . "/twitter_config.php");

//add_action( 'init', 'twitter_post' );
//function twitter_post() {
// Create TwitterOAuth object and pass in Twitter credentials.
$toa = new TwitterOAuth( $config['consumer_key'], $config['consumer_secret'] );
// Request token of application
$request_token = $toa->oauth('oauth/request_token', ['oauth_callback' => $config['url_callback']]);
// Throw exception in case of error_get_last
if ( $toa->getLastHttpCode() != 200) {
    throw new \Exception('There was problem performing this action');
    }

// Save fetched token in session
$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

// Generate url  to make request to authorize our application
$url = $toa->url('oauth/authorize', ['oauth_token' => $request_token['oauth_token']]); 	
var_dump($url);

// finally redirect
//header("Location:".$url); 
// or usee below
wp_redirect($url);

?>
<!--
<a href = "<?php echo $url ?>">Press Me </a>
-->
<?php
//exit();
//}
