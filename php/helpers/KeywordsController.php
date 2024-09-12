<?php

/**
 * Provides Global Keywords methods for
 */

namespace helpers;

class KeywordsController extends \helpers\VideotexController
{
    // Put keywords here, as you do on your controllers choix/touche
    // And derive your controlller from this one

    public function choixDcxEnvoi(): \helpers\ActionInterface
    {
        return new \helpers\DeconnexionAction();
    }

    public function toucheSommaire(string $saisie): ?\helpers\ActionInterface
    {
        return new \helpers\ControllerAction(\service\SommaireController::class, $this->context);
    }

    public function choix(string $touche, string $saisie): ?\helpers\ActionInterface
    {
        // Here adds choices management
        // use parent::choix($touche, $saisie);
        // if answer is not null, return it
        return null;
    }
}
