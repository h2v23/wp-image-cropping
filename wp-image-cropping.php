<?php
/**
 * @package WP Image Cropping
 */
/*
Plugin Name: WP Image Cropping
Description: Dynamic function for wordpress image library, using 404 ( file not found ) for media that can crop image to any size.
Version: 2.0.0
Author: h2v23
License: MIT
Text Domain: wpic
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'WPIC_VERSION', '2.0.0' );
define( 'WPIC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPIC_PLUGIN_URL', plugins_url( '', __FILE__ ) );

require WPIC_PLUGIN_DIR . '/vendor/autoload.php';

$app = new \SagoBoot\Application();

$app->addEvent(\SagoBoot\Application::BOOT_EVENT_NAME, function () use ($app) {
	$app->singleton('WPIC_Manager', 'WPIC\\Manager');
	$app->singleton('WPIC_Options', 'WPIC\\WPIC_Options');
	$app->make('WPIC_Manager');
});



add_action('init', function () use ($app) {
	$app->boot();
});