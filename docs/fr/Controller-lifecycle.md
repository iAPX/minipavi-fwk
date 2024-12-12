# Cycle de vie d'un contrôleur

Il est important de comprendre le cycle de vie du contrôleur pour savoir dans quellle méthode placer votre code, et savoir quand celui-ci sera appelé.


## Cycle de vie typique d'un contrôleur
Ce cycle représente un contrôleur appelé par AccueilAction, PageAction ou ControllerAction.<br/>
Mais d'autres actions interagissent différemment avec les contrôleurs.<br/>

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


## Les différentes méthodes

- `__construct()` est appelé à l'entrée sur le contrôleur, mais aussi une seconde fois pour traiter la réponse utilisateur
- `entree()` est appelé juste après `__construct()` et avant preAffichage()/ecran()/getCmd() lors de l'entrée sur le contrôleur.
- `preAffichage()` est appelé avant `ecran()` si des traitements pour l'affichage sont nécessaire. Si on réaffiche la page sans changer de contrôleur, celui-ci n'est pas réinstancié, donc ni `__construct()` ni `entree()` ne sont alors appelé. Voir ActionRepetition ci-desssous.
- `ecran()` est appelé juste après `preAffichage()` et `getCmd()` pour effectuer le rendu de la page
- `getCmd()` est toujours appelé après `ecran()` pour connaître les commandes acceptables par MiniPavi et termine le cycle d'affichage et de création de Zone de Saisie/Zone de Message/Formulaire. Il est aussi appelé même si un affichage n'est pas nécessaire, comme avec Ligne00Action().
- `getContext()` est alors appelé pour sauvegarder l'état du contrôleur, son contexte, afin de pouvoir le réinstancier à l'identique pour traiter laa réponse utilisateur.

- `preReponse` est appelé avant tout traitement de la réponse utilisateur
- `touche*()`, `choix*()`, `message()`, `formulaire()` et/ou `nonPropose()` sont appelés pour identifier la réponse utilisateur et renvoyer une Action. Voir à ce sujet [la documentation des contrôleurs](./Controllers.md).


## Actions et effets sur le cycle de vie
Les actions sont déclenchées par le contrôleur ou un parent à l'étape (9.).


### AccueilAction(), PageAction(), ControllerAction()
Le contrôleur courant ne reçoit plus aucun appel de méthode et est éliminé.<br/>

Ces trois Actions instancient un nouveau contrôleur, qui peut d'ailleurs être de la même classe que le contrôleur courant (qui ne sera pas réutilisé dans ce cas), il démarre donc à l'étape (1.) et suit les étapes suivantes dans l'ordre par défaut:<br/>
- `__construct()`
- `entree()` même si c'est la même classe, on considère que c,est une nouvelle entrée
- `preAffichage()`
- `ecran()`
- `getCmd()`
- `getContext()`

Puis après réponse utilisateur les étapes (7.) à (9.):<br/>
- `__construct()`
- `preReponse()`
- `touche*()`, `choix*()`, `message()`, `formulaire()` et/ou `nonPropose()`


### RepetitionAction()
Le contrôleur courant est conservé, les appels de méthodes lui sont destinés.<br/>

RepetitionAction fait généralement réafficher la page courante, sans changer de page, on repart donc à l'étape (3.):<br/>
- `preAffichage()`
- `ecran()`
- `getCmd()`
- `getContext()`

Puis après réponse utilisateur les étapes (7.) à (9.):<br/>
- `__construct()`
- `preReponse()`
- `touche*()`, `choix*()`, `message()`, `formulaire()` et/ou `nonPropose()`


### Ligne00Action() & VideotexOutputAction()
Le contrôleur courant est conservé, les appels de méthodes lui sont destinés.<br/>

Ces deux Actions renvoient le contrôleur courant à l'étape (5.), l'indication de la saisie:<br/>
- `getCmd()`
- `getContext()`

Puis après réponse utilisateur les étapes (7.) à (9.):<br/>
- `__construct()`
- `preReponse()`
- `touche*()`, `choix*()`, `message()`, `formulaire()` et/ou `nonPropose()`


### RedirectAction() & SwitchServiceAction()
Le contrôleur courant ne reçoit plus aucun appel de méthode et est éliminé.<br/>

Ces deux actions éliminent le contrôleur courant, servant à envoyer l'utilisateur sur un autre service local ou distant.<br/>


### DeconnexionAction()
Le contrôleur courant ne reçoit plus aucun appel de méthode et est éliminé.<br/>

Un DeconnexionController est instancié, il n'est pas surchargeable et ne sert qu'à s'assurer qu'en cas d'erreur l'usager ai perdu l'accès au service.<br/>
Vous pouvez ignorer son fonctionnement.<br/>

