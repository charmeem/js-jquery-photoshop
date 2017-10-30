<?php  /**
 * Layers Twitter Widget
 *
 * This file is used to register and display the Layers widget.
 * http://docs.layerswp.com/development-tutorials-layers-builder-widgets/
 * 
 * @package Layers
 * @since Layers 1.0.0
 */
if( !class_exists( 'Layers_Twitter_Widget' ) && class_exists( 'WP_Widget' ) ) {
// http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#widget-class
class Layers_Twitter_Widget extends WP_Widget {

    /**
    *  1 - Widget construction
    * http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#1-widget-construction
    */
    public function __construct(){
        $this->widget_title = __( 'Tweets' , 'layerswp' );
        $this->widget_id = 'tweets';
        $this->post_type = '';
        $this->taxonomy = '';
        $this->checkboxes = array();

        /* Widget settings. */ 
        $widget_ops = array( 
              'classname' => 'obox-layers-' . $this->widget_id .'-widget', 
              'description' => __( 'This widget is used to display your Tweets', 'layerswp')
        );

        /* Widget control settings. */ 
        $control_ops = array( 
              'width' => '660', 
              'height' => NULL, 
              'id_base' => 'layers-widget-' . $this->widget_id 
        );

        /* Create the widget. */ 

        parent::__construct( 'layers' . '-widget-' . $this->widget_id , $this->widget_title, $widget_ops, $control_ops );

        /* Setup Widget Defaults */
        $this->defaults = array (
            'title' => __( 'Twitter Feed', 'layerswp' ),
            'excerpt' => __( 'Display a list of your most recent tweets', 'layerswp' ),
            'twitter_username' => 'skizzar_sites',
            'no_of_tweets' => 3,
            'design' => array(
                'layout' => 'layout-boxed',
                'textalign' => 'text-left',
                'background' => array(
                    'position' => 'center',
                    'repeat' => 'no-repeat'
                ),
                'fonts' => array(
                    'align' => 'text-left',
                    'size' => 'medium',
                    'color' => NULL,
                    'shadow' => NULL
                )
            )
        );

    } // END main function

    /**
    *  2 - Widget form
    * http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#2-widget-form
    * We use regulage HTML here, it makes reading the widget much easier 
    * than if we used just php to echo all the HTML out.
    * 
    */
    function form( $instance ){

    // $instance Defaults
        $instance_defaults = $this->defaults;

        // If we have information in this widget, then ignore the defaults
        if( !empty( $instance ) ) $instance_defaults = array();

        // Parse $instance
        $instance = wp_parse_args( $instance, $instance_defaults );

        extract( $instance, EXTR_SKIP );

        // Design Bar Components
        $design_bar_components = apply_filters(
              'layers_' . $this->widget_id . '_widget_design_bar_components' ,
                  array(
                    'layout',
                    'fonts',
                    'background',
                    'advanced'
                  )
        );

        // Instantiate the Deisgn Bar
        $this->design_bar(
            'side', // CSS Class Name
              array(
                 'name' => $this->get_field_name( 'design' ),
                 'id' => $this->get_field_id( 'design' ),
              ), // Widget Object
             $instance, // Widget Values
             $design_bar_components // Standard Components
        );

        // Build Content Form 
        // http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#content-options-form
        ?>

        <div class="layers-container-large">
            <?php
                $this->form_elements()->header( 
                       array(
                        'title' =>  __( 'Tweets' , 'layerswp' ),
                        'icon_class' => 'post'
                       ) 
                );
            ?>
            <section class="layers-accordion-section layers-content">
                <div class="layers-row layers-push-bottom">

                <p>In order for the twitter widget to work, you will need to set up a twitter app and add your API credentials to the dashboard. Click here to enter your API details.</p>
                <p class="layers-form-item">
                      <?php echo $this->form_elements()->input(
                          array(
                              'type' => 'text',
                              'name' => $this->get_field_name( 'title' ) ,
                              'id' => $this->get_field_id( 'title' ) ,
                              'placeholder' => __( 'Enter title here' , 'layerswp' ),
                              'value' => ( isset( $title ) ) ? $title : NULL ,
                              'class' => 'layers-text layers-large'
                          )
                      ); ?>
                </p>

                <p class="layers-form-item">
                      <?php echo $this->form_elements()->input(
                          array(
                              'type' => 'rte',
                              'name' => $this->get_field_name( 'excerpt' ) ,
                              'id' => $this->get_field_id( 'excerpt' ) ,
                              'placeholder' => __( 'Short Excerpt' , 'layerswp' ),
                              'value' => ( isset( $excerpt ) ) ? $excerpt : NULL ,
                              'class' => 'layers-textarea layers-large'
                          )
                      ); ?>
                </p>

                <p class="layers-form-item">
                      <?php 
                        echo $this->form_elements()->input(
                          array(
                              'type' => 'text',
                              'name' => $this->get_field_name( 'twitter_username' ) ,
                              'id' => $this->get_field_id( 'twitter_username' ) ,
                              'placeholder' => __( 'Twitter username' , 'layerswp' ),
                              'value' => ( isset( $twitter_username ) ) ? $twitter_username : NULL ,
                              'class' => 'layers-text layers-large'
                          )
                      ); ?>
                </p>

                <p class="layers-form-item">
                    <?php 
                        echo __( 'Number of tweets to show' , 'layerswp' ); 
                        echo $this->form_elements()->input(
                            array(
                                'type' => 'number',
                                'name' => $this->get_field_name( 'no_of_tweets' ) ,
                                'id' => $this->get_field_id( 'no_of_tweets' ) ,
                                'value' => ( isset( $no_of_tweets ) ) ? $no_of_tweets : NULL ,
                                'min' => '1',
                                'max' => '20'
                            )
                    ); ?>
                </p>

                </div>
            </section>
        </div>

    <?php 
    } // Form

    /**
    *  3 - Update Options
    *  http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#3-update-controls 
    */    

    function update($new_instance, $old_instance) {
      if ( isset( $this->checkboxes ) ) {
        foreach( $this->checkboxes as $cb ) {
          if( isset( $old_instance[ $cb ] ) ) {
            $old_instance[ $cb ] = strip_tags( $new_instance[ $cb ] );
          }
        } // foreach checkboxes
      } // if checkboxes

      return $new_instance;
    } 


    /**
    *  4 - Widget front end display
    *  http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#4-widget-front-end
    */
    function widget( $args, $instance ) {

        // Turn $args array into variables.
        extract( $args );

        // $instance Defaults
        $instance_defaults = $this->defaults;

        // If we have information in this widget, then ignore the defaults
        if( !empty( $instance ) ) $instance_defaults = array();

        // Parse $instance
        $widget = wp_parse_args( $instance, $instance_defaults );

        // Apply Styling
        // http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#colors-and-font-settings
        layers_inline_styles( '#' . $widget_id, 'background', array( 'background' => $widget['design'][ 'background' ] ) );
        layers_inline_styles( '#' . $widget_id, 'color', array( 'selectors' => array( '.section-title h3.heading' , '.section-title div.excerpt' ) , 'color' => $widget['design']['fonts'][ 'color' ] ) );

        // Apply the advanced widget styling
        $this->apply_widget_advanced_styling( $widget_id, $widget );

        // Generate the widget container class
        // Do not edit
        $widget_container_class = array();
        $widget_container_class[] = 'widget row content-vertical-massive';
        $widget_container_class[] = $this->check_and_return( $widget , 'design', 'advanced', 'customclass' );
        $widget_container_class[] = $this->get_widget_spacing_class( $widget );
        $widget_container_class = implode( ' ', apply_filters( 'layers_post_widget_container_class' , $widget_container_class ) ); 
        /**
        *  Widget Markup
        *  http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#widget-html
        */
        ?> 

        <section class=" <?php echo $widget_container_class; ?>" id="<?php echo $widget_id; ?>">
            <?php if( '' != $this->check_and_return( $widget , 'title' ) ||'' != $this->check_and_return( $widget , 'excerpt' ) ) { ?>
                <div class="container clearfix">    
                    <?php 
                    // Generate the Section Title Classes
                    $section_title_class = array();
                    $section_title_class[] = 'section-title clearfix';
                    $section_title_class[] = $this->check_and_return( $widget , 'design', 'fonts', 'size' );
                    $section_title_class[] = $this->check_and_return( $widget , 'design', 'fonts', 'align' );
                    $section_title_class[] = ( $this->check_and_return( $widget, 'design', 'background' , 'color' ) && 'dark' == layers_is_light_or_dark( $this->check_and_return( $widget, 'design', 'background' , 'color' ) ) ? 'invert' : '' );
                    $section_title_class = implode( ' ', $section_title_class ); ?>

                    <div class="<?php echo $section_title_class; ?>">
                        <?php if( '' != $widget['title'] ) { ?>
                            <h3 class="heading"><?php echo esc_html( $widget['title'] ); ?></h3>
                        <?php } ?>
                        <?php if( '' != $widget['excerpt'] ) { ?>
                            <div class="excerpt"><?php echo $widget['excerpt']; ?></div>
                        <?php } ?>
                    </div>    
                </div>
            <?php }

            // Begin Post Structure ?>  
            <div class="row <?php echo $this->get_widget_layout_class( $widget ); ?> <?php echo $this->check_and_return( $widget , 'design', 'liststyle' ); ?>">

            <?php //start Twitter output


// draft sample display for array returned from oAuth Twitter Feed for Developers WP plugin
// http://wordpress.org/extend/plugins/oauth-twitter-feed-for-developers/

$no_of_tweets = $widget['no_of_tweets'];
$twitter_username = $widget['twitter_username'];
$tweets = getTweets($no_of_tweets, ''.$twitter_username.'');
    if(is_array($tweets)){

// to use with intents
echo '<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>';

foreach($tweets as $tweet){

if($tweet['text']){
    $the_tweet = $tweet['text'];

    // i. User_mentions must link to the mentioned user's profile.
    if(is_array($tweet['entities']['user_mentions'])){
        foreach($tweet['entities']['user_mentions'] as $key => $user_mention){
            $the_tweet = preg_replace(
                '/@'.$user_mention['screen_name'].'/i',
                '<a href="http://www.twitter.com/'.$user_mention['screen_name'].'" target="_blank">@'.$user_mention['screen_name'].'</a>',
                $the_tweet);
        }
    }

    // ii. Hashtags must link to a twitter.com search with the hashtag as the query.
    if(is_array($tweet['entities']['hashtags'])){
        foreach($tweet['entities']['hashtags'] as $key => $hashtag){
            $the_tweet = preg_replace(
                '/#'.$hashtag['text'].'/i',
                '<a href="https://twitter.com/search?q=%23'.$hashtag['text'].'&src=hash" target="_blank">#'.$hashtag['text'].'</a>',
                $the_tweet);
        }
    }

    // iii. Links in Tweet text must be displayed using the display_url
    //      field in the URL entities API response, and link to the original t.co url field.
    if(is_array($tweet['entities']['urls'])){
        foreach($tweet['entities']['urls'] as $key => $link){
            $the_tweet = preg_replace(
                '`'.$link['url'].'`',
                '<a href="'.$link['url'].'" target="_blank">'.$link['url'].'</a>',
                $the_tweet);
        }
    }

    echo $the_tweet;


    // 3. Tweet Actions
    //    Reply, Retweet, and Favorite action icons must always be visible for the user to interact with the Tweet. These actions must be implemented using Web Intents or with the authenticated Twitter API.
    //    No other social or 3rd party actions similar to Follow, Reply, Retweet and Favorite may be attached to a Tweet.
    // get the sprite or images from twitter's developers resource and update your stylesheet
    echo '
    <ul class="twitter_intents">
        <li><a class="reply" href="https://twitter.com/intent/tweet?in_reply_to='.$tweet['id_str'].'"><i class="fa fa-reply"></i>R</a></li>
        <li><a class="retweet" href="https://twitter.com/intent/retweet?tweet_id='.$tweet['id_str'].'"><i class="fa fa-retweet"></i>R</a></li>
        <li><a class="favorite" href="https://twitter.com/intent/favorite?tweet_id='.$tweet['id_str'].'"><i class="fa fa-heart"></i>F</a></li>
    </ul>';


    // 4. Tweet Timestamp
    //    The Tweet timestamp must always be visible and include the time and date. e.g., “3:00 PM - 31 May 12”.
    // 5. Tweet Permalink
    //    The Tweet timestamp must always be linked to the Tweet permalink.
    echo '
    <p class="timestamp">
        <a href="https://twitter.com/YOURUSERNAME/status/'.$tweet['id_str'].'" target="_blank">
            '.date('h:i A M d',strtotime($tweet['created_at']. '- 8 hours')).'
        </a>
    </p>';// -8 GMT for Pacific Standard Time
} else {
    echo '
    <br /><br />
    <a href="http://twitter.com/YOURUSERNAME" target="_blank">Click here to read YOURUSERNAME\'S Twitter feed</a>';
}
}
}    

        // end Twitter output ?>

            </div>

        </section>

    <?php }

} // Class

// Register our widget
// http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#register-and-initialize
add_action( 'widgets_init', function() {
register_widget( 'Layers_Twitter_Widget' );
} );


//register_widget('Layers_Twitter_Widget'); 
}