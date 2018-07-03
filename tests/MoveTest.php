<?php

use App\Game;
use App\Game\TileType;
use App\Game\BoardState;
use App\Game\Tile;

class MoveTest extends TestCase
{
    /** @test */
    public function it_implements_move_interface()
    {
        $moveClass = $this->getMockBuilder(Game\Move::class)->getMock();
        $this->assertInstanceOf(Game\MoveInterface::class, $moveClass);
    }

    /** @test */
    public function it_generates_valid_moves_for_bot()
    {
        $moveInterface = app(Game\MoveInterface::class);

        $boardState = app(BoardState::class);

        $boardState->add([
            app(Tile::class)->withType(TileType::O)->withPosition(1),
            app(Tile::class)->withType(TileType::X)->withPosition(2),
            app(Tile::class)->withType(TileType::O)->withPosition(6),
            app(Tile::class)->withType(TileType::X)->withPosition(5),
            app(Tile::class)->withType(TileType::O)->withPosition(8),
            app(Tile::class)->withType(TileType::X)->withPosition(7),
            app(Tile::class)->withType(TileType::O)->withPosition(3),
            app(Tile::class)->withType(TileType::X)->withPosition(9),
            // Last possible move should be...
            // app(Tile::class)->withType(TileType::O)->withPosition(4),
        ]);

        $botMove = $moveInterface->makeMove($boardState->toArray(), TileType::X);

        $this->assertEquals([1, 0, TileType::O], $botMove);
    }
}
