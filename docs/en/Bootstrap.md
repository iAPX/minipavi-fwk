# Bootstrap

Responsability : setting up the environment for Minitel Service Controller execution


## Query bootstrap order by default
1. services/global-config.php
2. Class autoloaders
3. MiniPavi\MiniPaviCli::start()
4. \MiniPaviFwk\handlers\SessionHandler::startSession()
5. \MiniPaviFwk\handlers\ServiceHandler::startService()
6. services/{serviceName}/service-config.php

There's a chain of dependencies, needing to identify and validate the Service, for that having a Session, thus having access to MiniPaviCli queries results, this one needing autoloaders.


## Session Handler
The Default Session Handler could be changed by modifying /services/global-config.php, see [Session Handler](./Session-handler.md)


## Service Handler
The Default Service Handler could be changed by modifying /services/global-config.php, see [Service Handler](./Service-handler.md)

