<?php

use App\Game\Tile;
use App\Game\TileType;
use App\Game\TilePosition;

class TileTest extends TestCase
{
    /** @test */
    public function it_can_return_instances_of_type_and_position()
    {
        $tileType = new TileType(TileType::O);
        $tilePosition = new TilePosition(9);

        $tile = new Tile($tileType, $tilePosition);

        $this->assertEquals($tileType, $tile->getTileType());
        $this->assertEquals($tilePosition, $tile->getTilePosition());

        $tile = (new Tile)->withType('O')->andPosition(9);

        $this->assertEquals($tileType, $tile->getTileType());
        $this->assertEquals($tilePosition, $tile->getTilePosition());
    }

    /** @test */
    public function it_can_get_tile_row()
    {
        $tile = (new Tile)->withType('O')->andPosition(2);

        $this->assertEquals(0, $tile->getRow());
    }

    /** @test */
    public function it_can_get_tile_row_position()
    {
        $tile = (new Tile)->withType('O')->andPosition(2);

        $this->assertEquals(1, $tile->getRowPosition());
    }
}
