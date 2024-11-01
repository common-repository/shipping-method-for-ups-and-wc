<?php
namespace WPRuby_Ups\Core;

use Exception;
use WC_Product;
use WC_Shipping_Method;
use WPRuby_UPS_Libs\DVDoug\BoxPacker\Packer;
use WPRuby_Ups\Core\Boxpacker\WPRuby_UPS_Box;
use WPRuby_Ups\Core\Boxpacker\WPRuby_UPS_Item;
use WPRuby_Ups\Core\Helpers\WPRuby_UPS_Debugger;
use WPRuby_Ups\Core\Ups\WPRuby_UPS_Calculator;
use WPRuby_Ups\Core\Ups\WPRuby_UPS_Package;
use WPRuby_Ups\Core\Ups\WPRuby_UPS_Rate;
use WPRuby_Ups\Core\Ups\WPRuby_UPS_Settings;


class WPRuby_UPS_Shipping_Method extends WC_Shipping_Method {

	private $usa_domestic_services = [
		"12" => "3 Day Select",
		"03" => "Ground",
		"02" => "2nd Day Air",
		"59" => "2nd Day Air AM",
		"01" => "Next Day Air",
		"13" => "Next Day Air Saver",
		"14" => "Next Day Air Early AM",
	];

	private $international_services = [
		"11" => "Standard",
		"65" => "Saver",
		"07" => "Worldwide Express",
		"54" => "Worldwide Express Plus",
		"08" => "Worldwide Expedited",
		"74" => "UPS Express 12:00",
	];

	private $global_settings;
	private $default_weight;
	private $default_dimensions;
	private $show_duration;
	private $enabled_domestic_options;
	private $enabled_intl_options;
	private $handling_fee;

	private $ups_user_id;
	private $ups_password;
	private $ups_access_key;
	private $ups_account_number;
	private $ups_customer_classification;
	private $debug_mode;

	public function __construct( $instance_id = 0 ){

		$this->id = 'ups';
		$this->instance_id = absint( $instance_id );
		$this->method_title = __('UPS' ,'wpruby-ups-shipping-method');
		$this->title = __('UPS' ,'wpruby-ups-shipping-method');

		$this->supports  = [
			'shipping-zones',
			'shipping-zones',
			'instance-settings',
		];
		$this->init_form_fields();
		$this->init_settings();


		$this->enabled = $this->get_option('enabled');
		$this->title = $this->get_option('title');

		$this->default_weight = $this->get_option('default_weight');
		$this->default_dimensions = $this->get_option('default_dimensions');
		$this->show_duration = $this->get_option( 'show_duration' );
		$this->enabled_domestic_options = $this->get_option( 'enabled_domestic_options' );
		$this->enabled_intl_options = $this->get_option( 'enabled_intl_options' );
		$this->handling_fee = $this->get_option('handling_fee');

		$this->global_settings = get_option('woocommerce_ups_config_settings');
		$this->ups_user_id = (isset($global_settings['ups_user_id']))? $global_settings['ups_user_id']: '';
		$this->ups_password = (isset($global_settings['ups_password']))? $global_settings['ups_password']: '';
		$this->ups_access_key = (isset($global_settings['ups_access_key']))? $global_settings['ups_access_key']: '';
		$this->ups_account_number = (isset($global_settings['ups_account_number']))? $global_settings['ups_account_number']: '';
		$this->ups_customer_classification = (isset($global_settings['ups_customer_classification']))? $global_settings['ups_customer_classification']: 'NA';

		$this->debug_mode = (isset($global_settings['debug_mode']))? $global_settings['debug_mode']: 'no';

		add_action('woocommerce_update_options_shipping_'.$this->id, [$this, 'process_admin_options']);

	}

