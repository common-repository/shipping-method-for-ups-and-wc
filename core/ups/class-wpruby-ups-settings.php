<?php
namespace WPRuby_Ups\Core\Ups;

use WPRuby_UPS_Libs\Ups\Rate;
use WPRuby_UPS_Libs\Ups\RateTimeInTransit;

class WPRuby_UPS_Settings {

	private $ups_customer_classification;
	private $show_duration;
	private $handling_fee;
	private $ups_access_key;
	private $ups_user_id;
	private $ups_password;
	private $live_mode;
	/**  @var array */
	private $enabled_services;

	/**
	 * WPRuby_UPS_Settings constructor.
	 *
	 * @param array $settings
	 * @param array $enabled_services
	 */
	public function __construct(array $settings, array $enabled_services)
	{
		$this->setUpsCustomerClassification($settings['ups_customer_classification']);
		$this->setShowDuration($settings['show_duration'] === 'yes');
		$this->setHandlingFee($settings['handling_fee']);
		$this->setUpsAccessKey($settings['ups_access_key']);
		$this->setUpsUserId($settings['ups_user_id']);
		$this->setUpsPassword($settings['ups_password']);
		$this->setLiveMode($settings['live_mode'] === 'test');

		$this->enabled_services = $enabled_services;
	}

	/**
	 * @return Rate|RateTimeInTransit
	 */
	public function get_client()
	{
		if ($this->isShowDuration()) {
			return new RateTimeInTransit($this->getUpsAccessKey(), $this->getUpsUserId(), $this->getUpsPassword(), $this->isLiveMode() );
		}

		return new Rate($this->getUpsAccessKey(), $this->getUpsUserId(), $this->getUpsPassword(), $this->isLiveMode()  );

	}

	/**
	 * @return string
	 */
	public function getUpsCustomerClassification(): string
	{
		return $this->ups_customer_classification;
	}

	/**
	 * @param string $ups_customer_classification
	 *
	 * @return WPRuby_UPS_Settings
	 */
	public function setUpsCustomerClassification( string $ups_customer_classification ): WPRuby_UPS_Settings {
		$this->ups_customer_classification = $ups_customer_classification;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isShowDuration(): bool {
		return $this->show_duration;
	}

	/**
	 * @param bool $show_duration
	 *
	 * @return WPRuby_UPS_Settings
	 */
	public function setShowDuration( bool $show_duration ): WPRuby_UPS_Settings {
		$this->show_duration = $show_duration;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getUpsAccessKey(): string {
		return $this->ups_access_key;
	}

	/**
	 * @param string $ups_access_key
	 *
	 * @return WPRuby_UPS_Settings
	 */
	public function setUpsAccessKey( string $ups_access_key ): WPRuby_UPS_Settings {
		$this->ups_access_key = $ups_access_key;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getUpsUserId() {
		return $this->ups_user_id;
	}

	/**
	 * @param mixed $ups_user_id
	 *
	 * @return WPRuby_UPS_Settings
	 */
	public function setUpsUserId( $ups_user_id ) {
		$this->ups_user_id = $ups_user_id;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getUpsPassword() {
		return $this->ups_password;
	}

	/**
	 * @param mixed $ups_password
	 *
	 * @return WPRuby_UPS_Settings
	 */
	public function setUpsPassword( $ups_password ) {
		$this->ups_password = $ups_password;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isLiveMode() {
		return $this->live_mode;
	}

	/**
	 * @param bool $live_mode
	 *
	 * @return WPRuby_UPS_Settings
	 */
	public function setLiveMode( $live_mode ) {
		$this->live_mode = $live_mode;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getHandlingFee() {
		return $this->handling_fee;
	}

	/**
	 * @param mixed $handling_fee
	 *
	 * @return WPRuby_UPS_Settings
	 */
	public function setHandlingFee( $handling_fee ) {
		$this->handling_fee = $handling_fee;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getEnabledServices(): array {
		return $this->enabled_services;
	}

	/**
	 * @param array $enabled_services
	 */
	public function setEnabledServices( array $enabled_services ): void {
		$this->enabled_services = $enabled_services;
	}


}
