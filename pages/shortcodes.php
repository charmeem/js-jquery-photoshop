<?php
/**
 * Table of Contents
 *
 * mmm          - a Sample 
 * contact_form - A Contact Form with email sending with validation and sanitation
 * list_cpt     - List Custom post types
 * list_cpt2     - List Custom post types
 * submission_form - Homework submission form
 *
 */

 
 
 /**
 * mmm- A Sample ShortCode
 */

 add_shortcode('mmm', 'test_shortcode');
 function test_shortcode()
 {
     ob_start();
	 ?>
	 <h2>Hello This is Mubashir, testing my short code </h2>
	 <?php
     $content = ob_get_contents();
	 ob_end_clean();
	 return $content;
}



/**
 * A Contact Form with validation and sanitation
 * This function is also implemented as Template Page in Schoolpress Theme Folder
 */


add_shortcode('contact_form', 'contact_form_shortcode');
function contact_form_shortcode()
{
    ob_start();
	
	html_code();
	
    return ob_get_clean();
	// or can use, 'return ob_get_clean'  instedd single liner
}

// Validating Form fields
function validate_form()
{
			if(isset($_POST['email'])){
                $email = sanitize_email( $_POST['email']);
				}
	        if(isset($_POST['cname'])){
                $cname = sanitize_text_field( $_POST['cname']);
				}
	        if(isset($_POST['phone'])){
            			$phone = sanitize_text_field( $_POST['phone'] );
						}
            if(isset($_POST['message'])){
            			$message = sanitize_text_field( $_POST['message'] );
						}
			if(isset($_POST['lname'])){
            			$lname = sanitize_text_field($_POST['lname']);  // HONEY POT
						}
			//NOTE: Above lines can be optimized as foreach array loop later
			$sendemail = !empty( $_POST['sendemail'] );
			
$errors = new WP_Error();  // utilizing built-in WP_Error Class 

if ( !empty( $sendemail )  && empty( $cname )) {
    $errors->add('name_error', 'Please fill in a valid name.');
    }

if ( !empty ( $sendemail )  && !empty ( $lname )) {
    $errors->add('cheater', 'Oye cheater, go and play somewhere else.');
    }

if ( !empty ( $sendemail ) && empty ( $email )) {
    $errors->add('email_error', 'Please fill in an email ID.');
    }

if ( !empty ( $sendemail ) && !is_email ( $email )) {
    $errors->add('email_error', 'Please fill in a valid email ID.');
    }

	
return $errors;

}

// Print messages if any
function print_message ()
{
global $errors;

	
if ( is_wp_error ( $errors ) && empty ( $errors->errors ) ) {
    echo '<div class="message">';
	echo '<p>Thanks for contacting us '.$_POST['cname'].' , a member of our team will contact you shortly</p>';
	echo '</div>';
	
	$_POST = '';
	
	} else {
	 if ( is_wp_error ( $errors ) && !empty ( $errors->errors ) ) {
		    $error_messages = $errors->get_error_messages();
			//var_dump($error_messages);
			foreach ($error_messages as $j => $message) {
			    echo '<div class="message '.$j.'">';
			    echo '<p> '.$message.' </p>';
	            echo '</div>';
			}
		}
	}
}
// Render html code for the contact form
function html_code()
{
global $cname, $lname, $email, $message, $phone;
?>
<div id="wrapper">
<div id="content">
<?php print_message(); //print message?> 
<form class="general" action="<?php the_permalink(); ?>" method="post">
<div class="form-row">
<label for="cname">Name</label>
<input type="text" name="cname" value="<?php echo esc_attr($cname);?>"/> 
<small class="red">* Required</small>
</div>
<div class="hidden">
<label for="lname">Last Name</label>
<input type="text" name="lname" value="<?php echo esc_attr($lname);?>"/>
<small class="red">LEAVE THIS FIELD BLANK</small>
</div>
<div class="form-row">
<label for="email">Email</label>
<input type="text" name="email" value="<?php echo esc_attr($email);?>"/>
<small class="red">* Required</small>
</div>
<div class="form-row">

<label for="phone">Phone</label>
<input type="text" name="phone" value="<?php echo esc_attr($phone);?>"/>
</div>
<div class="form-row">
<label for="message">Question or Comment</label>
<textarea class="textarea" id="message" name="message" rows="4" cols="55">
<?php echo esc_textarea( $message )?>
</textarea>
</div>
<div class="form-row">
<label for="sendemail">&nbsp;</label>
<input type="submit" id="sendemail" name="sendemail" value="Submit"/>
</div>
</form>

</div>
</div>
<?php
}

