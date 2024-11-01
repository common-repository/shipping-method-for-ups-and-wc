<?php
namespace WPRuby_Ups\Core\Ups;

use WPRuby_UPS_Libs\Ups\Entity\Address;
use WPRuby_UPS_Libs\Ups\Entity\CustomerClassification;
use WPRuby_UPS_Libs\Ups\Entity\DeliveryTimeInformation;
use WPRuby_UPS_Libs\Ups\Entity\Dimensions;
use WPRuby_UPS_Libs\Ups\Entity\Package;
use WPRuby_UPS_Libs\Ups\Entity\PackagingType;
use WPRuby_UPS_Libs\Ups\Entity\RatedShipment;
use WPRuby_UPS_Libs\Ups\Entity\RateRequest;
use WPRuby_UPS_Libs\Ups\Entity\ShipFrom;
use WPRuby_UPS_Libs\Ups\Entity\Shipment;
use WPRuby_UPS_Libs\Ups\Entity\ShipTo;
use WPRuby_UPS_Libs\Ups\Entity\UnitOfMeasurement;
use WPRuby_UPS_Libs\Ups\Exception\InvalidResponseException;
use WPRuby_Ups\Core\Helpers\WPRuby_UPS_Debugger;

class WPRuby_UPS_Calculator  {
	/**
	 * @var WPRuby_UPS_Package
	 */
	private $package;
	/**
	 * @var WPRuby_UPS_Settings
	 */
	private $settings;

	/**
	 * UPS_Calculator constructor.
	 *
	 * @param WPRuby_UPS_Package $package
	 * @param WPRuby_UPS_Settings $settings
	 */
	public function __construct(WPRuby_UPS_Package $package, WPRuby_UPS_Settings $settings) {
		$this->package = $package;
		$this->settings = $settings;
	}

	/**
	 * @return WPRuby_UPS_Rate[]
	 * @throws \Exception
	 */
	public function calculate(): array
    {

	    $rate = $this->settings->get_client();
	    $shipment = new Shipment();

	    // 1. Add From and To addresses
        $shipment = $this->add_addresses($shipment);

	    // 2. Set up package weight and dimensions
	    $shipment = $this->add_package_information($shipment);


	    $rateRequest = new RateRequest();

	    if ($this->settings->getUpsCustomerClassification() !== 'NA') {
		    $customerClassification = new CustomerClassification();
		    $customerClassification->setCode($this->settings->getUpsCustomerClassification());
		    $rateRequest->setCustomerClassification($customerClassification);
		    $rateRequest->setShipment($shipment);
	    }

	    $ups_rates = [];

	    try {
		    if ($this->settings->isShowDuration() === 'yes') {
			    $deliveryTimeInformation = new DeliveryTimeInformation();
			    $deliveryTimeInformation->setPackageBillType(DeliveryTimeInformation::PBT_NON_DOCUMENT);

			    $shipment->setDeliveryTimeInformation($deliveryTimeInformation);
			    $ratedShipments = ($this->settings->getUpsCustomerClassification() === 'NA')?
				    $rate->getRateTimeInTransit($shipment)->RatedShipment:
				    $rate->shopRatesTimeInTransit($rateRequest)->RatedShipment;
		    }else{
			    $ratedShipments = ($this->settings->getUpsCustomerClassification() === 'NA')?
				    $rate->getRate($shipment)->RatedShipment:
				    $rate->shopRates($rateRequest)->RatedShipment;
		    }
		    /** @var RatedShipment $ratedShipment */
		    foreach($ratedShipments as $ratedShipment) {
			    if (!in_array($ratedShipment->Service->getCode(), $this->settings->getEnabledServices() )) {
				    continue;
			    }
			    $service_key = $ratedShipment->Service->getCode();
			    $label = $ratedShipment->getServiceName() .' '. $this->get_delivery_time($ratedShipment);
			    $ups_rates[$service_key] = (new WPRuby_UPS_Rate())
				    ->setId($service_key)
				    ->setLabel($label)
				    ->setCost($this->get_monetary_rate($ratedShipment));
		    }
	    } catch (InvalidResponseException $e) {
		    WPRuby_UPS_Debugger::debug('Error', $e->getMessage(), 'error');
	    }

	    uasort($ups_rates, function ($rateA, $rateB) {
	    	return $rateA->getCost() <=> $rateB->getCost();
	    });

	    return $ups_rates;
    }

