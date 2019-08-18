<?php
/**
 * @package WP Image Cropping
 */
/*
Plugin Name: WP Image Cropping
Description: Dynamic function for wordpress image library, using 404 ( file not found ) for media that can crop image to any size.
Version: 1.0.0
Author: h2v
License: MIT
Text Domain: wpic
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'WPIC_VERSION', '1.0.0' );
define( 'WPIC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPIC_PLUGIN_URL', plugins_url( '', __FILE__ ) );

$GLOBALS['wpic_config_default'] = array(
	'image_if_not_found'=> WPIC_PLUGIN_URL.'/assets/images/default.jpg',
	'cropping_mode'=> 'CROPCENTER',
);

require_once( WPIC_PLUGIN_DIR . 'src/functions.php' );

register_activation_hook( __FILE__, 'wpic_plugin_activation' );
register_deactivation_hook( __FILE__,  'wpic_plugin_deactivation' );

if ( is_admin() ) {
	require_once( WPIC_PLUGIN_DIR . 'src/admin.php' );
} else {
	require_once( WPIC_PLUGIN_DIR . 'src/core.php' );
	add_action( 'template_redirect', 'wpic_do_404_image' );
}