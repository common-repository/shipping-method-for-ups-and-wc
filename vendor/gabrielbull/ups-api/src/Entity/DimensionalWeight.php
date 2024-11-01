<?php

namespace WPRuby_UPS_Libs\Ups\Entity;

class DimensionalWeight
{
    /**
     * @var UnitOfMeasurement
     */
    public $UnitOfMeasurement;

    public $Weight;

    public function __construct()
    {
        $this->UnitOfMeasurement = new UnitOfMeasurement();
    }
}
