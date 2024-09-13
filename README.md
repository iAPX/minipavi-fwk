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

Local execution:
```
php -S localhost:8000
```

Local phpUnit tests:
```
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/
```

Note that you should it from a publicly exposed port.
Alternatively if you have a server with ssh access, after allowing a port through ufw or iptable, you might want do something like that:
ssh -R 8000:localhost:8000 -N -f {username}@{serveur}
and then use http://{serveur}:8000

That enable you to test locally through your browser, without opening any port on your dev computer nor on your router.
Works anywhere, anytime.
You could also filter out any other IP than yours on ufw or iptable.
