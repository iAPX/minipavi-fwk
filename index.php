<?php

/**
 * Entry point for the MiniPavi Web Server
 */

// Global configuration, autoloaders and ou mb_ucfirst() function
require_once "services/global-config.php";
require_once "src/service_autoloader.php";
require_once "vendor/autoload.php";
require_once "src/helpers/mb_ucfirst.php";

use MiniPavi\MiniPaviCli;
use \MiniPaviFwk\helpers\ConstantHelper;
use \MiniPaviFwk\handlers\ServiceHandler;


// BUGFIX v1.1.1 for php 8.2 w/ help from @ludosevilla - temporary ugly fix until I figure out a better solution!
require_once "src/controllers/VideotexController.php";
require_once "src/controllers/MultipageController.php";


try {
    MiniPaviCli::start();

    // Start Session
    $session_handler = ConstantHelper::getConstValueByName(
        'SESSION_HANDLER_CLASSNAME',
        \MiniPaviFwk\handlers\SessionHandler::class
    );
    $session_handler::startSession();

    // Start Service
    $service_handler = ConstantHelper::getConstValueByName(
        'SERVICE_HANDLER_CLASSNAME',
        ServiceHandler::class
    );
    $service_handler::startService();

    // Execute the query
    // Many informations returned to enable wrapping by the Service optional Query Handler
    $query_handler = $service_handler::getQueryHandler();
    list($action, $controller, $cmd, $context, $output, $nextPage) = $query_handler::queryLogic();

    // Support Direct Call the right way
    list($action, $controller, $cmd, $context, $output, $nextPage, $directCall) = $query_handler::directCall(
        $action,
        $controller,
        $cmd,
        $context,
        $output,
        $nextPage
    );

    // Ends saving session and returning command
    $session_handler::setContext($context);
    MiniPaviCli::send($output, $nextPage, "", true, $cmd, $directCall);
} catch (Exception $e) {
    throw new Exception('Erreur MiniPavi ' . $e->getMessage());
}

trigger_error("fin");
