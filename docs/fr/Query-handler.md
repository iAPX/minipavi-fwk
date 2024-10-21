# Query Handler

Responsability : Handle Queries


## Using your own Query Handler for a Service

In your services/{serviceName}/service-config.php, add a `const QUERY_HANDLER_CLASSNAME` with the name of your Query Handler class.
This is used in the DemoChat Minitel Service.
This override default or global Query Handler selection.


## Changing the default Query Handler for all Services

In your services/{serviceName}/service-config.php, add a `const QUERY_HANDLER_CLASSNAME` with the name of your Query Handler class.
This is overridable in service-config.php Service specific configs.

## References

[Configuration](./Configurations.md)
