<?php
require_once "src/helpers/mb_ucfirst.php";

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\strings;

class mb_ucfirstTest extends TestCase
{
    public function test()
    {
        $this->assertEquals('Abcd', \MiniPaviFwk\helpers\mb_ucfirst('abcd'));
        $this->assertEquals('EFGH', \MiniPaviFwk\helpers\mb_ucfirst('eFGH'));
        $this->assertEquals('ÉèÀç', \MiniPaviFwk\helpers\mb_ucfirst('éèÀç'));
    }
}
