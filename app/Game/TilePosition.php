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

    /**
     * Get the position on the Y axis
     *
     * @return integer
     */
    public function yAxis(): int
    {
        if ($this->getPosition() > 6) {
            return 2;
        }

        if ($this->getPosition() > 3) {
            return 1;
        }

        return 0;
    }

    /**
     * Get the position on the X axis
     *
     * @return integer
     */
    public function xAxis(): int
    {
        $position = $this->getPosition() - ($this->yAxis() * 3);

        return $position === 0 ? $position : $position - 1;
    }
}
