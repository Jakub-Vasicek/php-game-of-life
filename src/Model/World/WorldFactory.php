<?php

declare(strict_types=1);

namespace BE\GoL\Model\World;

use BE\GoL\Model\Cell\Exception\CellDoesNotExistException;

class WorldFactory
{
    /**
     * @throws CellDoesNotExistException
     */
    public function createWorld(WorldData $worldData): World
    {
        $world = new World($worldData->getXDimension(), $worldData->getYDimension(), $worldData->getSpeciesCount());

        foreach ($worldData->getOccupiedCells() as $occupiedCell) {
            $world->updateCell($occupiedCell);
        }

        return $world;
    }
}