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
        $service_name = static::getServiceName();
        trigger_error("Service : " . $service_name, E_USER_NOTICE);
        define('SERVICE_NAME', $service_name);
        define('SERVICE_DIR', "services/" . mb_strtolower(\SERVICE_NAME) . "/");
        require_once \SERVICE_DIR . "service-config.php";

        // change the error reporting level if specified
        if (defined('SERVICE_ERROR_REPORTING')) {
            error_reporting(SERVICE_ERROR_REPORTING);
        }
    }

    public static function getServiceName(): string
    {
        if (!isset($_SESSION['service'])) {
            // @TODO Cleanup this mess! Checks for service={service_name}[& ... ] in $_SERVER['PATH_INFO']
            $parameter = substr($_SERVER['PATH_INFO'], 10);
            trigger_error("Service Parameter : " . $parameter, E_USER_NOTICE);

            $service_name = empty($parameter) ? \DEFAULT_SERVICE : $parameter;
            trigger_error("Service name : " . $service_name, E_USER_NOTICE);

            self::setServiceName($service_name);
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
        // @TODO should be generic override, have to think about it! Might be in service-config.php
        trigger_error("Service Query handler file : " . \SERVICE_DIR . "QueryHandler.php", E_USER_NOTICE);
        trigger_error("Service query handler class : " . \service\QueryHandler::class, E_USER_NOTICE);
        if (file_exists(\SERVICE_DIR . "QueryHandler.php")) {
            trigger_error("Service Query handler overriden", E_USER_NOTICE);
            return \service\QueryHandler::class;
        }
        return \MiniPaviFwk\handlers\QueryHandler::class;
    }
}
