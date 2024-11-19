<?php

namespace Tests\Mocks;

use MiniPaviFwk\actions\Action;
use MiniPaviFwk\controllers\VideotexController;
use Tests\Mocks\MockAction;


class MockFormController extends VideotexController
{
    private string $ecranTest = '*MOCK_ECRAN*';
    
    public string $touche;
    public string $field1, $field2, $field3;

    // Enable ecran() testing
    public function setEcranTest(string $textEcran) {
        $this->ecranTest = $textEcran;
    }

    public function ecran(): string
    {
        return $this->ecranTest;
    }

    public function formulaire(string $touche, string $field1, string $field2, string $field3) {
        $this->touche = $touche;
        $this->field1 = $field1;
        $this->field2 = $field2;
        $this->field3 = $field3;
        return new MockAction('formulaire("' . $touche . '")');
    }
}
