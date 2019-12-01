<?php


namespace WPIC;


class WPIC_Helper extends \SagoBoot\DataObject
	implements \SagoBoot\Singleton
{
	/**
	 * WPIC_Helper constructor.
	 */
	public function __construct() {
		parent::__construct([]);
	}

	/**
	 * @return mixed
	 */
	public function boot()
	{

	}

	/**
	 * @return bool
	 */
	public function isImage404Page()
	{
		return ($this->is404Page() && $this->isImagePage());
	}

	/**
	 * @return bool
	 */
	public function is404Page()
	{
		return is_404();
	}

	/**
	 * @return bool
	 */
	public function isImagePage()
	{
		$slug = $this->getCurrentSlug();
		return $this->isImageByPath($slug);
	}

	public function isImageByPath($path)
	{
		$type = explode('.', $path);
		if (count($type)<2) return false;

		$type = end($type);
		$type = strtolower($type);
		if (!in_array($type, array('jpg', 'png', 'jpeg', 'gif'))) {
			return false;
		}
		return true;
	}

	/**
	 * @return string
	 */
	protected function _getCurrentSlug()
	{
		global $wp;
		$slug = $wp->request;
		$this->setCurrentSlug($slug);
		return $slug;
	}

	/**
	 * @return string
	 */
	protected function _getImagePlaceholderPath()
	{
		$path = WPIC_PLUGIN_DIR . 'assets/images/default.jpg';
		if (file_exists($path)) {
			$this->setImagePlaceholderPath($path);
		} else {
			$this->setImagePlaceholderPath(false);
		}
		return $path;
	}

	/**
	 * @param null $url
	 *
	 * @return string
	 */
	public function realImagePath($url = null)
	{
		return ABSPATH . $this->parseImageData()['real_path'];
	}

	/**
	 * @param null $image_url
	 *
	 * @return array
	 */
	public function parseImageData($image_url = null)
	{
		if ($image_url==null) {
			$image_url = $this->getCurrentSlug();
		}

		if (!$this->isImageByPath($image_url)) {
			return [];
		}

		$type = explode('.', $image_url);
		$type = end($type);
		$type = strtolower($type);

		$link = substr($image_url, 0, strlen($image_url) - strlen($type) - 1);
		preg_match("/(\d+)x(\d+)$/i", $link, $dimension);
		$real_path = preg_replace("/(-\d+x\d+)/i", '', $image_url);
		return [
			'dimension' => $dimension[0] ?? null,
			'width' => $dimension[1] ?? null,
			'height' => $dimension[2] ?? null,
			'real_path' => $real_path,
			'image_url' => $image_url
		];
	}
}