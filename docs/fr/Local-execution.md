# Exécution locale de minipavi-fwk

Décris les quelques étapes permettant de faire tourner minipavi-fwk avec ses services de démonstration et/ou vos propres services.

## Requis
PHP 8.1+
Composer


## Composer : installation des dépendances
Dans le terminal, à la racine du projet
```
composer install
```

> [!IMPORTANT]
> Quand vous mettez à jour, n'oubliez pas de mettre aussi à jour composer et les autoloaders.<br/>
> ```
> composer update
> composer dump-autoload
> ```


## Exécution locale sur le port 8000
Depuis le terminal
```
php -S localhost:8000
```

## Accéder à votre serveur PHP local
Vous pouvez ouvrir un port sur votre routeur et rerouter celui-ci vers votre ordinateur.<br/>
Par exemple le port 8000.<br/>
Pensez à vérifier que ce port est disponible sur votre ordinateur, et qu'il n'est pas filtré ou bloqué par votre firewall.


## Lancer les tests phpUnit
Dans le terminal:
```
./vendor/bin/phpunit tests
```

## Accéder à votre ou vos services
Utilisez directement l'URL de votre service, vous pouvez aussi rajouter un paramètre comme ?service={nom-de-service} pour en sélectionner un.
Notez qu'une fois connecté sur un service, vous ne pouvez en changer sans vous déconnecter préalablement.
