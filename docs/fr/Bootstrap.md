# Ordre de démarrage (bootstrap)

Décris l'ordre de démarrage d'une requête minipavi-fwk.<br/>
Source file : [index.php](../../index.php)


## Ordre par défaut
1. services/global-config.php
2. Class autoloaders
3. MiniPavi\MiniPaviCli::start()
4. \MiniPaviFwk\handlers\SessionHandler::startSession()
5. \MiniPaviFwk\handlers\ServiceHandler::startService()
6. services/{serviceName}/service-config.php
7. \MiniPaviFwk\handlers\Queryhandler

Il y a une chaîne de dépendance forte, mais vous pouvez changer les Gestionnaires, soit globalement, soit par Service si ils n'interviennent pas dans la sélection de celui-ci.<br/>
Voir la [configuration](./Configurations.md)


## Gestionnaire de Session
Le Gestionnaire de Session par défaut peut être changé globalement dans /services/global-config.php.<br/>
Voir la [Configuration](./Configurations.md)


## Gestionnaire de Service
Le Gestionnaire de service peut être changé globalement dans /services/global-config.php.<br/>
voir la [Configuration](./Configurations.md)


## Gestionnire de Requête
Le Gestionnaire de Requête peut être changé globalement ou individuellement pour chaque service, respectivement dans /services/global-config.php et /services/{servicename}/service-config.php.<br/>
Voir la [Configuration](./Configurations.md)
