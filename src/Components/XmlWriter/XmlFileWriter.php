<?php declare(strict_types = 1);

namespace BE\GoL\Components\XmlWriter;

use BE\GoL\Components\XmlWriter\Exception\OutputWritingException;
use BE\GoL\Model\World\World;
use SimpleXMLElement;

class XmlFileWriter
{
    private const OUTPUT_TEMPLATE = '/output-template.xml';

    public function saveWorld(World $world, string $filePath): void
    {
        $life = simplexml_load_string(file_get_contents(__DIR__ . self::OUTPUT_TEMPLATE));
        $life->world->width = $world->getWidth();
        $life->world->height = $world->getHeight();
        $life->world->species = $world->getSpeciesCount();
        for ($y = 0; $y < $world->getHeight(); $y++) {
            for ($x = 0; $x < $world->getWidth(); $x++) {
                $cell = $world->getCellByCoordinates($x, $y);
                if ($cell->getType() !== null) {
                    /** @var SimpleXMLElement $organism */
                    $organism = $life->organisms->addChild('organism');
                    $organism->addChild('x_pos', (string)$x);
                    $organism->addChild('y_pos', (string)$y);
                    $organism->addChild('species', (string)$cell->getType());
                }
            }
        }
        $this->saveXml($life, $filePath);
    }


    private function saveXml(SimpleXMLElement $life, string $filePath): void
    {
        $dom = new \DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($life->asXML());
        $result = file_put_contents($filePath, $dom->saveXML());
        if ($result === false) {
            throw new OutputWritingException("Writing XML file failed");
        }
    }
}
