<?php

/**
 * Import XMLint existing and working project
 *
 * Notice that the steps are put into a single file to make it easier to read and debug.
 * And I use Global variables! An anti-pattern! Feel free to rewrite it your way!
 */

// Helpers methods
require_once "xmlint-import/helpers/YESno.php";
require_once "xmlint-import/helpers/input.php";
require_once "xmlint-import/helpers/curl.php";
require_once "xmlint-import/helpers/get_config_const_value.php";


// Global variables
require_once "xmlint-import/helpers/global-variables.php";

// Check directories
include_once "xmlint-import/steps/check-directories.php";

// 1-Display the purpose, ask for confirmation
include_once "xmlint-import/steps/purpose.php";

// 2-Ask for service name [AZaz09-]
include_once "xmlint-import/steps/service-name.php";

// 2-Ask for the XML url and import it
include_once "xmlint-import/steps/getxml.php";

// 2b-Create Controllers
include_once "xmlint-import/steps/xml-to-controllers.php";

// 3-Import the pages
include_once "xmlint-import/steps/getpages.php";

// Display proposed modifications
include_once  "xmlint-import/steps/proposed-config.php";

// Last chance, before modifying or creating anything
include_once  "xmlint-import/steps/summary.php";

// Create the new service, without modifying services/global-config.php
include_once  "xmlint-import/steps/create-service.php";

// Modify services/global-config.php
include_once  "xmlint-import/steps/update-global-config.php";

echo "Done! Enjoy your new $service_name Minitel Service!\n";
