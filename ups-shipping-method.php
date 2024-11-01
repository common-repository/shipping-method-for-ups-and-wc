<?php
/* @wordpress-plugin
 * Plugin Name:       Shipping Method for UPS and WooCommerce
 * Plugin URI:        https://wpruby.com/contact
 * Description:       Shipping Method for UPS and WooCommerce
 * Version:           1.0.3
 * WC requires at least: 3.0
 * WC tested up to: 8.0
 * Author:            WPRuby
 * Author URI:        https://wpruby.com
 * Text Domain:       wpruby-ups-shipping-method
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

namespace WPRuby_Ups;

use WPRuby_Ups\Core\WPRuby_UPS_Shipping_Configuration;
use WPRuby_Ups\Core\WPRuby_UPS_Shipping_Method;

define('WPRUBY_UPS_CURRENT_VERSION', '1.0.3');

class WPRuby_UPS_Shipping {

	/**
	 * The single instance of the class.
	 *
	 * @var WPRuby_UPS_Shipping
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	public static function get_instance(): WPRuby_UPS_Shipping
	{
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * WPRuby_UPS_Shipping_Lite constructor.
	 */
	public function __construct()
	{
		add_filter('woocommerce_shipping_methods', [$this, 'add_ups_method']);
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), [$this, 'plugin_action_links'] );
	}

	/**
	 * @param $methods
	 *
	 * @return array
	 */
	public function add_ups_method( $methods )
	{
		$methods['ups'] = WPRuby_UPS_Shipping_Method::class;
		$methods['ups_config'] = WPRuby_UPS_Shipping_Configuration::class;
		return $methods;
	}

	public function plugin_action_links( $links ): array
	{
		$links[] = '<a href="https://wpruby.com/plugin/woocommerce-ups-shipping-method-pro/" target="_blank">Get the Pro version</a>';
		$links[] = '<a href="https://wpruby.com/submit-ticket/" target="_blank">Support</a>';
		return $links;
	}

}

require_once dirname(__FILE__ ) . '/vendor/autoload.php';
require_once dirname(__FILE__ ) . '/includes/autoloader.php';

/** initiate the plugin */
WPRuby_UPS_Shipping::get_instance();
