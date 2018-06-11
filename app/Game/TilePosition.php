<?php

namespace App\Game;

use App\Exceptions\InvalidTilePositionException;

class TilePosition
{
    /**
     * Tile position
     *
     * @var integer
     */
    protected $position;

    /**
     * Minimum position possible.
     *
     * @var integer
     */
    protected $min = 1;

    /**
     * Maximum position possible.
     *
     * @var integer
     */
    protected $max = 9;

    /**
     * Class constructor.
     *
     * @param integer $position
     */
    public function __construct(int $position)
    {
        $this->setPosition($position);
    }

    /**
     * Sets the tiles position.
     *
     * @param integer $position
     * @return void
     * @throws \App\Exceptions\InvalidTilePositionException
     */
    protected function setPosition(int $position)
    {
        if ($position > $this->max or $position < $this->min) {
            throw new InvalidTilePositionException;
        }

        $this->position = $position;
    }

    /**
     * Get the tile's position.
     *
     * @return integer
     */
    public function getPosition(): int
    {
        return $this->position;
    }
}
