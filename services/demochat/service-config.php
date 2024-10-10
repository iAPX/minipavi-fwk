<?php

/**
 * Configuration for the Demo Chat service
 */

namespace service;

const DEFAULT_CONTROLLER = \service\controllers\AccueilController::class;


/**
 * Directory for temporary chat files, using system temp directory, write intensive.
 * Alternatively : const CHAT_DIR = '/tmp/minipavifwk-demochat/';
 * On Raspberry Pi with a microSD card, I recommend creating and mounting a 4MB virtual disk in /tmp/demochat
 * define('CHAT_DIR', sys_get_temp_dir() . '/minipavifwk-demochat/');
 */
define('CHAT_DIR', '/tmp/demochat/');

// Timeout for connects, in seconds
const CONNECTES_TIMEOUT = 600;

// Timeout for messages, in seconds
const MESSAGES_TIMEOUT = 3600;

// Service specific error reporting
const SERVICE_ERROR_REPORTING = E_ALL;

// Service specific Query Handler
const QUERY_HANDLER_CLASSNAME = \service\handlers\QueryHandler::class;
