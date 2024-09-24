<?php

/**
 *  Write a message
 */

namespace service\controllers;

class LireMessageController extends \MiniPaviFwk\controllers\VideotexController
{
    private \service\helpers\ChatHelper $chatHelper;
    private array $msg = [];
    private array $dest = [];

    public function __construct(array $context, array $params = [])
    {
        parent::__construct($context, $params);
        $this->chatHelper = new \service\helpers\ChatHelper();
        $this->chatHelper->watchdog();

        $this->msg = $this->chatHelper->readFirstMessage();
        if (!empty($this->msg)) {
            $this->dest = $this->chatHelper->getConnecteById($this->msg['srcUniqueId']);
        }
    }

    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\videotex\Videotex();
        $videotex->effaceLigne00();
        $videotex->ecritVideotex(file_get_contents(SERVICE_DIR . "vdt/demochat-page.vdt"));

        if (empty($this->msg)) {
            $videotex
            ->position(24, 13)
            ->ecritUnicode("Message effacé : ")
            ->inversionDebut()
            ->ecritUnicode(" SOMMAIRE ");
            return $videotex->getOutput();
        }

        // Ancien message
        $videotex->position(4, 1)->inversionDebut()->ecritUnicode("votre ancien messsage : ");
        for ($i = 0; $i < 3; $i++) {
            $videotex->position(5 + $i, 1)->ecritUnicode($this->msg['oldMessage'][$i]);
        }

        // Nouveau message
        $videotex->position(10, 1)->inversionDebut()->ecritUnicode("Message de : ");
        for ($i = 0; $i < 3; $i++) {
            $videotex->position(11 + $i, 1)->ecritUnicode($this->msg['message'][$i]);
        }

        if (empty($this->dest)) {
            $videotex
            ->position(24, 2)
            ->ecritUnicode("Destinataire déconnecté : ")
            ->inversionDebut()
            ->ecritUnicode(" ANNULATION ");
            return $videotex->getOutput();
        }

        // Destinataire affiché
        $videotex->position(10, 14)->inversionDebut()->ecritUnicode($this->dest['pseudonyme']);

        // Zone de saisie :
        $videotex->position(16, 1)->inversionDebut()->ecritUnicode("Votre réponse + [ENVOI]");

        $videotex->position(23, 10)->ecritUnicode("Ne pas répondre : * [SOMMAIRE]");

        $videotex
        ->position(24, 1)->inversionDebut()->ecritUnicode("SOMMAIRE")
        ->position(24, 18)->ecritUnicode("Votre réponse + ")
        ->inversionDebut()->ecritUnicode(" ENVOI ");

        return $videotex->getOutput();
    }

    public function getCmd(): array
    {
        // Vary depending on conditions. If no response possible, only [SOMMAIRE] or [ANNULATION]
        if (empty($this->dest) || !$this->chatHelper->uniqueIdExists($this->msg['srcUniqueId'])) {
            // Simple input for [SOMMAIRE]
            return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation(), 24, 40, 1, true);
        }

        // Here we define the Message input area
        return \MiniPaviFwk\cmd\ZoneMessageCmd::createMiniPaviCmd($this->validation(), 17, 4, true, '.', '-');
    }

    public function messageToucheEnvoi(array $message): ?\MiniPaviFwk\actions\Action
    {
        if (empty(implode('', $message))) {
            return new \MiniPaviFwk\actions\Ligne00Action($this, "* [SOMMAIRE] pour ne pas répondre");
        }

        // Envoi du message
        $this->chatHelper->sendMessage($this->msg['srcUniqueId'], $message, $this->msg['message']);
        if (!empty($this->msg)) {
            // Delete message
            $this->chatHelper->deleteMessage($this->msg);
        }

        return new \MiniPaviFwk\actions\ControllerAction(\service\controllers\ListeController::class, $this->context);
    }


    public function messageToucheSommaire(array $message): ?\MiniPaviFwk\actions\Action
    {
        if (implode('', $message) === '*' && !empty($this->msg)) {
            $this->chatHelper->deleteMessage($this->msg);
        }

        return new \MiniPaviFwk\actions\ControllerAction(\service\controllers\ListeController::class, $this->context);
    }

    public function toucheSommaire(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\ControllerAction(\service\controllers\ListeController::class, $this->context);
    }

    public function toucheAnnulation(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        // Delete the messsage if exists and quit
        if (!empty($this->msg)) {
            // Delete message
            $this->chatHelper->deleteMessage($this->msg);
        }

        return new \MiniPaviFwk\actions\ControllerAction(\service\controllers\ListeController::class, $this->context);
    }
}
