<?php

/**
 * Accueil of DemoChat : takes and validate the nickname
 */

namespace service\controllers;

class AccueilController extends \MiniPaviFwk\controllers\VideotexController
{
    private \service\helpers\ChatHelper $chatHelper;

    public function __construct(array $context, array $params = [])
    {
        parent::__construct($context, $params);
        $this->chatHelper = new \service\helpers\ChatHelper();
    }

    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\videotex\Videotex();
        $vdt = $videotex->ecritVideotex(file_get_contents(SERVICE_DIR . "vdt/demochat-page.vdt"))

        // Information about the pseudonyme
        ->position(20, 1)->ecritUnicode("Votre pseudonyme:")
        ->position(21, 1)->ecrituniCode("de 1 à 16 caractères inclus.")

        ->position(23, 18)->ecrituniCode("Pseudonyme + [ENVOI] : ")
        ->position(24, 36)->inversionDebut()->ecritUnicode("ENVOI")
        ->getOutput();
        return $vdt;
    }


    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation(), 24, 18, 16, true, '.');
    }

    public function getDirectCall(): string
    {
        if (isset($_SESSION['DIRECTCALL'])) {
            $directCall = $_SESSION['DIRECTCALL'];
            unset($_SESSION['DIRECTCALL']);
            return $directCall;
        }

        return "no";
    }

    public function toucheEnvoi(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        $chatHelper = new \service\helpers\ChatHelper();
        list($ok, $message) = $chatHelper->checkPseudonyme($saisie);
        if (!$ok) {
            return new \MiniPaviFwk\actions\Ligne00Action($this, $message);
        }

        $chatHelper->addPseudonyme($saisie);

        // Inform all connectes
        $connectes = $this->chatHelper->getConnectes();
        $currentConnecte = $this->chatHelper->getCurrentConnecte();
        $uniqueIds = [];
        $messages = [];
        foreach ($connectes as $connecte) {
            $uniqueIds[] = $connecte['uniqueId'];
            $messages[] = \MiniPavi\MiniPaviCli::toG2($currentConnecte['pseudonyme'] . " arrive sur le chat.");
        }

        $_SESSION['DIRECTCALL'] = 'yes';
        $_SESSION['DIRECT_QUERY_IDS'] = $uniqueIds;
        $_SESSION['DIRECT_QUERY_MSG'] = $messages;

        return new \MiniPaviFwk\actions\ControllerAction(\service\controllers\ListeController::class, $this->context);
    }
}