	public function init_form_fields(){

		$weight_unit = strtolower( get_option( 'woocommerce_weight_unit' ) );

		$this->instance_form_fields = [
			'title' => [
				'title' 		=> __( 'Method Title', 'wpruby-ups-shipping-method' ),
				'type' 			=> 'text',
				'description' 	=> __( 'This controls the title', 'wpruby-ups-shipping-method' ),
				'default'		=> __( 'UPS Shipping', 'wpruby-ups-shipping-method' ),
				'desc_tip'		=> true,
			],
			'default_weight' => [
				'title'             => __( 'Default Package Weight', 'wpruby-ups-shipping-method' ),
				'type'              => 'text',
				'default'           => '0.5',
				'description'       => __( $weight_unit , 'wpruby-ups-shipping-method' ),
                'css'				=> 'width:100px;',
			],
			'default_dimensions' => [
				'type' => 'default_dimensions',
				'default'=> 'default',
			],
			'handling_fee' => [
				'title' => __('Handling Fees', 'wpruby-ups-shipping-method'),
				'type' => 'text',
				'css' => 'width:75px',
				'description' => __('(Optional) Enter an amount e.g. 3.5 or a percentage e.g. 3% PS: you can use negative values e.g -3.5', 'wpruby-ups-shipping-method'),
				'default' => '',
			],
			'enabled_domestic_options' => [
				'title' 	=> __('USA Domestic Options', 'wpruby-ups-shipping-method'),
				'type' 		=> 'multiselect',
				'default' 	=> '03',
				'class' 	=> 'availability wc-enhanced-select',
				'css' 		=> 'width:80%;',
				'options' 	=> $this->usa_domestic_services,
			],
			'enabled_intl_options' => [
				'title'   => __('International Options', 'woocommerce'),
				'type' 	  => 'multiselect',
				'css' 	  => 'width:100%;',
				'class'   => 'availability wc-enhanced-select',
				'default' => '11',
				'options' => $this->international_services,
			],
			'show_duration' => [
				'title' 		=> __( 'Delivery Time', 'wpruby-ups-shipping-method' ),
				'type' 			=> 'checkbox',
				'label' 		=> __( 'Enable ', 'wpruby-ups-shipping-method' ),
				'default' 		=> 'yes',
				'description'	=> __( 'Show Delivery Time Estimation in the Checkout page.', 'wpruby-ups-shipping-method' ),
			],
		];
	}

	public function is_available( $package )
    {
		if ($this->debug_mode === 'yes') {
			return current_user_can('administrator');
		}

		return true;
	}

	public function calculate_shipping( $package = [] )
    {
    	$this->debug_environment();
	    WPRuby_UPS_Debugger::debug('Settings', $this->instance_settings);
		$package_details  =  $this->get_package_details( $package );
	    WPRuby_UPS_Debugger::debug('Packing Details', $package_details);

		$this->rates = [];
	    $packagesRates = [];
		foreach($package_details as  $pack){

			$package = (new WPRuby_UPS_Package())
				->setLength($pack['length'])
				->setWidth($pack['width'])
				->setHeight($pack['height'])
			    ->setWeight($pack['weight'])
			    ->setPostcode($package['destination']['postcode'])
			    ->setCountry($package['destination']['country'])
			    ->setPrice($pack['value']);

			$packagesRates[] = $this->get_rates($package);
		}

		if (empty($packagesRates)) {
		    return;
        }

        $rates = [];
		foreach ($packagesRates as $packageRates) {
		    /** @var WPRuby_UPS_Rate $rate */
			foreach ($packageRates as $key => $rate) {
		        if (!isset($rates[$key])) {
		            $rates[$key] = $rate;
                }
		        $rates[$key] = $rates[$key]->addCost($rate->getCost());
            }
        }

		/** @var $rate WPRuby_UPS_Rate **/
        foreach ($rates as $key => $rate) {
            $this->add_rate($rate->toArray());
        }


	}

	private function get_rates( WPRuby_UPS_Package $package): array
    {
    	$method_settings = array_merge($this->instance_settings, $this->global_settings);
		$settings = new WPRuby_UPS_Settings($method_settings, $this->get_enabled_services( $package->getCountry() ));
		$calculator = new WPRuby_UPS_Calculator($package, $settings);
		return $calculator->calculate();
	}

