<?php

namespace App\Game;

use App\Models\Game;
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
                'x' => $tile->getRow(),
                'y' => $tile->getRowPosition(),
                'unit' => $tile->getType(),
                'position' => $tile->getTilePosition()->getPosition()
            ]);

            $this->state[$tile->getRow()] = $currentState;
        }

        return $this;
    }

    /**
     * Gets the next player.
     *
     * @return string|false
     */
    public function nextPlayerUnit()
    {
        if ($lastMove = $this->getHistory()->last()) {
            return $lastMove['unit'] === TileType::O ? TileType::X : TileType::O;
        }

        return false;
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

        return $this;
    }

    /**
     * Saves the state to the database.
     *
     * @param Game $game
     * @return boolean
     */
    public function saveState(Game $game): bool
    {
        return (bool) $game->update([
            'state' => $this->toArray(),
            'history' => $this->getHistory()->toArray()
        ]);
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

        return $this;
    }

    public function checkWinner()
    {
        if ($this->getHistory()->count() < 5) {
            return false;
        }

        $move = new Collection($this->getHistory()->last());

        if ($this->wonOnXAxis($move) or $this->wonOnYAxis($move) or $this->wonOnXYAxis($move)) {
            return $move->get('unit');
        }

        return false;
    }

    /**
     * Gets the position from the X and Y coordinates.
     *
     * @param integer $x
     * @param integer $y
     * @return integer
     */
    public function getPositionFromCoordinates(int $x, int $y): int
    {
        return ($x + $y) + (($y + 1) + $y);
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
        throw_unless(count($state) === 3, InvalidBoardStateException::class, 'Invalid state');

        foreach ($state as $row) {
            $filtered = array_filter($row, function ($value) {
                return $value === TileType::O || $value === TileType::X || $value === '';
            });

            throw_unless(count($filtered) === 3, InvalidBoardStateException::class, 'Invalid state');
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
        throw_unless($tile instanceof Tile, InvalidBoardStateException::class, 'Invalid tile.');
        throw_unless($this->checkWinner() === false, InvalidBoardStateException::class, 'Game is over');

        if ($lastMove = $this->getHistory()->last()) {
            $sameUnit = $lastMove['unit'] === $tile->getType();
            throw_if($sameUnit, InvalidBoardStateException::class, 'Not your turn');

            $samePosition = ($lastMove['x'] == $tile->getRow() and $lastMove['y'] == $tile->getRowPosition());
            throw_if($samePosition or $sameUnit, InvalidBoardStateException::class, 'Tile position occupied');
        }
    }

    /**
     * Returns the moves made in the board.
     *
     * @return Collection
     */
    public function getHistory() : Collection
    {
        return $this->history;
    }

    /**
     * Checks if the last player won the game on the x-axis
     *
     * @param Collection $move
     * @return boolean
     */
    private function wonOnXAxis(Collection $move) : bool
    {
        $won = true;

        foreach ($this->state[$move['x']] as $tileUnit) {
            if ($move->get('unit') !== $tileUnit) {
                $won = false;
                break;
            }
        }

        return $won;
    }

    /**
     * Checks if the current user won the game on the Y axis.
     *
     * @param Collection $move
     * @return boolean
     */
    private function wonOnYAxis(Collection $move) : bool
    {
        $won = true;

        foreach ($this->state as $boardRow) {
            if ($move->get('unit') !== $boardRow[$move['y']]) {
                $won = false;
                break;
            }
        }

        return $won;
    }

    /**
     * Checks if the user won the game on the diagonal XY axis.
     *
     * @param Collection $move
     * @return bool
     */
    private function wonOnXYAxis(Collection $move): bool
    {
        if (($move->get('position') & 1) !== 1) {
            return false;
        }

        $axisPositions = [
            [0, 1, 2],
            [2, 1, 0],
        ];

        foreach ($axisPositions as $tiles) {
            $won = true;

            foreach ($this->state as $index => $boardRow) {
                $positionInRow = $tiles[$index];

                if ($move->get('unit') !== $boardRow[$positionInRow]) {
                    $won = false;
                    break;
                }
            }

            if ($won) {
                break;
            }
        }

        return $won;
    }
}
