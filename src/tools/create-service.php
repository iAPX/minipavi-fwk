<?php

/**
 * Creates a new service
 *
 * - Checks it doesn't already exist
 * - Creates the service directories
 * - service-config.php file
 * - AccueilController.php file
 * - Add it to global-config.php allowed service list and make it optionnally default
 */

// Protection against Web requests
require_once "xmlint-import/helpers/no_web.php";
