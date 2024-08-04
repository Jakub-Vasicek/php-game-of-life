<?php

declare(strict_types=1);

namespace BE\GoL\Model\World;

use BE\GoL\Model\Cell\Cell;
use BE\GoL\Model\Cell\Exception\CellDoesNotExistException;

class World
{
    /**
     * @var Cell[][]
     */
    private array $cells;

    public function __construct(
        private readonly int $width,
        private readonly int $height,
        private readonly int $speciesCount,
    ) {
        for ($y=0; $y < $this->height; $y++) {
            for ($x=0; $x < $this->width; $x++) {
                $this->cells[$x][$y] = new Cell($x, $y);
            }
        }
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @throws CellDoesNotExistException
     */
    public function getCellByCoordinates(int $x, int $y): Cell
    {
        return $this->cells[$x][$y] ?? throw CellDoesNotExistException::fromCoordinates($x, $y);
    }

    /**
     * @throws CellDoesNotExistException
     */
    public function updateCell(Cell $cell): void
    {
        $x = $cell->getXCoordinate();
        $y = $cell->getYCoordinate();

        $this->cells[$x][$y] ?? throw CellDoesNotExistException::fromCoordinates($x, $y);
        $this->cells[$x][$y] = $cell;
    }

    public function getSpeciesCount()
    {
        return $this->speciesCount;
    }
}
