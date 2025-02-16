<?php

namespace WPRuby_UPS_Libs\Ups\Entity;

class LabelImageFormat
{
    public $Code;

    /**
     * @param \stdClass|null $response
     */
    public function __construct(\stdClass $response = null)
    {
        if (null !== $response) {
            if (isset($response->Code)) {
                $this->Code = $response->Code;
            }
        }
    }
}
