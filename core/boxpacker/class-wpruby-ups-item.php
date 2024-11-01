<?php

namespace WPRuby_Ups\Core\Boxpacker;

use JsonSerializable;
use WPRuby_UPS_Libs\DVDoug\BoxPacker\Item;

class WPRuby_UPS_Item implements Item, JsonSerializable
{
	/**
	 * @var string
	 */
	private $description;

	/**
	 * @var int
	 */
	private $width;

	/**
	 * @var int
	 */
	private $length;

	/**
	 * @var int
	 */
	private $depth;

	/**
	 * @var int
	 */
	private $weight;

	/**
	 * @var int
	 */
	private $keepFlat;

	/**
	 * @var int
	 */
	private $volume;

	/**
	 * @var float
	 */
	private $price;



	/**
	 * Hermes_Item constructor.
	 *
	 * @param string $description
	 * @param int    $width
	 * @param int    $length
	 * @param int    $depth
	 * @param int    $weight
	 * @param bool   $keepFlat
	 * @param float   $price
	 */
	public function __construct(
		$description,
		$width,
		$length,
		$depth,
		$weight,
		$keepFlat,
		$price
	)
	{
		$this->description = $description;
		$this->width = $width;
		$this->length = $length;
		$this->depth = $depth;
		$this->weight = $weight;
		$this->keepFlat = $keepFlat;
		$this->price = $price;

		$this->volume = $this->width * $this->length * $this->depth;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @return int
	 */
	public function getWidth(): int
	{
		return $this->width;
	}

	/**
	 * @return int
	 */
	public function getLength(): int
	{
		return $this->length;
	}

	/**
	 * @return int
	 */
	public function getDepth(): int
	{
		return $this->depth;
	}

	/**
	 * @return int
	 */
	public function getWeight(): int
	{
		return $this->weight;
	}

	/**
	 * @return int
	 */
	public function getVolume()
	{
		return $this->volume;
	}

	/**
	 * @return bool
	 */
	public function getKeepFlat(): bool
	{
		return $this->keepFlat;
	}

	/**
	 * @return float
	 */
	public function getPrice(): float
	{
		return $this->price;
	}

	/**
	 * {@inheritdoc}
	 */
	public function jsonSerialize()
	{
		return [
			'description' => $this->description,
			'width' => $this->width,
			'length' => $this->length,
			'depth' => $this->depth,
			'weight' => $this->weight,
			'keepFlat' => $this->keepFlat,
			'price' => $this->price,
		];
	}
}
