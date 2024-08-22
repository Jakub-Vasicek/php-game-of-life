<?php

declare(strict_types=1);

namespace BE\GoL\Model\World;

class WorldData
{
    public function __construct(
        private readonly int $xDimension,
        private readonly int $yDimension,
        private readonly array $occupiedCells,
        private readonly int $speciesCount
    )
    {
    }

    public function getXDimension(): int
    {
        return $this->xDimension;
    }

    public function getYDimension(): int
    {
        return $this->yDimension;
    }

    public function getOccupiedCells(): array
    {
        return $this->occupiedCells;
    }

    public function getSpeciesCount(): int
    {
        return $this->speciesCount;
    }
}
