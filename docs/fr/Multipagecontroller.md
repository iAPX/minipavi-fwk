# Contrôleur Multipage (MultipageController)

Permet de gérer des contenus multipages, dérive de VideotexController et est utilisé par [MenuController](./Menucontroller.md) pour les menus multipages.<br/>
Il gère la pagination, les touches [SUITE] et [RETOUR], mais vous devez gérer l'affichage soit complet soit partiel pour chaque page.

Sources : [src/controllers/MultipageController.php](../../src/controllers/MultipageController.php)

Exemple d'usage : [Affichage d'un article dans le service Démo](../../services/demo/controllers/ArticleViewController.php)


## Comment implémenter votre contôleur dérivant de MultipageController
Il est très important de noter que la page courante doit absolument être stockée dans $this->context, afin de garder le contexte entre les requêtes envoyées par MiniPavi.<br/>
Le constructeur, est appelé au chargement initial de la page, lors du traitement de la réponse utilisateur, pour changer de page, et ainsi de suite.

Vous devez initialiser cette information dans $this->context ou la variable passée en paramètre $context avant l'instanciation du contrôleur, vous trouverez un exemple dans [ActiclesListController::menuSelectionAction()](../../services/demo/controllers/ArticlesListController.php).<br/>
le array() $params optionnel est aussi utilisable pour cela, il est automatiquement disponible via `$this->context['params]`.


### Variables $this->multipage_page_num et $this->multipage_nb_pages
Ces variables sont fournies par le constructeur de votre contrôleur à celui de MultipageController qui en garde une copie à laquelle vous pouvez accéder.

À chaque modification par MultipageController de $this->multipage_page_num, la méthode `multipageSavePageNumber()` que vous devez implémenter sera appelée pour vous permettre de mettre à jour l'entrée correspondante du array() `$this->context` ou `$this->context['params']`.


### __construct()
Signature : `public function __construct(array $context, array $params = [])`

Dans votre constructeur, vous devez appeler son parent, le constructeur de MultipageController.<br/>
Signature : `public function __construct(int $page_num, int $nb_pages, array $context, array $params = [])`

Vous devez lui fournir $page_num le numéro de page courante (1..n), $nb_pages le nombre de pages à afficher calculé par vos soins (1..n), et comme d'habitude le $context et des paramètres optionnels.<br/>
Usuellement vous chargerez le contenu multipage à afficher avant d'appeler le constructeur parent, afin de calculer le nombre de page courante.

Notez que si votre contenu est dynamique, et change en temps-réel, que ça soit sur un réaffichage par [Répétition] ou un changement de page, le nombre de pages doit être recalculé et MultipageController ajustera le numéro de page courante pour rester dans les limites, en appelant `multipageSavePageNumber()` si nécessaire.

Exemple:
```
    public function __construct(array $context, array $params = [])
    {
        $article_id = $context['articles']['view_id'];
        $this->article = \service\helpers\DataHelper::getArticleById($article_id);
        $nb_pages = intdiv(mb_strlen($this->article['content']) - 1, self::CODEPOINTS_PER_PAGE) + 1;

        parent::__construct($context['articles']['view_page'], $nb_pages, $context, $params);
    }
```

> [!IMPORTANT]
> Si votre contenu est vide, que vous envoyiez 0 pages,
> le nombre de pages sera forcé à 1, pour correspondre à la première page.<br/>
> Page 1/0 n'est pas une option!


### multipageSavePageNumber()
Signature : `public function multipageSavePageNumber(int $page_num): void`

Cette méthode intégrée dans votre contrôleur sera appelée à chaque fois que le numéro de page courante changera, donc via un changement de page avec [Suite] ou [Retour] mais aussi si le constructeur modifie celui-ci pour correspondre à un nombre de page moins important.

Exemple:
```
    public function multipageSavePageNumber(int $page_num): void
    {
        $this->context['articles']['view_page'] = $page_num;
    }
```


### ecran()
Votre méthode écran peut afficher la pagination, en utilisant `$this->multipage_page_num` et `$this->multipage_nb_pages` pour indiquer la paage courante, mais aussi avec le premier sélectionner la partie du contenu à afficher.

Exemple au sein de ecran():
```
        // Display the pagination, [ SUITE | RETOUR ] and the current page of the article
        $videotex
        ->position(3, 31)
        ->ecritUnicode("Page " . $this->multipage_page_num . "/" . $this->multipage_nb_pages);

        $videotex
        ->position(22, 27)
        ->inversionDebut()->ecritUnicode(" SUITE|RETOUR ")->inversionFin();

        $videotex
        ->position(9, 1)->couleurTexte('magenta')
        ->ecritUnicode(mb_substr(
            $this->article['content'],
            self::CODEPOINTS_PER_PAGE * ($this->multipage_page_num - 1),
            self::CODEPOINTS_PER_PAGE
        ));
```


### getCmd()
Comme pour tous les contrôleurs vous pouvez avoir un getCmd(), dans ce cas, pensez soit à activer toutes les touches de fonctions (premier paramètre null), soit au moins à activer les touches SUITE et RETOUR pour permettre à la pagination de fonctionner.

Voir la [documentation des Commandes](./Cmds.md).

Exemple:
```
    public function getCmd(): array
    {
        // We only accept [SUITE] + [RETOUR] for pagination, [SOMMAIRE] for the menu, and [REPETITION] to redraw.
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd(
            \MiniPaviFwk\helpers\ValidationHelper::SUITE
            | \MiniPaviFwk\helpers\ValidationHelper::RETOUR
            | \MiniPaviFwk\helpers\ValidationHelper::SOMMAIRE,
            24,
            20,
            1,
            false
        );
    }
```


## Aller plus loin
Vous êtes invités à aller plus loin, pour fournir une meilleure expérience.<br/>
Par exemple, rajoutez la touche [Répétition] aux touches de fonction autorisées, et VideotexController la gèrera automatiquement!<br/>
Mais il y a mieux...


### Gestion optionnel de la pagination et des touches de fonctions [SUITE] et/ou [RETOUR]
Si votre contenu peut être monopage, n'hésitez pas à ne pas afficher de pagination (numéro de page et touches ce fonctions) afin de simplifier l'interface.<br/>
Vous pouvez dans ce cas faire aussi un test dans getCmd() pour ne pas activer les touches [Suite] et [Retour].

Signature : `public function getCmd(): array`

Exemple dans getCmd() :
```
    public function getCmd(): array
    {
        // We only accept [SUITE] + [RETOUR] for pagination if needed, [SOMMAIRE] for the menu, and [REPETITION] to redraw.
        $validation = \MiniPaviFwk\helpers\ValidationHelper::SOMMAIRE;
        $validation |= \MiniPaviFwk\helpers\ValidationHelper::REPETITION;
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
Si vous implémentez `multipageRefreshEcran()`, cette méthode sera appelée à la place de ecran() lors des changements de pages par [Suite] et [Retour].<br/>
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
        $videotex->ecritVideotex($this->displayMyContentForThisPage());
        return $videotex->getOutput;
    }
```


### Messages d'erreurs customs: errorFirstPage() et errorLastPage()
Vous pouvez créer des messages d'erreurs personnalisés, qui seront affichés en Ligne00, respectivement si vous faites [Retour] depuis la première page, ou [Suite] sur la dernière page.

Signatures : `protected function errorFirstPage(): string` et `protected function errorLastPage(): string`

Exemples:
```
    protected function errorFirstPage(): string
    {
        // Overridable to change the error message
        return 'Première page!';
    }

    protected function errorlastPage(): string
    {
        // Overridable to change the error message
        return 'Dernière page!';
    }
```
