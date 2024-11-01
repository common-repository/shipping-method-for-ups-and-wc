<?php

namespace WPRuby_UPS_Libs\Ups\Entity\RateTimeInTransit;

use WPRuby_UPS_Libs\Ups\Entity\EstimatedArrivalTrait;

class EstimatedArrival
{
    use EstimatedArrivalTrait;

    protected $businessDaysInTransit;

    /**
     * @param \stdClass|null $response
     */
    public function __construct(\stdClass $response = null)
    {
        if (null !== $response) {
            $this->build($response);

            if (isset($response->BusinessDaysInTransit)) {
                $this->businessDaysInTransit = $response->BusinessDaysInTransit;
            }
        }
    }

    /**
     * @return string
     */
    public function getBusinessDaysInTransit()
    {
        return $this->businessDaysInTransit;
    }

    /**
     * @param string $BusinessDaysInTransit
     */
    public function setBusinessDaysInTransit($BusinessDaysInTransit)
    {
        $this->businessDaysInTransit = $BusinessDaysInTransit;
    }
}