	private function get_ship_from_address() : ShipFrom
	{
		$shipFrom = new ShipFrom();
		$shipFrom->setAddress( (new Address())
			->setPostalCode(WC()->countries->get_base_postcode())
			->setCountryCode(WC()->countries->get_base_country()));

		return $shipFrom;
	}

	private function get_ship_to_address() : ShipTo
	{
		$shipToAddress = new Address();
		$shipToAddress->setPostalCode($this->package->getPostcode());
		$shipToAddress->setCountryCode($this->package->getCountry());
		$shipTo = new ShipTo();
		$shipTo->setAddress($shipToAddress);
		return $shipTo;
	}

	private function get_dimensions() :Dimensions
	{
		$dimensions = new Dimensions();
		$dimensions->setHeight($this->package->getHeight());
		$dimensions->setWidth($this->package->getWidth());
		$dimensions->setLength($this->package->getLength());
		$unit = (new UnitOfMeasurement())->setCode( $this->get_dimension_unit());

		$dimensions->setUnitOfMeasurement($unit);

		return $dimensions;
	}


	/**
	 * @param RatedShipment $ratedShipment
	 *
	 * @return string
	 */
	private function get_delivery_time( RatedShipment $ratedShipment ): string
	{
		if($this->settings->isShowDuration() && is_numeric($ratedShipment->GuaranteedDaysToDelivery)){
			return sprintf('(est. delivery: %s days)', $ratedShipment->GuaranteedDaysToDelivery);
		}
		return '';
	}


	/**
	 * @param RatedShipment $ratedShipment
	 *
	 * @return float
	 */
	private function get_monetary_rate( RatedShipment $ratedShipment ): float
	{
		return $this->calculate_with_handling_fee($ratedShipment->TotalCharges->MonetaryValue);
	}

	/**
	 * [calculate the handling fees]
	 *
	 * @param $cost
	 *
	 * @return int|string [number]       [description]
	 * @internal param $ [number] $cost [description]
	 */
	public function calculate_with_handling_fee($cost)
	{
		$handling_fee = $this->settings->getHandlingFee();

		if ($handling_fee == '') {
			return 0 + $cost;
		}

		if (substr($handling_fee, -1) == '%') {
			$handling_fee = trim(str_replace('%', '', $handling_fee));
			$result = ($cost * ($handling_fee / 100));
			return $result + $cost;
		}

		if (is_numeric($handling_fee)) {
			return $handling_fee + $cost;
		}
		return 0 + $cost;
	}

	private function get_dimension_unit(): string
	{
		$store_unit = strtolower( get_option( 'woocommerce_dimension_unit' ) );
		switch ($store_unit) {
			case 'yd':
			case 'in':
				return UnitOfMeasurement::UOM_IN;
			case 'm':
			case 'cm':
			case 'mm':
				return UnitOfMeasurement::UOM_CM;
		}

		return UnitOfMeasurement::UOM_IN;
	}

	private function get_weight_unit(): string
	{
		$store_weight_unit = strtolower( get_option( 'woocommerce_weight_unit' ) );
		switch ($store_weight_unit) {
			case 'kg':
			case 'g':
				return UnitOfMeasurement::UOM_KGS;
			case 'lbs':
			case 'oz':
				return UnitOfMeasurement::UOM_LBS;
		}

		return UnitOfMeasurement::UOM_LBS;
	}

	private function add_addresses(Shipment $shipment) : Shipment
	{
		$shipment->setShipFrom($this->get_ship_from_address());
		$shipment->setShipTo($this->get_ship_to_address());
		return $shipment;
	}

	private function add_package_information( Shipment $shipment ) :Shipment
	{
		$package = new Package();
		$package->getPackagingType()->setCode( PackagingType::PT_PACKAGE);
		$measurementUnit = (new UnitOfMeasurement())->setCode( $this->get_weight_unit());
		$package->getPackageWeight()
		        ->setUnitOfMeasurement($measurementUnit)
		        ->setWeight($this->package->getWeight());
		$package->setDimensions($this->get_dimensions());
		$shipment->addPackage($package);
		return $shipment;
	}

}
