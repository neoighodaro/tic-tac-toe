<?php

namespace App\Game;

class Tile
{
    /**
     * The type of type with possible values of 'X' and 'O'.
     *
     * @var \App\Game\TileType
     */
    protected $type;

    /**
     * The position of the tile in the board.
     *
     * @var \App\Game\TilePosition
     */
    protected $position;

    /**
     * Creates a new instance of the class.
     *
     * @param \App\Game\TileType $type
     * @param \App\Game\TilePosition $position
     */
    public function __construct(TileType $type = null, TilePosition $position = null)
    {
        if ($type and $position) {
            $this->type = $type;
            $this->position = $position;
        }
    }

    /**
     * Gets the position of the tile in the row of a board state.
     *
     * @return integer
     */
    public function positionOnYAxis(): int
    {
        return $this->position->yAxis();
    }

    /**
     * Gets the row of the tile in the board state.
     *
     * @return integer
     */
    public function positionOnXAxis(): int
    {
        return $this->position->xAxis();
    }

    /**
     * Get the tile position as an integer.
     *
     * @return integer
     */
    public function gridPosition(): int
    {
        return $this->getTilePosition()->getPosition();
    }

    /**
     * Gets the tile unit.
     *
     * @return string
     */
    public function getType() : string
    {
        return $this->type->getType();
    }

    /**
     * Get the tile type.
     *
     * @return App\Game\TileType
     */
    public function getTileType(): TileType
    {
        return $this->type;
    }

    /**
     * Get the tile position.
     *
     * @return App\Game\TilePosition
     */
    public function getTilePosition(): TilePosition
    {
        return $this->position;
    }

    /**
     * Set the tile position.
     *
     * @param integer $position
     * @return \App\Game\Tile
     */
    protected function setTilePosition(int $position) : Tile
    {
        $this->position = new TilePosition($position);

        return $this;
    }

    /**
     * Set the tile type.
     *
     * @param string $type
     * @return \App\Game\Tile
     */
    protected function setTileType(string $type) : Tile
    {
        $this->type = new TileType($type);

        return $this;
    }

    /**
     * Make the calls more expressive and readable.
     *
     * Example:
     * $tile = (new Tile)->withType('O')->andPosition(9);
     *
     * @param  string $name
     * @param  array $args
     * @return void|App\Game\Tile
     */
    public function __call(string $name, array $args)
    {
        if (strpos($name, 'with') === 0 or strpos($name, 'and') === 0) {
            $position = substr($name, -8) === 'Position';

            if ($position) {
                return call_user_func_array([$this, 'setTilePosition'], $args);
            }

            $type = substr($name, -4) === 'Type';

            if ($type) {
                return call_user_func_array([$this, 'setTileType'], $args);
            }
        }
    }
}
