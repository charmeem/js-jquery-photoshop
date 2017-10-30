<?php
/*
	Helper functions for SchoolPress.
*/

/*
	Set the sp_msg and sp_msgt globals used to convey error messages/etc.
*/
function sp_setMessage($msg, $msgt = "")
{
	global $sp_msg, $sp_msgt;
	$sp_msg = $msg;
	$sp_msgt = $msgt;
}

/*
	Show the sp_msg value in a div.
*/
function sp_showMessage($msg = NULL, $msgt = NULL)
{		
	global $sp_msg, $sp_msgt;

	if(!empty($msg) || !empty($msgt))
		sp_setMessage($msg, $msgt);
		
	if(!empty($sp_msg))
	{
	?>
	<div class="pmpro_message pmpro_<?php echo esc_attr($sp_msgt);?>"><?php echo $sp_msg;?></div>
	<?php
	}
}

/*
	Convert a string of emails (e.g. "jason@strangerstudios.com, jason@paidmembershipspro.com") into an array.
*/
function sp_convertEmailStringToArray($emails)
{
	//swap commas and semi-colons for new lines
	$emails = str_replace(array(",", ";"), "\n", $emails);
	
	//convert to array
	$emails = explode("\n", $emails);
	
	//remove trailing spaces and make it all lowercase
	$remails = array();
	foreach($emails as $email)
		$remails[] = strtolower(trim($email));
		
	//remove any dupes
	$remails = array_unique($remails);
	
	return $remails;
}

/**
 * From PMPro bbPress: https://github.com/strangerstudios/pmpro-bbpress/blob/master/pmpro-bbpress.php#L122
 * Function to tell if the current forum, topic, or reply is a subpost of the forum_id passed.
 * If no forum_id is passed, it will return true if it is any forum, topic, or reply.
 */
if(!function_exists('pmpro_bbp_is_forum'))
{
	function pmpro_bbp_is_forum( $forum_id = NULL ) {
		global $post;

		if(bbp_is_forum($post->ID))
		{		
			if(!empty($forum_id) && $post->ID == $forum_id)
				return true;
			elseif(empty($forum_id))
				return true;
			else
				return false;
		}
		elseif(bbp_is_topic($post->ID))
		{		
			if(!empty($forum_id) && $post->post_parent == $forum_id)
				return true;
			elseif(empty($forum_id))
				return true;
			else
				return false;
		}
		elseif(bbp_is_reply($post->ID))
		{		
			if(!empty($forum_id) && in_array($forum_id, $post->ancestors))
				return true;
			elseif(empty($forum_id))
				return true;
			else
				return false;
		}
		else
			return false;
	}
}

/**
 * Ajax username server side code
 * The client side ajax code is included in mm-signup-user-form in theme directory
 */
 
//detect AJAX request for check_username
function wp_ajax_check_username() 
{
    global $wpdb;
	$username = $_REQUEST['username'];
	$taken = $wpdb->get_var( "
        SELECT user_login
        FROM $wpdb->users
        WHERE user_login = '" . esc_sql( $username ) . "' LIMIT 1"
    );
	
    if ( $taken )
        echo '1'; //taken
	else
        echo '2'; //available
}
add_action( 'wp_ajax_check_username', 'wp_ajax_check_username' );
//add_action( 'wp_ajax_nopriv_check_username', 'wp_ajax_check_username' );	


/**
 * Theme my Login
 * Changing Action Links
 *
function tml_action_url( $url, $action, $instance ) {
	//var_dump($action);
	if ( ('register' && 'lostpassword') == $action ) {
		//echo "SORRRYYY";		
		$url = plugins_url() . '/theme-my-login/templates/ms-signup-user-form.php' ;
	    return $url;
		}
	
	elseif ( 'login' == $action ) {
		//echo "SORRR";
	    $url = get_stylesheet_directory() . '/login-form.php' ;
		
		return $url;
	}
	elseif ( 'logout' == $action ) {
		$url = get_stylesheet_directory() . '/login-form.php';
	    return $url;
	}
	else {echo "SORRRYYY!!!";
	
	}
	      
	
}
add_filter( 'tml_action_url', 'tml_action_url', 10, 3 );
*/

//echo count($assignment->submissions);

//echo ($_SERVER['REQUEST_URI']);
//echo ABSPATH; 
//$one = get_stylesheet_directory();
//$two = get_stylesheet_directory_uri();
//$three = get_template_directory();
//$for = plugin_dir_url(__FILE__);


//var_dump($one);
//var_dump($two);
//var_dump($three);
//var_dump($for);