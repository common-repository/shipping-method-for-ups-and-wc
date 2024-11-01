<?php
namespace WPRuby_Ups\Core\Helpers;

class WPRuby_UPS_Debugger {

	/**
	 * Output a message
	 *
	 * @param $title
	 * @param $data
	 * @param string $type
	 */
	public static function debug($title, $data, $type = 'notice')
	{

		if (!self::isEnabled()) {
			return;
		}

		if (is_array($data)) {
			$data = json_encode($data, JSON_PRETTY_PRINT);
		}

		if (current_user_can('manage_options')) {
			wc_add_notice(sprintf('%s <pre>%s</pre>', $title, $data), $type);
		}
	}

	public static function isEnabled(): bool
	{
		$global_settings = get_option('woocommerce_ups_config_settings');

		if (!isset($global_settings['debug_mode'])) {
			return false;
		}

		if ($global_settings['debug_mode'] === 'no') {
			return false;
		}

		return true;
	}

}
