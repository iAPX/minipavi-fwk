
Here you could put your keywords handlers

It should extends \MiniPaviFwk\Keywords, see /src/Keywords.php
And provides 2 methods:

->validationKeys() returns an array of function key name string (case insensitive)
  these are the function keys handled with or without saisie parameter on the choix() method.
  All keys should be provided to avoid them to be blocked by MinipaviCli.
  example: ['Suite', 'ENVOI']

->choix() returns a \MiniPaviFwk\actions\Action if choix is recognized, null elsewhere.
  this is called by VideotexController->getSaisieAction() and take precedence over any local action.
