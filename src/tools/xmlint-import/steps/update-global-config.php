<?php

/**
 * Update the global config after the last check
 */

echo "\n";
echo "This is the last step : updating ./service/global-config.php\n";
echo "This step could affect your actual usage of MiniPaviFwk.\n";
echo "Are you sure? yes/NO? ";
$saisie = mb_strtolower(trim(readline()));
echo "\n";
if ($saisie !== 'y' && $saisie !== 'yes') {
    die("Aborted, modification done : your $service_name service is created on ./services/$service_name but not activated.\n");
}

file_put_contents("./services/global-config.php", $global_config);
echo " - ./services/global-config.php has been updated\n";

echo "\n";
