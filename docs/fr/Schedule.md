# Plan de développement


## v1.1 - Décembre 2024
- [X] Revoir VideotexController: séparer ses responsabilités dans une hiérarchie de classes [sources](../../src/controllers/hierarchy/)
- [X] PageAction peut aussi accepter directement un nom de contrôleur [Actions](./Actions.md)
- [X] Helper pour la conversion d'images en semi-graphique Alphamosaïque [Image Helper](./Image-helper.md)
- [X] Outil en ligne de commande pour la conversion d'images en semi-graphique Alphamosaïque [Image Helper](./Image-helper.md)


## v1.0 - Novembre 2024
- [X] phpUnit étendu [sources](../../tests/) et [documentation](./Local-execution.md)


## v0.9 - Novembre 2024
- [X] Support XML via XmlController enlevé (choc de simplification!)
- [X] Simplification Validation, défaut toutes touches acceptées [validations](./Validation-helper.md) et [Contrôleurs](./Controllers.md)
- [X] BUGFIX: RedirectAction flush Session [source](../../src/actions/RedirectAction.php)
- [X] Importation XMLint complète [XMLint Import](./XMLint-transition.md)
- [X] Support des formulaires MiniPaviCli via formulaire() [Contrôleurs](./Controllers.md)
- [X] Documentation étendue [Documentation](./README.md)
- [X] FormatHelper pour le formatage de titres et de textes [Format Helper](./Format-helper.md)
- [X] MultipageController pour gérer les contenus multipage [Multipage Controller](./Multipagecontroller.md)
- [X] MenuController pour gérer les menus, simple page ou multipages [Menu Controller](./Menucontroller.md)


## v0.8 - Novembre 2024
- [x] Demochat plus dynamique (notifications) [sources](../../services/demochat/controllers/)
- [x] Importation de projets XMLint [XMLint Import](./XMLint-transition.md)
- [x] Documentation minimale en Français [documentation](./README.md)


## Futuribles (sans ordre particulier)
- Gestion de pile pour le retour aux ancêtres en restaurant leur état
- Helper pour la génération d'images DRCS
- Gestion de contenus multi-formats (texte + images + ...) et multipages
- Déploiement Docker
- Exceptions
- Minitel logs (façon anciens services)
- Functional Tests
- Animations de texte
- Meilleure conversion d'image en semi-graphique Alphamosaïque
