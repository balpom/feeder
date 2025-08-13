<?php

declare(strict_types=1);

namespace Balpom\Feeder;

use Balpom\Feeder\AbstractCreator;
use Balpom\Entity\Structures\AbstractStructure;
use \DOMDocument as Document;
use \DOMElement as Element;

abstract class XmlCreator extends AbstractCreator
{
    protected Document $xml;

    public function __construct(AbstractStructure $structure)
    {
        $this->xml = $this->getDOMDocument();
        parent::__construct($structure);
    }

    protected function getDOMDocument(string $version = '1.0', string $encoding = 'UTF-8'): Document
    {
        $xml = new Document($version, $encoding);
        $xml->formatOutput = true;

        return $xml;
    }

    protected function appendElement(Element &$element, string $name, string $value = ''): void
    {
        $newElement = $this->xml->createElement($name, $value);
        $element->appendChild($newElement);
    }

    protected function appendNotEmptyElement(Element &$element, string $name, string|null $value = null): void
    {
        if (!empty($value)) {
            $newElement = $this->xml->createElement($name, $value);
            $element->appendChild($newElement);
        }
    }

    protected function appendNotEmptyChild(Element &$element, Element|null $child): void
    {
        if (!empty($child)) {
            $element->appendChild($child);
        }
    }

    protected function isEmpty(\Countable|null $countable): bool
    {
        if (empty($countable) || 0 === count($countable)) {
            return true;
        }

        return false;
    }

}
