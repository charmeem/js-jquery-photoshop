<?php
require (dirname(__FILE__) . "/../lib/twitteroauth/vendor/autoload.php");
use Abraham\TwitterOAuth\TwitterOAuth;

//session_start();


/*
Widget to show the current class note.
Teachers (Group Admins) can change note for each group.
Shows the global note set in the widget settings if non-empty.
*/
class Twitter_Widget extends WP_Widget
{
    public function __construct()
	{
	

    parent::__construct(
    'twitter_widget',
    'Mubashir twitter widget',
    array( 'description' => 'Showing twitter feeds' ));
    }
public function widget( $args, $instance )
{
    // reference the php library we downloaded from GitHub
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
//var_dump($url);
// finally redirect
//header("Location:".$url); 
// or usee below

wp_redirect($url);
exit();
}


public function form( $instance ) 
{
//var_dump($instance);
    if ( isset( $instance['note'] ) )
        $note = $instance['note'];
    else
        $note = "";
    ?>
    <p>
    <label for="<?php echo $this->get_field_id( 'note' ); ?>">
    <?php _e( 'Note:' ); ?>
    </label>
    <textarea id="<?php echo $this->get_field_id( 'note' ); ?>" name="<?php echo $this->get_field_name( 'note' ); ?>">
    <?php echo esc_textarea( $note );?>
    </textarea>
    </p>
    <?php
}

public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['note'] = $new_instance['note'];
	//var_dump($instance);
	return $instance;
}
}
add_action( 'widgets_init', function() {
register_widget( 'Twitter_Widget' );
} );
?>