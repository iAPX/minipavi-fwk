# Transition from XMLint

Describes the path from a XMLint Minitel Service using MiniPavi to the same service using MiniPaviFwk.


## Example
One of the available examples is a full XMLint project : [MacBidouille on Minitel](https://www.minipavi.fr/emulminitel/index.php?url=https://minitelbidouille.pvigier.com/macbidouille.xml&color=false)

Source directory : [MacBidouille](https://www.minipavi.fr/emulminitel/index.php?url=https://minitelbidouille.pvigier.com/macbidouille.xml&color=false)

It use the XML file and the Vidéotex files (.vdt) of the live Minitel Service, captured at one point in time.


## Your first and last stop?
You could now use the [xmlint-import.php tool](../../src/tools/xmlint-import.php) !!!

No hassle, no long doc to read, no directory to create or files to copy.
It only needs the current URL of your XML int xml file, the name of your new MinipaviFwkService, if you want your new Minitel Service to be the one proposed by default, and if you want extended logs for debugging.

Go to the MiniPaviFwk root directory [here](../../) .
Use this command: `php ./src/tools/xmlint-import.php` .
You could stop at each step, including before modifying your [./services/global-config.php](../../services/global-config.php).

Enjoy!


## References

[XML files](./XML-files.md)

[XML services](./XML-services.md)

[XML controllers](./XML-controllers.md)
