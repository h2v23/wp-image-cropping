<?php
use \Gumlet\ImageResize;
function wpic_do_404_image() {
	if(is_404()&&wpic_current_url_with_cache_file_existed()) {
		$file_path = wpic_current_slug();
		if ($file_data = wpic_parse_image($file_path)) {
			$real_path = ABSPATH . $file_data['real_path'];

			if (!file_exists($real_path)) {
				$image_if_not_found = str_replace(home_url(), '', wpic_get('image_if_not_found'));
				$real_path = ABSPATH . $image_if_not_found;
				$file_path = wpic_cache_file(wpic_current_slug());
			} else {
				$file_path = ABSPATH.$file_path;
			}

			include WPIC_PLUGIN_DIR.'libs/php-image-resize-master/lib/ImageResize.php';	
			$image = new ImageResize( $real_path );

			if (!empty($file_data['width'])||!empty($file_data['height'])) {
				$ImageResize_Ref = new ReflectionClass('\Gumlet\ImageResize');
				$image->crop($file_data['width'], $file_data['height'], true, $ImageResize_Ref->getConstants()[wpic_get('cropping_mode')]);
			}

			$image->save($file_path);

			header('Content-Type: image/jpeg');
			header('Cache-Control: max-age=86400');
			echo file_get_contents( $file_path );

		}
	}
}
