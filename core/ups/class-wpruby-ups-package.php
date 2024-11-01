<?php
namespace WPRuby_Ups\Core\Ups;

class WPRuby_UPS_Package
{

	/**  @var float */
	private $weight;
	/**  @var float */
	private $height;
	/**  @var float */
	private $width;
	/**  @var float */
	private $length;
	/**  @var string */
	private $postcode;
	/**  @var string */
	private $country;
	/**  @var float */
	private $price;

	/**
	 * @return float
	 */
	public function getWeight(): float
	{
		return $this->weight;
	}

	/**
	 * @param float $weight
	 *
	 * @return WPRuby_UPS_Package
	 */
	public function setWeight( float $weight ): WPRuby_UPS_Package
	{
		$this->weight = $weight;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getHeight(): float
	{
		return $this->height;
	}

	/**
	 * @param float $height
	 *
	 * @return WPRuby_UPS_Package
	 */
	public function setHeight( float $height ): WPRuby_UPS_Package
	{
		$this->height = $height;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getWidth(): float
	{
		return $this->width;
	}

	/**
	 * @param float $width
	 *
	 * @return WPRuby_UPS_Package
	 */
	public function setWidth( float $width ): WPRuby_UPS_Package
	{
		$this->width = $width;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getLength(): float
	{
		return $this->length;
	}

	/**
	 * @param float $length
	 *
	 * @return WPRuby_UPS_Package
	 */
	public function setLength( float $length ): WPRuby_UPS_Package
	{
		$this->length = $length;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPostcode(): string
	{
		return $this->postcode;
	}

	/**
	 * @param string $postcode
	 *
	 * @return WPRuby_UPS_Package
	 */
	public function setPostcode( string $postcode ): WPRuby_UPS_Package
	{
		$this->postcode = $postcode;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getCountry(): string
	{
		return $this->country;
	}

	/**
	 * @param string $country
	 *
	 * @return WPRuby_UPS_Package
	 */
	public function setCountry( string $country ): WPRuby_UPS_Package
	{
		$this->country = $country;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getPrice(): float {
		return $this->price;
	}

	/**
	 * @param float $price
	 *
	 * @return WPRuby_UPS_Package
	 */
	public function setPrice( float $price ): WPRuby_UPS_Package
	{
		$this->price = $price;

		return $this;
	}


}
