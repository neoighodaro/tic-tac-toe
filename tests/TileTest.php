<?php

use App\Game\Tile;
use App\Game\TileType;
use App\Game\TilePosition;

class TileTest extends TestCase
{
    /** @test */
    public function it_can_create_new_tile_instances()
    {
        $tileType = new TileType(TileType::O);
        $tilePosition = new TilePosition(9);

        $tile = new Tile($tileType, $tilePosition);

        $this->assertEquals($tileType, $tile->getType());
        $this->assertEquals($tilePosition, $tile->getPosition());
    }

    /** @test */
    public function it_creates_new_instance_easily()
    {
        $tile = (new Tile)->withType('O')->andPosition(9);

        $this->assertEquals(new TileType(TileType::O), $tile->getType());
        $this->assertEquals(new TilePosition(9), $tile->getPosition());
    }
}
