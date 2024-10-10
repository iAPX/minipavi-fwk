# Session Handler

Responsability: Session handling


## Usage

Used to auto-create Session, retrieve Sessions information, including current Controller Name and its Context, and eventually destroy Session on disconnection.

It's used through [index.php](../../index.php) to initialize, retrieve Session informations, store them back and also by the [Query Handler](./Query-handler.md) on disconnection. Also used on the SwitchServiceAction


## Your own session handler

Change the `\SESSION_HANDLER_CLASSNAME` with the full name of your own Class on ./services/global-config.php

Your class should implement these static functions:

### `public static function startSession(): void`
Starts the Session, beware Cookies are not enabled nor supported through MiniPaviCli and MiniPavi.
The user UID is given through `\MiniPavi\MiniPaviCli::$uniqueId`.

### `public static function destroySession(): void`
Destroys the current Session datas.
Used on user disconnection, through the DeconnexionAction and also SwitchServiceAction.

### `public static function getContext(): array`
Returns the Execution Context as an array.
Used at the beginning of the Query execution.

### `public static function setContext(array $context): void`
Sets the Context on the Session.
Used at the end of the Query execution.


## References

[Configuration](./Configurations.md)

[Session](./Session.md)

[Context](./Context.md)

[Bootstrap](./Bootstrap.md)

[Actions](./Actions.md)
