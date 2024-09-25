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
    MiniPavi\MiniPaviCli::start();
    \MiniPaviFwk\handlers\SessionHandler::startSession();
    \MiniPaviFwk\handlers\ServiceHandler::startService();
    $queryHandler = \MiniPaviFwk\handlers\ServiceHandler::getQueryHandler();

    // Execute the query
    // Many informations returned to enable wrapping by the optional service Query Handler
    list($action, $controller, $cmd, $context, $output, $nextPage, $directCall) = $queryHandler::queryLogic();

    // Ends saving session and returning command
    \MiniPaviFwk\handlers\SessionHandler::setContext($context);
    \MiniPavi\MiniPaviCli::send($output, $nextPage, "", true, $cmd, $directCall);
} catch (Exception $e) {
    throw new Exception('Erreur MiniPavi ' . $e->getMessage());
}

trigger_error("fin");
