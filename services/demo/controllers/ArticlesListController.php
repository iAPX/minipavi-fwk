<?php

/**
 * List found articles
 *
 * Example of a MenuController, with pagination when needed (more than 1 page)
 * and clean effaceZone() instead full redrawing of the page when using [SUITE] or [RETOUR]
 */

namespace service\controllers;

class ArticlesListController extends \MiniPaviFwk\controllers\MenuController
{
    private const ARTICLES_PER_PAGE = 5;

    public function __construct(array $context, array $params = [])
    {
        // Initialize our Menu!
        parent::__construct(
            $context['articles']['list_page'],
            self::ARTICLES_PER_PAGE,
            $context['articles']['list_results'],
            $context,
            $params
        );
    }

    public function multipageSavePageNumber(int $page_num): void
    {
        $this->context['articles']['list_page'] = $page_num;
    }

    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->effaceLigne00()
        ->page("articles");

        // Display the search type and choice
        switch ($this->context['articles']['search_type']) {
            case 'author':
                $author_id = $this->context['articles']['search_criteria'];
                $author_name = \MiniPaviFwk\helpers\mb_ucfirst(
                    \service\helpers\DataHelper::getAuthorNameById($author_id)
                );
                $videotex->position(3, 1)->ecritUnicode("Articles de @" . $author_name);
                break;
            case 'date':
                $french_date = \service\helpers\DataHelper::dateToFrench($this->context['articles']['search_criteria']);
                $videotex->position(3, 1)->ecritUnicode("Articles du " . $french_date);
                break;
            default:
                $videotex->position(3, 1)->ecritUnicode("Tous les articles");
                break;
        }

        // Display the article list, separated for a better UX when scrolling list, through MenuController
        $videotex->ecritVideotex($this->menuDisplayItemList());

        return $videotex->getoutput();
    }

    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd(null, 24, 28, 3, true, '.');
    }

    public function menuSelectionAction(int|string $item_key, mixed $item_value): ?\MiniPaviFwk\actions\Action
    {
        $this->context['articles']['view_id'] = $item_value;
        $this->context['articles']['view_page'] = 1;
        return new \MiniPaviFwk\actions\PageAction($this->context, 'article-view');
    }

    public function choixSommaire(): ?\MiniPaviFwk\actions\Action
    {
        if ($this->context['articles']['search_type'] == 'all') {
            return new \MiniPaviFwk\actions\PageAction($this->context, 'articles-menu');
        }
        return new \MiniPaviFwk\actions\PageAction($this->context, 'articles-search');
    }

    public function choixETOILESommaire(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-sommaire');
    }

    protected function multipageRefreshEcran(): string
    {
        // Override default full screen redrawing when scrolling on the list
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        return $videotex->effaceLigne00()->effaceZone(4, 19)->ecritVideotex($this->menuDisplayItemList())->getoutput();
    }

    public function menuDisplayItem(int $choice_number, int $rank, int|string $item_key, mixed $item_value): string
    {
        $article = \service\helpers\DataHelper::getArticleById($item_value);
        $author_id = $article['author_id'];
        $author_name = \service\helpers\DataHelper::getAuthorNameById($author_id);

        $ligne = 5 + $rank * 3;

        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->position($ligne, 1)
        ->inversionDebut()->ecritUnicode(" " . substr(" " . ($choice_number), -2) . " ")->inversionFin();

        // If long title, display it in 2 lines, else use second line to display the author
        if (mb_strlen($article['title']) > 35) {
            $videotex->ecritVideotex(
                \MiniPaviFwk\helpers\FormatHelper::formatTitle(
                    $article['title'],
                    $ligne,
                    2,
                    5,
                    0,
                    "magenta"
                )
            );
        } else {
            $videotex
            ->couleurTexte('magenta')
            ->ecritUnicode(' ' . $article['title'])
            ->position($ligne + 1, 6)
            ->ecritUnicode('@' . \MiniPaviFwk\helpers\mb_ucfirst($author_name) . ', le ')
            ->ecritUnicode(\service\helpers\DataHelper::dateToFrench($article['date']));
        }

        return $videotex->getoutput();
    }

    public function menuDisplayPagination(int $page_num, int $nb_pages): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        // None article
        if ($nb_pages === 0) {
            $videotex
            ->position(12, 1)->doubleTaille()->ecritUnicode("Aucun article!!!")
            ->position(24, 1)->effaceFinDeLigne()
            ->position(24, 31)->inversionDebut()->ecritUnicode(" SOMMAIRE ")->inversionFin();
            return $videotex->getoutput();
        }

        // Only display pagination if needed
        if ($nb_pages > 1) {
            $videotex->position(4, 31)->ecritUnicode("Page $page_num/$nb_pages");
            if ($page_num == 1) {
                $videotex->position(22, 34)->inversionDebut()->ecritUnicode(" SUITE ")->inversionFin();
            } elseif ($page_num < $nb_pages) {
                $videotex->position(22, 27)->inversionDebut()->ecritUnicode(" SUITE|RETOUR ")->inversionFin();
            } else {
                $videotex->position(22, 33)->inversionDebut()->ecritUnicode(" RETOUR ")->inversionFin();
            }
        }

        return $videotex->getoutput();
    }

    protected function errorFirstPage(): string
    {
        // Overridable to change the error message
        return  'Première page!';
    }

    protected function errorlastPage(): string
    {
        // Overridable to change the error message
        return 'Dernière page!';
    }
}
