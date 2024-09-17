
The different \MiniPaviFwk\actions\Action

- AccueilAction : send back to the Accueil (homepage) of the service
  Depending on service/config.php,
  instantiate the DEFAULT_CONTROLLER is not false or empty.
  if not, use the DEFAULT_XML_FILE, in service/xml and adding ".xml" extension,
  in this case startup page is provided by the <service><debut nom="xxx" /> of the XML

- ControllerAction : take a controller class name, instantiate it, and execution continues with it

- DeconnexionAction : Disconnect the user. Block aany future usage until reconnected.
  Used to get ride of a user.

- Ligne00Action : output texte in the first line of the Minitel (line 00 technically)
  often used to display an error message when input is not handled.

- PageAction : go to any page on any XML file, or any page of the actual XML File for any XmlController

- RepetitionAction : redisplay the actual screen content

- UnicodeOutputAction: output unicode text, after conversion to videotex

- VideotexOutputAction: output videotex text
