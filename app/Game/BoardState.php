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
    protected $history;

    /**
     * Class constructor
     *
     * @param array $state
     */
    public function __construct()
    {
        $this->loadHistory([]);
        $this->loadState($this->defaultState);
    }

    /**
     * Adds a new tile to the board state.
     *
     * @param Tile|array $tiles
     * @return BoardState
     */
    public function add($tiles): BoardState
    {
        if (!is_array($tiles)) {
            $tiles = [$tiles];
        }

        foreach ($tiles as $tile) {
            $this->validateMoveForTile($tile);

            $currentState = $this->state[$tile->getRow()];
            $currentState[$tile->getRowPosition()] = $tile->getType();

            $this->history->push([
                'x' => $tile->getRowPosition(),
                'y' => $tile->getRow(),
                'unit' => $tile->getType(),
            ]);

            $this->state[$tile->getRow()] = $currentState;
        }

        return $this;
    }

    /**
     * Load state from array
     *
     * @param array $state
     * @return void
     */
    public function loadState(array $state)
    {
        $this->validateLoadedState($state);

        $this->state = new Collection($state);
    }

    /**
     * Load moves history from array.
     *
     * @param array $moves
     * @return void
     */
    public function loadHistory(array $moves)
    {
        $this->history = new Collection($moves);
    }

    /**
     * Returns the moves made in the board.
     *
     * @return Collection
     */
    public function getHistory(): Collection
    {
        return $this->history;
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

    /**
     * Validates loaded state.
     *
     * @param array $state
     * @return void
     */
    private function validateLoadedState(array $state)
    {
        throw_unless(count($state) === 3, InvalidBoardStateException::class);

        foreach ($state as $row) {
            $filtered = array_filter($row, function ($value) {
                return $value === TileType::O || $value === TileType::X || $value === '';
            });

            throw_unless(count($filtered) === 3, InvalidBoardStateException::class);
        }
    }

    /**
     * Validates the move for the tile.
     *
     * @param Tile $tile
     * @return void
     * @throws \App\Exceptions\InvalidBoardStateException
     */
    private function validateMoveForTile(Tile $tile)
    {
        throw_unless($tile instanceof Tile, InvalidBoardStateException::class);

        if ($lastMove = $this->getHistory()->last()) {
            $sameUnit = $lastMove['unit'] === $tile->getType();
            $samePosition = ($lastMove['x'] == $tile->getRowPosition() and $lastMove['y'] == $tile->getRow());

            throw_if($samePosition or $sameUnit, InvalidBoardStateException::class);
        }
    }
}
