<?php

/**
 * Displays a single article
 */

namespace service\controllers;

class ArticleViewController extends \MiniPaviFwk\controllers\MultipageController
{
    private const LINES_PER_PAGE = 12;
    private array $article = [];
    private array $formatted_pages = [];

    public function __construct(array $context, array $params = [])
    {
        $article_id = $context['articles']['view_id'];
        $this->article = \service\helpers\DataHelper::getArticleById($article_id);
        $this->formatted_pages = \MiniPaviFwk\helpers\FormatHelper::formatMultipageRawText(
            $this->article['content'],
            self::LINES_PER_PAGE
        );
        $nb_pages = count($this->formatted_pages);

        parent::__construct($context['articles']['view_page'], $nb_pages, $context, $params);
    }

    public function multipageSavePageNumber(int $page_num): void
    {
        $this->context['articles']['view_page'] = $page_num;
    }

    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->effaceLigne00()
        ->page("article");

        // Display the Article
        $author_name = \MiniPaviFwk\helpers\mb_ucfirst(
            \service\helpers\DataHelper::getAuthorNameById($this->article['author_id'])
        );
        $french_date = \service\helpers\DataHelper::dateToFrench($this->article['date']);

        $videotex
        ->position(3, 1)->ecritUnicode("Article #" . $this->context['articles']['view_id'])
        ->ecritVideotex(
            \MiniPaviFwk\helpers\FormatHelper::formatTitle(
                $this->article['title'],
                5,
                2
            )
        )

        ->position(7, 1)->effaceFinDeLigne()->couleurFond('bleu')
        ->ecritUnicode(' Par @' . \MiniPaviFwk\helpers\mb_ucfirst($author_name) . ', le ')
        ->ecritUnicode($french_date);

        // Display the pagination, [ SUITE | RETOUR ] and the current page of the article
        $videotex
        ->position(3, 31)
        ->ecritUnicode("Page " . $this->multipage_page_num . "/" . $this->multipage_nb_pages);

        $videotex
        ->position(22, 27)
        ->inversionDebut()->ecritUnicode(" SUITE|RETOUR ")->inversionFin();

        $videotex
        ->position(9, 1)->couleurTexte('magenta')
        ->ecritVideotex($this->formatted_pages[$this->multipage_page_num - 1]);

        return $videotex->getOutput();
    }

    public function getCmd(): array
    {
        // We only accept [SUITE] + [RETOUR] for pagination, [SOMMAIRE] for the menu, and [REPETITION] to redraw.
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd(
            \MiniPaviFwk\helpers\ValidationHelper::SUITE
            | \MiniPaviFwk\helpers\ValidationHelper::RETOUR
            | \MiniPaviFwk\helpers\ValidationHelper::SOMMAIRE
            | \MiniPaviFwk\helpers\ValidationHelper::REPETITION,
            24,
            20,
            1,
            false
        );
    }

    public function choixSommaire(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'articles-list');
    }

    public function choixETOILESommaire(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-sommaire');
    }
}
