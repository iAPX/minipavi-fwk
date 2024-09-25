<?php

/**
 * MiniPavi Global Configuration file, common toall and every service
 *
 * Each service configuration is in services/{serviceName}/service-config.php
 */

// Global error reporting config
// You might change it the way you want
error_reporting(E_USER_NOTICE | E_USER_WARNING);
ini_set('display_errors', 0);

// Set to true to enable Debug informations on-screen.
const DEBUG = false;

// Set the default service
// Included services : demo, macbidouille
const DEFAULT_SERVICE = 'demochat';

// Set the allowed services
const ALLOWED_SERVICES = ['demo', 'demochat', 'macbidouille'];
