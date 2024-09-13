<?php
require_once "src/strings/mb_ucfirst.php";

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\strings;

class mb_ucfirstTest extends TestCase
{
    public function test()
    {
        $this->assertEquals('Abcd', \MiniPaviFwk\strings\mb_ucfirst('abcd'));
        $this->assertEquals('EFGH', \MiniPaviFwk\strings\mb_ucfirst('eFGH'));
        $this->assertEquals('ÉèÀç', \MiniPaviFwk\strings\mb_ucfirst('éèÀç'));
    }
}
