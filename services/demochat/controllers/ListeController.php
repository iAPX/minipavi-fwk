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
    }

    public function ecran(): string
    {
        // Store the connectes to be able to reuse them as displayed!
        $connectes = $this->chatHelper->getConnectes();
        $this->context['connectes'] = $connectes;

        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex->page("demochat-page");

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
            ->position(3 + $num, 1)->ecritUniCode(substr('  ' . $num, -2) . '-')
            ->couleurTexte($pseudonymeCouleur)->ecritUnicode($connecte['pseudonyme'])
            ->couleurTexte($humeurCouleur)
            ->ecritUnicode(' ' . mb_substr($connecte['humeur'], 0, 35 - mb_strlen($connecte['pseudonyme'])));
            $num++;
        }

        $videotex
        ->position(22, 2)->ecritUnicode("Votre humeur + [SUITE]")
        ->position(23, 2)->ecritUniCode("Lire: * [ENVOI], Écrire: No [ENVOI]")
        ->position(24, 36)->inversionDebut()->ecritUniCode("ENVOI");

        $vdt = $videotex->getOutput();
        return $vdt;
    }

    public function getCmd(): array
    {
        // Humeur length limited by pseudonyme!
        $connecte = $this->chatHelper->getCurrentConnecte();
        $humeurMaxLength = 35 - mb_strlen($connecte['pseudonyme']);

        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd(
            null,
            24,
            35 - $humeurMaxLength,
            $humeurMaxLength,
            true,
            '.'
        );
    }

    public function choixETOILEEnvoi(): ?\MiniPaviFwk\actions\Action
    {
        $nbMsg = $this->chatHelper->getNbMessage();
        if ($nbMsg == 0) {
            return new \MiniPaviFwk\actions\Ligne00Action($this, "Aucun message en attente...");
        }
        return new \MiniPaviFwk\actions\PageAction($this->context, 'lire-message');
    }

    public function toucheEnvoi(string $saisie): ?\MiniPaviFwk\actions\Action
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

        // Inform the user that this one is writing a message
        $connecte = $this->chatHelper->getCurrentConnecte();
        $_SESSION["DIRECTCALL_CMD"] = \MiniPaviFwk\cmd\PushServiceMsgCmd::createMiniPaviCmd(
            [$this->context['destUniqueId']],
            [\MiniPavi\MiniPaviCli::toG2($connecte['pseudonyme'] . " vous écrit.")]
        );
        trigger_error(
            "EcrireController::Ecriture - DIRECTCALL_CMD : " . print_r($_SESSION["DIRECTCALL_CMD"], true)
        );

        return new \MiniPaviFwk\actions\PageAction($this->context, 'ecrire-message');
    }

    public function toucheSuite(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        list($humeurOk, $humeurMessage) = $this->chatHelper->checkHumeur($saisie);
        if (!$humeurOk) {
            return new \MiniPaviFwk\actions\Ligne00Action($this, $humeurMessage);
        }

        $this->chatHelper->setHumeur($saisie);

        // Inform the other connectes
        $currentConnecte = $this->chatHelper->getCurrentConnecte();
        list($uniqueIds, $messages) = $this->chatHelper->createOtherUsersLigne00Msg(
            $currentConnecte['pseudonyme'] . " a changé d'humeur."
        );
        if (count($uniqueIds) > 0) {
            $_SESSION["DIRECTCALL_CMD"] = \MiniPaviFwk\cmd\PushServiceMsgCmd::createMiniPaviCmd($uniqueIds, $messages);
            trigger_error(
                "ListeController::toucheSuite - DIRECTCALL_CMD : " . print_r($_SESSION["DIRECTCALL_CMD"], true),
                E_USER_NOTICE
            );
        }

        return new \MiniPaviFwk\actions\PageAction($this->context, 'liste');
    }
}
