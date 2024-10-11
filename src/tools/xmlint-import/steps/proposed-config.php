<?php

/**
 * Displays the proposed configuration for the new service
 */

$default_service = YESno("Do you want to make you new '" . $service_name . "' service as default service ? YES/no ");

$report_all_errors = YESno("Do you want to see all errors ? YES/no ");

echo "Proposed Configuration :\n";
echo "  - service_name : " . $service_name . "\n";
echo "  - pages_path : " . $pages_path . "\n";

echo " - " . count($pages_url) . " pages will be imported.\n";

echo "\n";
$service_config = <<<EOF
<?php

/**
 * Configuration for the $service_name Minitel Service
 */

namespace service;

const DEFAULT_XML_FILE = 'default.xml';
const XML_PAGES_URL = "$pages_path";

EOF;
if ($report_all_errors) {
    $service_config .= <<<EOF
// Service specific error reporting, for ALL errors
const SERVICE_ERROR_REPORTING = E_ALL;

EOF;
}
echo " - Service config in .services/$service_name/service-config.php :\n";
echo $service_config . "\n";

echo "\n";
$global_config = file_get_contents("./services/global-config.php");
$old_allowed_services = get_config_const_value($global_config, "ALLOWED_SERVICES");
$new_allowed_services = substr($old_allowed_services, 0, -1) . ", '" . $service_name . "']";
$global_config = str_replace($old_allowed_services, $new_allowed_services, $global_config);
if ($default_service) {
    $old_default_service = " = " . get_config_const_value($global_config, "DEFAULT_SERVICE");
    $new_default_service = " = '" . $service_name . "'";
    $global_config = str_replace($old_default_service, $new_default_service, $global_config);
}

echo " - Global config in ./services/global-config.php :\n";
echo "   - Allowed services : " . $old_allowed_services . " >> " . $new_allowed_services . "\n";
if ($default_service) {
    echo "   - Default service : " . $old_default_service . " >> " . $new_default_service . "\n";
}

echo "\n";
echo "All files will be created in ./services/$service_name\n";

echo "Are you sure? yes/NO? ";
$saisie = mb_strtolower(trim(readline()));
echo "\n";
if ($saisie !== 'y' && $saisie !== 'yes') {
    die("Aborted, no modification done.\n");
}
