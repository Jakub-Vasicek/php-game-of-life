<?php

declare(strict_types=1);

namespace BE\GoL\Model\Cell;

class Cell
{
    public function __construct(
        private int $xCoordinate,
        private int $yCoordinate,
        private ?int $type = null
    )
    {
    }

    public function getXCoordinate(): int
    {
        return $this->xCoordinate;
    }

    public function getYCoordinate(): int
    {
        return $this->yCoordinate;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;
        return $this;
    }
}