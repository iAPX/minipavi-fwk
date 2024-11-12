# Helper Vidéotex

Décris le Helper Vidéotex conseillé pour générer les sorties en Vidéotex.
Il peut être utilisé pour l'affichage d'une page de l'arborescence dans ecran(), tout comme pour générer des messages Vidéotex dans tous les autres cas de figure.

Source : [src/helpers/VideotexHelper.php](../../src/helpers/VideotexHelper.php)

Je recommande chaudement la lecture des [STUM 1b, documentation de développement pour le Minitel](https://www.minipavi.fr/stum1b.pdf) (PDF) pour dépasser le stade d'affichage du texte, aborder l'alphamosaïque, mais aussi comprendre le fonctionnement du souligné et de la couleur de fond en mode texte.
Vous aurez quelques informations pour vous aider dans cette page, mais qui ne remplacent en rien la lecture assidue de ce pavé de référence.


## Usage

Signature : `new \MiniPaviFwk\helpers\VideotexController()`

Exemple d'usage, effacement d'écran et affichage de la page vidéotex "accueil" (vdt/accueil.vdt):
```
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex
        ->effaceEcran()
        ->page("accueil");

        return $videotex->getOutput();
    }
```


## Méthodes

Hors getOutput() qui renvoie une chaîne et est censée être utilisée en fin de cycle de vie de l'objet VideotexHelper, 
toutes les autres méthodes renvoient l'objet VideotexHelper courant, permettant de chaîner les appels pour une meilleure lisibilité. Voir l'exemple ci-dessus.

### getOutput() : renvoie le stream Vidéotex

Signature: `public function getOutput(): string`

Renvoie le flux Vidéotex tel-que comme une chaîne de caractère. Ce n'est ni de l'Ascii ni de l'Unicode mais du Vidéotex.
Notez que l'objet VideotexHelper n'est pas réinitialisé, tout appel ultérieur à une méthode y ajoutant du contenu sera concaténé.


### page() : ajoute le contenu d'une page Vidéotex stockée dans vdt/

Signature : `public function page(string $pagename): VideotexHelper`

Permet d'afficher la page nommée $pagename, le comportement officiel est d'utiliser ce nom avec l'extension .vdt pour charger le fichier de même nom dans le sous-répertoire vdt/.
Vous pouvez utiliser des sous-répertoires, en créant par exemple vdt/demo/toto.vdt et en demandant l'affichage de "demo/toto".

Cette méthode renvoie l'objet Videotexhelper courant pour permettre le chaînage des appels.


### position() ; permet de positionner le curseur d'écriture à l'écran

Signature : `public function position(int $ligne, int $col = 1): VideotexHelper`

Positionne le curseur d'écriture à l'écran, entre la ligne 0 et la ligne 24, et la colonne 1 à 40.

Cette méthode renvoie l'objet Videotexhelper courant pour permettre le chaînage des appels.

> [!IMPORTANT]
> La ligne 00 est traitée différemment et il est déconseillé d'utiliser position() pour y écrire.
> Utilisez la méthode ligne00() à la place!


### curseurVisible() & curseurInvisible() : fait afficher ou cache le curseur

Signatures : `public function curseurVisible(): VideotexHelper` et `public function curseurInvisible(): VideotexHelper`

Fait apparaître ou disparaître le curseur en forme de bloc solide.
Usuellement on n'affiche le curseur que lors des saisies utilisateurs et non lors de l'affichage d'une page, ce qui est le comportement par défaut de MiniPavi.

Ces méthodes renvoient l'objet Videotexhelper courant pour permettre le chaînage des appels.


### texteClignote() et texteFixe() : fait clignoter ou rend fixe le texte

Signatures : `public function texteClignote(): VideotexHelper` et `public function texteFixe(): VideotexHelper`

Fait clignoter les caractère suivant, qu'ils soient alphanumériques ou alphamosaïques, ou s'assure qu'ils ne clignoteront pas.

Ces méthodes renvoient l'objet Videotexhelper courant pour permettre le chaînage des appels.


### souligneDebut() et souligneFin() : souligne du texte (et sépare les "pixels" en alphamosaïque)

Signatures : `public function souligneDebut(): VideotexHelper` et `public function souligneFin(): VideotexHelper`

En mode alphanumérique, fait souligner les caractères qui suivent le prochain espace ' ' affiché, ou s'assure de ne plus souligner à partir du prochain espace ' ' affiché, respectivement.
En mode alphamosaïque permet de séparer d'une ligne chaque "pixel", ou d'enlever cette séparation.

Ces méthodes renvoient l'objet Videotexhelper courant pour permettre le chaînage des appels.

Exemple:
```
   $videotex->souligneDebut()->ecritUnicode("souligneDebut() ici");
   $videotex->souligneFin()->ecritUnicode("...fin là!");
```
Le souligné débutera sous le mot ici, après le premier espace affiché donc après "souligneDebut()".
Le souligné s'arrêtera avant l'espace entre "...fin" et "là!", "...fin" sera souligné, et l'espace ainsi que "là!" ne seront pas soulignés.
Le résultat sera donc: "souligneDebut() <ins>ici...fin</ins> là"
C'est un comportement à connaître: le début et la fin de souligné en alphanumérique ne sont effectifs qu'avec un espace, respectivement après lui et à son niveau.


### inversionDebut() et inversionFin() : fait une inversion vidéo

Signatures : `public function inversionDebut(): VideotexHelper` et `public function inversionFin(): VideotexHelper`

Inverse les couleurs de fond et de caractères affichés pour les caractères suivant.
Blanc sur fond noir, le défaut, devient noir sur fond blanc. Souvent utilisé pour indiquer les touches de fonctions.

Ces méthodes renvoient l'objet Videotexhelper courant pour permettre le chaînage des appels.


### ecritUnicode() : affiche du texte Unicode en le convertissant en alphanumérique Vidéotex

Signature : `public function ecritUnicode(string $unicodeTexte): VideotexHelper`

Ajoute la chaîne unicode à la sortie en Vidéotex en convertissant son contenu.

Cette méthode renvoie l'objet Videotexhelper courant pour permettre le chaînage des appels.


### ecritVideotex() : ajoute du vidéotex

Signature : `public function ecritVideotex(string $videotexTexte): VideotexHelper`

Ajoute la chaîne Vidéotex telle-que à la sortie Vidéotex.

Cette méthode renvoie l'objet Videotexhelper courant pour permettre le chaînage des appels.


### couleurTexte() : change la couleur du texte

Signature : `public function couleurTexte(string $couleur): VideotexHelper`

Change la couleur du texte pour la couleur sélectionnée.
Les couleurs disponibles sont : noir, rouge, vert, jaune, bleu, magenta, cyan et blanc.
Ces couleurs sont aussi disponibles via $videotex::NOIR à $videotex::BLANC, ou via la classe VideotexHelper::NOIR à VideotexHelper::BLANC.

Cette méthode renvoie l'objet Videotexhelper courant pour permettre le chaînage des appels.


### couleurFond() : change la couleur de fond des caractères

Signature : `public function couleurFond(string $couleur): VideotexHelper`

Change la couleur du fond pour la couleur sélectionnée, avec effet à partir du prochain espace ' ' affiché en mode alphanumérique.
Les couleurs disponibles sont : noir, rouge, vert, jaune, bleu, magenta, cyan et blanc.
Ces couleurs sont aussi disponibles via $videotex::NOIR à $videotex::BLANC, ou via la classe VideotexHelper::NOIR à VideotexHelper::BLANC.

Cette méthode renvoie l'objet Videotexhelper courant pour permettre le chaînage des appels.


### tailleNormale(), doubleLargeur(), doubleHauteur() et doubleTaille() : changent la taille des caractères alphanumériques

Signature : `public function tailleNormale(): VideotexHelper` (idem pour les autres au nom près)

Change la taille des caractères alphanumériques, non des caractères alphamosaïques, utile pour les titres.

Ces méthodes renvoient l'objet Videotexhelper courant pour permettre le chaînage des appels.


### effaceFinDeligne() : efface la fin de la ligne

Signature : `public function effaceFinDeLigne(): VideotexHelper`

Efface la fin de la ligne à partir de la position courante du curseur d'écriture.
Agit en posant des espaces, l'inversion n'est pas prise en compte, la couleur de fond choisie apparaît donc jusqu'à la fin de la ligne.

> [!IMPORTANT]
> Il y a un effet de bord à noter avec les attributs de couleur de fond et de souligné.
> Vous pouvez utiliser effaceZone() pour effacer toute une ligne sans effet de bord.


Cette méthode renvoie l'objet Videotexhelper courant pour permettre le chaînage des appels.


### effaceZone() : efface une zone de l'écran, plusieurs lignes consécutives

Signature : `public function effaceZone(int $ligne, int $hauteur): VideotexHelper`

Efface $hauteur lignes (1..n) consécutives débutants par la ligne $ligne.
Agit en posant des caractères semi-graphique/alphamosaïques espaces ' ' (\x20) pour servir de bloqueurs d'attributs de zone (couleur de fond, souligné)
Vous pouvez donc utiliser cette zone après effacement sans effet-de-bord, contrairement à effaceFinDeLigne().


### modeGraphique() : passe en Alphamosaïque

Signature : `public function modeGraphique(): VideotexHelper`

Passe en mode semi-graphique appelé Alphamosaïque, voir [le glossaire](./Glossary.md) et les [STUM](https://www.minipavi.fr/stum1b.pdf) (PDF)

Cette méthode renvoie l'objet Videotexhelper courant pour permettre le chaînage des appels.


### modeTexte() : repasse en mode Alphanumérique

Signature : `public function modeTexte(): VideotexHelper`

Repasse en mode texte quand on était en mode semi-graphique appelé Alphamosaïque.

Cette méthode renvoie l'objet Videotexhelper courant pour permettre le chaînage des appels.


### effaceEcran() : repasse en mode Alphanumérique

Signature : `public function effaceEcran(): VideotexHelper`

Efface complètement l'écran, techniquement en le remplissant de caractères alphamosaïques qui apparaissent noirs.
Le curseur est placé en Ligne 1 et en première colonne.
La ligne 00 n'est pas modifiée, voir effaceLigne00() pour effacer celle-ci.

Cette méthode renvoie l'objet Videotexhelper courant pour permettre le chaînage des appels.


### afficheDateParis() : affiche la date courante à Paris

Signature : `public function afficheDateParis(): VideotexHelper`

Affiche la date actuelle à Paris (timezone Europe/Paris) sous la forme "jj-mm-aaaaa". Par exemple "25-10-2024" au moment où j'écris ces lignes.
Le choix de la timezone de Paris est lié à l'historique Franco-Français du Minitel, et au fait que je suis souvent dans une autre TimeZone que des personnes qui pourraient utiliser mes services.

Cette méthode renvoie l'objet Videotexhelper courant pour permettre le chaînage des appels.


### afficheHeureParis() : affiche l'heure courante à Paris

Signature : `public function afficheHeureParis(): VideotexHelper`

Affiche l'heure actuelle à Paris (timezone Europe/Paris) sous la forme "hh:mn". Par exemple "22:08" au moment où j'écris ces lignes.
Le choix de la timezone de Paris est lié à l'historique Franco-Français du Minitel, et au fait que je suis souvent dans une autre TimeZone que des personnes qui pourraient utiliser mes services.

Cette méthode renvoie l'objet Videotexhelper courant pour permettre le chaînage des appels.


### repeteCaractere() : répète un caractère de manière optimisée (cf. STUM !)

Signature : `public function repeteCaractere(string $caractere, int $nombre): VideotexHelper`

Affiche le caractère demandé, puis encode sa répétition de (nombre-1) fois.
$nombre est limité à 64.

Cette méthode renvoie l'objet Videotexhelper courant pour permettre le chaînage des appels.


### ecritUnicodeCentre() : affiche une chaîne unicode centrée sur une ligne d'écran

Signature : `public function ecritUnicodeCentre(int $ligne, string $unicodeTexte, string $videotexAttributs = ''): VideotexHelper`

Affichage la chaîne $unicodeTexte sur la ligne $ligne, de manière centrée.
$videotexAttributs peut contenir une chaîne vidéotex à insérer entre le positionnement du curseur pour l'affichage et l'affichage de la chaîne elle-même.

Cette méthode renvoie l'objet Videotexhelper courant pour permettre le chaînage des appels.


### afficheRectangleInverse() : affiche un rectangle en vidéo inverse

Signature :
```
public function afficheRectangleInverse(
        int $ligne,
        int $col,
        int $largeur,
        int $hauteur,
        string $couleur
    ): VideotexHelper
```

Affiche un rectangle, rempli en espaces alphanumériques en inversion vidéo de la $couleur demandée.
Il commence à la ligne $ligne (1..24) et la colonne $col (1..40) et fait $largeur caractères de large et $hauteur lignes de hauteur.
Les couleurs disponibles sont : noir, rouge, vert, jaune, bleu, magenta, cyan et blanc.
Ces couleurs sont aussi disponibles via $videotex::NOIR à $videotex::BLANC, ou via la classe VideotexHelper::NOIR à VideotexHelper::BLANC.

Cette méthode renvoie l'objet Videotexhelper courant pour permettre le chaînage des appels.


### effaceLigne00() : efface la Ligne 00

Signature : `public function effaceLigne00(): VideotexHelper`

Efface la Ligne 00 sans toucher au reste de l'écran, le curseur d'écriture revient dans la position qu'il occupait précédemment, ainsi que les attributs séléctionnés.
effaceEcran() n'efface pas la Ligne 00.

Cette méthode renvoie l'objet Videotexhelper courant pour permettre le chaînage des appels.


### ecritUnicodeLigne00() : écrit un texte Unicode en Ligne 00 après conversion en Alphanumérique Vidéotex

Signature : `public function ecritUnicodeLigne00(string $unicodeTexte): VideotexHelper`

Efface la Ligne 00 et y écrit le texte Unicode envoyé, le curseur d'écriture revient dans la position qu'il occupait précédemment, ainsi que les attributs séléctionnés.

Cette méthode renvoie l'objet Videotexhelper courant pour permettre le chaînage des appels.


### ecritPIN() : Affiche le PIN de 4 chiffres pour les WebMedias

Signature : `public function ecritPIN(): VideotexHelper`

Affiche le PIN à 4 chiffres nécessaire pour regarder les WebMedias depuis un autre dispositif lorsqu'on est sur un Minitel physique.

Cette méthode renvoie l'objet Videotexhelper courant pour permettre le chaînage des appels.


### webMedia() : Affiche un média Web ou un lien

Signature : `public function webMedia(string $type, string $url): VideotexHelper`

Fait afficher le média ou un lien suivant le type, chacun indiqué par $url, sauf pour YouTube.
- $type "YT" : affichage d'une vidéo YouTube, $url contient l'identifiant de la vidéo, par exemple "s86K-p089R8" pour afficher la vidéo à l'URL "h􏰁ps://www.youtube.com/watch?v=s86K-p089R8".
- $type "VID" : affiche une vidéo par son URL
- $type "SND" : joue un fichier sonore par son URL
- $type "IMG" : affiche une image par son URL
- $type "URL" : affiche un lien sur l'URL

### deconnexionModem() : envoi <kbd> Esc 9g </kbd> au Minitel pour qu'il fasse raccrocher son modem

Signature : `public function deconnexionModem(): VideotexHelper`

Uniquement pour le plaisir, vous devriez utiliser DeconnexionCmd à la place.
Envoi <kbd> Esc 9g </kbd> au Minitel, qui s'il s'agit d'un modèle physique et non d'un émulateur, demaande alors à son modem de raccrocher la ligne, coupant la communication.
Fut utilisé pour "jeter" des importuns...

Cette méthode renvoie l'objet Videotexhelper courant pour permettre le chaînage des appels.


## Et un méga exemple, regroupant quasiment toutes les fonctions, un!

```
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex
        ->effaceLigne00()

        ->page(demo-controller-page"))
        ->ecritVideotex(file_get_contents(SERVICE_DIR . "vdt/demo-choix-code.vdt"))

        ->position(3, 1)->ecritUnicode("Affichage par ecran() et ecritUnicode()")
        ->position(4, 1)->curseurVisible()->ecritUnicode("curseurVisible() ")
        ->position(5, 1)->souligneDebut()->ecritUnicode(" souligneDebut()")
        ->souligneFin()->ecritUnicode(" souligneFin()")
        ->position(6, 1)->texteClignote()->ecritUnicode("texteClignote()")->texteFixe()->ecritUnicode(" texteFixe()")
        ->position(7, 1)->inversionDebut()->ecritUnicode("inversionDebut()")->inversionFin()
        ->ecritUnicode(" inversionFin()")
        ->position(8, 1)->couleurTexte("vert")->couleurFond("rouge")->ecritUnicode("couleurTexte() + couleurFond()")
        ->position(10, 1)->doubleHauteur()->ecritUnicode("doubleHauteur() ")->tailleNormale()
        ->ecritUnicode("tailleNormale()")
        ->position(12, 1)->doubleTaille()->ecritUnicode("doubleTaille()")
        ->position(13, 1)->doublelargeur()->ecritUnicode("doublelargeur()")
        ->position(14, 1)->texteClignote()->ecritUnicode("texteClignote()")->texteFixe()->ecritUnicode(" texteFixe()")

        // Animation to demonstrate End of Line deletion
        ->position(15, 1)->ecritUnicode("effaceFinDeLigne()oihfeoihfihfiuw")
        ->position(16, 1)->couleurTexte("noir")->ecritUnicode("feqhfiuhnfiuhbiqhbibhfiqwbhfqwiubfqwibqwfifquwibfqw")
        ->position(16, 1)->couleurTexte("noir")->ecritUnicode("feqhfiuhnfiuhbiqhbibhfiqwbhfqwiubfqwibqwfifquwibfqw")
        ->position(16, 1)->couleurTexte("noir")->ecritUnicode("feqhfiuhnfiuhbiqhbibhfiqwbhfqwiubfqwibqwfifquwibfqw")
        ->position(16, 1)->couleurTexte("noir")->ecritUnicode("feqhfiuhnfiuhbiqhbibhfiqwbhfqwiubfqwibqwfifquwibfqw")
        ->position(15, 19)->effacefindeligne()

        ->position(16, 1)->ecritUnicode("modeGraphique() ")->modeGraphique()->ecritUnicode("feifwevw")->modeTexte()
        ->ecritUnicode(" modeTexte()")
        ->position(17, 1)->ecritUnicode("afficheDateParis() : ")->afficheDateParis()
        ->position(18, 1)->ecritUnicode("afficheHeureParis() : ")->afficheHeureParis()
        ->position(19, 1)->ecritUnicode("repeteCaractere()")->repeteCaractere(".", 63)->repeteCaractere("", 63)
        ->position(18, 21)->ecritUnicode("rectangle() : ")->afficheRectangleInverse(19, 21, 16, 4, "magenta")

        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')
        ->ecritUnicode(" " . end(explode('\\', $this::class)));

        return $videotex->getOutput();
    }
```