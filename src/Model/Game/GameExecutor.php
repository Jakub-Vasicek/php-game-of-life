<?php declare(strict_types = 1);

namespace BE\GoL\Model\Game;

use BE\GoL\Model\Cell\Cell;
use BE\GoL\Model\Cell\Exception\CellDoesNotExistException;
use BE\GoL\Model\World\World;

class GameExecutor
{
    private const LOWER_BOUND_FOR_CELL_SURVIVAL = 2;
    private const UPPER_BOUND_FOR_CELL_SURVIVAL = 3;
    private const NUMBER_OF_CELLS_NEEDED_FOR_REPRODUCTION = 3;

    /**
     * @throws CellDoesNotExistException
     */
    public function run(World $world, int $iterationsCount): World
    {
        for ($i = 0; $i < $iterationsCount; $i++) {
            $world = $this->evolveWorld($world);
        }

        return $world;
    }

    /**
     * @throws CellDoesNotExistException
     */
    private function evolveWorld(World $world): World
    {
        $newWorld = clone $world;
        for ($y=0; $y < $world->getHeight(); $y++) {
            for ($x=0; $x < $world->getWidth(); $x++) {
                $newCell = $this->evolveCellInWorld($world->getCellByCoordinates($x, $y), $world);
                $newWorld->updateCell($newCell);
            }
        }

        return $newWorld;
    }

    private function evolveCellInWorld(Cell $cell, World $world): Cell
    {
        $neighbours = $this->getCellNeighbours($cell, $world);
        $sameSpeciesCount = $this->getSameSpeciesCountFromNeighbouringCell($neighbours, $cell);

        if (
            $cell->getType() !== null &&
            $sameSpeciesCount >= self::LOWER_BOUND_FOR_CELL_SURVIVAL &&
            $sameSpeciesCount <= self::UPPER_BOUND_FOR_CELL_SURVIVAL
        ) {
            return $cell;
        }

        return $this->createNewCellFromNeighbouringCells($neighbours, clone $cell);
    }

    /**
     * @return Cell[]
     */
    private function getCellNeighbours(Cell $cell, World $world): array
    {
        $neighbours = [];
        $y = $cell->getYCoordinate();
        $x = $cell->getXCoordinate();

        $offsetModificators = [
            [-1,1], [0,1], [1,1],
            [-1,0],/*[0,0]*/[1,0],
            [-1,-1],[0,-1],[1,-1]
        ];

        foreach ($offsetModificators as [$xModifier, $yModifier]) {
            try {
                $neighbours[] = $world->getCellByCoordinates($x + $xModifier, $y + $yModifier);
            } catch (CellDoesNotExistException) {
                //If cell does not exist, it is not a neighbour
                continue;
            }
        }

        return $neighbours;
    }

    private function getSameSpeciesCountFromNeighbouringCell(array $neighbours, Cell $cell): int
    {
        $sameSpeciesCount = 0;
        if ($cell->getType() === null) {
            return $sameSpeciesCount;
        }

        foreach ($neighbours as $neighbour) {
            if ($neighbour->getType() === $cell->getType()) {
                $sameSpeciesCount++;
            }
        }

        return $sameSpeciesCount;
    }

    public function createNewCellFromNeighbouringCells(array $neighbours, Cell $cell): Cell
    {
        $neighbouringSpeciesCount = [];
        foreach ($neighbours as $neighbour) {
            if ($neighbour->getType() === null) {
                continue;
            }

            if (!array_key_exists($neighbour->getType(), $neighbouringSpeciesCount)) {
                $neighbouringSpeciesCount[$neighbour->getType()] = 1;
                continue;
            }

            $neighbouringSpeciesCount[$neighbour->getType()]++;
        }

        $speciesForBirth = [];
        foreach ($neighbouringSpeciesCount as $type => $count) {
            if ($count === self::NUMBER_OF_CELLS_NEEDED_FOR_REPRODUCTION) {
                $speciesForBirth[$type] = $count;
            }
        }

        if (count($speciesForBirth) > 0) {
            $cell->setType(array_rand($speciesForBirth));
            return $cell;
        }

        $cell->setType(null);
        return $cell;
    }
}
