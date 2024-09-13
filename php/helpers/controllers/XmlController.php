<?php

/**
 * XmL Controller
 *
 * Compatibility with XML files, based on MiniPavi and XMlint
 * Notice that this Controller might be directly used, as it could call itself with xml file and page parameters!
 */

namespace helpers\controllers;

class XmlController extends VideotexController
{
    private \SimpleXMLElement $pageXml;

    public function __construct(array $context, array $params = [])
    {
        parent::__construct($context, $params);

        // Load the XML file
        $xml_filename = empty($context['xml_filename']) ? DEFAULT_XML_FILE : $context['xml_filename'];
        DEBUG && trigger_error("XML file: $xml_filename");
        $simpleXml = simplexml_load_file('xml/' . $xml_filename . '.xml');
        //// DEBUG && trigger_error(print_r($simpleXml, true));

        if (empty($this->context['xml_page'])) {
            DEBUG && trigger_error("No XML page, searching in <debut/>");
            $page_debut = (string) ($simpleXml->xpath('//debut')[0]->attributes()->nom[0]);
            DEBUG && trigger_error("XML <debut> : " . $page_debut);
            $this->context['xml_page'] = $page_debut;
        }
        $this->pageXml = $simpleXml->xpath('//page[@nom="' . $this->context['xml_page'] . '"]')[0];
        //// DEBUG && trigger_error(print_r($this->pageXml, true));
    }

    public function ecran(): string
    {
        DEBUG && trigger_error("XmlController : ecran()");
        return \helpers\xml\EcranXml::ecran($this->pageXml);
    }

    public function validation(): \helpers\Validation
    {
        DEBUG && trigger_error("XmlController : validation()");
        $validation = parent::validation();
        DEBUG && trigger_error("XmlController : validation() - add Xml validation keys");
        $validation->addValideKeys(\helpers\xml\ValidationXml::validationKeys($this->pageXml));
        return $validation;
    }

    public function zonesaisie(): \helpers\ZoneSaisie
    {
        DEBUG && trigger_error("XmlController : zonesaisie()");
        return \helpers\xml\ZoneSaisieXml::zonesaisie($this->pageXml);
    }

    public function choix(string $touche, string $saisie): ?\helpers\actions\Action
    {
        DEBUG && trigger_error("XmlController : choix()");
        return \helpers\xml\ChoixXml::choix($this->pageXml, $touche, $saisie, $this->context);
    }
}
