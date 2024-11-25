# Contrôleur de Menu (MenuController)

Le contrôleur de Menu MenuController dérive du MultipageController qui dérive lui-même VideotexController et supporte toutes leurs fonctionnalités.<br/>
Il fourni un service permettant d'afficher simplement des items de menu, ainsi qu'une pagination optionnelle.

Sources : [src/controllers/MenuController.php](../../src/controllers/MenuController.php)

Exemple simple et monopage : [services/demo/controllers/ArticlesMenuController.php](../../services/demo/controllers/ArticlesMenuController.php)<br/>
Exemple complexe et multipage : [services/demo/controllers/ArticlesListController.php](../../services/demo/controllers/ArticlesListController.php)

Je recommande de lire [la documentation de MultipageController](./Multipagecontroller.md) sur lequel s'appuie MenuController.


## Comment implémenter votre contôleur dérivant de MenuController (et MultipageController)
Il est essentiel de comprendre que MenuController va fournir une partie de la logique nécessaire pour supporter le multipage du MultipageController, mais vous aurez quand-même une partie de support Multipage à effectuer par vous-même si vous le souhaitez, pour aller plus loin.


### Variables $this->multipage_page_num et $this->multipage_nb_pages
Ces variables sont fournies par le constructeur de MenuController à celui de MultipageController qui en garde une copie à laquelle vous pouvez accéder.

À chaque modification par MultipageController de $this->multipage_page_num, la méthode `multipageSavePageNumber()` que vous devez implémenter sera appelée pour vous permettre de mettre à jour l'entrée correspondante du array() `$this->context` ou `$this->context['params']`.


### Votre constucteur __construct()
Signature : `public function __construct(array $context, array $params = [])`

Votre contrôleur doit appeler le contrôleur parent `parent::__construct()` en lui fournissant une liste d'entrée de menu.<br/>
Cette liste doit être sous la forme d'un array associatif $items [clé => valeur], où clé est soit un entier (int) soit une chaîne (str), la valeur peut être de n'importe quel type, y-compris un array ou un objet.

Signature : `public function __construct(int $page_num, ?int $items_per_page, array $items, array $context, array $params = [])`

Vous devez lui fournir $page_num le numéro de page courante (1..n), $items_per_page le nombre d'entrées affichées par page, $items, et comme d'habitude le $context et des paramètres optionnels.<br/>
Si $items_per_page est nul, c'est que vous ne voulez pas de pagination du menu.<br/>
MenuController calculera le nombre de pages nécessaires et enverra cette information à MultipageController.

Exemple:
```
    private const ITEMS_PER_PAGE = 10;

    public function __construct($context, $params = [])
    {
        $items = [
            'all-articles' => "Tous les articles",
            'author-search' => "Articles par auteur",
            'date-search' => "Articles par date",
            'none-search' => "Aucun critère",
            'none-article' => "Aucun article",
        ];
        parent::__construct(1, self::ITEMS_PER_PAGE, $items, $context, $params);
    }
```


### Affichage de la page : ecran()
Signature : `public function ecran(): string`

Vous devrez ajouter à votre écran, un appel à `$this->menuDisplayItemList()` pour afficher la pagination et la liste des entrées de menu de la page courante.<br/>
Utilisez de préférence ecritVideotex() comme cela : `$videotex->ecritVideotex($this->menuDisplayItemList());` car menuDisplayItem retourne un flux Vidéotex.

Exemple:
```
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->effaceLigne00()
        ->page("articles")
        ->ecritVideotex($this->menuDisplayItemList());  // Mandatory
        return $videotex->getoutput();
```


### Affichage optionnel du numéro de page : menuDisplayPagination()
Signature : `public function menuDisplayPagination(int $page_num, int $nb_pages): string`

Cette méthode sera appelée par MenuController à chaque fois que ecran() ou multipageRefreshEcran() sont appelés, via leur appel à menuDisplayItemList().

Vous devrez utiliser `$this->multipage_page_num` et optionnellement `$this->multipage_nb_pages` pour afficher l'information de la page courante, et retourner le flux Vidééotex correspondant.

> [!IMPORTANT]
> Si votre contenu est multipage donc avec plus d'une page à afficher,
> cette méthode est obligatoirement présente, ou cela déclenche une erreur E_USER_ERROR.

Exemple:
```
    public function menuDisplayPagination(int $page_num, int $nb_pages): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        videotex->position(4, 31)->ecritUnicode("Page $page_num/$nb_pages");
        return $videotex->getoutput();
    }
```


### Affichage d'une entrée de menu : menuDisplayItem() 
Signature : `public function menuDisplayItem(int $choice_number, int $rank, int|string $item_key, mixed $item_value): string`

