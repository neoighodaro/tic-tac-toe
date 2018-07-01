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
            ['x' => 0, 'y' => 0, 'unit' => TileType::O, 'position' => 1],
            ['x' => 0, 'y' => 1, 'unit' => TileType::X, 'position' => 2],
            ['x' => 2, 'y' => 2, 'unit' => TileType::O, 'position' => 9],
        ];

        $this->assertEquals($expected, $boardState->getHistory()->toArray());
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

    /** @test */
    public function it_validates_incomplete_state_loaded_from_array()
    {
        $this->expectException(InvalidBoardStateException::class);

        app(BoardState::class)->loadState([
            [TileType::O, TileType::X, ''],
            ['', '', ''],
        ]);
    }

    /** @test */
    public function it_validates_invalid_type_for_state_loaded_from_array()
    {
        $this->expectException(InvalidBoardStateException::class);

        app(BoardState::class)->loadState([
            ['Z', TileType::X, ''],
            ['', '', ''],
            ['', '', ''],
        ]);
    }

    /** @test */
    public function it_loads_history_from_array()
    {
        $boardState = app(BoardState::class);

        $historyArray = [
            ['x' => 0, 'y' => 0, 'unit' => TileType::O, 'position' => 1],
            ['x' => 1, 'y' => 0, 'unit' => TileType::X, 'position' => 2],
            ['x' => 2, 'y' => 2, 'unit' => TileType::O, 'position' => 9],
        ];

        $boardState->loadHistory($historyArray);

        $this->assertEquals($historyArray, $boardState->getHistory()->toArray());
    }

    /** @test */
    public function it_returns_the_next_player()
    {
        $boardState = app(BoardState::class);

        $boardState->add([
            (new Tile)->withType(TileType::O)->withPosition(1),
            (new Tile)->withType(TileType::X)->withPosition(2),
            (new Tile)->withType(TileType::O)->withPosition(5),
            (new Tile)->withType(TileType::X)->withPosition(4),
            (new Tile)->withType(TileType::O)->withPosition(8),
        ]);

        $this->assertEquals(TileType::X, $boardState->nextPlayerUnit());
    }

    /** @test */
    public function it_checks_you_cant_win_with_less_than_five_moves()
    {
        $boardState = app(BoardState::class);

        $boardState->add([
            (new Tile)->withType(TileType::O)->withPosition(1),
            (new Tile)->withType(TileType::X)->withPosition(3),
            (new Tile)->withType(TileType::O)->withPosition(4),
            (new Tile)->withType(TileType::X)->withPosition(5),
        ]);

        $this->assertFalse($boardState->checkWinner());
    }

    /** @test */
    public function it_checks_that_user_can_win_diagonally()
    {
        $boardState = app(BoardState::class);

        $boardState->add([
            (new Tile)->withType(TileType::O)->withPosition(1),
            (new Tile)->withType(TileType::X)->withPosition(3),
            (new Tile)->withType(TileType::O)->withPosition(4),
            (new Tile)->withType(TileType::X)->withPosition(5),
            (new Tile)->withType(TileType::O)->withPosition(8),
            (new Tile)->withType(TileType::X)->withPosition(7),
        ]);

        $this->assertEquals(TileType::X, $boardState->checkWinner());
    }

    /** @test */
    public function it_checks_that_user_can_win_on_x_axis()
    {
        $boardState = app(BoardState::class);

        $boardState->add([
            (new Tile)->withType(TileType::O)->withPosition(4),
            (new Tile)->withType(TileType::X)->withPosition(3),
            (new Tile)->withType(TileType::O)->withPosition(9),
            (new Tile)->withType(TileType::X)->withPosition(2),
            (new Tile)->withType(TileType::O)->withPosition(8),
            (new Tile)->withType(TileType::X)->withPosition(1),
        ]);

        $this->assertEquals(TileType::X, $boardState->checkWinner());
    }

    /** @test */
    public function it_checks_that_user_can_win_on_y_axis()
    {
        $boardState = app(BoardState::class);

        $boardState->add([
            (new Tile)->withType(TileType::O)->withPosition(4),
            (new Tile)->withType(TileType::X)->withPosition(2),
            (new Tile)->withType(TileType::O)->withPosition(1),
            (new Tile)->withType(TileType::X)->withPosition(9),
            (new Tile)->withType(TileType::O)->withPosition(7),
        ]);

        $this->assertEquals(TileType::O, $boardState->checkWinner());
    }
}
