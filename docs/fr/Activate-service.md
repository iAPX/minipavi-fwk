# Activez un service Minitel

Décris les deux étapes permettant d'autoriser (activer) un service Minitel, ainsi que de le servir par défaut.


## Autoriser le service

Modifiez la liste des services ALLOWED_SERVICES dans le fichier [./services/global-config.php](../../services/global-config.php).<br/>
Assurez-vous d'y mettre le nom de votre service.


## Servez ce service par défaut

Modifiez le nom du service par défaut DEFAULT_SERVICE dans le fichier [./services/global-config.php](../../services/global-config.php).


## Accéder à un service spécifique

Vous pouvez rajouter ?service={nom-du-service} dans l'URL initiale afin de sélectionner un des services autorisés par son nom.
Notez que les usagers ne connaissent pas les noms internes de vos services.

## Référence
[Documentation sur les configurations](./Configurations.md)
