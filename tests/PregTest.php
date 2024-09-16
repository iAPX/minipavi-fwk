<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\Validation;
use Tests\Mocks\MockValidationController;

class PregTest extends TestCase
{
    public function test (): void
    {
        $this->assertEquals(0, preg_match('/^[A-Za-z0-9*#]+$/u', ""));
        $this->assertEquals(1, preg_match('/^[A-Za-z0-9*#]+$/u', "#*AABCDabccde1234"));
        $this->assertEquals(0, preg_match('/^[A-Za-z0-9*#]+$/u', "envoi::attack"));
        $this->assertEquals(0, preg_match('/^[A-Za-z0-9*#]+$/u', "envoi\\\\attack"));
    }
}
