<?php

/**
 * XML Controller
 *
 * Compatibility with XML files, based on MiniPavi and XMlint
 * Notice that this Controller might be directly used, as it could call itself with xml file and page parameters!
 *
 * It could also be overriden totally or partially to extend XML services features.
 */

namespace MiniPaviFwk\controllers;

use MiniPavi\MiniPaviCli;
use MiniPaviFwk\actions\Action;
use MiniPaviFwk\actions\Ligne00Action;
use MiniPaviFwk\Validation;
use MiniPaviFwk\xml\ChoixXml;
use MiniPaviFwk\xml\EcranXml;
use MiniPaviFwk\xml\ValidationXml;
use MiniPaviFwk\xml\ZoneSaisieMessageCmdXml;

class XmlController extends VideotexController
{
    private \SimpleXMLElement $pageXml;

    public function __construct(array $context, array $params = [])
    {
        parent::__construct($context, $params);

        // Load the XML file
        $xml_filename = empty($context['xml_filename']) ? \service\DEFAULT_XML_FILE : $context['xml_filename'];
        trigger_error("XML file: $xml_filename", E_USER_NOTICE);
        $simpleXml = simplexml_load_file(SERVICE_DIR . 'xml/' . $xml_filename . '.xml');

        if (empty($this->context['xml_page'])) {
            trigger_error("No XML page, searching in <debut/>", E_USER_NOTICE);
            $page_debut = (string) ($simpleXml->xpath('//debut')[0]->attributes()->nom[0]);
            trigger_error("XML <debut> : " . $page_debut, E_USER_NOTICE);
            $this->context['xml_page'] = $page_debut;
        }

        $pageXml = $simpleXml->xpath('//page[@nom="' . $this->context['xml_page'] . '"]')[0];
        if (is_null($pageXml)) {
            trigger_error("No XML page found: " . $this->context['xml_page'], E_ERROR);
        } else {
            $this->pageXml = $pageXml;
        }
    }

    public function ecran(): string
    {
        // Clear Line00 when a new page is displayed, to be consistent with minipavi behaviour
        trigger_error("XmlController : ecran()", E_USER_NOTICE);
        $output = EcranXml::ecran($this->pageXml);
        return str_replace(chr(12), chr(12) . \MiniPavi\MiniPaviCli::writeLine0(''), $output);
    }

    public function validation(): Validation
    {
        trigger_error("XmlController : validation()", E_USER_NOTICE);
        $validation = parent::validation();
        trigger_error("XmlController : validation() - add Xml validation keys", E_USER_NOTICE);
        $validation->addValidKeys(ValidationXml::validationKeys($this->pageXml));
        return $validation;
    }

    public function getCmd(): array
    {
        trigger_error("XmlController : getCmd()", E_USER_NOTICE);
        return ZoneSaisieMessageCmdXml::createMiniPaviCmd($this->validation(), $this->pageXml);
    }

    public function choix(string $touche, string $saisie): ?Action
    {
        trigger_error("XmlController : choix()", E_USER_NOTICE);
        return ChoixXml::choix($this->pageXml, $touche, $saisie, $this->context);
    }

    public function message(string $touche, array $message): ?Action
    {
        trigger_error("XmlController : message()", E_USER_NOTICE);
        return ChoixXml::choix($this->pageXml, $touche, $message[0], $this->context);
    }

    public function nonPropose(string $touche, string $saisie): ?Action
    {
        trigger_error("XmlController : nonPropose()", E_USER_NOTICE);
        $action_xml = $this->pageXml->action;
        $defaut = (string) $action_xml->attributes()['defaut'];
        if (!empty($defaut)) {
            trigger_error("XmlController : nonPropose() - defaut: $defaut", E_USER_NOTICE);
            return new Ligne00Action($this, $defaut);
        } else {
            trigger_error("XmlController : nonPropose() - defaut vide - Choix invalide", E_USER_NOTICE);
            return new Ligne00Action($this, "Invalide: $saisie + [$touche]");
        }
    }
}