add_action('init', 'check_form');
function check_form()
{

	global $errors,$cname, $lname, $email, $message, $phone;
	
    if(isset($_POST['sendemail'])) {
    //var_dump($errors);
	
	
	$errors = validate_form();
	
	if (empty($errors->errors)) {
	        
			if(isset($_POST['email'])){
                $email = sanitize_email( $_POST['email']);
				}
	        if(isset($_POST['cname'])){
                $cname = sanitize_text_field( $_POST['cname']);
				}
	        if(isset($_POST['phone'])){
            			$phone = sanitize_text_field( $_POST['phone'] );
						}
            if(isset($_POST['message'])){
            			$message = sanitize_text_field( $_POST['message'] );
						}
			//NOTE: Above lines can be optimized as foreach array loop later
			
			$mailto = get_bloginfo( 'admin_email' );  //configured gmail account
            $mailsubj = "Contact Form Submission from " . get_bloginfo( 'name' );
            $mailhead = "From: mmufti@hotmail.com"; 
            // NOTE: 1. This parameter is needed to have mail send from localhost.
            //       2.Used harcoded value to avoid Fatal Error sentTo.

            $mailbody = "Name: " . $cname . "\n\n";
            $mailbody .= "Email: $email\n\n";
            $mailbody .= "Phone: $phone\n\n";
            $mailbody .= "Message:\n" . $message;

            // send email to us
            //// Using Wp mail plugin to utilize smtp mail server instead of mail() of Wp
            //// configured hotmail smtp in settings of wp plugin dashboard
            wp_mail( $mailto, $mailsubj, $mailbody, $mailhead);

// clear vars
$email = "";
$cname = "";
$phone = "";
$message = "";	
			
        } elseif (!empty($errors->errors)){
		    return $errors;
			}
		}	
	}


/**
 * list_cpt
 *
 * Displays list of Custom post types -submission- in this plug-in
 */

 add_shortcode('list_cpt', 'list_cpt_shortcode');
 function list_cpt_shortcode()
 {    
     global $wpdb;
	 $sqlQuery = "SELECT * FROM  $wpdb->posts
	 WHERE post_type = 'submission' 
     AND post_status = 'publish' LIMIT 10";
	 $assignments = $wpdb->get_results($sqlQuery);
	 foreach ( $assignments as $assignment) { ?>
	     <h3><?php echo $assignment->post_title;?></h3>
		 <?php echo apply_filters("the_content", $assignment->post_content);
	 }
 }
 
/**
 * list_cpt2
 *
 * Displays list of Custom post types -submission- in this plug-in
 */
add_shortcode('list_cpt2', 'list_cpt2_shortcode');
function list_cpt2_shortcode()
{    
     $gpt = get_post_types();
	 var_dump($gpt);
	 
     $mycustom_posts = get_posts( array (
	        'post_type' => 'any', 'numberposts' => 100));
			
	 foreach ( $mycustom_posts as $mycustom_post) { ?>
	     <h3><?php echo $mycustom_post->post_title . '--' . $mycustom_post->post_type;?></h3>
		 		 
		 <?php //echo apply_filters("the_content", $assignment->post_content);
	 }
 }
 
 
