<?php
add_action( 'admin_menu', 'wpic_admin_menu');

add_action ( 'admin_enqueue_scripts', function () {
	wp_enqueue_media();
});

function wpic_admin_menu() {
	if (current_user_can('manage_options')) {
		add_submenu_page( 'options-general.php',
		__( 'Image Cropping Setting' , 'wpic'), 
		__( 'Image Cropping Setting' , 'wpic'), 
		'manage_options', 
		'wp-image-cropping', 
		'wpic_admin_menu_display' );
	}
}

function wpic_admin_menu_display()
{
	if (isset($_POST['wpic_update_field'])) {
		if(check_admin_referer('wpic_update_field')&&isset($_POST['wpic_update_field'])&&$_POST['wpic_update_field']==1&&wp_verify_nonce( $_POST['_wpnonce'], 'wpic_update_field' ) ) {
			// apply setting
			$update_code = 0;
			// get global var, that autoload and override before
			$config = wpic_get();
			$config['image_ext'] = $GLOBALS['wpic_config_default']['image_ext'];

			$config['default_width'] = (int) (isset($_POST['wpic_default_width'])) ? 
			sanitize_text_field($_POST['wpic_default_width']) : $config['default_width'];

			$config['default_height'] = (int) (isset($_POST['wpic_default_height'])) ? 
			sanitize_text_field($_POST['wpic_default_height']) : $config['default_height'];

			$config['image_if_not_found'] = (string) (isset($_POST['wpic_image_if_not_found'])) ? 
			sanitize_text_field($_POST['wpic_image_if_not_found']) : $config['image_if_not_found'];

			$config['cropping_mode'] = (string) (isset($_POST['wpic_cropping_mode'])&&in_array($_POST['wpic_cropping_mode'], array(
				'CROPCENTER', 'CROPTOP', 'CROPBOTTOM'
			))) ? 
			sanitize_text_field($_POST['wpic_cropping_mode']) : $config['cropping_mode'];

				// almost done
			$update_code = update_option( 'wpic_config', $config, 'yes' );

			if ($update_code) 
				$update_code = 1;
			else
				$update_code = 0;

			// reload page
			?>
			<script type="text/javascript">
				window.location='<?php echo wpic_admin_url('&_update='.$update_code) ;?>';
			</script>
			<?php 
			wp_die();
		}
	}
	include WPIC_PLUGIN_DIR . 'assets/html/setting-page.php';
}