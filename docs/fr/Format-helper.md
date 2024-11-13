# Helper de formatage

Fourni des services de formatage des données
[Sources](../../src/helpers/FormatHelper.php)

## Formatage de titres : formatTitle
Signature :
```
    public static function formatTitle(
        string $titre,
        int $ligne,
        int $nb_texte_lignes,
        int $retrait = 0,
        int $margeDroite = 0,
        string $couleur = 'blanc',
        string $continuation = ' ...',
        string $couleurContinuation = 'blanc',
        int $alignement = self::ALIGN_LEFT,
        int $attributes = self::ATTRIBUTE_DEFAULT,
        ?int $retraitPremiereLigne = null
    ): string
```

Constantes:
```
    // Text alignment
    public const ALIGN_LEFT = 0;
    public const ALIGN_CENTER = 1;
    public const ALIGN_RIGHT = 2;

    // Text attributes
    public const ATTRIBUTE_DEFAULT = 0;
    public const ATTRIBUTE_DOUBLE_LARGEUR = 1;
    public const ATTRIBUTE_DOUBLE_HAUTEUR = 2;
    public const ATTRIBUTE_DOUBLE_TAILLE = 3;
    public const ATTRIBUTE_INVERSION = 4;
```

Renvoie un chaîne contenant un flux Vidéotex utilisable dans `$videotex->ecritVideotex()`.
$titre votre titre
$ligne la première ligne d'affichagge du texte. Attention, si votre texte est en Double Hauteur ou Double Taille, cette ligne doit être celle de la base de la première ligne du texte, le haut des caractères étant affichés sur la ligne précédente!
$nb_texte_lignes nombre de lignes de textes autorisées, différent du nombre de lignes à l'écran si vous utilisez Double Hauteur ou Double Taille, alors le double.
$retrait nombre de caractères de retrait à gauche, 0 par défaut
$margeDroite nombre de caractères de marge à droite, 0 par défaut
$couleur la couleur du texte, 'blanc' par défaut
$continuation chaîne de continuation lorsque le texte est trop long, est affiché alors sur la dernière ligne du titre à la fin de celui-ci.
$couleurContinuation couleur de la chaîne de continuation, 'blanc' par défaut
$alignment une des constantes, alignement à gauche (au retrait), alignement à droite (à la marge droite) ou centrée entre ces deux limites
$attributes un mask définissant la taille, comme \MiniPaviFwk\helpers\FormatHelper::ATTRIBUTE_DOUBLE_TAILLE, et l'inversion vidéo
$retraitPremiereLigne permet de créer un retrait à gauche différent pour la première ligne, par exemple pour afficher un numéro d'option dans un menu


Exemple d'usage dans ecran():
```
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex->ecritVideotex(
            \MiniPaviFwk\helpers\FormatHelper::formatTitle(
                "Titre centré en double hauteur",
                5,
                1,
                0,
                0,
                "blanc",
                " ...",
                "blanc",
                \MiniPaviFwk\helpers\FormatHelper::ALIGN_CENTER,
                \MiniPaviFwk\helpers\FormatHelper::ATTRIBUTE_DOUBLE_HAUTEUR
            )
        );
```

Voir aussi la démo dur format helper [DemoFormatHelperController](../../services/demo/controllers/DemoFormatHelperController.php)
