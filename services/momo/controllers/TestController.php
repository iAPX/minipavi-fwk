<?php

/**
 * Handle the test XML Page
 */

namespace service\controllers;

class TestController extends \MiniPaviFwk\controllers\VideotexController
{


    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        // Element webmedia unsupported.
        // Element pin unsupported.


        return $videotex->getOutput();
    }





        // Saisie with email send to E is not supported
}
