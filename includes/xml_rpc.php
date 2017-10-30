<?php

/**
 * XMP-RPC Setup
 *
 */
 
add_action( 'init', 'wds_include_IXR' );
function wds_include_IXR() {
    // You need to include this file in order to use the IXR class.
    require_once ABSPATH . 'wp-includes/class-IXR.php';
    global $xmlrpc_url, $xmlrpc_user, $xmlrpc_pass;
    // Another WordPress site you want to push and pull data from
    $xmlrpc_url = 'http://localhost/wordpress/xmlrpc.php';
    $xmlrpc_user = 'mmufti'; // Hope you're not using "admin" ;)
    $xmlrpc_pass = 'pw'; // Really hope you're not using "password" ;)
}

/*XML-RPC getUsersBlogs
function bwawwp_xmlrpc_getUsersBlogs() {
    global $xmlrpc_url, $xmlrpc_user, $xmlrpc_pass;
    $rpc = new IXR_CLIENT( $xmlrpc_url );
    // returns all blogs in a multisite network
    $rpc->query( 'wp.getUsersBlogs', $xmlrpc_user, $xmlrpc_pass );
    echo '<h1>Blogs</h1>';
    echo '<pre>';
    print_r( $rpc->getResponse() );
    echo '</pre>';
    exit();
}
add_action( 'init', 'bwawwp_xmlrpc_getUsersBlogs', 999 );
*/

/*XML-RPC getPosts
function bwawwp_xmlrpc_getPosts() {
    global $xmlrpc_url, $xmlrpc_user, $xmlrpc_pass;
    $rpc = new IXR_CLIENT( $xmlrpc_url );
	 
	 // returns all posts of post post_type
    $rpc->query( 'wp.getPosts', 0, $xmlrpc_user, $xmlrpc_pass );
    echo '<h1>posts</h1>';
    echo '<pre>';
    print_r( $rpc->getResponse() );
    echo '</pre>';
    exit();
     	
	
	// returns all posts of page post_type (or any specific post type)
    $filter = array( 'post_type' => 'page' );
    $rpc->query( 'wp.getPosts', 0, $xmlrpc_user, $xmlrpc_pass, $filter );
    echo '<h1>pages</h1>';
    echo '<pre>';
    print_r( $rpc->getResponse() );
    echo '</pre>';
}
add_action( 'init', 'bwawwp_xmlrpc_getPosts', 999 );
*/

/* Add new post via XML-RPC
function bwawwp_xmlrpc_newPost() {
    global $xmlrpc_url, $xmlrpc_user, $xmlrpc_pass;
    $rpc = new IXR_CLIENT( $xmlrpc_url );
    // create an array with post data
    $content = array(
        'post_title' => 'Mubashir new Post with XML-RPC'
    );
    $rpc->query( 'wp.newPost', 0, $xmlrpc_user, $xmlrpc_pass, $content );
    $post_id = $rpc->getResponse();
    echo '<h1>New Post ID: '. $post_id .'</h1>';
    exit();
}
add_action( 'init', 'bwawwp_xmlrpc_newPost', 999 );
*/

// Get post Authors via XML-RPC
/* Problem: ONly getting one Author???
function bwawwp_xmlrpc_newPost() {
    global $xmlrpc_url, $xmlrpc_user, $xmlrpc_pass;
    $rpc = new IXR_CLIENT( $xmlrpc_url );
    
	$rpc->query( 'wp.getAuthors', 2 , $xmlrpc_user, $xmlrpc_pass);
    echo '<h1>Authors</h1>';
    $authors = $rpc->getResponse();
	//print_r ($authors);
	foreach($authors as $author){
	    echo '<pre>';
	    echo 'Author Name = ';
	    print_r ($author['display_name']);
        echo '</pre>';
	}
    exit();
}
add_action( 'init', 'bwawwp_xmlrpc_newPost', 999 );
*/

//Post type
function bwawwp_xmlrpc_getPostTypes() {
global $xmlrpc_url, $xmlrpc_user, $xmlrpc_pass;
    $rpc = new IXR_CLIENT( $xmlrpc_url );
    //$filter = array( 'public' => 1 );
    $rpc->query( 'wp.getPostTypes', 0 , $xmlrpc_user, $xmlrpc_pass );
    $response = $rpc->getResponse();
    echo '<h1>Post Types</h1>';
    echo '<pre>';
    print_r( $response );
    echo '</pre>';
    exit();
}
add_action( 'init', 'bwawwp_xmlrpc_getPostTypes', 999 );