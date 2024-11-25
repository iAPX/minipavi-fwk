# Organisation des fichiers

Décris l'organisation des fichiers des services Minitel dans ./services

```
**./services/** --+ **global-config.php** : configuration globale
             |
             + **{servicename}/** --+ **service-config.php** : configuration du service
                               |
                               + actions/ : les actions
                               |
                               + **controllers/** : les contrôleurs PHP
                               |
                               + handlers/ : les handlers
                               |
                               + helpers/ : les helpers
                               |
                               + keywords/ : les gestionnaires de mots-clé
                               |
                               + vdt/ : les pages vidéotex (.vdt)
```

Seuls les répertoires et fichiers en gras sont indispensables, les autres sont optionnels.

Par défaut, [./services/myservice/](../../services/myservice/) contient un squelette fonctionnel avec un seul contrôleur qui affiche un joli "Hello world!", en n'incluant que les fichiers indiqués en gras ici.<br/>
Il est intégré dans la liste des services autorisés, vous pouvez suivre les deux étapes ici pour [le servir par défaut](./Activate-service.md).


## ./services/global-config.php

Contient la configuration de base, le niveau d'erreur par défaut, la liste des services autorisés et le service actif par défaut.
Version simplifiée:
```
<?php

error_reporting(E_ERROR | E_USER_WARNING | E_PARSE);
ini_set('display_errors', 0);

// Services autorisés.
const ALLOWED_SERVICES = ['demo', 'demochat', 'demoxml', 'macbidouille', 'myservice'];

// Service par défaut.
const DEFAULT_SERVICE = 'demo';
```

D'autres informations peuvent y être mises pour étendre les capacités de vos minipavi-fwk et de vos services.<br/>
Voir [la page sur les configurations](./Configurations.md)


## ./services/{servicename}/service-config.php

Contient la configuration du service.<br/>
Seul le namespace et le nom du contrôleur par défaut, souvent AccueilController sont nécessaires.<br/>
On utilise couramment le chemin vers le contrôleur puis le nom de sa classe pour permettre la vérification par le compilateur PHP, et avoir une erreur claire et immédiate en cas de nom erronné. Les IDE s'en servent aussi pour pouvoir pointer sur le code.
```
<?php

namespace service;

const DEFAULT_CONTROLLER = \service\controllers\AccueilController::class;

```

D'autres informations peuvent y être mises pour étendre les capacités de vos minipavi-fwk et de vos services.<br/>
Voir [la page sur les configurations](./Configurations.md)


## ./services/{servicename}/controllers/

Contient les contrôleurs du service.<br/>
Voir la [documentation sur les contrôleurs](./Controllers.md).
