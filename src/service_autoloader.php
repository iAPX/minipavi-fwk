<?php

/**
 * The autoloader class
 */

spl_autoload_register(function ($class) {
    if (substr($class, 0, 8) === 'service\\') {
        trigger_error("Autoloader Service class : " . $class, E_USER_NOTICE);
        // Get the relative class name : service\xxxx\yyy -> xxxx\yyy
        $relativeClass = substr($class, 8);

        // Replace the namespace separator with a directory separator: xxx\yyy -> services/{servicenName}/xxxx/yyy.php
        $file = SERVICE_DIR . str_replace('\\', '/', $relativeClass) . '.php';
        trigger_error("Autoloader service file : " . $file, E_USER_NOTICE);

        // If the file exists, require it
        if (file_exists($file)) {
            require_once $file;
        }
    }
});
