<?php

/**
 * MiniPavi Global Configuration file, common toall and every service
 *
 * Each service configuration is in services/{serviceName}/service-config.php
 */

// Set to true to enable Debug informations on-screen.
const DEBUG = false;

// Set the default service
// Included services : demo, macbidouille
const DEFAULT_SERVICE = 'demo';

// Set the allowed services
const ALLOWED_SERVICES = ['demo', 'macbidouille'];
