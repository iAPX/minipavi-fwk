<?php

/**
 * MiniPavi Global Configuration file, common toall and every service
 *
 * Each service configuration is in services/{serviceName}/service-config.php
 */

// Global error reporting config
// You might change it globally or by service in their services/{serviceName}/service-config.php
error_reporting(E_ERROR | E_USER_WARNING | E_PARSE);

// Don't remove, or NOTICE, WARNINGS and ERRORS will be displlayed on the Minitel screen!
ini_set('display_errors', 0);

// Sets the allowed services.
const ALLOWED_SERVICES = ['demo', 'demochat', 'myservice'];

// Sets the default service, should be in the ALLOWED_SERVICES array.
const DEFAULT_SERVICE = 'demo';

// Global Ligne00 message when a choice is not offered
const NON_PROPOSE_LIGNE00 = "Choix non proposé.";


// Sets the Session Handler class name. Default is \MiniPaviFwk\handlers\SessionHandler
// If you have problems with Sessions, uncomment the following line, your sessions will be handled by Minipavi!
// const SESSION_HANDLER_CLASSNAME = \MiniPaviFwk\handlers\MiniPaviSessionHandler::class;

// Sets the Service Handler class name.
// const SERVICE_HANDLER_CLASSNAME = \MiniPaviFwk\handlers\ServiceHandler::class;

// Sets the Default Query Handler class name.
// const QUERY_HANDLER_CLASSNAME = \MiniPaviFwk\handlers\QueryHandler::class;
