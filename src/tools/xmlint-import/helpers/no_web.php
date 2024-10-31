<?php

/**
 * Disallow execution from Web or http request
 *
 * Notice that its presence could be checked remotely to identify minipavi-fwk if server is misconfigured.
 */

if (php_sapi_name() !== 'cli') {
    header('HTTP/1.0 403 Forbidden');
    exit(0);
}
