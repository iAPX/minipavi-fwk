# Actions

Responsabilité : fournir les informations à propos de la fin du traitement de la requête actuelle, ainsi que sur le contrôleur à instancier pour la prochaine requête (réponse/interaction utilisateur).

Répertoire source : [src/actions/](../../src/actions/)



## Cycle de vie
Les Actions sont renvoyées par les contrôleurs lors d'une interaction utilisateur, pour envoyer sur un autre contrôleur/une autre page de l'arborescence, afficher un messsage d'erreur, aller sur un autre service intégré, déconnecter l'utilisateur, etc.
Dans le cycle de vie du contrôleur l'action est renvoyée à la fin.

Les méthodes toucheXXX(), choixXXXXYYY(), choix() pour les saisie en-ligne et leurs pendants pour les messages multilignes, messageToucheXXX(), messageChoixXXXYYY(), messageChoix(), se terminent tous par une Action ou un null.
Voir [la documentation des contrôleurs](./Controllers.md)

Lorsque aucune Action n'a été retournée par le ou les méthodes correspondant à la saisie de l'utilisateur, la méthode nonPropose() est alors appelée, et dans le cas de VideotexController elle renvoie un message d'erreur en ligne 00.
Vous pouvez donc surcharger cette méthode pour envoyer un autre messsage d'erreur.


## Principales Actions fournies par MiniPaviFwk

### AccueilAction : retourner à la page d'accueil
[src/actions/AccueilAction.php](../../src/actions/AccueilAction.php)
`new \MiniPaviFwk\actions\AccueilAction(array $context)`

$context est le contexte courant, soit $this->context depuis le contrôleur qui l'instancie.


### ControllerAction : activer un autre Contrôleur = aller sur une autre page de l'arbo
[src/actions/ControllerAction.php](../../src/actions/ControllerAction.php)
`new \MiniPaviFwk\actions\ControllerAction(string $newControllerClassName, array $context, array $params = [])`

$newControllerClassName est le nom du contrôleur que l'on veut voir s'exécuter en réponse à une interaction utilisateur.
On l'exprime souvent sous la forme `\service\controllers\{controller}::class` pour éviter une constante "magique".

$context est le contexte courant, soit $this->context depuis le contrôleur qui l'instancie.

$params sont des paramètres optionnels envoyés au constructeur du nouveau contrôleur.


### Ligne00Action : afficher un message Unicode en ligne, usuellement un message d'erreur
[src/actions/Ligne00Action.php](../../src/actions/Ligne00Action.php)
`\MiniPaviFwk\actions\Ligne00Action($this, string $texte)`

$texte le texte unicode à afficher.

Affiche le texte Unicode en ligne 00, et reste sur ce contrôleur. Sauf si vous êtes joueur ;)


## Actions moins fréquentes

### RepetitionAction : réaffiche la page courante
[src/actions/RepetitionAction](../../src/actions/RepetitionAction.php)
`new \MiniPaviFwk\actions\RepetitionAction($this)`

Réaffiche la page courante en restant sur le même contrôleur. Sauf si vous êtes joueurs ;)
Notez que par défaut VidéotexController supporte l'appui sur la touche de fonction [Répétition] pour assurer ce réaffichage en utilisant cette même action.


### DeconnexionAction : met fin à la visite d'un usager
[src/actions/DeconnexionAction](../../src/actions/DeconnexionAction.php)
`new \MiniPaviFwk\actions\DeconnexionAction()`

Déconnecte l'usager. Fin de sa visite.


### SwitchServiceAction : bascule sur un des autres services gérés par minipavi-fwk
[src/actions/SwitchServiceAction](../../src/actions/SwitchServiceAction.php)
`new \MiniPaviFwk\actions\SwitchServiceAction(string $newServiceName, string $videotexOutput = "", int $waitSeconds = 0)`

$newServiceName doit contenir le nom d'un service local qui est autorisé dans [services/global-config.php](../../services/global-config.php), voir à ce sujet la [documentation sur la configuration](./Configurations.md).

$videotexOutput contient optionnellement un text encodé en Vidéotex qui sera affiché avant la bascule sur le nouveau service.
$waitSeconds permet d'ajouter une attente (en secondes) entre l'affichage du texte et la bascule vers le nouveau service, il n'est utilisé que si $output n'est pas vide. Vous avez le droit de tricher.


### VideotexOutputAction : sortie Vidéotex simple
[src/actions/VideotexOutputAction](../../src/actions/VideotexOutputAction.php)
`new \MiniPaviFwk\actions\VideotexOutputAction($this, string $videotexOutput);`

$videotexOutput contient les codes Vidéotex à envoyer au Minitel.

### RedirectAction : redirige vers un autre service supporté par MiniPavi, via son URL
[src/actions/RedirectAction](../../src/actions/RedirectAction.php)
`new \MiniPaviFwk\actions\RedirectAction(string $newUrl, string $videotexOutput = "", int $waitSeconds = 0)`

Similaire à SwitchServiceAction qui redirige sur un service interne, cleui-ci redirige sur un service externe via son URL.

$url contient l'URL du services vers lequel l'usager sera redirigé. Cela ne peut être l'URL d'un service interne.

$videotexOutput contient optionnellement un text encodé en Vidéotex qui sera affiché avant la bascule sur le nouveau service.
$waitSeconds permet d'ajouter une attente (en secondes) entre l'affichage du texte et la bascule vers le nouveau service, il n'est utilisé que si $output n'est pas vide. Vous avez le droit de tricher.
