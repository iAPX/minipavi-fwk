<?php

/**
 * Service Creation
 */

$service_dir = "./services/$service_name";

// Create the service directories
mkdir($service_dir, 0755, true);
echo " - services/$service_name   directory has been created\n";

mkdir("{$service_dir}/xml", 0755, true);
echo " - services/$service_name/xml   directory has been created\n";

mkdir("{$service_dir}/vdt", 0755, true);
echo " - services/$service_name/vdt   directory has been created\n";

// Copy the XML file
file_put_contents("{$service_dir}/xml/default.xml", $xml);
echo " - services/$service_name/xml/default.xm   XML file has been copied\n";

// Create the pages subdirectory
$pages_subdirs = [];
foreach ($pages_url as $url => $nb) {
    $page_path = substr($url, strlen($pages_path));
    $pos = strrpos($page_path, '/');
    if ($pos !== false) {
        $page_subdir = substr($page_path, 0, $pos);
        $pages_subdirs[$page_subdir] = true;
    }
}
foreach ($pages_subdirs as $page_subdir => $dummy) {
    mkdir("{$service_dir}/vdt/$page_subdir", 0755, true);
    echo " - services/$service_name/vdt/$page_subdir   Videotex pages subdirectory has been created\n";
}

// Copy the pages
foreach ($pages as $url => $page) {
    $page_path = "{$service_dir}/vdt/" . substr($url, strlen($pages_path));
    echo " - " . $page_path . "\n";
    file_put_contents($page_path, $page);
}
echo " - " . count($pages_url) . "   pages has been copied\n";

// Create the service config
file_put_contents("{$service_dir}/service-config.php", $service_config);
echo " - services/$service_name/service-config.php   has been created\n";

echo "\n";
echo "Your $service_name Minitel Service has been created.\n\n";
