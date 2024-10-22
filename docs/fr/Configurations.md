# Configurations

There are 2 levels of configuration, beside php.ini and your web server configuration.

@TODO

## Global Configuration
[Global configuration file](../../services/global-config.php)
This file contains the first executed statements.


> [!NOTICE]
> The global constants in services/global-config.php are at the root namespace ( \\ ).
> You should always refer to them using `\{ConstantName}`


### Global Configuration mandatory entries

- `const ALLOWED_SERVICES` : array of allowed services names, that should be the services/{serviceName}

- `const DEFAULT_SERVICE` : name of default service, selected by default


### Global Configuration optional entries

- `error_reporting(E_ERROR | E_USER_WARNING | E_PARSE)` : default error reporting, preferably modified for development or debug through the service-config.php

- `const SESSION_HANDLER_CLASSNAME` : full name of the Session handler Classname

- `const SERVICE_HANDLER_CLASSNAME` : full name of the Service Handler Classname

- `const QUERY_HANDLER_CLASSNAME` : contains the class name of the service Query Handler as a string, it is overridable in the service-config.php of a specific service


## Service Configuration
Service configuration is named `service-config.php` on the services/{serviceName} directory.
It is executed after Session is started and Service is identified and validated.


> [!IMPORTANT]
> `namespace service;` should be put at the beginning of your service-config.php file
> Service configuration constants are all in the `\service` namespace while global configuration is at the \\ root
> You should always refer to them using `\service\{ConstantName}`
>
> There's also the `\MiniPaviFwk\helpers\ConstantHelper::getConstValueByName(string $constName, $defaultValue = null): string`
> This static function could be used to retrieve Const values hierarchically by name in this order, with a default value:
> Service Configuration -> Global Configuration -> optionally provided Default Value -> null


### Service Configuration mandatory entries

- `const DEFAULT_CONTROLLER` : might contain the full name of a Videotex or XML Controller, or false if none.


### Service Configuration optional entries

- `const QUERY_HANDLER_CLASSNAME` : contains the class name of the service Query Handler as a string, override the same constant on the global configuration if present


- `const SERVICE_ERROR_REPORTING` allow to change the `error_reporting()` mask, useful for development by enabling more traces for some sites while keeping stable sites more quiet.
You will encounter that on the DemoChat Minitel service, through its [service-config.php](../../services/demochat/service-config.php)

You could add whatever entry fit the needs of your service too.

I recommend to always use `\MiniPaviFwk\helpers\ConstantHelper::getConstValueByName(string $constName, $defaultValue = null): string` to access the Constants, to be able to put them hierarchically and also to prevent disruption if/when Constant Management or a Profile Management might be implemented.


## References
[Global configuration file](../../services/global-config.php)

[Session Handler](./Session-handler.md)

[Service Handler](./Service-handler.md)

[Bootstrap](./Bootstrap.md)

[myservice service-config.php](../../services/myservice/service-config.php)
