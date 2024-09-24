<?php

/**
 * Accueil of DemoChat : takes and validate the nickname
 */

namespace service\controllers;

class AccueilController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\videotex\Videotex();
        $vdt = $videotex->ecritVideotex(file_get_contents(SERVICE_DIR . "vdt/demochat-page.vdt"))
        ->position(23, 18)->ecrituniCode("Pseudonyme + [ENVOI] : ")
        ->getOutput();
        return $vdt;
    }


    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation(), 24, 32, 8, true);
    }

    public function toucheEnvoi(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        $chatHelper = new \service\helpers\ChatHelper();
        list($ok, $message) = $chatHelper->checkPseudonyme($saisie);
        if (!$ok) {
            return new \MiniPaviFwk\actions\Ligne00Action($this, $message);
        }

        $chatHelper->addPseudonyme($saisie);

        return new \MiniPaviFwk\actions\ControllerAction(\service\controllers\ListeController::class, $this->context);
    }
}
