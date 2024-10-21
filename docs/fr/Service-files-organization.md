# File Organization of Services

Here are your services files.

- services/{serviceName}/service-config.php : service configuration
- services/{servicename}/QueryHandler.php : optional QueryHandler extending \MiniPaviFwk\handlers\QueryHandler
- services/{serviceName}/actions : your own actions
- services/{serviceName}/controllers : your own Videotex or Xml controllers
- services/{serviceName}/keywords : your keywords handlers
- services/{serviceName}/helpers : the helpers of your service
- services/{serviceName}/vdt : videotex pages, with .vdt extension by default
- services/{serviceName}/xml : XML based services

