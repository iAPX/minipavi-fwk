# Actions

Responsabilité : fournir les informations à propos de la fin du traitement de la requête actuelle, ainsi que sur le contrôleur à instancier pour la prochaine requête (réponse/interaction utilisateur).

Répertoire source : [src/actions/](../../src/actions/)



## Cycle de vie
Les Actions sont renvoyées par les contrôleurs lors d'une interaction utilisateur, pour envoyer sur un autre contrôleur/une autre page de l'arborescence, afficher un messsage d'erreur, aller sur un autre service intégré, déconnecter l'utilisateur, etc.<br/>
Dans le cycle de vie du contrôleur l'action est renvoyée à la fin, en même temps que la Commande.<br/>
L'action est destinée à MiniPaviFwk pour savoir quelles actions réaliser, la commande est renvoyée à MiniPavi via MiniPaviCli.

Les méthodes toucheXXX(), choixXXXXYYY(), choix() pour les saisie en-ligne et message() pour les messages multilignes renvoient toutes par une Action ou un null.<br/>
Voir [la documentation des contrôleurs](./Controllers.md) et surtout [le Cycle de vie des contrôleurs](./Controller-lifecycle.md).

Lorsque aucune Action n'a été retournée par le ou les méthodes correspondant à la saisie de l'utilisateur, la méthode nonPropose() est alors appelée, et dans le cas de VideotexController elle renvoie un message d'erreur en ligne 00.<br/>
Vous pouvez aussi surcharger cette méthode dans votre contrôleur pour afficher un autre messsage d'erreur.


## Principales Actions fournies par MiniPaviFwk

### AccueilAction : retourner à la page d'accueil
Signature : `new \MiniPaviFwk\actions\AccueilAction(array $context)`

Source : [src/actions/AccueilAction.php](../../src/actions/AccueilAction.php)

$context est le contexte courant, soit $this->context depuis le contrôleur qui l'instancie.

Exemple de code, sur appui de 1 + [envoi], ramène à l'accueil du service:
```
    public function choix1Envoi(): ?MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\AccueilAction($this->context);
    }
```


### PageAction : aller sur une autre page de l'arbo
Signature : `new \MiniPaviFwk\actions\PageAction(array $context, string $pagename)`

Source : [src/actions/PageAction.php](../../src/actions/PageAction.php)

Le contrôleur courant ne reçoit plus aucun appel de méthode et est éliminé.<br/>
Lance le contrôleur correspondant: soit via le nom du contrôleur lui-même, soit pas un nom dérivé d'une page importée, cf. [XMLint importation](./XMLint-transition.md)

Vous pouvez directement utiliser le nom du contrôleur, par exemple `"DemoAccueil"` pour le contrôleur DemoAccueilController.

Vous pouvez alternativement utiliser un nom de page, chaque partie séparée par un tiret '-' est converti via mb_ucfirst() pour obtenir un nom de contrôleur en CamelCase. On y ajoute "Controller" à la fin. `"demo-accueil"` sera alors là aussi traduit en DemoAccueilController.<br/>
Si une partie du nom ne commence pas par un caractère alphabétique [a .. z], un underscore est utilisé pour séparer les parties.

"accueil" -> AcccueilController<br/>
"sommaire-AUTEURS" -> SommaireAuteursController<br/>
"accueil-2b" -> Accueil_2bController<br/>

Exemple de code, pour aller sur le contrôleur DemoSommaireController:
```
    public function toucheSommaire(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, "DemoSommaire");
    }
```


### ControllerAction : activer un autre Contrôleur = aller sur une autre page de l'arbo
Signature : `new \MiniPaviFwk\actions\ControllerAction(string $newControllerClassName, array $context, array $params = [])`

Source : [src/actions/ControllerAction.php](../../src/actions/ControllerAction.php)

Le contrôleur courant ne reçoit plus aucun appel de méthode et est éliminé.<br/>
$newControllerClassName est le nom du contrôleur que l'on veut voir s'exécuter en réponse à une interaction utilisateur.<br/>
On l'exprime souvent sous la forme `\service\controllers\{controller}::class` pour éviter une constante "magique".<br/>
$context est le contexte courant, soit $this->context depuis le contrôleur qui l'instancie.<br/>
$params sont des paramètres optionnels envoyés au constructeur du nouveau contrôleur.<br/>

Exemple de code, sur appui sur [Sommaire], quelque-soit la saisie, envoie sur ListeControlller:
```
    public function toucheSommaire(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\ControllerAction(\service\controllers\ListeController::class, $this->context);
    }
```


### Ligne00Action : afficher un message Unicode en ligne, usuellement un message d'erreur
Signature : `\MiniPaviFwk\actions\Ligne00Action($this, string $texte)`

Source : [src/actions/Ligne00Action.php](../../src/actions/Ligne00Action.php)

Le contrôleur courant est conservé, les appels de méthodes lui sont destinés.<br/>
$texte le texte unicode à afficher.<br/>
Affiche le texte Unicode en ligne 00, et reste sur ce contrôleur. Sauf si vous êtes joueur ;)

