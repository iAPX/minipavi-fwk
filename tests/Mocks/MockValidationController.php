<?php

namespace Tests\Mocks;
use MiniPaviFwk\actions\Action;

class MockValidationController extends \MiniPaviFwk\controllers\VideotexController
{
    public function choixETOILEEnvoi(): ?Action
    {
        return null;
    }

    public function toucheSuite(string $saisie): ?Action
    {
        return null;
    }

    public function validation(): \MiniPaviFwk\Validation
    {
        return new \MiniPaviFwk\actions\Validation(["SOMMAIRE"]);
    }
}
