<?php

/**
 * Demo Articles Menu
 *
 * Display choice of articles search by Author, Date, or all articles
 */

namespace service\controllers;

class ArticlesMenuController extends \MiniPaviFwk\controllers\VideotexController
{
    public function __construct($context, $params = [])
    {
        parent::__construct($context, $params);

        // We initialize our context for articles, to avoid repeating it on multiple places
        $this->context['articles'] = [
            'search_type' => 'all',
            'search_criteria' => '',
            'search_page' => 0,
            'search_results' => [],
            'list_page' => 0,
            'list_results' => [],
            'view_id' => 0,
        ];
    }

    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->effaceLigne00()
        ->page("articles")
        ->position(4, 1)->inversionDebut()->ecritUnicode(' 1 ')->inversionFin()->ecritUnicode(" Tous les articles")
        ->position(6, 1)->inversionDebut()->ecritUnicode(' 2 ')->inversionFin()->ecritUnicode(" Articles par auteur")
        ->position(8, 1)->inversionDebut()->ecritUnicode(' 3 ')->inversionFin()->ecritUnicode(" Articles par date")
        ->position(10, 1)->inversionDebut()->ecritUnicode(' 4 ')->inversionFin()->ecritUnicode(" Aucun critère")
        ->position(12, 1)->inversionDebut()->ecritUnicode(' 5 ')->inversionFin()->ecritUnicode(" Aucun article");
        return $videotex->getoutput();
    }

    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd(null, 24, 28, 3, true, '.');
    }

    public function choixSommaire(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-sommaire');
    }

    public function choixETOILESommaire(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-sommaire');
    }

    public function choix1Envoi(): ?\MiniPaviFwk\actions\Action
    {
        // Get all articles ID and go!
        $this->context['articles']['list_results'] = \service\helpers\DataHelper::getAllArticlesIds();
        return new \MiniPaviFwk\actions\PageAction($this->context, 'articles-list');
    }

    public function choix2Envoi(): ?\MiniPaviFwk\actions\Action
    {
        // Search articles by Author
        $this->context['articles']['search_type'] = 'author';
        $this->context['articles']['search_results'] = \service\helpers\DataHelper::getCriterias('author');
        return new \MiniPaviFwk\actions\PageAction($this->context, 'articles-search');
    }

    public function choix3Envoi(): ?\MiniPaviFwk\actions\Action
    {
        // Search articles by Author
        $this->context['articles']['search_type'] = 'date';
        $this->context['articles']['search_results'] = \service\helpers\DataHelper::getCriterias('date');
        return new \MiniPaviFwk\actions\PageAction($this->context, 'articles-search');
    }

    public function choix4Envoi(): ?\MiniPaviFwk\actions\Action
    {
        // Search articles by Author
        $this->context['articles']['search_type'] = 'xxx';
        $this->context['articles']['search_results'] = [];
        return new \MiniPaviFwk\actions\PageAction($this->context, 'articles-search');
    }

    public function choix5Envoi(): ?\MiniPaviFwk\actions\Action
    {
        // Get all articles ID and go!
        $this->context['articles']['list_results'] = [];
        return new \MiniPaviFwk\actions\PageAction($this->context, 'articles-list');
    }
}
