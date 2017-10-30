<?php
/*
Plugin Name: SchoolPress
Plugin URI: http://schoolpress.me
Description: Core SchoolPress Plugin
Version: .6
Author: Jason Coleman, Brian Mesenlehner
*/
//version constant
define("SP_VERSION", ".6");
define("PMPRO_NETWORK_MANAGE_SITES_SLUG", "manage-sites");
/*
	Includes
*/
define("SP_DIR", dirname(__FILE__));
require_once(SP_DIR . "/includes/functions.php");			//misc functions used by the plugin
require_once(SP_DIR . "/includes/widgets.php");			//my custom widgets @mmm
//require_once(SP_DIR . "/includes/twitter_widget.php");			//my custom twitter widgets @mmm

//require_once(SP_DIR . "/includes/sidebars.php");			//my custom sidebars @mmm
//require_once(SP_DIR . "/includes/dashboard.php");			//my custom dashboard widgets @mmm
//require_once(SP_DIR . "/includes/xml_rpc.php");			//my custom xml-rpc file @mmm

//require_once(SP_DIR . "/lib/twitteroauth/autoload.php"); //including twitterAPI/twitteroauth
//require_once(SP_DIR . "/includes/twitter_api_old.php");            //Code for my twitter app. file

require_once(SP_DIR . "/scheduled/crons.php");//crons for expiring members, sending expiration emails, etc

//Loading Classes
require_once(SP_DIR . "/classes/class.SPClass.php");		//class for Class
require_once(SP_DIR . "/classes/class.SPAssignment.php");	//class for Assignments
require_once(SP_DIR . "/classes/class.SPSubmission.php");	//class for Assignments
require_once(SP_DIR . "/classes/class.SPStudent.php");		//class for Student
require_once(SP_DIR . "/classes/class.SPTeacher.php");		//class for Teacher
require_once(SP_DIR . "/classes/class.SPSchool.php");		//class for School

//Loading Page Templates
require_once(SP_DIR . "/pages/my_classes.php");
require_once(SP_DIR . "/pages/edit_class.php");
require_once(SP_DIR . "/pages/edit_assignment.php");
require_once(SP_DIR . "/pages/shortcodes.php");                //shortcodes file

//Activation
function sp_activation()
{
	add_action('init', array('SPClass', 'createVisibilities'), 20);
}
register_activation_hook(__FILE__, 'sp_activation');

// Enqueue jQuery
function sp_enqueue_scripts()
{
    wp_enqueue_script('jquery');
}
add_action('init', 'sp_enqueue_scripts');