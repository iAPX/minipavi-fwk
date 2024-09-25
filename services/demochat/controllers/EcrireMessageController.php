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
        $videotex = new \MiniPaviFwk\videotex\Videotex();
        $videotex->effaceLigne00();
        $videotex->ecritVideotex(file_get_contents(SERVICE_DIR . "vdt/demochat-page.vdt"));

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

    public function getCmd(): array
    {
        if (empty($this->dest)) {
            return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation(), 24, 31, 1, true);
        }
        // Here we define the Message input area
        return \MiniPaviFwk\cmd\ZoneMessageCmd::createMiniPaviCmd($this->validation(), 5, 4, true, '.', '-');
    }

    public function getDirectCall(): string
    {
        if (isset($_SESSION['DIRECTCALL'])) {
            $directCall = $_SESSION['DIRECTCALL'];
            trigger_error("EcrireMessageController::getDirectCall: " . $directCall);
            unset($_SESSION['DIRECTCALL']);
            return $directCall;
        }
        
        return "no";
    }

    public function messageToucheEnvoi(array $message): ?\MiniPaviFwk\actions\Action
    {
        if (empty(implode('', $message))) {
            return new \MiniPaviFwk\actions\Ligne00Action($this, "Message vide, [SOMMAIRE] pour quitter");
        }

        // Envoi du message
        $this->chatHelper->sendMessage($this->context['destUniqueId'], $message);

        // Ligne 00 pour le destinataire
        $_SESSION['DIRECTCALL'] = 'yes';
        $_SESSION['DIRECT_QUERY_IDS'] = [$this->context['destUniqueId']];
        $connecte = $this->chatHelper->getCurrentConnecte();
        $_SESSION['DIRECT_QUERY_MSG'] = [\MiniPavi\MiniPaviCli::toG2("Message de " . $connecte['pseudonyme'])];

        // Send to Liste or LireMessage depending on existing incoming message
        if ($this->chatHelper->getNbMessage() > 0) {
            return new \MiniPaviFwk\actions\ControllerAction(
                \service\controllers\LireMessageController::class,
                $this->context
            );
        }
        return new \MiniPaviFwk\actions\ControllerAction(\service\controllers\ListeController::class, $this->context);
    }


    public function messageToucheSommaire(array $message): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\ControllerAction(\service\controllers\ListeController::class, $this->context);
    }

    public function toucheSommaire(array $message): ?\MiniPaviFwk\actions\Action
    {
        // Alternatively if user was disconnected when displaying or redisplaying this page
        return new \MiniPaviFwk\actions\ControllerAction(\service\controllers\ListeController::class, $this->context);
    }
}
