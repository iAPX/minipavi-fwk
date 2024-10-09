# Local execution of minipavi-fwk and its provided services and examples

minipavi-fwk is a PHP 8.1+ project to support minipavi for Minitel and emulators.

Standard is PSR12.


## Composer dependencies installation
Install dependencies (minipavicli, phpUnit, etc.) through composer:
```
composer install
```

> [!IMPORTANT]
> When updating, don't forget to update composer and autoloaders
> ```
> composer update
> composer dump-autoload
> ```


## Launching locally on port 8000
Local execution:
```
php -S localhost:8000
```

## Accessing your local PHP webserver
You could open a port through your router and forward it to your computer.
Then use the Public IP Address of your router and the port you opened and forwarded.

This doesn't work if you don't control your router, and also it let your local port always available from The Internet when you use this router with the same local private IP Address. Worse, due to DHCP, another computer or device might be put on the same private IP Address another time.

I recommend to use an Internet facing server, or at least one where you could forward public traffic, having an ssh account on it (key pair, not password!). And use ssh Remote Port Forwarding.

ssh -R 8000:localhost:8000 -N -f {username}@{server}
and then use http://{server}:8000

That enable you to test locally through your browser, without opening any port on your dev computer nor on your router.
Works anywhere, anytime.

You should create an ssh user *WITHOUT* shell (nor bash nor zsh etc.) for the purpose of the Remote Port Forwarding.
You stop the forwarding by killing the ssh process.


## phpUnit tests, just in case
Local phpUnit tests:
```
./vendor/bin/phpunit tests
```