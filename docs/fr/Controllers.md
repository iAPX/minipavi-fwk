# Contrôleurs

Décris le cycle de vie des contrôleurs, les détails sont dans des pages séparées.

Les contrôleurs gèrent les interactions utilisateur
Source directory : [src/controllers/](../../src/controllers/)

Source du contrôleur VideotexController, parent de vos contrôleurs [src/controllers/VideotexController.php](../../src/controllers/VideotexController.php)


## Cycle de vie typique d'un contrôleur
1. Affichage de la page courante, via `ecran()`
2. Indication du champ de saisie à utiliser, et les touches de fonction autorisées via `getCmd()`

Minipavi-fwk s'assure d'envoyer ces informations à MiniPaviCli, et de conserver le contexte du contrôleur.
Lors de la requête suivante, typiquement une réponse de l'utilisateur, minipavi-fwk réinstance le contrôleur en lui fournissant le contexte sauvé, pour qu'il puisse traiter la réponse utilisateur.

3. Le contrôleur identifie la réponse de l'utilisateur et renvoie une [Action](./Actions.md) en réponse, qui peut être une page Vidéotex, un message d'erreur en ligne 00, un changement de contrôleur/changement de page de l'arbo, etc.


## En détail

Exemple de contrôleur vide nommé AccueilController:
```
<?php

/**
 * Accueil
 */

namespace service\controllers;

class AccueilController extends \MiniPaviFwk\controllers\VideotexController
{

  // Ici le contenu de votre contrôleur

}
```

Les services sont alors tous fournis par le parent VideotexController, c'est à dire presque rien.

### ecran()
Signature : `public function ecran(): string`

La méthode ecran() renvoie du texte encodé en Vidéotex à l'entrée sur une nouvelle page de l'arbo.
Je recommande d'utiliser le [Helper Vidéotex](./Videotex-helper.md) pour construire la sortie.

