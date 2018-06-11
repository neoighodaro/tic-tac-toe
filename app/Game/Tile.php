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
     * Get the tile type.
     *
     * @return App\Game\TileType
     */
    public function getType(): TileType
    {
        return $this->type;
    }

    /**
     * Get the tile position.
     *
     * @return App\Game\TilePosition
     */
    public function getPosition(): TilePosition
    {
        return $this->position;
    }

    /**
     * Set the tile position.
     *
     * @param integer $position
     * @return \App\Game\Tile
     */
    protected function setPosition(int $position) : Tile
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
    protected function setType(string $type) : Tile
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
                return call_user_func_array([$this, 'setPosition'], $args);
            }

            $type = substr($name, -4) === 'Type';

            if ($type) {
                return call_user_func_array([$this, 'setType'], $args);
            }
        }
    }
}