Exemple de code, affiche un message d'erreur en ligne00 si aucun choix n'a été trouvé:
```
    public function nonPropose(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\Ligne00Action($this, "Message d'erreur ici");
    }
```


## Actions moins fréquentes

### RepetitionAction : réaffiche la page courante
Signature : `new \MiniPaviFwk\actions\RepetitionAction($this)`

Source : [src/actions/RepetitionAction](../../src/actions/RepetitionAction.php)

Le contrôleur courant est conservé, les appels de méthodes lui sont destinés.<br/>
Réaffiche la page courante en restant sur le même contrôleur. Sauf si vous êtes joueurs ;)<br/>
Notez que par défaut VidéotexController supporte l'appui sur la touche de fonction [Répétition] pour assurer ce réaffichage en utilisant cette même action.

Exemple de code, répétant la page courante sur appui sur la touche [Répétition]:
```
    public function toucheRepetition(string $saisie): ?MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\RepetitionAction($this);
    }
```


### DeconnexionAction : met fin à la visite d'un usager
Signature : `new \MiniPaviFwk\actions\DeconnexionAction()`

Source : [src/actions/DeconnexionAction](../../src/actions/DeconnexionAction.php)

Le contrôleur courant ne reçoit plus aucun appel de méthode et est éliminé.<br/>
Déconnecte l'usager. Fin de sa visite.

Exemple de code, déconnecte l'usager si il entre *dcx + [Envoi] (insensible à la casse, *DCX ou *Dcx son équivalents) :
```
    public function choixETOILEdcxEnvoi(): ?MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\DeconnexionAction();
    }
```


### SwitchServiceAction : bascule sur un des autres services gérés par minipavi-fwk
Signature : `new \MiniPaviFwk\actions\SwitchServiceAction(string $newServiceName, string $videotexOutput = "", int $waitSeconds = 0)`

Source : [src/actions/SwitchServiceAction](../../src/actions/SwitchServiceAction.php)

Le contrôleur courant ne reçoit plus aucun appel de méthode et est éliminé.<br/>
$newServiceName doit contenir le nom d'un service local qui est autorisé dans [services/global-config.php](../../services/global-config.php), voir à ce sujet la [documentation sur la configuration](./Configurations.md).

$videotexOutput contient optionnellement un text encodé en Vidéotex qui sera affiché avant la bascule sur le nouveau service.<br/>
$waitSeconds permet d'ajouter une attente (en secondes) entre l'affichage du texte et la bascule vers le nouveau service, il n'est utilisé que si $output n'est pas vide. Vous avez le droit de tricher.

Exemple de code, envoi l'usager sur le service interne MacBidouille sur appui de 8 + [Envoi], avec affichage d'un message et 2 secondes d'attente:
```
    public function choix8Envoi(): ?MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\SwitchServiceAction(
            'macbidouille',
            chr(12) . \MiniPavi\MiniPaviCli::toG2("*** REDIRECTION VERS MACBIDOUILLE ***"),
            2
        );
    }
```


### VideotexOutputAction : sortie Vidéotex simple
Signature : `new \MiniPaviFwk\actions\VideotexOutputAction($this, string $videotexOutput);`

Source : [src/actions/VideotexOutputAction](../../src/actions/VideotexOutputAction.php)

Le contrôleur courant est conservé, les appels de méthodes lui sont destinés.<br/>
$videotexOutput contient les codes Vidéotex à envoyer au Minitel.

Exemple de code, affiche un message Vidéotex à l'écran sur 7 + [Envoi]:
```
    public function choix7Envoi(): ?MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\VideotexOutputAction($this, " \x1bE Texte \x1b Fvideotex               ");
    }
```

### RedirectAction : redirige vers un autre service supporté par MiniPavi, via son URL
Signature : `new \MiniPaviFwk\actions\RedirectAction(string $newUrl, string $videotexOutput = "", int $waitSeconds = 0)`

Source : [src/actions/RedirectAction](../../src/actions/RedirectAction.php)

Le contrôleur courant ne reçoit plus aucun appel de méthode et est éliminé.<br/>
Similaire à SwitchServiceAction qui redirige sur un service interne, celui-ci redirige sur un service externe via son URL.

$url contient l'URL du services vers lequel l'usager sera redirigé. Cela ne peut être l'URL d'un service interne.

$videotexOutput contient optionnellement un text encodé en Vidéotex qui sera affiché avant la bascule sur le nouveau service.<br/>
$waitSeconds permet d'ajouter une attente (en secondes) entre l'affichage du texte et la bascule vers le nouveau service, il n'est utilisé que si $output n'est pas vide. Vous avez le droit de tricher.

Exemple de code, envoi l'usager sur le service Minitel https://minitel.example.com sur appui de 8 + [Envoi], avec affichage d'un message et 2 secondes d'attente:
```
    public function choix8Envoi(): ?MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\RedirectAction(
            'https://minitel.example.com/',
            chr(12) . \MiniPavi\MiniPaviCli::toG2("*** REDIRECTION VERS MACBIDOUILLE ***"),
            2
        );
    }
```