Cette méthode de votre contrôleur est appelée pour chaque entrée de menu à afficher.<br/>
$choice_number est le numéro d'entrée à afficher par exemple 1 ou 23, qui devra être tapé par l'utilisateur pour sélectionner cette entrée.<br/>
$rank est le rang de l'entrée dans la page courante, de 0 à n.<br/>
$item_key est la clé (entier ou chaîne) de l'entrée dans le tableau associatif $items fourni au constructeur de MenuController.<br/>
$item_value est la valeur associée à la clé dans $items.<br/>

Positionnez votre curseur suivant $rank, afficher le numéro $choice_number puis affichez le texte correspondant à cette entrée.

Exemple:
```
    public function menuDisplayItem(int $choice_number, int $rank, int|string $item_key, mixed $item_value): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->position(4 + $rank * 2, 1)
        ->inversionDebut()->ecritUnicode(" $choice_number ")->inversionFin()
        ->ecritUnicode(' ' . $item_value[0]);
        return $videotex->getoutput();
    }
```


### Sélection d'une entrée de menu : menuSelectionAction()
Signature : `public function menuSelectionAction(int|string $item_key, mixed $item_value): ?\MiniPaviFwk\actions\Action`

Cette méthode que vous devez intégrer sera appelé par MenuController lorsqu'un usager aura sélectionné une entrée dans le menu.<br/>
$item_key est la clé (entier ou chaîne) de l'entrée dans le tableau associatif $items fourni au constructeur de MenuController
$item_value est la valeur associée à la clé dans $items.

Vous devez retourner une action ou null exceptionnellement.

Exemple avec une clé qui est un nom de page pointant sur un contrôleur:
```
    public function menuSelectionAction(int|string $item_key, mixed $item_value): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, $item_key);
    }

```


## Pour aller plus loin avec MenuController et MultipageController
Vous pouvez bien sûr customiser pour améliorer l'expérience utilisateur.


### Gestion optionnel de la pagination et des touches de fonctions [SUITE] et/ou [RETOUR]
Si votre contenu peut être monopage, n'hésitez pas à ne pas afficher de pagination (numéro de page et touches ce fonctions) afin de simplifier l'interface.<br/>
Vous pouvez dans ce cas faire aussi un test dans getCmd() pour ne pas activer les touches [Suite] et [Retour].

Signature : `public function getCmd(): array`

Exemple dans getCmd() :
```
    public function getCmd(): array
    {
        // We only accept [SUITE] + [RETOUR] for pagination if needed, [SOMMAIRE] for the menu, and [ENVOI] to select a menu item.
        $validation = \MiniPaviFwk\helpers\ValidationHelper::SOMMAIRE;
        $validation |= \MiniPaviFwk\helpers\ValidationHelper::ENVOI;
        if ($this->multipage_nb_pages > 1) {
            $validation |= \MiniPaviFwk\helpers\ValidationHelper::SUITE;
            $validation |= \MiniPaviFwk\helpers\ValidationHelper::RETOUR;
        }
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd(
            $validation,
            24,
            20,
            1,
            false
        );
    }
```


### Rafraîchissement de votre contenu sans réafficher toute la page : multipageRefreshEcran()
Si vous implémentez `multipageRefreshEcran()`, cette méthode sera appelée à la place de ecran() lors des changements de pages par [Suite] et [Retour].
[Répétition] gardera son comportement et réaffichera bien toute la page.

Signature : `protected function multipageRefreshEcran(): string`

Vous aurez à effacer la portion d'écran contenant votre contenu, par exemple avec `VideotexHelper->effaceZone()` [Voir doc ici](./Videotex-helper.md).<br/>
Puis réafficher la pagination et le contenu correspondant à la page sélectionnée, toujours accessible via `$this->multipage_page_num`, en retournant le flux Vidéotex.


Exemple:
```
    protected function multipageRefreshEcran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        // Clear the display zone
        $videotex->effaceZone(4, 19);

        // Display the content of the current page
        $videotex->ecritVideotex($this->menuDisplayItemList());
        return $videotex->getOutput;
    }
```


### Messages d'erreurs customs: errorFirstPage() et errorLastPage()
Vous pouvez créer des messages d'erreurs personnalisés, qui seront affichés en Ligne00, respectivement si vous faites [Retour] depuis la première page, ou [Suite] sur la dernière page.<br/>
Notez que MenuController par défaut n'affiche pas de message d'erreur si vous avez setté le $item_per_page à null (pas de pagination), mais ces méthodes seront quand-même appelées par MultipageController.

Signatures : `protected function errorFirstPage(): string` et `protected function errorLastPage(): string`

Exemples:
```
    protected function errorFirstPage(): string
    {
        // Overridable to change the error message
        return 'Première page de ce superbe menu!';
    }

    protected function errorlastPage(): string
    {
        // Overridable to change the error message
        return 'Dernière page de ce superbe menu!';
    }
```
