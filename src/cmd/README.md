
The minipavi commands, 'cmd' in short

- Cmd : abstract class exposing ->getCmd() returning a command as an array

- ZoneSaisieCmd : command to handle an input area with validated keys

- ZoneMessageCmd : command to handle a multiline message area with validated keys

- DeconnexionCmd : command to disconnect the user from the service through minipavi
  minipavi might offer the menu of all available services to the user subsequently.

- PushServiceMsgCmd : command to send Ligne00 short messages to a list of user
  usually used through DIRECTCALL and DIRECT fctn, could also be used in FIN/DCX fcn

->createMiniPaviCmd(...) : returns the MiniPavi Command that will be sent back as an array

