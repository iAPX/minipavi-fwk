<?php

/**
 * Configuration for the Demo Chat service
 */

namespace service;

// Homepage controller
const DEFAULT_CONTROLLER = \service\controllers\AccueilController::class;

// Service specific error reporting
const SERVICE_ERROR_REPORTING = E_ALL;

// Service specific Query Handler
const QUERY_HANDLER_CLASSNAME = \service\handlers\QueryHandler::class;


// Service specific Ligne00 message when a choice is not offered
const NON_PROPOSE_LIGNE00 = "C'est votre dernier mot jean-Pierre?";

/**
 * Directory for temporary chat files, using system temp directory, write intensive.
 * On Raspberry Pi with a microSD card, I recommend creating and mounting a 4MB virtual disk in /tmp/demochat
 */
const CHAT_DIR =  '/tmp/demochat/';


// Timeout for connects, in seconds
const CONNECTES_TIMEOUT = 600;

// Timeout for messages, in seconds
const MESSAGES_TIMEOUT = 3600;
