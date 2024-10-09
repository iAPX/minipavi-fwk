# minipavi-fwk

minipavi-fwk is a PHP 8.1+ project to support minipavi for Minitel and emulators.

Standard is PSR12.

## Documentation
The [developper's README.md](./docs/en/README.md)

[Locally install and launch minipavi-fwk](./docs/en/Local-execution.md)

See [all the docs in ./docs directory](./docs/en/)


You could change service, within the services/global-config.php ALLOWED_SERVICES list by putting the service name this way:
http://{server}[:{portNumber}]/%3Fservice={serviceName}
Simply add %3Fservice={serviceName}


Your service files:
services/{serviceName}/xml : your xml files (.xml extension)
services/{serviceName}/vdt : your videotex page as files (.vdt extension)
services/{serviceName}/controllers: your videotex and xml controllers
services/{serviceName}/actions : your actions if needed
services/{serviceName}/helpers : your helpers
services/{serviceName}/keywords : your keyword handlers

