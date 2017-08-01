<?php
add_action( 'init', 'register_sp_widget' );
function register_sp_widget() {
    register_sidebar(array(
        'name' => 'Assignment Pages Sidebar',
        'id' => 'schoolpress_assignment_pages',
        'description' => 'Sidebar used on assignment pages.',
        'before_widget' => '',
	    'after_widget' => '',
        'before_title' => '',
        'after_title' => ''
    ));
}