<?php

namespace Tests\Mocks;

class MockController extends \MiniPaviFwk\controllers\VideotexController
{
    private string $ecranTest = '';

    // Enable ecran() testing
    public function setEcranTest(string $textEcran) {
        $this->ecranTest = $textEcran;
    }

    public function ecran(): string
    {
        return $this->ecranTest;
    }
}
