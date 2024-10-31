<?php

/**
 *  Write a message
 */

namespace service\controllers;

class EcrireMessageController extends \MiniPaviFwk\controllers\VideotexController
{
    private \service\helpers\ChatHelper $chatHelper;
    private array $dest;

    public function __construct(array $context, array $params = [])
    {
        parent::__construct($context, $params);
        $this->chatHelper = new \service\helpers\ChatHelper();
        $this->chatHelper->watchdog();

        $this->dest = $this->chatHelper->getConnecteById($this->context['destUniqueId']);
    }

    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex->effaceLigne00();
        $videotex->page("demochat-page");

        $videotex
        ->position(4, 1)->inversionDebut()->ecritUnicode("Message pour : ");

        if (empty($this->dest)) {
            $videotex
            ->position(24, 10)
            ->ecritUnicode("Destinataire déconnecté : ")
            ->inversionDebut()
            ->ecritUnicode(" SOMMAIRE ");
            return $videotex->getOutput();
        }

        $videotex
        ->ecritUnicode($this->dest['pseudonyme']);

        $videotex
        ->position(24, 1)->inversionDebut()->ecritUnicode("SOMMAIRE")
        ->position(24, 26)->ecritUnicode("Message + ")
        ->inversionDebut()->ecritUnicode("ENVOI");

        return $videotex->getOutput();
    }

    public function validation(): \MiniPaviFwk\Validation
    {
        $validation = parent::validation();
        $validation->addValidKeys(['Suite', 'RETOUR', 'Envoi']);
        return $validation;
    }

    public function getCmd(): array
    {
        if (empty($this->dest)) {
            return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation(), 24, 31, 1, true);
        }
        // Here we define the Message input area
        return \MiniPaviFwk\cmd\ZoneMessageCmd::createMiniPaviCmd($this->validation(), 5, 4, true, '.', '-');
    }

    public function message(string $touche, array $message): ?\MiniPaviFwk\actions\Action
    {
        if ($touche === "SOMMAIRE") {
            return new \MiniPaviFwk\actions\ControllerAction(
                \service\controllers\ListeController::class,
                $this->context
            );
        } elseif ($touche === "ENVOI") {
            if (empty(implode('', $message))) {
                return new \MiniPaviFwk\actions\Ligne00Action($this, "Message vide, [SOMMAIRE] pour quitter");
            }

            // Envoi du message
            $this->chatHelper->sendMessage($this->context['destUniqueId'], $message);

            // Ligne 00 pour le destinataire
            $connecte = $this->chatHelper->getCurrentConnecte();
            $_SESSION["DIRECTCALL_CMD"] = \MiniPaviFwk\cmd\PushServiceMsgCmd::createMiniPaviCmd(
                [$this->context['destUniqueId']],
                [\MiniPavi\MiniPaviCli::toG2("Message de " . $connecte['pseudonyme'])]
            );
            trigger_error(
                "EcrireController::nouveau message - DIRECTCALL_CMD : " . print_r($_SESSION["DIRECTCALL_CMD"], true)
            );

            // Send to Liste or LireMessage depending on existing incoming message
            if ($this->chatHelper->getNbMessage() > 0) {
                return new \MiniPaviFwk\actions\ControllerAction(
                    \service\controllers\LireMessageController::class,
                    $this->context
                );
            }
            return new \MiniPaviFwk\actions\ControllerAction(
                \service\controllers\ListeController::class,
                $this->context
            );
        }

        // Fallback
        return null;
    }

    public function toucheSommaire(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        // Alternatively if user was disconnected when displaying or redisplaying this page
        return new \MiniPaviFwk\actions\ControllerAction(\service\controllers\ListeController::class, $this->context);
    }
}
