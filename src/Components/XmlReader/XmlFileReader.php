<?php declare(strict_types = 1);

namespace BE\GoL\Components\XmlReader;

use BE\GoL\Components\XmlReader\Exception\InvalidInputException;
use BE\GoL\Model\Cell\Cell;
use BE\GoL\Model\Cell\Exception\CellDoesNotExistException;
use BE\GoL\Model\Game\GameData;
use BE\GoL\Model\World\WorldData;
use BE\GoL\Model\World\WorldFactory;
use SimpleXMLElement;

class XmlFileReader
{
    private WorldFactory $worldFactory;

    public function __construct()
    {
        $this->worldFactory = new WorldFactory();
    }

    /**
     * @throws InvalidInputException
     * @throws CellDoesNotExistException
     */
    public function loadFileAsGameData(string $filePath): GameData
    {
        $life = $this->loadXmlFile($filePath);
        $this->validateXmlFile($life);

        $xDimension = (int)$life->world->width;
        $yDimension = (int)$life->world->height;

        $world = $this->worldFactory->createWorld(
            new WorldData(
                $xDimension,
                $yDimension,
                $this->readCells($life),
                (int)$life->world->speciesCount
            )
        );

        return new GameData($world, (int)$life->world->iterations);
    }

    private function loadXmlFile(string $filePath): SimpleXMLElement
    {
        if (!file_exists($filePath)) {
            throw new InvalidInputException("Unable to read nonexistent file");
        }
        try {
            libxml_use_internal_errors(true);
            $life = simplexml_load_string(file_get_contents($filePath));
            $errors = libxml_get_errors();
            libxml_clear_errors();
            if (count($errors) > 0) {
                throw new InvalidInputException("Cannot read XML file");
            }
        }
        catch (\Exception) {
            throw new InvalidInputException("Cannot read XML file");
        }
        return $life;
    }

    /**
     * @throws InvalidInputException
     */
    private function validateXmlFile(SimpleXMLElement $life): void
    {
        if (!isset($life->world)) {
            throw new InvalidInputException("Missing element 'world'");
        }

        $iterations = (int)$life->world->iterations;
        if (!isset($iterations)) {
            throw new InvalidInputException("Missing element 'iterations'");
        }
        if ($iterations < 0) {
            throw new InvalidInputException("Value of element 'iterations' must be zero or positive number");
        }

        $width = (int)$life->world->width;
        if (!isset($width)) {
            throw new InvalidInputException("Missing element 'width'");
        }
        if ($width <= 0) {
            throw new InvalidInputException("Value of element 'width' must be positive number");
        }

        $height = (int)$life->world->height;
        if (!isset($height)) {
            throw new InvalidInputException("Missing element 'height'");
        }
        if ($height <= 0) {
            throw new InvalidInputException("Value of element 'height' must be positive number");
        }

        $speciesCount = (int)$life->world->speciesCount;
        if (!isset($speciesCount)) {
            throw new InvalidInputException("Missing element 'speciesCount'");
        }
        if ($speciesCount <= 0) {
            throw new InvalidInputException("Value of element 'speciesCount' must be positive number");
        }

        if (!isset($life->organisms)) {
            throw new InvalidInputException("Missing element 'organisms'");
        }
        foreach ($life->organisms->organism as $organism) {
            if (!isset($organism->x_pos)) {
                throw new InvalidInputException("Missing element 'x_pos' in some of the element 'organism'");
            }
            if (!isset($organism->y_pos)) {
                throw new InvalidInputException("Missing element 'y_pos' in some of the element 'organism'");
            }
            if (!isset($organism->speciesType)) {
                throw new InvalidInputException("Missing element 'speciesType' in some of the element 'organism'");
            }

            if ($organism->x_pos < 0 || $organism->x_pos >= $width) {
                throw new InvalidInputException("Value of element 'x_pos' of element 'organism' must be between 0 and height");
            }
            if ($organism->y_pos < 0 || $organism->y_pos >= $height) {
                throw new InvalidInputException("Value of element 'y_pos' of element 'organism' must be between 0 and width");
            }
            $thisSpecies = (int)$organism->species;
            if ($thisSpecies < 0 || $thisSpecies >= $speciesCount) {
                throw new InvalidInputException("Value of element 'speciesType' of element 'organism' must be between 0 and number of 'species'");
            }
        }
    }

    /**
     * @return Cell[]
     */
    private function readCells(SimpleXMLElement $life): array
    {
        $cells = [];
        $newCells = [];
        foreach ($life->organisms->organism as $organism) {
            $x = (int)$organism->x_pos;
            $y = (int)$organism->y_pos;
            $newCells[$y][$x][] = new Cell(
                $x,
                $y,
                (int)$organism->speciesType
            );
        }

        foreach ($newCells as $cellsRow) {
            foreach ($cellsRow as $cellOccupants) {
                $cells[] = $cellOccupants[array_rand($cellOccupants)];
            }
        }
        return $cells;
    }
}
