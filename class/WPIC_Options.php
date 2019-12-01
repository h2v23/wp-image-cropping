<?php


namespace WPIC;


class WPIC_Options extends \SagoBoot\DataObject
{

	const WPIC_OPTION_NAME = 'wpic_config';

	/**
	 * @var array
	 */
	public $default = array(
		'image_path_if_not_found'=> WPIC_PLUGIN_DIR.'/assets/images/default.jpg',
		'image_id_if_not_found'=> null,
		'image_url_if_not_found'=> WPIC_PLUGIN_URL.'/assets/images/default.jpg',
		'cropping_mode'=> 'CROPCENTER',
	);

	public function __construct() {
		$data = $this->_getOptions();
		parent::__construct( $data );
	}

	/**
	 * @return array|mixed|void
	 */
	protected function _getOptions()
	{
		$option = get_option(self::WPIC_OPTION_NAME, null);

		if (is_null($option)) {
			$option = [];
		}

		$option = array_replace_recursive( $this->getDefault(), $option);
		return $option;
	}

	/**
	 * @return array
	 */
	public function getDefault()
	{
		return $this->default;
	}

	/**
	 * @return bool
	 */
	public function save()
	{
		return update_option(self::WPIC_OPTION_NAME, $this->getData());
	}
}