/**
 * submission_form 
 * Add submission form under the content of Assignment or any other CPT
 * 
 * NOTE: I have also added this functionality as a default Post content
 *       for CPT 'homework'. So this form will appear as default whenver
 *       new post type of homework is published. See ..theme/schoolpress/functions.php
 */
add_shortcode('submission_form', 'submission_form_shortcode');
function submission_form_shortcode()
{
   // This filter seems unnecessary here...more exploration required
   
   //add_filter( 'the_content', 'form_code', 999);
	//function form_code($post_content) 
	//{   
	    global $post, $submission_id, $content;
        // do this only for CPT Homework and 
        // if current user is logged in
        $current_user = wp_get_current_user();	
        //var_dump($current_user->ID);
//var_dump($post->post_type);		
	    if ( ! is_single() || $post->post_type != 'homework' || ! $current_user )
		    if(isset($post_content)) return $post_content;
		
		// Get from DB the submission to this homework assignmnet
		$submissions = get_posts( array(
		    'post_author'    => $current_user->ID, 
		    'posts_per_page' => '3',
		    'post_type'      => 'submission', 
		    'meta_key'       => '_submission_homework_id', 
		    'meta_value'     => $post->ID  
	        ));
			//var_dump($submissions);
		foreach ( $submissions as $submission ) {
		$submission_id = $submission->ID;
	    }
		
		// Process the form submission if the user hasn't already
	    if ( !$submission_id && 
			isset( $_POST['submit-homework-submission'] ) && 
			isset( $_POST['homework-submission'] ) ) {
		    
			$submission = $_POST['homework-submission'];
		    $post_title = $post->post_title; 
		    $post_title .= ' - Submission by ' . $current_user->display_name;	
			// Insert submission as a post into our submissions CPT.
	    	$args = array(
			'post_title'   => $post_title,
			'post_content' => $submission,
			'post_type'    => 'submission',
			'post_status'  => 'publish',
			'post_author'  => $current_user->ID
		     );
		    $submission_id = wp_insert_post( $args );
		    //var_dump($submission_id);
		    // add post meta to tie this submission post to the homework post
		    add_post_meta( $submission_id, '_submission_homework_id', $post->ID);
		    $message = __( 
			    'Homework submitted and is awaiting review.', 'schoolpress');
		    $message = '<div class="message">' . $message . '</div>';
		   // drop message before the filtered $content variable
		   $content = $message . $post_content;
		   
		   $message = sprintf( __( 
			'Click %s here %s to view your submission.','schoolpress' ), 
			'<a href="' . get_permalink( $submission_id ) . '">',
			'</a>' );
		   $message = '<div class="link">' . $message . '</div>';
		   $content .= $message;
		
		} elseif( $submission_id ) {
		// Add a link to the user's submission
	    
		//$main_post_id = $post->ID;
		//echo $main_post_id;
		//var_dump($submission_id);
		echo 'This assignment has been already submitted.';
//$perm = get_permalink($submission_id);
//var_dump($perm);
//var_dump($submission_id);
		$message = sprintf( __( 
			'Click %s here %s to view your submission.','schoolpress' ), 
			'<a href="' . get_permalink( $submission_id ) . '">',
			'</a>' );
		$message = '<div class="link">' . $message . '</div>';
		$content .= $message;
		
	// Add a form after the $content variable being filtered.
	
	} else {
	
	    ob_start();
		
		if(isset($post_content))
		echo $post_content;
		//$main_post_id = $post->ID;
		//echo $main_post_id;
		?>
		<p>
		<?php _e( 'Submit your Homework below!', 'schoolpress' );?>
		</p>
		<form method="post">
		<?php 
		wp_editor( '', 'homework-submission', array(
    		'media_buttons' => false ) 
	    	);
		?>
		<input type="submit" name="submit-homework-submission" value="Submit" />
		</form>
		<?php 
		$form = ob_get_contents();
		ob_end_clean();
		$content .= $form;
	}
	
	return $content;
	//}
}
 
 