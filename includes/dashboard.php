<?php
/**
 * Remove all default WordPress dashboard widgets
 */
 function sp_remove_dashboard_widgets()
{
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // 'at a glance'
	remove_meta_box('dashboard_activity', 'dashboard', 'normal');  //'activity'
	remove_meta_box('dashboard_quick_press', 'dashboard', 'side');  // 'quick Draft'
    remove_meta_box('dashboard_primary', 'dashboard', 'side');    //'news'
    //remove_meta_box('dashboard_secondary', 'dashboard', 'side');
	//remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    //remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
    //remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
	//remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
	//remove_meta_box('dashboard_browser_nag', 'dashboard', 'normal');
}
add_action('wp_dashboard_setup', 'sp_remove_dashboard_widgets');

/*
Add dashboard widgets
*/
function sp_add_dashboard_widgets() {
    wp_add_dashboard_widget(
    'schoolpress_assignments',
    'Assignments',
	'sp_assignments_dashboard_widget_configuration' , // control call back
    'sp_assignments_dashboard_widget'  // call back
    
    );
}
add_action( 'wp_dashboard_setup', 'sp_add_dashboard_widgets' );

/**
 * Call back function
 */

function sp_assignments_dashboard_widget() {
    $options = get_option( "assignments_dashboard_widget_options", array() );
    //var_dump($options);
	
	// get taxonomies
	//$subjects = get_terms('subject');
	//var_dump($subjects);
	
	if ( !empty( $options['course_id'] ) ) {
        $group = groups_get_group( array(
        'group_id'=>$options['course_id']
        ) );
    }

    if ( !empty( $group ) ) {
        echo "Showing assignments for class " .
        $group->name . ".<br />...";

    //get assignments for this group and list their status
    } else {
        echo "Showing all assignments.<br />...";
        //get all assignments and list their status
       }
}

//configuration function
function sp_assignments_dashboard_widget_configuration() {

    $subjects = array ( "php", "wordpress", "javascript");
	//var_dump($subjects);
    //get old settings or default to empty array
    //$options = get_option( "assignments_dashboard_widget_options", array() );
    //saving options?
    if ( isset( $_POST['assignments_dashboard_options_save'] ) ) {
    //get course_id
       $options['subject'] = $_POST['assignments_dashboard_course_id'];
       var_dump($options);       
	   //save it
        //update_option( "assignments_dashboard_widget_options", $options );
		
		// Output Homework posts 
	$hw_posts = get_posts( array (
	        'post_type' => 'homework', 'numberposts' => 100));
			
	 foreach ( $hw_posts as $hw_post) { ?>
	     <li> <?php echo $hw_post->post_title ; ?> </li>
		 <?php
	}
    }
	
	
	
	//show options form
   // $groups = groups_get_groups( array( 'orderby'=>'name', 'order'=>'ASC' ) );
    ?>
    <p>Choose a class/group to show assignments from.</p>
    <div class="feature_post_class_wrap">
    <label>Class</label>
    <select name="assignments_dashboard_course_id">
	<?php foreach ( $subjects as $subject ) { ?>
    <option value= " <?php echo $subject; ?> "  <?php selected( $subjects, $subject );?>>
    <?php echo $subject; ?>
    </option>
    <?php 
	}
    ?>
    </select>
    </div>
    <input type="hidden" name="assignments_dashboard_options_save" value="1" />
	<input type="submit" name="submit_me"  />
    <?php 
	
}