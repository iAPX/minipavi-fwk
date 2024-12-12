# Contrôleurs

Décris le cycle de vie des contrôleurs, les détails sont dans des pages séparées.

Les contrôleurs gèrent les interactions utilisateur.<br/>
Source directory : [src/controllers/](../../src/controllers/)

Source du contrôleur VideotexController, parent de vos contrôleurs [src/controllers/VideotexController.php](../../src/controllers/VideotexController.php)

Vous pouvez aussi créer des menus plus facilement avec le Contrôleur de Menu [source ici](../../src/controllers/MenuController.php) et la [documentation du MenuController ici](./Menucontroller.md), ainsi que des affichages de contenus multipages avec le Contrôleur Multipage [source ici](../../src/controllers/MultipageController.php) et [la documentation du MultipageController ici](./Multipagecontroller.md).


## Cycle de vie typique d'un contrôleur
1. `__construct()` appelé, avec le contexte et des paramètres optionnels
2. Si on rentre dans ce contrôleur depuis PageAction (changement de page), ControllerAction (changement de contrôleur) ou AccueilAction (retour à la home page), `entree()` est appelé, pour différencier cela d'un réaffichage de la page, et permettre des initialisations spécifiques.
3. Pré-affichage hook `preAffichage()`, permettant des pré-traitements avant affichage ou réaffichage
4. Affichage de la page courante, via `ecran()`
5. Indication du champ de saisie à utiliser, et les touches de fonction autorisées via `getCmd()`
6. Le contexte du contrôleur est sauvé via sa méthode `getContext()`

Minipavi-fwk s'assure d'envoyer ces informations à MiniPaviCli, et de conserver le contexte du contrôleur.<br/>
Lors de la requête suivante, typiquement une réponse de l'utilisateur, minipavi-fwk réinstance le contrôleur en lui fournissant le contexte sauvé, pour qu'il puisse traiter la réponse utilisateur.

7. `__construct()` est rappelé, avec son ancien contexte lu via getContext() (pas de paramètres optionnels)
8. Pré-réponse hook `preReponse()`, permettant des pré-traitements avant de gérer la réponse utilisateur
9. Le contrôleur identifie la réponse de l'utilisateur et renvoie une [Action](./Actions.md) en réponse, qui peut être une page Vidéotex, un message d'erreur en ligne 00, un changement de contrôleur/changement de page de l'arbo (retour au point 1 avec un contrôleur instancié), etc.


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

La méthode ecran() renvoie du texte encodé en Vidéotex à l'entrée sur une nouvelle page de l'arbo.<br/>
Je recommande d'utiliser le [Helper Vidéotex](./Videotex-helper.md) pour construire la sortie.

Vous pouvez aussi profiter des capacités de formatage du [Helper Formatage](./Format-helper.md), ainsi que du Helper de conversion d'images JPEG ou PNG [Helper Image](./Image-helper.md)

L'usage le plus courant sera d'afficher une [Page Vidéotex](./Videotex-files.md) que vous aurez mis dans le sous-répertoire vdt, voir [l'organisation des fichiers](./Service-files-organization.md).<br/>
Un exemple ici pour afficher la page du fichier vdt/accueil.vdt:
```
    public function ecran(): string
    {
        return (new \MiniPaviFwk\helpers\VideotexHelper())->page("accueil")->getOutput();
    }
```

Vous pouvez aussi utiliser le Helper Vidéotex pour construire votre affichage, voir sa documentation [ici](./Videotex-helper.md).<br/>
Un grand nombre de fonctionnalités sont supportées, mais vous pouvez aussi envoyer du Vidéotex à la main: ` return chr(12) . "Page d'accueil";`


### getCmd()
Signature : `public function getCmd(): array`

Cette méthode retourne un array() créé par la [commande MiniPaviCli](./Cmds.md) choisie.<br/>
Certaines prennent un paramêtre `?int $validation` où vous pouvez utiliser le [Helper Validation](./Validation-helper.md)

Les deux principales commandes sont \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd et \MiniPaviFwk\cmd\ZoneMessageCmd::createMiniPaviCmd, permettant respectivement de créer une Zone de Saisie sur une seule ligne, ou un message multi-ligne.<br/>
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
            false
        );
    }
