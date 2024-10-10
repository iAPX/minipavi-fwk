# Transition from XMLint

Describes the path from a XMLint Minitel Service using MiniPavi to the same service using MiniPaviFwk.


## Example
One of the available examples is a full XMLint project : [MacBidouille on Minitel](https://www.minipavi.fr/emulminitel/index.php?url=https://minitelbidouille.pvigier.com/macbidouille.xml&color=false)

Source directory : [MacBidouille](https://www.minipavi.fr/emulminitel/index.php?url=https://minitelbidouille.pvigier.com/macbidouille.xml&color=false)

It use the XML file and the Vidéotex files (.vdt) of the live Minitel Service, captured at one point in time.


## Goal & limitations
Goal is to be able to easily transfert your XMLint Minitel Service into a MiniPaviFwk, as easily as possible.
Limitations are:
- No Email messaging supported on XMLint project (will change)
- No WebMedia support


## One-step (yes, only one!)
You need to copy your XML file inside services/myservice/xml, under the name "default.xml" (without quotes).

Check the file is correctly copied and present [services/myservice/xml/default.xml](../../services/myservice/xml/default.xml).

Now you can try it through the Demo Minitel Service, it will be presented on the main menu under the name "My Service".
To launch it locally, see [Local Execution](./Local-execution.md)


## Step-by-step to go further
Here's the guide, follow-me step-by-step.
I choose to name your site "myservice", so everytime you see yourservice, you know its your own new service.

But before that, you might want to read the [Local Execution tuto](./Local-execution.md), and try the 3 different services availables, Demo being the default, but you could switch services through its main menu.


### Directories (one less)
They are already created for you on [myservice](../../services/myservice/)

One step less!

So you should have [services/myservice](../../services/myservice/), [services/myservice/xml](../../services/myservice/xml/) and [services/myservice/vdt](../../services/myservice/vdt/) .

### XMl File
Next, you need to copy your XML file inside services/myservice/xml, under the name "default.xml" (without quotes).

Check the file is correctly copied and present [here](../../services/myservice/xml/default.xml).

### Service configuration (one less again)
It's already present here [your service service-config.php](../../services/myservice/service-config.php)

This file indicates that you have a services/myservice/xml/default.xml that will be served through MiniPaviFwk.

### Without Global configuration (speed-run it!)
You could skip the next step.

Test with the Demo service, it will list "myservice" as an available service on its menu.
From the option on the menu you will be able to access your own XMLint service hosted through MiniPaviFwk.

One step less. But if you want to work on your service, let's follow the next step (or skip it).

### Global configuration (if not skipped)
The global service configuration [Global Configuration file services/global-config.php](../../services/global-config.php) already list your service as available!

You want your service to be available, and in fact, you will want to have it as the default service for your work.

Modify the [Global Configuration file services/global-config.php](../../services/global-config.php) with your preferred editor.

Modify the line : `const DEFAULT_SERVICE = 'demo';`
Change 'demo' to 'myservice'.
You should endup with `const DEFAULT_SERVICE = 'myservice';``
This will make your service the default service that will be presented to you by default.

Save your file. [Check it](../../services/global-config.php).

### Time to test
Using the [Local Execution tutorial](./Local-execution.md), you could now test your XMLint Minitel Service on MiniPaviFwk.

### Optional Vidéotex .vdt files & configuration modification (we get serious)
Actually, the Vidéotex Pages are served from your existing web hosted XMLint service, through the URL you put in the `<ecran><affiche url="xxx"/></ecran>`in each page.

We want the pages to be served locally to avoid using an external website, and to make this project independent from your existing web hosted XMLint project!
It is also a speed up. Just saying. (in real life it doesn't matter until you have thousands users)

#### Copy the vidéotex pages in your project
Copy all your vidéotex pages in your [services/myservice/vdt](../../services/myservice/vdt) directory.

#### Use the local copy of your Pages instead the online ones
Now is the hard part: you have to extract the path of your pages from their URL on the XML.
For example, if your URL is http://example.com/pages/accueil.vdt, thus the path is "http://example.com/pages/" (without quotes).
You might want to try through your browser to see what's happen, but essentially the last part of the pages URL, before the slash "/" should be removed. Exception if you are pages subdirectories.

Now you will edit your [services/myservice/service-config.php](../../services/myservice/service-config.php) file:
Change `const XML_PAGES_URL = false;` to `const XML_PAGES_URL = "{url path}"`;

In our example where a page is "http://example.com/pages/accueil.vdt", and the path is "http://example.com/", you will put "http://example.com/pages/" (the path) on the file.

Save the file, it should look like that:
```
<?php

/**
 * Expecting you!
 * 
 * Here is the place for you service configuration
 */

namespace service;

const DEFAULT_XML_FILE = 'default.xml';

const XML_PAGES_URL = "http://example.com/pages/";
```

Now your pages will be served locally when the URL begins with "http://example.com/pages/" (example) from the [services/myservice/vdt directory](../../services/myservice/vdt/)

You will want to check by shutting down your actual Minitel service, temporarily naturally, to be sure that the page are served locally.
Please, don't forget to start/restart your actual XMLint Minitel Service.

Now you could read the other parts of the documentation, to enhance your XML based project!


## Conclusion
MiniPaviFwk has been created after I have a full XMLint Minitel service: [MacBidouille](https://www.minipavi.fr/emulminitel/index.php?url=https://minitelbidouille.pvigier.com/macbidouille.xml&color=false) .
Source directory : [services/macbidouilles](../../services/macbidouille/)
A capture of the site as the XML and pages are periodically generated, as well as the Mistral AI résumé of the articles.

The idea was to be able to take the original XMLint work, to put it on a MiniPaviFwk project, and have it running as fast as possible and as simply as possible.
Then, expand its features using PHP code.

Notice that, basically there's only one step: copy your XML file on [services/myservice/xml](../../services/myservice/xml) under the name "default.xml" and enjoy yourself (excuse my bad English).
