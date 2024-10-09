<?php

/**
 * MiniPavi Global Configuration file, common toall and every service
 *
 * Each service configuration is in services/{serviceName}/service-config.php
 */

// Global error reporting config
// You might change it globallly or by service in their services/{serviceName}/service-config.php
error_reporting(E_ERROR | E_USER_WARNING | E_PARSE);
ini_set('display_errors', 0);

// Sets the allowed services.
const ALLOWED_SERVICES = ['demo', 'demochat', 'macbidouille'];

// Sets the default service, should be in the ALLOWED_SERVICES array.
const DEFAULT_SERVICE = 'demo';
