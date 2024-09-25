minipavi-fwk is a PHP 8.1+ project to support minipavi for Minitel and emulators.

Standard is PSR12.

How to setup:
set the DEFAULT_XML_FILE on config.php file, without .xml extension.
```
const DEFAULT_XML_FILE = 'macbidouille';
```
You would be able to access the demo.xml from the main menu using * [ENVOI].

Install dependencies (minipavicli) through composer:
```
composer install
```

When updating, don't forget to update composer and autoloaders
```
composer update
composer dump-autoload
```


Local execution:
```
php -S localhost:8000
```

Local phpUnit tests:
```
./vendor/bin/phpunit tests
```

Note that you should run it from a publicly exposed port as minipavi will callback it through Internet.
Alternatively if you have a server with ssh access, after allowing a port through ufw or iptable on this server, you might want do something like that:
ssh -R 8000:localhost:8000 -N -f {username}@{server}
and then use http://{server}:8000

That enable you to test locally through your browser, without opening any port on your dev computer nor on your router.
Works anywhere, anytime.


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
