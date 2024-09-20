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

class XmlController extends VideotexController
{
    private \SimpleXMLElement $pageXml;

    public function __construct(array $context, array $params = [])
    {
        parent::__construct($context, $params);

        // Load the XML file
        $xml_filename = empty($context['xml_filename']) ? DEFAULT_XML_FILE : $context['xml_filename'];
        DEBUG && trigger_error("XML file: $xml_filename");
        $simpleXml = simplexml_load_file(SERVICE_DIR . 'xml/' . $xml_filename . '.xml');

        if (empty($this->context['xml_page'])) {
            DEBUG && trigger_error("No XML page, searching in <debut/>");
            $page_debut = (string) ($simpleXml->xpath('//debut')[0]->attributes()->nom[0]);
            DEBUG && trigger_error("XML <debut> : " . $page_debut);
            $this->context['xml_page'] = $page_debut;
        }

        $pageXml = $simpleXml->xpath('//page[@nom="' . $this->context['xml_page'] . '"]')[0];
        if (is_null($pageXml)) {
            error_log("No XML page found: " . $this->context['xml_page']);
        } else {
            $this->pageXml = $pageXml;
        }
    }

    public function ecran(): string
    {
        // Clear Line00 when a new page is displayed, to be consistent with minipavi behaviour
        DEBUG && trigger_error("XmlController : ecran()");
        $output = \MiniPaviFwk\xml\EcranXml::ecran($this->pageXml);
        return str_replace(chr(12), chr(12) . \MiniPavi\MiniPaviCli::writeLine0(''), $output);
    }

    public function validation(): \MiniPaviFwk\Validation
    {
        DEBUG && trigger_error("XmlController : validation()");
        $validation = parent::validation();
        DEBUG && trigger_error("XmlController : validation() - add Xml validation keys");
        $validation->addValidKeys(\MiniPaviFwk\xml\ValidationXml::validationKeys($this->pageXml));
        return $validation;
    }

    public function getCmd(): array
    {
        DEBUG && trigger_error("XmlController : getCmd()");
        return \MiniPaviFwk\xml\ZoneSaisieMessageCmdXml::createMiniPaviCmd($this->validation(), $this->pageXml);
    }

    public function choix(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        DEBUG && trigger_error("XmlController : choix()");
        return \MiniPaviFwk\xml\ChoixXml::choix($this->pageXml, $touche, $saisie, $this->context);
    }

    public function message(string $touche, array $message): ?\MiniPaviFwk\actions\Action
    {
        DEBUG && trigger_error("XmlController : message()");
        return \MiniPaviFwk\xml\ChoixXml::choix($this->pageXml, $touche, $message[0], $this->context);
    }

    public function nonPropose(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        DEBUG && trigger_error("XmlController : nonPropose()");
        $action_xml = $this->pageXml->action;
        $defaut = (string) $action_xml->attributes()['defaut'];
        if (!empty($defaut)) {
            DEBUG && trigger_error("XmlController : nonPropose() - defaut: $defaut");
            return new \MiniPaviFwk\actions\Ligne00Action($this, $defaut);
        } else {
            DEBUG && trigger_error("XmlController : nonPropose() - defaut vide - Choix invalide");
            return new \MiniPaviFwk\actions\Ligne00Action($this, "Invalide: $saisie + [$touche]");
        }
    }
}
