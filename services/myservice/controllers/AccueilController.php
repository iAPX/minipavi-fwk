<?php

/**
 * Minimalistic homepage controller
 */

namespace services\controllers;

class AccueilController extends \MinipaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex->effaceEcran()->ecritUnicode("Hello world! 'myservice' is running.");
        return $videotex->getOutput();
    }
}