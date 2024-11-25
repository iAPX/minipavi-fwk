# Configurations

Il y a deux niveaux de configuration, globalement et au niveau de chaque service.<br/>
Toutes les configurations faites au niveau du service peuvent être effectuées globalement, et surchargées dans chaque service si vous utilisez le ConstantHelper.


## ConstantHelper : Permet de récupérer une configuration en respectant la hiérarchie
Signature : `public static function getConstValueByName(string $constName, $defaultValue = null): string`

Récupère la constante $constName en consultant d'abord la configuration du service dans service-config.php, celle globale dans global-config.php et renvoie une valeur par défaut (ou null) si elle n'est pas trouvée.

Exemple:
```
        $session_handler = ConstantHelper::getConstValueByName(
            'SESSION_HANDLER_CLASSNAME',
            SessionHandler::class
        );
```


## Configuration globale
[Fichier de configuration globale](../../services/global-config.php)


### Configuration globale : entrées obligatoires

- `const ALLOWED_SERVICES` : array() des noms de services autorisés

- `const DEFAULT_SERVICE` : nom du service actif par défaut


### Configuration globale : entrées optionnelles

- `error_reporting( ... )` : rapport d'erreur par défaut pour tous les services

- `const SESSION_HANDLER_CLASSNAME` : nom complet de la classe de gestion des sessions, \MiniPaviFwk\handlers\SessionHandler si absent

- `const SERVICE_HANDLER_CLASSNAME` : nom complet de la classe de gestion des services, \MiniPaviFwk\handlers\ServiceHandler si absent

- `const QUERY_HANDLER_CLASSNAME` : nom complet de la classe de gestion des requêtes qui peut être surchargé dans la configuration de chaque service, \MiniPaviFwk\handlers\QueryHandler si absent


## Configuration par service
La configuration par services est placé dans le fichier `service-config.php` dans le répertoire services/{serviceName}.<br/>
Il est exécuté après que la Session ai démarrée et que le Service ai été identifié et validé.


> [!IMPORTANT]
> `namespace service;` doit être au début de votre fichier service-config.php.



### Configuration par service : entrée obligatoire

- `const DEFAULT_CONTROLLER` : doit contenir le nom de votre premier contrôleur, celui affichant la page d'accueil.


### Configuration par service : entrées optionnelles

- `const QUERY_HANDLER_CLASSNAME` : peut contenir le nom complet de la classe d'un gestionnaire de requête pour ce service.


- `const SERVICE_ERROR_REPORTING` : permet de changer le masque de `error_reporting()` permettant de logger plus d'informations pour certains sites, notamment lors du cycle de développement.

- Vous pouvez aussi y ajouter ce que vous voulez pour les besoins de votre service


## References
[Global configuration file](../../services/global-config.php)

[Bootstrap](./Bootstrap.md)

