<?php

/**
 * The autoloader class
 */

spl_autoload_register(function ($class) {
    if (substr($call, 0, 8) === 'service\\') {
        // Get the relative class name : service\xxxx\yyy -> xxxx\yyy
        $relativeClass = substr($class, 8);

        // Replace the namespace separator with a directory separator: xxx\yyy -> services/{servicenName}/xxxx/yyy.php
        $file = SERVICE_DIR . str_replace('\\', '/', $relativeClass) . '.php';

        // If the file exists, require it
        if (file_exists($file)) {
            require_once $file;
        }
    }
});
