<?php

namespace WPRuby_UPS_Libs\Ups\Entity;

use DOMDocument;
use DOMElement;
use WPRuby_UPS_Libs\Ups\NodeInterface;

class Locale implements NodeInterface
{
    /**
     * @var string
     */
    private $language;

    /**
     * @var string
     */
    private $dialect;

    /**
     * @param null|DOMDocument $document
     *
     * @return DOMElement
     */
    public function toNode(DOMDocument $document = null)
    {
        if (null === $document) {
            $document = new DOMDocument();
        }

        $node = $document->createElement('Locale');

        $node->appendChild($document->createElement('Language', $this->getLanguage()));
        $node->appendChild($document->createElement('Dialect', $this->getDialect()));

        return $node;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     *
     * @return Locale
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return string
     */
    public function getDialect()
    {
        return $this->dialect;
    }

    /**
     * @param string $dialect
     *
     * @return Locale
     */
    public function setDialect($dialect)
    {
        $this->dialect = $dialect;

        return $this;
    }
}
