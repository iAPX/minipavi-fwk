# Configurations

There are 2 levels of configuration, beside php.ini and your web server configuration.

## Global Configuration
[Global configuration file](../../services/global-config.php)
This file contains the first executed statements.

- `error_reporting(E_ERROR | E_USER_WARNING | E_PARSE)` : default error reporting, preferably modified for development or debug through the service-config.php

- `const ALLOWED_SERVICES` : array of allowed services names, that should be the services/{serviceName}

- `const DEFAULT_SERVICE` : name of default service, selected by default


## Service Configuration
Service configuration is named `service-config.php` on the services/{serviceName} directory.
It is executed after Session is started and Service is identified and validated.


### Service Configuration mandatory entries

- `const DEFAULT_XML_FILE` : default XML filename (without extension) located in the services/{serviceName}/xml directory, or false if none should be used

- `const XML_PAGES_URL` : when using a XmlController XML managed service or parts, contains the URL and start path for Vidéotex Pages. (see below)

- `const DEFAULT_CONTROLLER` : might contain the full name of a Videotex or XML Controller, or false if none. If false, AccueilAction fallback to the DEFAULT_XML_FILE xml file handled by the [default XmlController](../../src/controllers/XmlController.php).


#### XML_PAGES_URL mechanism


// The URL for XML local Videotex Pages, replaced by services/{serviceName}/vdt/{pagename} when interpreting XML
// Elsewhere pages will be fetched through a http/https query to keep compatiblity with existing XML (TEST only)
// const XML_PAGES_URL = "https://minitelbidouille.pvigier.com/pages/";
const XML_PAGES_URL = false;  // Don't try to use pages from vdt folder when a http/https scheme is present

// The default controller & Sommaire controller
// For a service beginning with a VideotexController or XmlController, put it here
const DEFAULT_CONTROLLER = false;  // If false, use xml/{DEFAULT_XML_FILE}.xml



### Service Configuration optional entry

- `const SERVICE_ERROR_REPORTING` allow to change the `error_reporting()` mask, useful for development by enabling more traces for some sites while keeping stable sites more quiet.
You will encounter that on the DemoChat Minitel service, through its [service-config.php](../../services/demochat/service-config.php)

You might add whatever entry fit the needs of your service too.


## Query bootstrap order
1. services/global-config.php
2. Class autoloaders
3. MiniPavi\MiniPaviCli::start()
4. \MiniPaviFwk\handlers\SessionHandler::startSession()
5. \MiniPaviFwk\handlers\ServiceHandler::startService()
6. services/{serviceName}/service-config.php

There's a chain of dependencies, needing to identify and validate the Service, for that having a Session, thus having access to MiniPaviCli queries results, this one needing autoloaders.


## References
[Global configuration file](../../services/global-config.php)

[default XmlController](../../src/controllers/XmlController.php)

[DemoChat service's service-config.php](../../services/demochat/service-config.php)

[Service Handler](../../src/handlers/ServiceHandler.php)