<?php

/**
 * List found articles
 */

namespace service\controllers;

class ArticlesListController extends \MiniPaviFwk\controllers\VideotexController
{
    private const ARTICLES_PER_PAGE = 5;

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

        // Display the article list, separated for a better UX when scrolling list
        $videotex->ecritVideotex($this->displayArticleList());

        return $videotex->getoutput();
    }

    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd(null, 24, 28, 3, true, '.');
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

    public function toucheEnvoi(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        $article_num = (int) $saisie;
        if ($article_num > 0) {
            $article_id = $this->context['articles']['list_results'][$article_num - 1];
            if ($article_id) {
                $this->context['articles']['view_id'] = $article_id;
                return new \MiniPaviFwk\actions\PageAction($this->context, 'article-view');
            }
        }
        return new \MiniPaviFwk\actions\Ligne00Action($this, 'Article introuvable!');
    }

    public function choixSuite(): ?\MiniPaviFwk\actions\Action
    {
        $articles = $this->context['articles']['list_results'];
        $nb_pages = floor((count($articles) - 1) / self::ARTICLES_PER_PAGE) + 1;
        if ($nb_pages <= 1) {
            // No pagination!
            return $this->nonPropose();
        }

        if ($this->context['articles']['list_page'] >= $nb_pages - 1) {
            // End
            return new \MiniPaviFwk\actions\Ligne00Action($this, 'Dernière page!');
        }
        $this->context['articles']['list_page']++;
        $output = $this->displayArticleList();
        return new \MiniPaviFwk\actions\VideotexOutputAction($this, $output);
    }

    public function choixRetour(): ?\MiniPaviFwk\actions\Action
    {
        $articles = $this->context['articles']['list_results'];
        $nb_pages = floor((count($articles) - 1) / self::ARTICLES_PER_PAGE) + 1;
        if ($nb_pages <= 1) {
            // No pagination!
            return $this->nonPropose();
        }

        if ($this->context['articles']['list_page'] == 0) {
            // First page
            return new \MiniPaviFwk\actions\Ligne00Action($this, 'Première page!');
        }

        $this->context['articles']['list_page']--;
        $output = $this->displayArticleList();
        return new \MiniPaviFwk\actions\VideotexOutputAction($this, $output);
    }

    private function displayArticleList(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        // Clear the list and the ligne00
        $videotex->effaceLigne00();
        for ($ligne = 4; $ligne <= 22; $ligne++) {
            $videotex->position($ligne, 1)->effaceFinDeLigne();
        }

        // No articles?!?
        $articles = $this->context['articles']['list_results'];
        if (count($articles) === 0) {
            $videotex
            ->position(12, 1)->doubleTaille()->ecritUnicode("Aucun article!!!")
            ->position(24, 1)->effaceFinDeLigne()
            ->position(24, 31)->inversionDebut()->ecritUnicode(" SOMMAIRE ")->inversionFin();
            return $videotex->getoutput();
        }

        // Don't go after the last page, or before the first one, sanity check
        $nb_pages = floor((count($articles) - 1) / self::ARTICLES_PER_PAGE) + 1;
        $current_page = $this->context['articles']['list_page'];
        if ($current_page >= $nb_pages) {
            $current_page = $nb_pages - 1;
        } elseif ($current_page < 0) {
            $current_page = 0;
        }

        // Display the pagination
        if ($nb_pages > 1) {
            $videotex->position(4, 31)->ecritUnicode("Page " . ($current_page + 1) . "/" . $nb_pages);
            if ($current_page == 0) {
                $videotex->position(22, 34)->inversionDebut()->ecritUnicode(" SUITE ")->inversionFin();
            } elseif ($current_page < $nb_pages - 1) {
                $videotex->position(22, 27)->inversionDebut()->ecritUnicode(" SUITE|RETOUR ")->inversionFin();
            } else {
                $videotex->position(22, 33)->inversionDebut()->ecritUnicode(" RETOUR ")->inversionFin();
            }
        }

        // Display the articles title, author and data
        $base = $current_page * self::ARTICLES_PER_PAGE;
        $ligne = 5;
        for ($dep = 0; $dep < self::ARTICLES_PER_PAGE; $dep++) {
            if (! isset($this->context['articles']['list_results'][$base + $dep])) {
                // End of list
                break;
            }

            $article_id = $this->context['articles']['list_results'][$base + $dep];
            $article = \service\helpers\DataHelper::getArticleById($article_id);
            $author_id = $article['author_id'];
            $author_name = \service\helpers\DataHelper::getAuthorNameById($author_id);

            $videotex
            ->position($ligne, 1)
            ->inversionDebut()->ecritUnicode(" " . substr(" " . ($base + $dep + 1), -2) . " ")->inversionFin()
            ->ecritUnicode(' ' . mb_substr($article['title'], 0, 35))

            ->position($ligne + 1, 6)
            ->ecritUnicode('@' . \MiniPaviFwk\helpers\mb_ucfirst($author_name) . ', le ')
            ->ecritUnicode(\service\helpers\DataHelper::dateToFrench($article['date']));

            $ligne += 3;
        }

        return $videotex->getOutput();
    }
}
