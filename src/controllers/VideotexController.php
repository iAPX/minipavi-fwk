<?php

/**
 * Full Videotex controller providing default behaviours
 */

namespace MiniPaviFwk\Controllers;

use MiniPaviFwk\actions\Action;
use MiniPaviFwk\actions\Ligne00Action;
use MiniPaviFwk\actions\RepetitionAction;
use MiniPaviFwk\controllers\hierarchy\InputVideotex;
use MiniPaviFwk\helpers\ConstantHelper;

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
        // Use const NON_PROPOSE_LIGNE00 message if present, null if not.
        $message = ConstantHelper::getConstValueByName('NON_PROPOSE_LIGNE00');
        if ($message) {
            return new Ligne00Action($this, $message);
        }
        return null;
    }
}