L'usage le plus courant sera d'afficher une [Page Vidéotex](./Videotex-files.md) que vous aurez mis dans le sous-répertoire vdt, voir [l'organisation des fichiers](./Service-files-organization.md).
Un exemple ici pour afficher la page du fichier vdt/accueil.vdt:
```
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        return $videotex->page("accueil")->getOutput();
    }
```

Vous pouvez aussi utiliser le Helper Vidéotex pour construire votre affichage, voir sa documentation [ici](./Videotex-helper.md).
Un grand nombre de fonctionnalités sont supportées, mais vous pouvez aussi envoyer du Vidéotex à la main: ` return chr(12) . "Page d'accueil";`


### getCmd()
Signature : `public function getCmd(): array`

Cette méthode retourne un array() créé par la [commande MiniPaviCli](./Cmds.md) choisie.
Certaines prennent un paramêtre `?int $validation` où vous pouvez utiliser le [Helper Validation](./Validation-helper.md)

Les deux principales commandes sont \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd et \MiniPaviFwk\cmd\ZoneMessageCmd::createMiniPaviCmd, permettant respectivement de créer une Zone de Saisie sur une seule ligne, ou un message multi-ligne.
Au sein d'un service arborescent simple, seule la première sera généralement utilisée.

Un exemple simple avec une zone de saisie, acceptant toutes les touches de fonction du Minitel:
```
    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd(
            \MiniPaviFwk\helpers\ValidationHelper::ALL,
            24,
            40,
            1,
            true
        );
    }
```
Cela créé une zone de saisie en ligne 24 et colonne 40, d'un seul caractère, avec le curseur visible.


### Interlude
À ce moment les données à sortir, les touches de fonction autorisées et la commande pour la saisie (zonesaisie, zonemessage, etc.) sont envoyées à MiniPavi via MiniPaviCli.

L'utilisateur va saisir des informations ou faire un choix, puis utiliser une des touches de fonctions que vous avez autorisé.
Les autres touches de fonction seront inactives.

La prochaine étape est le traitement de cette saisie.
Entre-temps la requête http a été achevée, et l'état du contrôleur stocké. Il sera donc réinstancié et populé avec son contexte pour finir de traiter la page de l'arbo.
C'est une différence fondamentale avec les contrôleur de requêtes pour le Web, où son cycle de vie se termine avec l'affichage d'une réponse à la requête reçue (via une URL générée) qui le fait initialiser. Le cycle de vie d'un contrôleur est donc totalement différent.


### Identification et traitement de la réponse utilisateur

Seules deux commandes attendent une réponse utilisateur, ZoneSaisieCmd et ZoneMessageCmd.
La première utilise de l'introspection pour rendre le code plus lisible, appelant hiérarchiquement différentes méthodes pour faciliter la gestion des menus et des arborescence, la seconde appelle uniquement message().

Ces méthodes peuvent renvoyer une [Action](./Actions.md) ou null ssi un traitement a pu être effectué ou pour indiquer une erreur.
Si aucune méthode n'a été trouvée, la méthode nonPropose() sera alors appelé, qui par défaut affiche un message d'erreur en Ligne 00.


#### ZoneMessageCmd, saisie multiligne : message()
Signature: `public function message(string $touche, array $message): ?\MiniPaviFwk\actions\Action`

Reçoit la touche de fonction utilisée dans $touche (en MAJUSCULES) et le message dans un array comportant une entrée par ligne.
Le comportement de MiniPavi et MiniPaviCli est de fournir une ligne (même vide) par ligne de la zone de saisie du message: si le message fait 4 lignes, $message contiendra 4 entrées, chacune contenant une chaîne éventuellement vide "".



#### ZoneSaisiecmd, choix simplifié : choix{Saisie}{Touche}()
Signature : `public function choix{Saisie}{Touche}(): ?\MiniPaviFwk\actions\Action`

Vous pouvez créer des méthodes avec ce format dans vos contrôleurs.
Saisie étant la saisie utilisateur attendue, comme '1' 'toto' ou ce que vous voulez, qui sera à écrire avec le premier caractère en majuscule, les autres en minuscules, à l'exception de * et # écrits resspectivement ETOILE et DIESE. La saisie est donc insensible à la casse, D et d seront traités identiquement.
Touche étant la touche de fonction, là aussi avec le premier caractère en majuscule le reste en minuscule.
Si la saisie est vide le format est choix{Touche}(), indiquant un appui sur la Touche de fonction en l'absence de saisie.

Par exemple 1 + [Envoi] sera traité par choix1Envoi() si présent, toto + [Sommaire] le sera par choixTotoSommaire() si présent, *Dev + [Guide] par choixETOILEdev()
Cela simplifie l'écriture de menu, où on trouvera souvent choix1Envoi(), choix2Envoi(), choix3Envoi(), choixRetour(), etc.

Là aussi votre méthode doit renvoyer une [Action](./Actions.md), ou null.

Par exemple :
```
    public function choix1Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\ControllerAction(\service\controllers\Choix1Controller::class, $this->context);
    }
```


#### ZoneSaisieCmd, touche quelquesoit la saisie : touche{Touche}()
Signature : `public function touche{Touche}(string $saisie): ?\MiniPaviFwk\actions\Action`

Cette méthode est testée après la méthode choix{Saisie}{Touche}() si elle a renvoyé null ou si elle est absente.
Touche étant la touche de fonction, là aussi avec le premier caractère en majuscule le reste en minuscule.
Cela permet de dissocier la gestion de différentes ensembles de touches de fonction, mais aussi de gérer une touche sans se soucier de la saisie associée.

On pourra trouver toucheSommaire() par exemple, la différence avec choixSommaire() est que le premier sera appelé quelque-soir la saisie faite avant l'appui de la touche de fonction, le second s'attend à une absence de saisie.

Là aussi votre méthode doit renvoyer une [Action](./Actions.md), ou null.

Par exemple:
```
    public function toucheSommaire(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\ControllerAction(\service\controllers\SommaireController::class, $this->context);
    }
```


#### ZoneSaisieCmd, choix générique : choix()
Signature : `public function choix(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action`

$touche est la touche de fonction en MAJUSCULES: "ENVOI", "SUITE", etc.
Cette méthode permet de traiter les saisies génériques et est appelée après les deux premières si elles existent et ont renvoyées null, ou en leur absence.

Là aussi votre méthode doit renvoyer une [Action](./Actions.md), ou null.

Par exemple :
```
    public function choix(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action
    {
        if ($touche === "ENVOI" and $saisie === "1") {
            return new \MiniPaviFwk\actions\ControllerAction(\service\controllers\Choix1Controller::class, $this->context);
        }
        if ($touche === "SOMMAIRE") {
            return new \MiniPaviFwk\actions\ControllerAction(\service\controllers\SommaireController::class, $this->context);
        }
        return null;
    }
```


### En absence d'Action renvoyée, typiquement un choix non reconnu nonPropose()
Signature : `public function nonPropose(string $touche, string $saisie): ?\MiniPaviFwk\actions\Action`

Cette méthode est appelée en dernier si aucune autre n'a renvoyé d'Action à effectué, et par défaut dans le VideotexController affiche un message d'erreur en Ligne 00.
