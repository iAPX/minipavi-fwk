<?php

namespace Tests\Mocks;

use MiniPaviFwk\actions\Action;
use MiniPaviFwk\controllers\VideotexController;
use Tests\Mocks\MockAction;

class MockController extends VideotexController
{
    private string $ecranTest = '*MOCK_ECRAN*';
    public array $test_message;

    // Enable ecran() testing
    public function setEcranTest(string $textEcran) {
        $this->ecranTest = $textEcran;
    }

    public function ecran(): string
    {
        return $this->ecranTest;
    }

    public function choixRepetition(): ?Action
    {
        return new MockAction('choixRepetition()');
    }
    public function choix1Envoi(): ?Action
    {
        return new MockAction('choix1Envoi()');
    }

    public function toucheSuite(string $saisie): ?Action
    {
        return new MockAction('toucheSuite("' . $saisie . '")');
    }

    public function choix(string $touche, string $saisie): ?Action
    {
        if ($touche == 'RETOUR') {
            // Should trigger nonPropose()
            return null;
        }
        return new MockAction('choix("' . $touche . '", "' . $saisie . '")');
    }

    public function message(string $touche, array $message): ?Action
    {
        $this->test_message = $message;
        return new MockAction('message("' . $touche . '")');
    }

    public function nonPropose(string $touche, string $saisie): ?Action
    {
        return new MockAction('nonPropose("' . $touche . '", "' . $saisie . '")');
    }
}
