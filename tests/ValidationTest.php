<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\Validation;
use Tests\Mocks\MockValidationController;

class ValidationTest extends TestCase
{
    public function test (): void
    {
        $controller = new MockValidationController([]);
        $validation = new Validation($controller);
        $validation->addValidKeys(['RETOUR']);

        $this->assertEquals(204, $validation->getKeyMask());
    }
}
