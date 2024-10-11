<?php

/**
 * Ensure we are in the root directory of the project, and that services are correctly set with aa global-config.php
 */

if (!file_exists("./src/tools/xmlint-import.php")) {
    die("Please run this script from the root directory of the project.\n");
}

if (!is_dir("./services")) {
    die("./services does not exist.\n");
}
if (!file_exists("./services/global-config.php")) {
    die("./services/global-config.php does not exist.\n");
}
