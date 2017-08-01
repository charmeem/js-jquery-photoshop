<?php
/*
Widget to show the current class note.
Teachers (Group Admins) can change note for each group.
Shows the global note set in the widget settings if non-empty.
*/
class SchoolPress_Note_Widget extends WP_Widget
{
    public function __construct()
	{
    parent::__construct(
    'schoolpress_note',
    'Mubashir widget Note',
    array( 'description' => 'Note to Show on Group Pages' ));
    }
public function widget( $args, $instance )
{
    global $current_user;
    //saving a note edit?
    if ( !empty( $_POST['schoolpress_note_text'] ) && !empty( $_POST['class_id'] ) ) {
    //make sure this is an admin
     if(groups_is_user_admin($current_user->ID,intval($_POST['class_id']))){
        //should escape the text and possibly use a nonce
        update_option(
        'schoolpress_note_' . intval( $_POST['class_id'] ),
        $_POST['schoolpress_note_text']
        );
    }
    }
//look for a global note
$note = $instance['note'];
//var_dump($note);
//get class id for this group
// commenting out as bb is not used yet
//$class_id = bp_get_current_group_id();
//look for a class note
if ( empty( $note ) && !empty( $class_id ) ) {
    $note = get_option( "schoolpress_note_" . $class_id );
}
//display note
if ( !empty( $note ) ) {
    ?>
    <div id="schoolpress_note"><?php echo wpautop( $note );?></div>
<?php
//show edit for group admins
// Only applicable when bb plugin is activated
//if ( groups_is_user_admin( $current_user->ID, $class_id ) ) {
?>
<a id="schoolpress_note_edit_trigger">Edit</a>
<div id="schoolpress_note_edit" style="display: none;">
<form action="" method="post">
  <input type="hidden" name="class_id" value="<?php echo intval($class_id);?>" />
  <textarea name="schoolpress_note_text" cols="30" rows="5">
  <?php echo esc_textarea(get_option('schoolpress_note_'.$class_id));?>

  </textarea>
  <input type="submit" value="Sav" />
  <a id="schoolpress_note_edit_cancel" href="javascript:void(0);">Cancel</a>
</form>
</div>
<script>
    jQuery(document).ready(function() {
    jQuery('#schoolpress_note_edit_trigger').click(function(){
        jQuery('#schoolpress_note').hide();
        jQuery('#schoolpress_note_edit').show();
    });
    jQuery('#schoolpress_note_edit_cancel').click(function(){
        jQuery('#schoolpress_note').show();
        jQuery('#schoolpress_note_edit').hide();
    });
    });
</script>
<?php
//}
}
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
register_widget( 'SchoolPress_Note_Widget' );
} );
?>