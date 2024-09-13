<?php

/**
 * Sommaire de la démo du service
 *
 * Exemple utilisant les Touche*() et Choix() ainsi que la page affichée par Videotex
 */

namespace service;

class SommaireController extends \MiniPaviFwk\controllers\XmlController
{
    public function __construct(array $context, array $params = [])
    {
        parent::__construct($context, $params);

        // Now keywords are handled for this controller!
        $this->keywordHandler = new \service\Keywords();
    }

    public function ecran(): string
    {
        // Demo: overload ecran(), adding to it or replacing it, works as well for XmlController and VideotexController
        $vdt = parent::ecran();

        $vdt .= \MiniPavi\MiniPaviCli::setPos(1, 23);
        $vdt .= \MiniPavi\MiniPaviCli::toG2("Démo : Overloaded XmlController::ecran()");
        return $vdt;
    }


    public function toucheRetour(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        // Add a behaviour: on [RETOUR] go to the this XML default Accueil page
        return new \MiniPaviFwk\actions\PageAction($this->context, "", $this->context['xml_filename']);
    }

    public function choixETOILESuite(): ?\MiniPaviFwk\actions\Action
    {
        // Go back to another Xml
        return new \MiniPaviFwk\actions\PageAction($this->context, "", "macbidouille");
    }
}
