<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\Keywords;

class KeywordsTest extends TestCase
{
    public function testGetKeyword()
    {
        define('DEBUG', false);
        $keywords = new Keywords();

        $choix = $keywords->choix('*', 'ENVOI');
        $this->assertNull($choix);

        $validation = $keywords->validationKeys();
        $this->assertEmpty($validation);
    }
}
