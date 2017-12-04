<?php

/*
  Plugin Name: GK Class Appender
  Plugin URI: http://www.greateck.com/
  Description: Allowing specific classes to be added to html tags on runtime without code editing
  Version: 0.1.0
  Author: mcfarhat
  Author URI: http://www.greateck.com
  License: GPLv2
 */

/* adding relevant backend menu */
add_action( 'admin_menu', 'gk_class_appender_menu' );

function gk_class_appender_menu() {
	add_menu_page( 'Class Appender', 'Class Appender', 'manage_options', 'gk-class-appender', 'gk_render_class_options');
}

function gk_render_class_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<h2>GK Class Appender</h2>';
	//container for the form data
	echo '<div class="wrap">';
	// print_r($_POST);
	
	if (isset($_POST['gk_save_data'])){
		//handling saving of the data
		//validating the nonce
		check_admin_referer( 'gk-class-appender_'.get_current_user_id() );
		update_option('gk_class_htmlentity', sanitize_text_field($_POST['htmlentity']));
		update_option('gk_class_classname', sanitize_text_field($_POST['classname']));
	}
	//handling rendering data
	echo '<form method="post">';
	echo '<label>HTML Entity</label><input type="text" id="htmlentity" name="htmlentity" value="'.get_option('gk_class_htmlentity').'"></input>';
	echo '<label>Class Name</label><input type="text" id="classname" name="classname"value="'.get_option('gk_class_classname').'"></input>';
	echo '<input type="hidden" id="gk_save_data" name="gk_save_data">';
	//adding nonce support
	wp_nonce_field( 'gk-class-appender_'.get_current_user_id() );
	echo '<input type="submit" value="Save">';
	echo '</form>';
	echo '</div>';
	echo '<br />';
}

add_action('wp_footer','gk_include_class_frontend');

function gk_include_class_frontend(){
	?>
	<script>
		jQuery(document).ready(function($){
			console.log('here');
			console.log(('<?php echo get_option('gk_class_classname');?>'));
			//loop through all entries of this tag/class
			$('<?php echo get_option('gk_class_htmlentity');?>').each(function(index){
					$( this ).addClass('<?php echo get_option('gk_class_classname');?>');
			});
		});
	</script>
	<?php
}


?>