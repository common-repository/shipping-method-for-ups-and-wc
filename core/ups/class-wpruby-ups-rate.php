<?php
namespace WPRuby_Ups\Core\Ups;

class WPRuby_UPS_Rate {

	/** @var string */
	private $id;
	/** @var string */
	private $label;
	/** @var float */
	private $cost;

	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * @param string $id
	 *
	 * @return WPRuby_UPS_Rate
	 */
	public function setId( string $id ): WPRuby_UPS_Rate {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLabel(): string {
		return $this->label;
	}

	/**
	 * @param string $label
	 *
	 * @return WPRuby_UPS_Rate
	 */
	public function setLabel( string $label ): WPRuby_UPS_Rate {
		$this->label = $label;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getCost(): float {
		return $this->cost;
	}

	public function addCost(float $cost): WPRuby_UPS_Rate {
		$this->setCost($cost + $this->getCost());
		return $this;
	}

	/**
	 * @param float $cost
	 *
	 * @return WPRuby_UPS_Rate
	 */
	public function setCost( float $cost ): WPRuby_UPS_Rate {
		$this->cost = $cost;

		return $this;
	}

	public function toArray(): array
	{
		return [
			'id'      => $this->getId(),
			'cost'    => $this->getCost(),
			'label'   => $this->getLabel(),
		];
	}

}
