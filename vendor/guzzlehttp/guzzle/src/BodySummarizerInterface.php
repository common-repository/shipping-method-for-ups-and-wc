<?php

namespace WPRuby_UPS_Libs\GuzzleHttp;

use WPRuby_UPS_Libs\Psr\Http\Message\MessageInterface;

interface BodySummarizerInterface
{
    /**
     * Returns a summarized message body.
     */
    public function summarize(MessageInterface $message): ?string;
}
