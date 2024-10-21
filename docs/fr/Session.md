# Session

Responsability: store the Context

## What is stored

- `$_SESSION['service']` : the actual Service Name as a string

- `$_SESSION['context']` : the array containing the current controller context

- `$_SESSION['DIRECTCALL_CMD']` : an array containing a prioritized command for MiniPaviCli, usually a PushServiceMsgCmd


## Where do I store the data?

On the Context for what is relevant to the actual controller, scope is the actual Controller.

On the Session directly for what should be persistent between any Controller, for example the nickname chosen by an user.


## Reference
[Context](./Context.md)

[Session](./Session.md)

[Session Handler](./Session-handler.md)

[Commands](./Cmds.md)

[Push Service Messages](./PushServiceMsg.md)
