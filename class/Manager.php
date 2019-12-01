<?php
namespace WPIC;

class Manager
{
	/**
	 * @var WPIC_Helper
	 */
	protected $helper;

	/**
	 * Manager constructor.
	 *
	 * @param WPIC_Helper $helper
	 *
	 * @throws \ReflectionException
	 * @throws \SagoBoot\Framework\Illuminate\Container\BindingResolutionException
	 */
	public function __construct(
		\WPIC\WPIC_Helper $helper
	) {
		$this->helper = $helper;

		if (is_admin()) {
			$this->onAdminPanel();
		} else {
			add_action( 'template_redirect', [$this, 'onTemplateRedirect'] );
		}

		register_activation_hook( __FILE__, [$this, 'activate'] );
		register_deactivation_hook( __FILE__,  [$this, 'deactivate'] );
	}

	/**
	 * @throws \ReflectionException
	 * @throws \SagoBoot\Framework\Illuminate\Container\BindingResolutionException
	 */
	public function onTemplateRedirect()
	{
		if ($this->helper->isImage404Page()) {
			$core = sgb_app('WPIC\\WPIC_Core');
			sgb_event('onImageNotFoundPage');
		}
	}

	/**
	 * @throws \ReflectionException
	 * @throws \SagoBoot\Framework\Illuminate\Container\BindingResolutionException
	 */
	public function onAdminPanel()
	{
		$admin = sgb_app()->make( 'WPIC\\WPIC_Admin' );
		/**
		 * Let boot
		 */
		$admin->boot();
	}

	/**
	 * add the configurations
	 */
	public function activate()
	{
		add_option(\WPIC\WPIC_Options::WPIC_OPTION_NAME, sgb_app('WPIC_Options')->getData());
	}

	/**
	 * remove the configurations
	 */
	public function deactivate()
	{
		delete_option(\WPIC\WPIC_Options::WPIC_OPTION_NAME);
	}
}