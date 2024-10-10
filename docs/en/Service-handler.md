# Service Handler

Responsability: handle service selection and bootstrap

Source file : [src/handlers/ServiceHandler.php](../../src/handlers/ServiceHandler.php)


## Usage

Select Service, Start selected Service, set Service with checks, and get the Service Query Handler


## Your own Service Handler

You could change your Service Handler by modifying ./services/global-config.php `const SERVICE_HANDLER_CLASSNAME` with the full name or your Service Handler Class.

It should support:

### `public static function startService(): void`

Starts the service itself, defining `\SERVICE_NAME`, `\SERVICE_DIR`, including the service configuration `service-config.php` and changin the `error_report()` mask if defined in `SERVICE_ERROR_REPORTING`.

### `public static function getServiceName(): string`

Gets the actual service name.
Default behaviour is to check in this order: `$_SESSION['service']` and `\DEFAULT_SERVICE` from ./services/global-config.php

### `public static function setServiceName(string $service_name): void`

Sets a new service by its name.
The service name should be present in the `\ALLOWED_SERVICES` array of ./services/global-config.php

### `public static function getQueryHandler(): string`

Gets the Service Query Handler class name.
Actual behaviour, that *WILL* change, by checking if there's a QueryHandler.php file on the root directory of the service.
This is pretty ugly.


## Side note to directly access any of the allowed Minitel Services
You could change service, within the services/global-config.php `const ALLOWED_SERVICES` list by putting the service name this way:
http://{server}[:{portNumber}]/%3Fservice={serviceName}

Simply add %3Fservice={serviceName} on the URL


## References

[Query Handler](./Query-handler.md)

[Bootstrap](./Bootstrap.md)

[Configuration](./Configurations.md)

[Actions](./Actions)
