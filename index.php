<?php

/**
 * Entry point for the MiniPavi Web Server
 */

// Global configuration, autoloaders and ou mb_ucfirst() function
require_once "services/global-config.php";
require_once "src/service_autoloader.php";
require_once "vendor/autoload.php";
require_once "src/strings/mb_ucfirst.php";

try {
    \MiniPavi\MiniPaviCli::start();
    (\SESSION_HANDLER_CLASSNAME)::startSession();
    (\SERVICE_HANDLER_CLASSNAME)::startService();
    $queryHandler = (SERVICE_HANDLER_CLASSNAME)::getQueryHandler();

    // Execute the query
    // Many informations returned to enable wrapping by the Service optional Query Handler
    list($action, $controller, $cmd, $context, $output, $nextPage) = $queryHandler::queryLogic();

    // Support Direct Call the right way
    list($action, $controller, $cmd, $context, $output, $nextPage, $directCall) = $queryHandler::directCall(
        $action,
        $controller,
        $cmd,
        $context,
        $output,
        $nextPage
    );

    // Ends saving session and returning command
    (\SESSION_HANDLER_CLASSNAME)::setContext($context);
    \MiniPavi\MiniPaviCli::send($output, $nextPage, "", true, $cmd, $directCall);
} catch (Exception $e) {
    throw new Exception('Erreur MiniPavi ' . $e->getMessage());
}

trigger_error("fin");
