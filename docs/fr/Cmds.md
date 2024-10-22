# Commandes (Cmd)

@TODO

Responsability : build MiniPaviCli commands.

Source directory : [src/cmd/](../../src/cmd/)


Essentially an input area or a multiline input area.
Could also be used to throw away an user, or to create Push Service Messages

On XmlController XML managed services, the commands are created through `<zonesaisie/>` and `<zonemessage/>`.


## Provided commands

- Cmd : abstract class exposing ->getCmd() returning a command as an array

- ZoneSaisieCmd : command to handle an input area with validated keys (single line input)

- ZoneMessageCmd : command to handle a multiline message area with validated keys

- DeconnexionCmd : command to disconnect the user from the service through minipavi
  minipavi might offer the menu of all available services to the user subsequently.

- PushServiceMsgCmd : command to send Ligne00 short messages to a list of user
  usually used through DIRECTCALL and DIRECT fctn, could also be used in FIN/DCX fcn


## Create your own command
You might want to add new Commands for example supporting new and extended features of MiniPaviCli.

Add a subdirectory cmd, and add your own extending [\minipavifwk\cmd\Cmd](../../src/cmd/Cmd.php), and implementing a `public static function createMiniPaviCmd( ... ): array` static method.


## References

[Provided commands](../../src/cmd/)

[Validation and validated keys documentation](./Validation.md)

[XmlController.php](../../src/controllers/XmlController.php)

[Controllers documentation](./Controllers.md)

[Query Handler documentation](./Query-handler.md)

[MiniPaviCli documentation](https://github.com/ludosevilla/minipaviCli/blob/main/MiniPaviCli-doc.pdf)

[XMLint documentation](https://raw.githubusercontent.com/ludosevilla/minipaviCli/master/XMLint/XMLint-doc.pdf)
