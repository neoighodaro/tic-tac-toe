<?php

use App\Game;

class MoveTest extends TestCase
{
    /** @test */
    public function it_implements_move_interface()
    {
        $moveClass = $this->getMockBuilder(Game\Move::class)->getMock();
        $this->assertInstanceOf(Game\MoveInterface::class, $moveClass);
    }
}
