# Helper de formatage

Fourni des services de formatage des données
[Sources](../../src/helpers/FormatHelper.php)

Les services offerts sont le formatage d'un titre et le formatage d'un texte multipage.


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

> [!TIP]
> Une astuce, vous pouvez connaître à posteriori le nombre de lignes affichées en comptant le nombre de caractères ASCII "\x1F" qui permet le positionnement dans le stream via la fonction PHP substr_count()
> Le compte indique le nombre de lignes de texte affichées exactement.
> Notez qu'en Double Hauteur ou Double Taille cela double le nombre de lignes occupées à l'écran du Minitel.

Exemple:
```
        $videotex_title = \MiniPaviFwk\helpers\FormatHelper::formatTitle(
            $article['title'],
            $ligne,
            2,
            5,
            0,
            "magenta"
        );
        $videotex->ecritVideotex($videotex_title);

        if (substr_count($videotex_title, "\x1F") == 1) {
            // Title use only 1 line, we could display the author and date on the next line
            $videotex
            ->position($ligne + 1, 6)
            ->ecritUnicode('@' . \MiniPaviFwk\helpers\mb_ucfirst($author_name) . ', le ')
            ->ecritUnicode(\service\helpers\DataHelper::dateToFrench($article['date']));                    
        }
```


## Formatage de textes multipages : formatMultipageRawText()
Signature : `public static function formatMultipageRawText(string $unicodeText, int $hauteur): array`

$unicodeText est le texte Unicode à afficher, LF (\x0A) servant à séparer les lignes désirées, et les paragraphes séparéés par une ligne vide (\x0A\x0A).
$hauteur est le nombre de ligne à afficher par page

Le retour contient une pleine page de texte en Vidéotex par entrée, autant d'entrées que nécessaire.
L'affichage n'inclut pas de positionnement ou de couleur, vous devez les préciser avant via `videotex->position()` et `videotex->couleurTexte()`.

Pour afficher une page, utilisez `videotex->ecritVideotex()` car chacune est un flux Vidéotex. 

Exemple utilisant le MultiPageController pour paginer:
```
class ArticleViewController extends \MiniPaviFwk\controllers\MultipageController
{
    private const LINES_PER_PAGE = 12;
    private array $article = [];
    private array $formatted_pages = [];

    public function __construct(array $context, array $params = [])
    {
        $article_id = $context['articles']['view_id'];
        $this->article = \service\helpers\DataHelper::getArticleById(2);
        $this->formatted_pages = \MiniPaviFwk\helpers\FormatHelper::formatMultipageRawText(
            $this->article['content'],
            self::LINES_PER_PAGE
        );
        $nb_pages = count($this->formatted_pages);

        parent::__construct($context['articles']['view_page'], $nb_pages, $context, $params);
    }

    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->page("demo-controller")
        ->position(5, 1)
        ->ecritVideotex($this->formatted_pages[$this->multipage_page_num - 1]);
        return $videotex->getOutput();
    }
```
