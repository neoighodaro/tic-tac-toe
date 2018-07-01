<?php

use App\Game\Tile;
use App\Game\TileType;
use App\Game\BoardState;
use App\Game\TilePosition;
use App\Exceptions\InvalidBoardStateException;

class BoardStateTest extends TestCase
{
    /** @test */
    public function it_can_add_new_state()
    {
        $boardState = app(BoardState::class); // BoardState singleton

        $boardState->add((new Tile)->withType(TileType::O)->withPosition(1));

        $expectedBoardState = [
            [TileType::O, '', ''],
            ['', '', ''],
            ['', '', ''],
        ];

        $this->assertEquals($expectedBoardState, $boardState->toArray());
    }

    /** @test */
    public function it_can_add_new_states()
    {
        $boardState = app(BoardState::class); // BoardState singleton

        $boardState->add([
            (new Tile)->withType(TileType::O)->withPosition(1),
            (new Tile)->withType(TileType::X)->withPosition(2),
            (new Tile)->withType(TileType::O)->withPosition(9)
        ]);

        $expectedStateArray = [
            [TileType::O, TileType::X, ''],
            ['', '', ''],
            ['', '', TileType::O],
        ];

        $this->assertEquals($expectedStateArray, $boardState->toArray());
    }

    /** @test */
    public function it_can_show_moves_history()
    {
        $boardState = app(BoardState::class); // BoardState singleton

        $boardState->add([
            (new Tile)->withType(TileType::O)->withPosition(1),
            (new Tile)->withType(TileType::X)->withPosition(2),
            (new Tile)->withType(TileType::O)->withPosition(9)
        ]);

        $expected = [
            ['x' => 0, 'y' => 0, 'unit' => TileType::O],
            ['x' => 1, 'y' => 0, 'unit' => TileType::X],
            ['x' => 2, 'y' => 2, 'unit' => TileType::O],
        ];

        $this->assertEquals($expected, $boardState->getMoves()->toArray());
    }

    /** @test */
    public function it_can_stop_multiple_successive_unit_plays()
    {
        $this->expectException(InvalidBoardStateException::class);

        app(BoardState::class)->add([
            (new Tile)->withType(TileType::O)->withPosition(1),
            (new Tile)->withType(TileType::X)->withPosition(2),
            (new Tile)->withType(TileType::X)->withPosition(3),
            (new Tile)->withType(TileType::O)->withPosition(9)
        ]);
    }

    /** @test */
    public function it_can_determine_if_move_is_valid()
    {
        $this->expectException(InvalidBoardStateException::class);

        app(BoardState::class)->add([
            new Tile(new TileType(TileType::O), new TilePosition(1)),
            new Tile(new TileType(TileType::X), new TilePosition(1)),
        ]);
    }

    /** @test */
    public function it_loads_state_from_array()
    {
        $boardState = app(BoardState::class);

        $stateArray = [
            [TileType::O, TileType::X, ''],
            ['', '', ''],
            ['', '', TileType::O],
        ];

        $boardState->loadState($stateArray);

        $this->assertEquals($stateArray, $boardState->toArray());
    }
}
