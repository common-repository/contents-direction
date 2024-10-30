<?php
/**
Plugin Name: Content Direction
Description: This plugin add a metabox in every post type you got, with title 'The direction of the content', so you can direct the content RTL or LTR ( please refer to the screenshot for more information)
Author: Osama Ahmed
Version: 1.0
Author URI: http://osamaahmedattia.wordpress.com
*/

// create a select box in the post to tell that the post need to be right to left post
add_action('add_meta_boxes', 'os_custom_box_for_rtl');
add_action('save_post', 'os_custom_box_save');
add_filter('wp_head', 'os_custom_box_style');
add_filter('tiny_mce_before_init', 'my_os_tinymce_config'); #run filter for the configuration for tinymce
add_filter('mce_external_plugins', 'os_tinymce_external_plugin'); #add external plugin
add_filter('mce_css', 'os_tinymce_css');
add_action( 'plugins_loaded', 'os_custom_box_load_textdomain' );

// load the text domain for the plugin
function os_custom_box_load_textdomain() {
    load_plugin_textdomain( 'os_custom_box', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
}
// add the os_style to tinymce
function os_tinymce_css($mce_css) {
    if(!empty($mce_css)) {
        $mce_css .= ',';
    }
    $mce_css .= plugin_dir_url(__FILE__) . 'os_style.css';
    return $mce_css;
}
function os_tinymce_external_plugin($plugins_array) {

        $plugins_array['os_direction'] = plugin_dir_url(__FILE__) . '/plugin.js';
        if (get_bloginfo('version') < 3.9) {
            $plugins_array['directionality'] = includes_url('js/tinymce/plugins/directionality/editor_plugin.js');
        } else {
            $plugins_array['directionality'] = includes_url('js/tinymce/plugins/directionality/plugin.min.js');
        }
        return $plugins_array;

}
function my_os_tinymce_config( $init ) {
	global $post;
	$direction = get_post_meta($post->ID, 'os_custom_box_for_rtl', true);

	if($direction == 'rtl')
	{
		$init['directionality'] = 'rtl';
	}
	else
	{
		$init['directionality'] = 'ltr';
	}

    return $init;
}


function os_custom_box_for_rtl() {
	add_meta_box('os_custom_box_for_rtl', __('The direction of the content', 'os_custom_box'), 'os_custom_box_for_rtl_callback', '', 'side', 'high');

	// the callback function
	function os_custom_box_for_rtl_callback() {
		global $post;
		$direction = get_post_meta($post->ID, 'os_custom_box_for_rtl', true);

		if(!isset($direction))
		{
			$direction = 'ltr'; 	# if the meta post doesn't defined
		}
		?>
			<select name="os_custom_box" class="widefat">
                <option value="ltr" <?php selected($direction, 'ltr') ?>><?php _e('Left to right', 'os_custom_box') ?></option>
                <option value="rtl" <?php selected($direction, 'rtl') ?>><?php _e('Right to left', 'os_custom_box') ?></option>
			</select>
		<?php

	}
}


// Save the post
function os_custom_box_save($id) {
	if(isset($_POST['os_custom_box'])) {
		update_post_meta($id ,'os_custom_box_for_rtl', $_POST['os_custom_box']);	# get the posted data ( no need for security because it's just selectbox
	}
}

function os_custom_box_style() {
	?>
	<style>
		<?php
			global $post;
			$direction = get_post_meta($post->ID, 'os_custom_box_for_rtl', true);
			if($direction == "rtl"):
		?>
			#post-<?php echo $post->ID ?> {
				direction: rtl;
			}
	<?php
			endif;
	?>
	</style>
	<?php
}

