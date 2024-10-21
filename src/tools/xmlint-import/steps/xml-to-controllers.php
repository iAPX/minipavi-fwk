<?php

/**
 * Parse the XML to create Controllers
 */

require_once __DIR__ . "/../helpers/pagename_to_controllername.php";
require_once __DIR__ . "/../ControllerBuilder.php";
require_once __DIR__ . "/../helpers/xml_choix.php";
require_once __DIR__ . "/../helpers/xml_cmd.php";
require_once __DIR__ . "/../helpers/xml_ecran.php";
require_once __DIR__ . "/../helpers/xml_validation.php";


echo "\n";
echo "Parsing the XML to create Controllers\n";

$xml_element = new \SimpleXMLElement($xml);
$default_pagename = (string) ($xml_element->xpath('//debut')[0]->attributes()->nom[0]);
$default_controller_name = \pagename_to_controllername($default_pagename);
echo " - Default controller : $default_controller_name\n";

$xml_pages = $xml_element->xpath('//page');
echo " - " . count($xml_pages) . "   pages have been found\n";
foreach ($xml_pages as $xml_page) {
    $page_name = (string) ($xml_page->attributes()->nom[0]);
    $controller_name = pagename_to_controllername($page_name);
    echo " - Page : " . $page_name . " /  Controller : $controller_name\n";

    $controller = new ControllerBuilder($controller_name, $page_name);
    $controllers[$controller_name] = $controller;

    // 1-Ecran
    //$xml_ecran = $xml_page->xpath('//ecran')[0];
    xml_ecran($controller, $xml_page->ecran[0], $pages_path);

    // 2-Validation
    // $xml_entree = $xml_page->xpath('//entree')[0];
    xml_validation($controller, $xml_page->entree[0]);

    // 3-Cmd
    xml_cmd($controller, $xml_page->entree[0]);

    // 4-Choix
    //$xml_choix = $xml_page->xpath('//action')[0];
    xml_choix($controller, $xml_page->action[0]);
}
