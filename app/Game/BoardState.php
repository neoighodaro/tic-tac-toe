<?php

namespace App\Game;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use App\Exceptions\InvalidBoardStateException;

class BoardState implements Arrayable, Jsonable
{
    /**
     * Board state.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $state;

    /**
     * Default board state.
     *
     * @var array
     */
    protected $defaultState = [
        ['', '', ''],
        ['', '', ''],
        ['', '', ''],
    ];

    /**
     * Class constructor
     *
     * @param array $state
     */
    public function __construct()
    {
        $this->state = new Collection($this->defaultState);
    }

    public function add($tiles): BoardState
    {
        if (! is_array($tiles)) {
            $tiles = [$tiles];
        }

        foreach ($tiles as $tile) {
            if (! $tile instanceof Tile) {
                throw new InvalidBoardStateException;
            }

            $currentState = $this->state[$tile->getRow()];
            $currentState[$tile->getRowPosition()] = $tile->getUnit();

            $this->state[$tile->getRow()] = $currentState;
        }

        return $this;
    }

    /**
     * Returns an array representation of the state.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->state->toArray();
    }

    /**
     * Returns a JSON object representation.
     *
     * @param integer $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return $this->state->toJson($options);
    }
}
