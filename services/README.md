
Here are your services.

services/{serviceName}/service-config.php : service configuration
services/{servicename}/QueryHandler.php : optional QueryHandler extending \MiniPaviFwk\handlers\QueryHandler
services/{serviceName}/actions : your own actions
services/{serviceName}/controllers : your own Videotex or Xml controllers
services/{serviceName}/keywords : your keywords handlers
services/{serviceName}/vdt : videotex pages, with .vdt extension by default
services/{serviceName}/xml : XML based services

Modify the services/global-config.php :
- Add your own service to the ALLOWED_SERVICES
- Remove any useless services from ALLOWED_SERVICES
- Set your own service as DEFAULT_SERVICE
