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

mkdir("{$service_dir}/controllers", 0755, true);
echo " - services/$service_name/controllers   directory has been created\n";

mkdir("{$service_dir}/handlers", 0755, true);
echo " - services/$service_name/handlers   directory has been created\n";

// Copy the XML file
file_put_contents("{$service_dir}/xml/imported.xml", $xml);
echo " - services/$service_name/xml/imported.xml   XML file has been copied for future references\n";

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
    $page_dir = "{$service_dir}/vdt/{$page_subdir}";
    mkdir($page_dir, 0755, true);
    echo " - $page_dir   Videotex pages subdirectory has been created\n";
}

// Copy the pages
foreach ($pages as $url => $page) {
    $page_filename = "{$service_dir}/vdt/" . substr($url, strlen($pages_path));
    echo " - " . $page_filename . "\n";
    file_put_contents($page_filename, $page);
}
echo " - " . count($pages_url) . "   pages have been copied\n\n";

// Store controllers
foreach ($controllers as $controller_name => $controller) {
    $controller_code = $controller->buildController();
    file_put_contents("{$service_dir}/controllers/{$controller_name}.php", $controller_code);
    echo " - Controller : " . $controller_name . "\n";
}
echo " - " . count($controllers) . "   controllers have been created\n\n";

// Create the service config
file_put_contents("{$service_dir}/service-config.php", $service_config);
echo " - services/$service_name/service-config.php   has been created\n";

echo "\n";
echo "Your $service_name Minitel Service has been created.\n\n";
