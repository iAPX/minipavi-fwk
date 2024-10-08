<?php

/**
 * Handles the actual service and provides means to service switching
 *
 */

namespace MiniPaviFwk\handlers;

class ServiceHandler
{
    public static function startService(): void
    {
        // Get the service-config and set constants for autoloader and code
        $service_name = static::getService();
        trigger_error("Service : " . $service_name, E_USER_NOTICE);
        define('SERVICE_NAME', $service_name);
        define('SERVICE_DIR', "services/" . mb_strtolower(\SERVICE_NAME) . "/");
        require_once \SERVICE_DIR . "service-config.php";

        // change the error reporting level if specified
        if (defined('SERVICE_ERROR_REPORTING')) {
            error_reporting(SERVICE_ERROR_REPORTING);
        }
    }

    protected static function getService(): string
    {
        if (!isset($_SESSION['service'])) {
            $parameter = substr($_SERVER['PATH_INFO'], 10);
            trigger_error("Service Parameter : " . $parameter, E_USER_NOTICE);

            $service_parameter = empty($parameter) ? \DEFAULT_SERVICE : $parameter;
            trigger_error("Service parameter chosen : " . $service_parameter, E_USER_NOTICE);
            $service_name = \DEFAULT_SERVICE;
            if (in_array($service_parameter, \ALLOWED_SERVICES, true) && is_dir("services/" . $service_parameter)) {
                $service_name = $service_parameter;
            }
            $_SESSION['service'] = $service_name;
            trigger_error("Service trouvé : " . $_SESSION['service'], E_USER_NOTICE);
        } else {
            trigger_error("Service en session : " . $_SESSION['service'], E_USER_NOTICE);
            $service_name = $_SESSION['service'];
        }
        return $service_name;
    }

    public static function getQueryHandler(): string
    {
        trigger_error("Service Query handler file : " . \SERVICE_DIR . "QueryHandler.php", E_USER_NOTICE);
        trigger_error("Service query handler class : " . \service\QueryHandler::class, E_USER_NOTICE);
        if (file_exists(\SERVICE_DIR . "QueryHandler.php")) {
            trigger_error("Service Query handler overriden", E_USER_NOTICE);
            return \service\QueryHandler::class;
        }
        return \MiniPaviFwk\handlers\QueryHandler::class;
    }
}
