<?php

/**
 * Full Videotex controller providing default behaviours
 */

namespace MiniPaviFwk\controllers;

use MiniPaviFwk\actions\Action;
use MiniPaviFwk\actions\RepetitionAction;
use MiniPaviFwk\controllers\hierarchy\InputVideotex;

class VideotexController extends InputVideotex
{
    public function choix(string $touche, string $saisie): ?Action
    {
        // Overridable in sub-classes
        // Default : error (nonPropose)
        return null;
    }

    public function message(string $touche, array $message): ?Action
    {
        // Overridable in sub-classes
        // Default : error (nonPropose)
        return null;
    }


    public function toucheRepetition(string $saisie): ?Action
    {
        // Default overridable in sub-classes
        return new RepetitionAction($this);
    }

    public function nonPropose(string $touche, string $saisie): ?Action
    {
        return null;
    }
}
