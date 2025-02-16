<?php

namespace WPRuby_UPS_Libs\Ups;

use DOMDocument;
use DOMElement;
use Exception;
use SimpleXMLElement;
use WPRuby_UPS_Libs\Ups\Entity\RateRequest;
use WPRuby_UPS_Libs\Ups\Entity\RateResponse;
use WPRuby_UPS_Libs\Ups\Entity\Shipment;

/**
 * RateTimeInTransit API Wrapper.
 */
class RateTimeInTransit extends Rate
{
    /**
     * @param $rateRequest
     *
     * @throws Exception
     *
     * @return RateResponse
     */
    public function getRateTimeInTransit($rateRequest)
    {
        if ($rateRequest instanceof Shipment) {
            $shipment = $rateRequest;
            $rateRequest = new RateRequest();
            $rateRequest->setShipment($shipment);
        }

        $this->requestOption = 'Ratetimeintransit';

        return $this->sendRequest($rateRequest);
    }

    /**
     * @param $rateRequest
     *
     * @throws Exception
     *
     * @return RateResponse
     */
    public function shopRatesTimeInTransit($rateRequest)
    {
        if ($rateRequest instanceof Shipment) {
            $shipment = $rateRequest;
            $rateRequest = new RateRequest();
            $rateRequest->setShipment($shipment);
        }

        $this->requestOption = 'Shoptimeintransit';

        return $this->sendRequest($rateRequest);
    }
}
