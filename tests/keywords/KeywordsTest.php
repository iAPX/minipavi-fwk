<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\Keywords;

class KeywordsTest extends TestCase
{
    public function testGetKeyword(): void
    {
        $keywords = new Keywords();

        $choix = $keywords->choix('*', 'ENVOI');
        $this->assertNull($choix);
    }
}
