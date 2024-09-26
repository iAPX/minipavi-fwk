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
    list($action, $controller, $cmd, $context, $output, $nextPage) = $queryHandler::queryLogic();

    // Ends saving session and returning command
    \MiniPaviFwk\handlers\SessionHandler::setContext($context);

    // Handle DirectCall commands
    // @TODO have to be on QueryLogic!
    if (!empty($_SESSION['DIRECTCALL_CMD'])) {
        DEBUG && trigger_error("DIRECTCALL_CMD : " . print_r($_SESSION['DIRECTCALL_CMD'], true));
        $_SESSION['DIRECTCALL_RELAY'] = [
            'output' => $output,
            'nextPage' => $nextPage,
            'cmd' => $cmd,
        ];
        DEBUG && trigger_error("DIRECTCALL_CMD - Relay créé :" . print_r($_SESSION['DIRECTCALL_RELAY'], true));
        
        $output = "";
        $nextPage = "";
        $cmd = $_SESSION['DIRECTCALL_CMD'];
        unset($_SESSION['DIRECTCALL_CMD']);
        $directCall = 'yes';
    } else {
        $directCall = false;
    }
    \MiniPavi\MiniPaviCli::send($output, $nextPage, "", true, $cmd, $directCall);
} catch (Exception $e) {
    throw new Exception('Erreur MiniPavi ' . $e->getMessage());
}

trigger_error("fin");
