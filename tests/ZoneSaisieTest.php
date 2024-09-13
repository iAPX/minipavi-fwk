<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\ZoneSaisie;

class ZoneSaisieTest extends TestCase
{
    public function testDefault()
    {
        $zonesaisie = new ZoneSaisie();
        $this->assertEquals(24, $zonesaisie->ligne);
        $this->assertEquals(40, $zonesaisie->col);
        $this->assertEquals(1, $zonesaisie->longueur);
        $this->assertEquals(true, $zonesaisie->curseur);
        $this->assertEquals(' ', $zonesaisie->spaceChar);
        $this->assertEquals('', $zonesaisie->replacementChar);
        $this->assertEquals('', $zonesaisie->prefill);
    }

    public function testCustom()
    {
        $zonesaisie = new ZoneSaisie(1, 2, 3, false, 'x', 'y', 'z');
        $this->assertEquals(1, $zonesaisie->ligne);
        $this->assertEquals(2, $zonesaisie->col);
        $this->assertEquals(3, $zonesaisie->longueur);
        $this->assertFalse($zonesaisie->curseur);
        $this->assertEquals('x', $zonesaisie->spaceChar);
        $this->assertEquals('y', $zonesaisie->replacementChar);
        $this->assertEquals('z', $zonesaisie->prefill);
    }
}