```
Cela créé une zone de saisie en ligne 24 et colonne 40, d'un seul caractère, avec le curseur invisible.


### Interlude
À ce moment les données à sortir, les touches de fonction autorisées et la commande pour la saisie (zonesaisie, zonemessage, etc.) sont envoyées à MiniPavi via MiniPaviCli.

L'utilisateur va saisir des informations ou faire un choix, puis utiliser une des touches de fonctions que vous avez autorisé.<br/>
Les autres touches de fonction seront inactives.

La prochaine étape est le traitement de cette saisie.<br/>
Entre-temps la requête http a été achevée, et l'état du contrôleur stocké. Il sera donc réinstancié et populé avec son contexte pour finir de traiter la page de l'arbo.<br/>
C'est une différence fondamentale avec les contrôleur de requêtes pour le Web, où son cycle de vie se termine avec l'affichage d'une réponse à la requête reçue (via une URL générée) qui le fait initialiser.<br/>
Le cycle de vie d'un contrôleur est donc totalement différent dans MiniPaviFwk.


### Identification et traitement de la réponse utilisateur

Trois commandes attendent une réponse utilisateur, ZoneMessageCmd, InputFormCmd et ZoneSaisieCmd.<br/>
La dernière utilise de l'introspection pour rendre le code plus lisible, appelant hiérarchiquement différentes méthodes pour faciliter la gestion des menus et des arborescence, la seconde appelle uniquement message().

Ces méthodes peuvent renvoyer une [Action](./Actions.md) ou null ssi un traitement a pu être effectué ou pour indiquer une erreur.<br/>
Si aucune méthode n'a été trouvée, la méthode nonPropose() sera alors appelé, qui par défaut affiche un message d'erreur en Ligne 00.

Voir la [documentation sur les commandes](./Cmds.md).


#### ZoneMessageCmd, saisie multiligne : message()
Signature: `public function message(string $touche, array $message): ?\MiniPaviFwk\actions\Action`

Reçoit la touche de fonction utilisée dans $touche (en MAJUSCULES) et le message dans un array comportant une entrée par ligne.<br/>
Le comportement de MiniPavi et MiniPaviCli est de fournir une ligne (même vide) par ligne de la zone de saisie du message: si le message fait 4 lignes, $message contiendra 4 entrées, chacune contenant une chaîne éventuellement vide "".<br/>
[documentation sur les commandes](./Cmds.md)


### InputFormCmd, saisie de formulaire multichamps : formulaire()
Signature: `public function message(string $touche, ...): ?\MiniPaviFwk\actions\Action`

Reçoit la touche de fonction utilisée dans $touche (en MAJUSCULES) et les différents champs chacun dans un paramètre nommé, dans l'ordre de définition via InputFormCmd.<br/>
Le comportement de MiniPavi et MiniPaviCli est de fournir une entrée (même vide) par champ du formulaire. [documentation sur les commandes](./Cmds.md)


Exemple pour une saisie de nom, prénom et code_postal:
```
    public function formulaire(string $touche, string $nom, string $prenom, string $cp): ?\MiniPaviFwk\actions\Action
    {
        if ($touche === 'ENVOI') {
            // Nom and Prénom are mandatory (*)
            if ($nom === '' || $prenom === '') {
                return new \MiniPaviFwk\actions\Ligne00Action($this, "Nom et prénom obligatoires");
            }

            // Display the form content
            $vdt = $this->displayPrecedentForm($nom, $prenom, $cp);
            return new \MiniPaviFwk\actions\VideotexOutputAction($this, $vdt);
        } elseif ($touche === 'SOMMAIRE') {
            // Handle [SOMMAIRE] to return to the Sommaire (service menu)
            return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-sommaire');
        }
        return null;
    }
```

#### ZoneSaisiecmd, choix simplifié : choix{Saisie}{Touche}()
Signature : `public function choix{Saisie}{Touche}(): ?\MiniPaviFwk\actions\Action`

Vous pouvez créer des méthodes avec ce format dans vos contrôleurs.<br/>
Saisie étant la saisie utilisateur attendue, comme '1' 'toto' ou ce que vous voulez, qui sera à écrire avec le premier caractère en majuscule, les autres en minuscules, à l'exception de * et # écrits resspectivement ETOILE et DIESE. La saisie est donc insensible à la casse, D et d seront traités identiquement.<br/>
Touche étant la touche de fonction, là aussi avec le premier caractère en majuscule le reste en minuscule.<br/>
Si la saisie est vide le format est choix{Touche}(), indiquant un appui sur la Touche de fonction en l'absence de saisie.<br/>

Par exemple 1 + [Envoi] sera traité par choix1Envoi() si présent, toto + [Sommaire] le sera par choixTotoSommaire() si présent, *Dev + [Guide] par choixETOILEdev().<br/>
Cela simplifie l'écriture de menu, où on trouvera souvent choix1Envoi(), choix2Envoi(), choix3Envoi(), choixRetour(), etc.

Là aussi votre méthode doit renvoyer une [Action](./Actions.md), ou null.

Par exemple :
```
    public function choix1Envoi(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\ControllerAction(\service\controllers\Choix1Controller::class, $this->context);
    }
```


#### ZoneSaisieCmd, touche quelque soit la saisie : touche{Touche}()
Signature : `public function touche{Touche}(string $saisie): ?\MiniPaviFwk\actions\Action`

Cette méthode est testée après la méthode choix{Saisie}{Touche}() si elle a renvoyé null ou si elle est absente.<br/>
Touche étant la touche de fonction, là aussi avec le premier caractère en majuscule le reste en minuscule.<br/>
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

$touche est la touche de fonction en MAJUSCULES: "ENVOI", "SUITE", etc.<br/>
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
Ce message peut être défini via la `const NON_PROPOSE_LIGNE00` globalement ou au niveau de chaque service, voir [les configurations](./Configurations.md).

$touche est la touche de fonction en MAJUSCULES.<br/>
$saisie est ce qui a été saisi par l'utilisateur, ou une chaîne vide "" sur appui direct de la touche de fonction.
