<?php

namespace App\Game;

use App\Exceptions\GameException;
use App\Exceptions\InvalidTileTypeException;

class TileType
{
    /**
     * Available tile types.
     */
    const X = 'X';
    const O = 'O';

    /**
     * The tile type.
     *
     * @var string
     */
    protected $type;

    /**
     * Creates a new instance of the class.
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->setType($type);
    }

    /**
     * Set the type of tile.
     *
     * @param string $type
     * @return void
     * @throws \App\Exceptions\InvalidTileTypeException
     */
    protected function setType(string $type)
    {
        if ($type !== static::X and $type !== static::O) {
            throw new InvalidTileTypeException;
        }

        $this->type = $type;
    }

    /**
     * Get the type of tile.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
