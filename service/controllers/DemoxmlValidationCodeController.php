<?php

/**
 * Sommaire de la démo du service
 *
 * Exemple utilisant les Touche*() et Choix() ainsi que la page affichée par Videotex
 */

namespace service\controllers;

class DemoxmlValidationCodeController extends \MiniPaviFwk\controllers\XmlController
{
    public function ecran(): string
    {
        $vdt = parent::ecran();

        $videotex = new \MiniPaviFwk\videotex\Videotex();
        $vdt .= $videotex
        ->position(0,1)->effaceFinDeLigne()
        ->position(7, 1)->ecritUnicode("Le contrôleur autorise [RETOUR] qui est géré par le XML mais pas autorisé par celui-ci! Et [ENVOI] qui génèrera une erreur via nonProppose()")
        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')->ecritUnicode(" " . end(explode('\\', $this::class)))
        ->getOutput();

        return $vdt;
    }
    
    public function validation(): \MiniPaviFwk\Validation
    {
        $validation = parent::validation();
        $validation->addValidKeys(['retour', 'envoi']);
        return $validation;
    }

    public function nonPropose(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\Ligne00Action($this, "Message erreur codé PHP");
    }
    
}
