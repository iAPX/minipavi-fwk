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
        $service_name = self::getService();
        trigger_error("Service : " . $service_name);
        define('SERVICE_NAME', $service_name);
        define('SERVICE_DIR', "services/" . mb_strtolower(\SERVICE_NAME) . "/");
        require_once \SERVICE_DIR . "service-config.php";
    }

    protected static function getService(): string
    {
        if (!isset($_SESSION['service'])) {
            $parameter = substr($_SERVER['PATH_INFO'], 10);
            DEBUG && trigger_error("Service Parameter : " . $parameter);

            $service_parameter = empty($parameter) ? \DEFAULT_SERVICE : $parameter;
            DEBUG && trigger_error("Service parameter chosen : " . $service_parameter);
            $service_name = \DEFAULT_SERVICE;
            if (in_array($service_parameter, \ALLOWED_SERVICES, true) && is_dir("services/" . $service_parameter)) {
                $service_name = $service_parameter;
            }
            $_SESSION['service'] = $service_name;
        } else {
            $service_name = $_SESSION['service'];
        }
        return $service_name;
    }

    public static function getQueryHandler(): string
    {
        trigger_error("Service Query hadnler file : " . \SERVICE_DIR . "QueryHandler.php");
        trigger_error("Service query handler class : " . \service\QueryHandler::class);
        if (file_exists(\SERVICE_DIR . "QueryHandler.php")) {
            return \service\QueryHandler::class;
        }
        return \MiniPaviFwk\handlers\QueryHandler::class;
    }
}
