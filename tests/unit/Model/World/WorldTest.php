<?php

declare(strict_types=1);

namespace Tests\Life\unit\Model\World;

use BE\GoL\Model\World\World;
use PHPUnit\Framework\TestCase;

class WorldTest extends TestCase
{

    public function testWorldGetsGeneratedWithEmptyCells(): void
    {
        $testWorld = new World(2,2, 1);
        $testCell = $testWorld->getCellByCoordinates(0,0);
        $this->assertNull($testCell->getType());
    }
}
