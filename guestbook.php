<?php
/*
Plugin Name: Guestbook Plugin
Plugin URI: http://example.com/wordpress-plugins/my-plugin
Description: A plugin to create Guestbook
Version: 1.0
Author: Robert Dabu
Author URI: http://donsworld.co.nf/portfolio/
License: GPLv2
*/

class guestbook_widget extends WP_Widget {
 
 
    /** constructor -- name this the same as the class above */
    function guestbook_widget() {
        parent::WP_Widget(false, $name = 'Guestbook Widget');	
    }
 
    /** @see WP_Widget::widget -- do not rename this */
    function widget($args, $instance) {	
        extract( $args );
        $title 		= apply_filters('guestbook_widget', $instance['title']);
        $message 	= $instance['message'];
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo "<h3>".$before_title . $title . $after_title."</h3>"; ?>
							<ul>
								<li><a><?php echo $message; ?></a></li>
							</ul>
              <?php echo $after_widget; ?>
        <?php
    }
 
    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['message'] = strip_tags($new_instance['message']);
        return $instance;
    }
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {	
 
        $title 		= esc_attr($instance['title']);
        $message	= esc_attr($instance['message']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p>
          <label for="<?php echo $this->get_field_id('message'); ?>"><?php _e('Simple Message'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('message'); ?>" name="<?php echo $this->get_field_name('message'); ?>" type="text" value="<?php echo $message; ?>" />
        </p>
        <?php 
    }

 
    function html_form_code() {
        echo '<form action="../guestbook" method="post">';
        echo '<p>';
        echo 'Your Name (required) <br />';
        echo '<input type="text" name="fullname" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["name"] ) ? esc_attr( $_POST["name"] ) : '' ) . '" size="40" />';
        echo '</p>';
        echo '<p>';
        echo 'Your Message (required) <br />';
        echo '<textarea rows="10" cols="35" name="comment">' . ( isset( $_POST["comment"] ) ? esc_attr( $_POST["comment"] ) : '' ) . '</textarea>';
        echo '</p>';
        echo '<p><input type="submit" name="submit" value="Send"/></p>';
        echo '</form>';
    }
    
    function updateForm($fullname, $comment) {
        global $wpdb;
        $fullname = filter_var($fullname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $comment = filter_var($comment, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        if($fullname && $comment) {
            $wpdb->insert( 'guestbook', array( 'id' => NULL, 'name' => $fullname , 'comment' => $comment ));
        }            
    }        
} // end class guestbook_widget

add_action('widgets_init', create_function('', 'return register_widget("guestbook_widget");'));
add_action( 'guestbook', 'html_form_code' );
?>                                         