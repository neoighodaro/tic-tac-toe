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
     * An array of moves made.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $moves;

    /**
     * Class constructor
     *
     * @param array $state
     */
    public function __construct()
    {
        $this->moves = new Collection([]);

        $this->state = new Collection($this->defaultState);
    }

    /**
     * Adds a new tile to the board state.
     *
     * @param Tile|array $tiles
     * @return BoardState
     */
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
            $currentState[$tile->getRowPosition()] = $tile->getType();

            $this->moves->push([
                'x' => $tile->getRowPosition(),
                'y' => $tile->getRow(),
                'unit' => $tile->getType(),
            ]);

            $this->state[$tile->getRow()] = $currentState;
        }

        return $this;
    }

    /**
     * Returns the moves made in the board.
     *
     * @return Collection
     */
    public function getMoves(): Collection
    {
        return $this->moves;
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
