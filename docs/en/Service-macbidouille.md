# MacBidouille Service

Located in ./services/macbidouille
This is Minitel service actually online here [Macbidouille Minitel service](https://www.minipavi.fr/emulminitel/index.php?url=https://minitelbidouille.pvigier.com/minitelbidouille.xml&color=false).

The real Macbidouille is also available here [Macbidouille.com](https://macbidouille.com)
Notice that it is a French service in plain French.

This service run using a periodically generated XML file and Vidéotex .vdt files
There is also Mistral AI generated résumé of the articles to ease readings.

## How-to
I extracted the relevant XML and Vidéotex (vdt) files at one point, and implemented in the service.
You could consult them on the ./services/macbidouille repository.
The ./services/macbidouille/service-config.php has been created to enable local access to the Vidéotex (.vdt) files without generating http requests.

An addition was to add a controller (./services/macbidouille/ArticlesController.php) to displaay option to go back to the Demo service.

## Goal
To demonstrate how you could go from a XML + Vidéotex service using XMLInt to a MiniPaviFwk service with minimal or none modification.
So if you have a XMLInt service, except if using zonemessage to receive email messages, or webmedias, the path is straightforward.
