<?php

/**
 * Summary to be sure, before modifying or creating anything
 */

echo "Summary:\n\n";


echo " - services/$service_name   directory will be created\n";
echo " - services/$service_name/xml   directory will be created\n";
echo " - services/$service_name/vdt   directory will be created\n";

echo " - services/$service_name/xml/default.xm   XML file will be copied\n";
echo "   from this URL : $xml_url\n";

echo " - " . count($pages_url) . "   pages will be copied\n";
echo "   Their common url is : " . $pages_path . "\n";

echo " - " . count($controllers) . "   controllers will be created and populated\n";

echo " - services/$service_name/service-config.php   will be created\n";

echo " - services/global-config.php   global configuration file will be modified";
echo "   adding the new service '$service_name' in ALLOWED_SERVICES\n";
if ($default_service) {
    echo "   and '$service_name' will be set as default service *** IMPORTANT ***\n";
}
echo "\n";

echo "These operations will be done in this order.\n";
echo "Only the last one, modifying services/global-config.php could affect your usage of MiniPaviFwk.\n";
echo "For this reason you will be asked a last time to confirm services/global-config.php modification.\n";
echo "\n";

echo "Are you sure? yes/NO? ";
$saisie = mb_strtolower(trim(readline()));
echo "\n";
if ($saisie !== 'y' && $saisie !== 'yes') {
    die("Aborted, no modification done.\n");
}
