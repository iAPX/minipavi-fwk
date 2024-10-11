<?php

/**
 * Asks for the XML url, get it and parse it, populating Pages informations
 */

// Asks the XML URL
while (true) {
    echo "Your XMLint XML file URL : ";
    $xml_url = trim(readline());
    echo "\n";
    if (!empty($xml_url)) {
        $xml = get_web_file($xml_url);
        if ($xml !== false) {
            break;
        }
        echo "Error fetching XML from $xml_url\n";
    } else {
        echo "Please enter an XML URL\n";
    }
}

echo "XML fetched from $xml_url. " . strlen($xml) .  "Bytes.\n\n";

// Gets the Pages URL
$xml_element = new \SimpleXMLElement($xml);
$pages_element = $xml_element->xpath("//affiche[@url]");
echo count($pages_element) . " XML <affiche url='xxx'> found in XML :\n";
$pages_url = [];
foreach ($pages_element as $page) {
    $url = (string) $page['url'];
    if (isset($pages_url[$url])) {
        $pages_url[$url] = $pages_url[$url] + 1;
    } else {
        $pages_url[$url] = 1;
    }
}

if (count($pages_url) === 0) {
    echo "No <affiche url='xxx'> found in XML.\n";
} else {
    echo count($pages_url) . " unique Pages URL found in XML\n";

    // Search the longest page common path
    $common_path = key($pages_url);
    echo $pages_path . "\n";
    foreach ($pages_url as $url => $nb) {
        $page_path = substr($url, 0, strrpos($url, '/'));
        $common_length = strspn($common_path ^ $url, "\0");
        $common_path = substr($common_path, 0, $common_length);
    }

    $pages_path = substr($common_path, 0, strrpos($common_path, '/') + 1);   // Includes the last common slash
    echo "Common Pages path : " . $pages_path . "\n\n";
    if (strlen($pages_path) < 10) {
        $pages_path = "";
        echo "No common path found, so none page will be imported.\n";
    }
}

YESno() || die("Aborted, no modification done.\n");
