<?php

declare(strict_types=1);

namespace BE\GoL\Model\Cell\Exception;

class CellDoesNotExistException extends \Exception
{
    public static function fromCoordinates(int $x, int $y): CellDoesNotExistException
    {
        return new self(sprintf("Cell with coordinates [%d, %d] doesn't exist.", $x, $y));
    }
}