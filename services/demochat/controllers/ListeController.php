<?php

/**
 * Liste of demo chat
 */

namespace service\controllers;

class ListeController extends \MiniPaviFwk\controllers\VideotexController
{
    private \service\helpers\ChatHelper $chatHelper;

    public function __construct(array $context, array $params = [])
    {
        parent::__construct($context, $params);
        $this->chatHelper = new \service\helpers\ChatHelper();
        $this->chatHelper->watchdog();
        $this->chatHelper->cleanMessages();
    }

    public function ecran(): string
    {
        // Store the connectes to be able to reuse them as displayed!
        $connectes = $this->chatHelper->getConnectes();
        $this->context['connectes'] = $connectes;

        $videotex = new \MiniPaviFwk\videotex\Videotex();
        $videotex
        ->ecritVideotex(file_get_contents(SERVICE_DIR . "vdt/demochat-page.vdt"));

        $nbMessage = $this->chatHelper->getNbMessage();
        if ($nbMessage == 1) {
            $videotex->ecritUnicodeLigne00("Un message en attente");
        } elseif ($nbMessage > 1) {
            $videotex->ecritUnicodeLigne00($this->chatHelper->getNbMessage() . " messages en attente");
        } else {
            $videotex->ecritUnicodeLigne00("Aucun message");
        }

        $num = 1;
        foreach ($connectes as $connecte) {
            // Pseudonyme colour depending on timestamp
            $deltatime = time() - $connecte['timestamp'];
            $pseudonymeCouleur = 'blanc';
            $humeurCouleur = 'magenta';
            if ($deltatime > 300) {
                $pseudonymeCouleur = 'rouge';
                $humeurCouleur = 'noir';
            } elseif ($deltatime > 120) {
                $pseudonymeCouleur = 'jaune';
                $humeurCouleur = 'bleu';
            }

            $videotex
            ->position(3 + $num, 1)->ecritUniCode(substr('  ' . $num, -2) . ' - ')
            ->couleurTexte($pseudonymeCouleur)->ecritUnicode($connecte['pseudonyme'])
            ->couleurTexte($humeurCouleur)->ecritUnicode(' ' . mb_substr($connecte['humeur'], 0, 24));
            $num++;
        }

        $videotex->position(23, 14)->ecritUniCode("Lire un message : * [ENVOI]")
        ->position(24, 8)->ecritUniCode("Écrire un message : No .. [ENVOI]");

        $vdt = $videotex->getOutput();
        return $vdt;
    }

    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd($this->validation(), 24, 31, 2, true);
    }

    public function choixETOILEEnvoi(): ?\MiniPaviFwk\actions\Action
    {
        $nbMsg = $this->chatHelper->getNbMessage();
        if ($nbMsg == 0) {
            return new \MiniPaviFwk\actions\Ligne00Action($this, "Aucun message en attente...");
        }
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\LireMessageController::class,
            $this->context
        );
    }

    public function toucheEnvoi($saisie): ?\MiniPaviFwk\actions\Action
    {
        $num = (int) $saisie;
        if ($num < 1 || $num > count($this->context['connectes'])) {
            return new \MiniPaviFwk\actions\Ligne00Action($this, "Choix non autorisé!");
        }

        $destUniqueId = $this->context['connectes'][$num - 1]['uniqueId'];
        if (empty($this->chatHelper->getConnecteById($destUniqueId))) {
            return new \MiniPaviFwk\actions\Ligne00Action($this, "Cet usager c'est déconnecté.");
        }

        $this->context['destUniqueId'] = $this->context['connectes'][$num - 1]['uniqueId'];
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\EcrireMessageController::class,
            $this->context
        );
    }
}
