<?php

/**
 * Handles the actual service and provides means to service switching
 *
 */

namespace MiniPaviFwk\handlers;

use MiniPavi\MiniPaviCli;
use MiniPaviFwk\handlers\QueryHandler;
use MiniPaviFwk\helpers\ConstantHelper;

class ServiceHandler
{
    public static function startService(): void
    {
        // Get the service-config and set constants for autoloader and code
        $service_name = static::getServiceName();
        trigger_error("Service : " . $service_name, E_USER_NOTICE);
        define('SERVICE_NAME', $service_name);
        define('SERVICE_DIR', "services/" . mb_strtolower(\SERVICE_NAME) . "/");
        require_once \SERVICE_DIR . "service-config.php";

        // change the error reporting level if specified
        if (defined('\\service\\SERVICE_ERROR_REPORTING')) {
            error_reporting(\service\SERVICE_ERROR_REPORTING);
        }
    }

    public static function getServiceName(): string
    {
        if (!isset($_SESSION['service'])) {
            $service_name = isset(MiniPaviCli::$urlParams->service) ?
                MiniPaviCli::$urlParams->service : \DEFAULT_SERVICE ;
            trigger_error("Service name : " . $service_name, E_USER_NOTICE);

            static::setServiceName($service_name);
        } else {
            trigger_error("Service en session : " . $_SESSION['service'], E_USER_NOTICE);
            $service_name = $_SESSION['service'];
        }
        return $service_name;
    }

    public static function setServiceName(string $service_name): void
    {
        if (!in_array($service_name, \ALLOWED_SERVICES, true) || ! is_dir("services/" . $service_name)) {
            trigger_error("Service not allowed : " . $service_name, E_USER_ERROR);
            throw new Exception("Service not allowed : " . $service_name);
        }
        $_SESSION['service'] = $service_name;
        trigger_error("Service setted : " . $service_name, E_USER_NOTICE);
    }

    public static function getQueryHandler(): string
    {
        return ConstantHelper::getConstValueByName(
            'QUERY_HANDLER_CLASSNAME',
            QueryHandler::class
        );
    }
}
