
Here you could put your Minitel service Controllers.

Demo*Controllers.php : used by the demo.xml service

I recommend you start by modifying Demo*Controller.php to try every feature, 
before writing your own controllers.


Also see /src/controllers/* and /src/controllers/README.md

A controller provides these basic services, by itself or through its parents:

->getContext() : returns the context array to be serialized in the session.
  context is reloaded at each request and the controller instantiated again to handle user input.

->ecran() : returns the string to be displayed when entering the controller.
  Notice that by default [REPETITION] will also use ->ecran() output.
  XmlController use EcranXml::ecran() to interpret XML service files
  see /src/xml/EcranXml.php

->getCmd() : returns a new MiniPavi Command as an array
  this array contains the informations about the location of the input area,
  and various optional informations.
  see /src/cmd/ZoneSaisieCmd.php
  @TODO extend it later on to support multiline input
  XmlController use ZoneSaisieCmdXml to interpret XML service files
  see /src/xml/ZoneSaisieCmdXml.php

->validation() : return a \MiniPaviFwk\Validation object
  this object handles the validated function keys for MiniPavi.
  Any key that is not validated won't be handled by MiniPavi and thus not transferred to the service.
  The input is silently discarded.
  see /src/Validation.php
  So better to be safe acccepting too many keys or all of them, that to have them blocked.
  As often XmlController use ValidationXml to interpret XML service files
  see /src/xml/ValidationXml.php

->getSaisieAction() : return an \MiniPaviFwk\actions\Action object when a non-message saisie is recveived.
  This object stores the information about the controller to be instantiated by the next user input.
  Also the videotex code to be output.
  see /src/actions/* and /src/actions/README.md

  Notice that Touche is mb_ucfirst when used on a method name, and in uppercase as a parameter.
  Saisie is mb_ucfirst when used on a methodname, and keep its case (so case sensitive) when a parameter.

  \MiniPaviFwk\controllers\VideotexController implement its logic.
  1-Try the KeywordHandler->choix(), and stop if an \MiniPaviFwk\actions\Action is returned
  2-Try a $this->choix{Saisie}{Touche}() if exists. mb_ucfirst() is used for both saisie and touche.
    For example 1 + [ENVOI] will try to call $this->choix1Envoi()
    or DEV [SOMMAIRE] will try $this->choixDevSommaire()
    Notice that * is replaced by 'ETOILE', thus * [ENVOI] trigger $this->choixETOILEEnvoi()
    And # is replaced by DIESE, thus #DEV [ENVOI] trigger $this->choixDIESEdevEnvoi()
  3-Try a $this->touche{Touche}($saisie) if exists. Touche is mb_ucfirst().
    For example, if none have been found at the preceding step,
    1 + [ENVOI] will try to call $this->toucheEnvoi("1")
    or DEV [SOMMAIRE] will try $this->toucheSommaire("DEV")
    * [ENVOI] will trigger $this->toucheEnvoi("*"), * or # are kept as-is when passed as parameters,
    thus #DEV [ENVOI] trigger $this->toucheEnvoi("#DEV")
    Stop when one method is found and send back a \MiniPaviFwk\actions\Action object
  4-call the $this->choix($touche, $saisie)
    Stop if it returns a \MiniPaviFwk\actions\Action.
    1 + [ENVOI] will try to call $this->choix("ENVOI", "1")
    or DEV [SOMMAIRE] will try $this->choix("SOMMAIRE", "DEV")
    * [ENVOI] will trigger $this->choix("ENVOI", "*")
  5-call $this->nonPropose($touche, $saisie)
    see ->nonPropose()

->nonPropose() : return a Unicode string that will be displayed on line 00 when user input is not recognized.
  VideotexController implements a default, XmlController will use the <page><action default="xyz"> if present,
  and your controller might override it totally or conditionaly.

->choix() : return an \MiniPaviFwk\actions\Action object or null.
  Handle the user input, and respond with any Action object or null.
  Null will trigger call to nonPropose() in cascade.

Optional methods, 
->touche*() : see ->getSaisieAction()
->choix*() : see ->getSaisieAction()

