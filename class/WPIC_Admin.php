<?php


namespace WPIC;


class WPIC_Admin implements \SagoBoot\Singleton
{
	/**
	 * @var WPIC_Helper
	 */
	public $helper;
	/**
	 * @var \SagoBoot\RequestHelper
	 */
	private $request;
	/**
	 * @var WPIC_Options
	 */
	private $options;

	public function __construct(
		\WPIC\WPIC_Helper $helper,
		\SagoBoot\RequestHelper $request,
		\WPIC\WPIC_Options $options
	) {
		$this->helper = $helper;
		$this->request = $request;
		$this->options = $options;
	}

	public function boot()
	{
		add_action( 'admin_menu', [$this, 'adminMenu']);
		add_action ( 'admin_enqueue_scripts', [$this, 'wpEnqueueMedia']);
	}

	public function wpEnqueueMedia()
	{
		wp_enqueue_media();
	}


	public function adminMenu()
	{
		if (current_user_can('manage_options')) {
			add_submenu_page( 'options-general.php',
				__( 'Image Cropping Setting' , 'wpic'),
				__( 'Image Cropping Setting' , 'wpic'),
				'manage_options',
				'wp-image-cropping',
				[$this, 'adminMenuAction'] );
		}
	}


	public function adminMenuAction()
	{
		if ($this->request->isPost() && check_admin_referer('wpic_update_field') && $this->request->getData('wpic_update_field')) {
			$image_id = $this->request->getData('wpic_image_id_if_not_found');
			$image_id = filter_var($image_id, FILTER_VALIDATE_INT);
			if ($image_id && $image_id==$this->options->getData('wpic_image_id_if_not_found')) {
				// do nothing
			} elseif ($image_id) {
				$this->options->setData([
					'image_id_if_not_found' => $image_id,
					'image_url_if_not_found' => wp_get_attachment_image_src($image_id)[0],
					'image_path_if_not_found' => str_replace(ABSPATH, '', get_attached_file($image_id)),
				]);
			} else {
				$default = $this->options->getDefault();
				$this->options->setData([
					'image_id_if_not_found' => null,
					'image_url_if_not_found' => $default['wpic_image_url_if_not_found'],
					'image_path_if_not_found' => $default['wpic_image_path_if_not_found'],
				]);
			}
			$cropping_mode = filter_var($this->request->getData('wpic_cropping_mode'));
			$this->options->setCroppingMode( $cropping_mode );
			$update_code = $this->options->save();

			$this->reloadPage( $update_code );
		} else {
			include WPIC_PLUGIN_DIR . 'assets/html/setting-page.php';
		}

	}

	/**
	 * @param int $update_code
	 */
	public function reloadPage( $update_code = 1)
	{
		$admin_url = wpic_admin_url('&_update='.$update_code);
		echo <<<BS
			<script type="text/javascript">
				window.location.href ='{$admin_url}';
			</script>
			<?php
BS
		;
		wp_die();
	}
}