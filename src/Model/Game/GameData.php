<?php

declare(strict_types=1);

namespace BE\GoL\Model\Game;

use BE\GoL\Model\World\World;

class GameData
{
    public function __construct(
        private readonly World $world,
        private readonly int $iterationsCount,
    )
    {
    }

    public function getWorld(): World
    {
        return $this->world;
    }

    public function getIterationsCount(): int
    {
        return $this->iterationsCount;
    }
}
