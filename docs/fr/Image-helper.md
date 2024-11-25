# Helper pour les images

Transforme des images JPEG, PNG ou autres en stream Vidéotex semi-graphique Alphamosaïque optimisé.

Source: [Image Helper](../../src/helpers/ImageHelper.php)

Les Minitel ont différentes façons de représenter les images:
via du semi-graphique alphamosaïque dès les premiers, via des caractères reprogrammées DRCS pour les modèled 1b et ultérieurs, et du JPEG pour les modèles 2.

Le semi-graphique ou Alphamosaïque permet de représenter 6 "pixels" (ou gros pâtés!) par caractère à l'écran, 3 en hauteur et 2 en largeur.
Cela correspond à une définition de 80 "pixels" de large (40 x 2) sur 72 "pixels" (24 x 3) de haut, et où chaque groupe de 2x3 "pixels" correspondant à un caractère peut être présenté en deux couleurs, la couleur de texte et la couleur de fond.

C'est très médiocre mais le fin du fin pour un terminal grand-public distribué gratuitement à la population dans les années 80!
Pour remettre en perspective, les USA avaient des BBS en mode texte à l'époque, Compuserve, The  Source, etc., accessibles via un ordinateur individuel et modem.
Il y avait plus de terminaux Minitel en France que d'usagers de BBS aux USA dans les années 80!
Quand un Français moyen pouvait utiliser un Minitel en passant par un central électronique, au début des années 80, l'Américain moyen décrochait son téléphone pour les renseignements en passant par des centraux vétustes. La France a eu une avancée incroyable en terme de télécommunication et d'accès aux services télématiques.

Image Helper vous permet de convertir en temps-réel des imaages JPEG ou PNG en semi-graphique Alphamosaïque.
Il vous permet aussi de convertir vos images off-line via son outil en ligne de commande.


> [!IMPORTANT]
> Une image en positionnement relatif fera toujours 40 caractères de large.
> L'espace non utilisé en largeur sera rempli d'espace semi-graphiques alphamosaïques sur fond noir.
> Ceci afin de vous permettre d'exploiter ultérieurement cet espace proprement.
> Seule la dernière ligne fait moins de 40 caractères et s'arrête au dernier signe à afficher.


## Usage au sein de votre service Minitel, pour la génération dynamique
Signature :
```
    public static function imageToAlphamosaic(
        \GDImage $image,
        int $lignes,
        int $cols,
        bool $relative = true,
        ?int $startLigne = null,
        ?int $startCol = null
    ): array
```

$image est une image GD, lue par imagecreatefromjpeg() ou imagecreatefrompng(), notez que vous pouvez modifier la luminance, contraste ou ce que vous voulez avant de passer l'image à imageToAlphaMosaic(). 
$lignes est le nombre de lignes de caractère à l'écran, chaque ligne correspondant à 3 "pixels" Alphamosaïques.
$cols est le nombre de colonnes occupées à l'écran, chacune correspondant à 2 "pixels" Alphamosaïques.
$relative indique si le flux vidéotex doit être relatif à la position courante du curseur d'écriture (par défaut) ou en positionnement absolu (false).
$startLigne ligne de départ en positionnement absolu.
$startCol colonne de départ en positionnement absolu.

Cette méthode statique retourne un array() contenant le flux vidéotex de l'image (string), le nombre de lignes occupées à l'écran (int) ainsi que le nombre de colonnes occupées à l'écran (int).
Les deux valeurs de nombre de lignes et colonnes permettent de connaître l'espace réellement occupé par l'image à l'écran si son ratio est différent de celui que vous avez indiqué en paramètre, facilitant ainsi le centrage de celle-ci.


Exemple de code, voir [DemoImageHelperController](../../services/demo/controllers/DemoImageHelperController.php) :
```
    public function ecran(): string
    {
        // Plus court: return reset(\MiniPaviFwk\helpers\ImageHelper::imageToAlphamosaic($gdImage, 20, 40)); 
        $gdImage = imagecreatefromjpeg(\SERVICE_DIR . '/images/example0.jpg');
        list($videotex_image, $lignes, $cols) = \MiniPaviFwk\helpers\ImageHelper::imageToAlphamosaic($gdImage, 20, 40);
        return $videotex_image;
    }
```


## Usage en ligne de commande

Source : [src/tools/image-converter.php](../../src/tools/image-converter.php)

`php ./src/tools/image-converter.php <lines> <columns> <image-filename> <videotex-filename>`

<lines> est le nombre de lignes de caractère à l'écran, chaque ligne correspondant à 3 "pixels" Alphamosaïques.
<cols> est le nombre de colonnes occupées à l'écran, chacune correspondant à 2 "pixels" Alphamosaïques.
<image-filename> est le fichier contenant l'image d'origine en JPEG ou PNG, généralement aavec les extensions .jpg, .jpeg ou .png
<videotex-file> est le fichier de sortie contenant le flux vidéotex en positionnement relatif de l'image, généralement avec l'extension .vdt

Exemple : `php ./src/tools/image-converter.php 20 40 services/demo/images/example1.jpg ./example1.vdt`


## Défauts & limitations
La conversion est très simple, elle ne respecte ni la luminance moyenne de chaque caractère (2x3 pixels) ni la luminance globale.
Les couleurs sont mal sélectionnées, amenant à une perte de détail en privilégiant les zones (relativement) uniformes.
Si c'est good enough pour vous, ça sera Godunov pour moi!

J'ai noté de revenir dessus pour essayer d'avoir mieux à l'avenir.
