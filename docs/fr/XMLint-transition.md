# Transition from XMLint

Décris les étapes de transition d'un service Minitel réalisé avec XMLint, disponible au traavers d'internet via MiniPavi, vers un service utilisant minipavi-fwk.


## Étape 1
Depuis le répertoire racine de minipavi-fwk, vous pouvez utiliser le [script d'import de service Minitel XMLint](../../src/tools/xmlint-import.php).

Tapez cette commande : `php ./src/tools/xmlint-import.php`

Vous aurez besoin de l'URL publique ou privée de votre fichier XML.
Si les pages ne peuvent être importées, vous pourrez les copier manuellement après, lors de vos tests les pages manquantes seront indiquées comme des erreurs dans les logs ou sur votre écran en exécution locale via le terminal.


## Étape 2
Profitez!


## Exemple
Vous pouvez utiliser le XML disponible à cette URL : [https://minitelbidouille.pvigier.com/macbidouille.xml](https://minitelbidouille.pvigier.com/macbidouille.xml)
Il s'agit du service MacBidouille, réalisé en utilisant XMLint, et [consultable via minipavi et l'émulateur ici](https://www.minipavi.fr/emulminitel/index.php?url=https://minitelbidouille.pvigier.com/macbidouille.xml&color=false).


## Références
[Guide d'exécution locale de minipavi-fwk](./Local-execution.md)

[Documentation XMLint](https://raw.githubusercontent.com/ludosevilla/minipaviCli/master/XMLint/XMLint-doc.pdf)
