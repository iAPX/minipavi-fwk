<?php

/**
 * Entry point for the MiniPavi Web Server
 */

// Global configuration, autoloaders and ou mb_ucfirst() function
require_once "services/global-config.php";
require_once "src/service_autoloader.php";
require_once "vendor/autoload.php";
require_once "src/strings/mb_ucfirst.php";

// Disable session cookies, session is identified through minipavi's uniqueId
ini_set('session.use_cookies', '0');
ini_set('session.use_only_cookies', '0');

error_reporting(E_USER_NOTICE | E_USER_WARNING);
ini_set('display_errors', 0);

try {
    trigger_error("PHP SELF : " . $_SERVER['PHP_SELF']);
    MiniPavi\MiniPaviCli::start();
    \MiniPaviFwk\handlers\SessionHandler::startSession();
    \MiniPaviFwk\handlers\ServiceHandler::startService();
    $queryHandler = \MiniPaviFwk\handlers\ServiceHandler::getQueryHandler();

    // Execute the query
    list($action, $controller, $cmd, $context, $output, $nextPage) = $queryHandler::queryLogic();

    // Ends saving session and returning command
    \MiniPaviFwk\handlers\SessionHandler::setContext($context);
    \MiniPavi\MiniPaviCli::send($output, $nextPage, "", true, $cmd, false);
} catch (Exception $e) {
    throw new Exception('Erreur MiniPavi ' . $e->getMessage());
}

trigger_error("fin");
