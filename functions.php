<?php

function wpic_notification() {
	if (isset($_GET['_update'])&&(isset($_GET['page']))) {
		$type = (int) (isset($_GET['_update'])) ? $_GET['_update'] : 0 ;

		$class = '';
		$mess = '';

		switch ($type) {
			case 1:
				$mess = __('Settings saved', 'wpic');
				$class = 'updated';
				break;

			default:
				$mess = __('Something wrong', 'wpic');
				$class = 'error';
				break;
		}

		printf('<div class="%2$s settings-error notice is-dismissible"> <p><strong>%1$s.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"></span></button></div>',
			$mess,
			$class
		);
	}
}

function wpic_admin_url($trail = '') {
	return admin_url('options-general.php?page=wp-image-cropping'.$trail);
}