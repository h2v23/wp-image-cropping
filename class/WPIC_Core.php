<?php


namespace WPIC;

use Gumlet\ImageResize;
use SagoBoot\Traits\Event as EventTrait;

class WPIC_Core implements \SagoBoot\Singleton
{
	use EventTrait;
	/**
	 * @var WPIC_Helper
	 */
	protected $helper;

	/**
	 * WPIC_Core constructor.
	 *
	 * @param WPIC_Helper $helper
	 */
	public function __construct(
		\WPIC\WPIC_Helper $helper
	) {
		$this->helper = $helper;
		$this->addEvent('onImageNotFoundPage', [$this, 'boot']);
	}

	/**
	 * Break point for main functions come here
	 * @return mixed
	 * @throws \Gumlet\ImageResizeException
	 */
	public function boot()
	{
		$url = $this->helper->getCurrentSlug();
		$real_path = $this->helper->realImagePath($url);

		if (file_exists($real_path)) {
			$image_data = $this->helper->parseImageData($url);
			$image = new ImageResize($real_path);
			$image->crop($image_data['width'], $image_data['height']);
			$image->save(ABSPATH . '/' . $url);
			$this->loadImage(ABSPATH . '/' . $url);
		} else {
			$this->loadImage();
		}

		$this->die();
	}

	/**
	 * @param null $path
	 *
	 * @return |null
	 */
	public function loadImage($path = null)
	{
		if (!$path) {
			$path = $this->helper->getImagePlaceholderPath();
		}
		$image_info = getimagesize($path);
		switch ($image_info[2]) {
			case IMAGETYPE_JPEG:
				header("Content-Type: image/jpeg");
				break;
			case IMAGETYPE_GIF:
				header("Content-Type: image/gif");
				break;
			case IMAGETYPE_PNG:
				header("Content-Type: image/png");
				break;
			default:
				// back to default
				return null;
				break;
		}

		// Set the content-length header
		header('Content-Length: ' . filesize($path));
		header('Cache-Control: max-age=86400');
		// let set 200
		header("HTTP/1.1 200 OK");
		// Write the image bytes to the client
		readfile($path);
	}

	/**
	 *
	 */
	private function die()
	{
		die;
	}
}