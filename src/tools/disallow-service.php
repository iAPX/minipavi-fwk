<?php

/**
 * Disallow a service
 *
 * - List all services existing
 * - Ask for service name
 * - if it's the default service, ask for the new default service
 * - Remove it from allowed list
 * - Change the default service if needed
 */

// Protection against Web requests
require_once "xmlint-import/helpers/no_web.php";
