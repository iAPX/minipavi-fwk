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
> Quand vous mettez à jour, n'oubliez pas de mettre aussi à jour composer et les autoloaders.
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
Vous pouvez ouvrir un port sur votre routeur et rerouter celui-ci vers votre ordinateur.
Par exemple le port 8000.
Pensez à vérifier que ce port est disponible sur votre ordinateur, et qu'il n'est pas filtré ou bloqué par votre firewall.


## Lancer les tests phpUnit
Dans le terminal:
```
./vendor/bin/phpunit tests
```