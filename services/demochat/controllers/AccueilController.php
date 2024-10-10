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
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
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
        // Connection, we inform the other users
        list($uniqueIds, $messages) = $this->chatHelper->createOtherUsersLigne00Msg(
            "Un nouvel utilisateur se connecte."
        );
        if (count($uniqueIds) > 0) {
            // No need if alone!
            $_SESSION["DIRECTCALL_CMD"] = \MiniPaviFwk\cmd\PushServiceMsgCmd::createMiniPaviCmd($uniqueIds, $messages);
            trigger_error(
                "AccueilController::getCmd - DIRECTCALL_CMD : " . print_r($_SESSION["DIRECTCALL_CMD"], true)
            );
        }
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation(), 24, 18, 16, true, '.');
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
        $currentConnecte = $this->chatHelper->getCurrentConnecte();
        list($uniqueIds, $messages) = $chatHelper->createOtherUsersLigne00Msg(
            $currentConnecte['pseudonyme'] . " arrive sur le chat."
        );

        if (count($uniqueIds) > 0) {
            // No need if alone!
            $_SESSION["DIRECTCALL_CMD"] = \MiniPaviFwk\cmd\PushServiceMsgCmd::createMiniPaviCmd($uniqueIds, $messages);
            trigger_error(
                "AccueilController::toucheEnvoi - DIRECTCALL_CMD : " . print_r($_SESSION["DIRECTCALL_CMD"], true)
            );
        }

        // This command will be executed after the DIRECTCALL_CMD!
        return new \MiniPaviFwk\actions\ControllerAction(\service\controllers\ListeController::class, $this->context);
    }
}