	/**
	 * get_package_details function.
	 *
	 * @access private
	 * @param mixed $package
	 * @return mixed
	 */
	private function get_package_details( $package ): array
	{
		$default_length = isset($this->default_size['length'])?$this->default_size['length']:1;
		$default_width 	=  isset($this->default_size['width'])?$this->default_size['width']:1;
		$default_height = isset($this->default_size['height'])?$this->default_size['height']:1;
		//1. adding boxes
		$boxes = [];

		$boxes[] = new WPRuby_UPS_Box("Product", 1000, 700, 450, 0, 1000, 700, 450, 20000);


		$packer = new Packer();
		foreach ($boxes as $box)
		{
			$packer->addBox($box);
		}

		// Get weight of order
		foreach ($package['contents'] as $item_id => $values) {
			/** @var WC_Product $_product */
			$_product = $values['data'];
			//info: since 1.9.2 skipp virtual products
			if ($_product->is_virtual()) {
				continue;
			}

			$weight = wc_get_weight((floatval($_product->get_weight()) <= 0) ? $this->default_weight : $_product->get_weight(), 'g');
			$length = wc_get_dimension((floatval($_product->get_length()) <= 0) ? $default_length : $_product->get_length(), 'mm');
			$height = wc_get_dimension((floatval($_product->get_height()) <= 0) ? $default_height : $_product->get_height(), 'mm');
			$width = wc_get_dimension((floatval($_product->get_width()) <= 0) ? $default_width : $_product->get_width(), 'mm');
			$value = $_product->get_price();

			//adding the packer code
			//2. adding items
			for ($i = 0 ; $i < $values['quantity']; $i++) {
				$item = new WPRuby_UPS_Item("Product", $width, $length, $height, $weight,  false, $value);
				$packer->addItem($item);
			}
			//end of the packer code
		}

		//adding the packer code
		//3. packing
		try {
			$packedBoxes = $packer->pack();
		} catch (Exception $e) {
			return [];
		}

		$pack = [];
		$packs_count = 1;
		foreach ($packedBoxes as $packedBox) {
			$pack[$packs_count]['weight'] = $packedBox->getWeight() / 1000;
			$pack[$packs_count]['length'] = $packedBox->getUsedLength() / 10;
			$pack[$packs_count]['width'] =  $packedBox->getUsedWidth() / 10;
			$pack[$packs_count]['height'] = ($packedBox->getUsedDepth() === 0)? $height / 10: $packedBox->getUsedDepth() / 10;
			$pack[$packs_count]['quantity'] = count($packedBox->getItems());
			$pack[$packs_count]['postcode'] = $package['destination']['postcode'];
			$pack[$packs_count]['value'] = array_reduce($packedBox->getItems()->asItemArray(), function ($carry, $item){
				$carry += $item->getPrice();
				return $carry;
			});
			$packs_count++;
		}

		return $pack;
	}

	/**
	 * @return string
	 */
	public function generate_default_dimensions_html()
	{
		$dimensions_unit = strtolower(get_option('woocommerce_dimension_unit'));
		$length = (isset($this->instance_settings['default_dimensions']['length']))?$this->instance_settings['default_dimensions']['length']:'';
		$width = (isset($this->instance_settings['default_dimensions']['width']))?$this->instance_settings['default_dimensions']['width']:'';
		$height = (isset($this->instance_settings['default_dimensions']['height']))?$this->instance_settings['default_dimensions']['height']:'';
		ob_start();
		require_once (dirname(__FILE__).'/views/default_dimensions.php');
		return ob_get_clean();
	}

	/**
	 * validate_default_dimensions_field function.
	 *
	 * @access public
	 * @return array
	 * @internal param mixed $key
	 */
	public function validate_default_dimensions_field()
	{
		$dimensions = [];
		if (is_numeric($_POST['woocommerce_ups_default_length']) && $_POST['woocommerce_ups_default_length'] > 0) {
			$dimensions['length'] = sanitize_text_field($_POST['woocommerce_ups_default_length']);
		}
		if (is_numeric($_POST['woocommerce_ups_default_width']) && $_POST['woocommerce_ups_default_width'] > 0) {
			$dimensions['width']  = sanitize_text_field($_POST['woocommerce_ups_default_width']);
		}
		if (is_numeric($_POST['woocommerce_ups_default_height']) && $_POST['woocommerce_ups_default_height'] > 0) {
			$dimensions['height'] = sanitize_text_field($_POST['woocommerce_ups_default_height']);
		}
		return $dimensions;
	}

	/**
	 * @param $country
	 *
	 * @return array
	 */
	private function get_enabled_services($country)
	{
	    $enabled_domestic_options = $this->enabled_domestic_options;

	    if ( !is_array($enabled_domestic_options) ) {
	        $enabled_domestic_options = [ $enabled_domestic_options];
	    }

        if ($country === 'US') {
            return $enabled_domestic_options;
        }

		$enabled_intl_options = $this->enabled_intl_options;

		if ( !is_array($enabled_intl_options) ) {
			$enabled_intl_options = [ $enabled_intl_options];
		}

		return $enabled_intl_options;
	}

	/**
	 * Admin Panel Options
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 *
	 * @since 1.3.0
	 * @return void
	 */
	public function admin_options()
	{
		require_once (dirname(__FILE__).'/views/admin-options.php');
	}

	private function debug_environment()
	{
		if (!WPRuby_UPS_Debugger::isEnabled()) {
			return;
		}

		$environment = [
			'php_version'         =>  phpversion(),
			'woocommerce_version' =>  WC()->version,
			'plugin_version'      =>  WPRUBY_UPS_CURRENT_VERSION,
			'weight_unit'         =>  get_option('woocommerce_weight_unit'),
			'dimensions_unit'     =>  get_option('woocommerce_dimension_unit'),
			'base_country'        =>  WC()->countries->get_base_country(),
		];

		WPRuby_UPS_Debugger::debug('Environment', $environment);
	}
}
