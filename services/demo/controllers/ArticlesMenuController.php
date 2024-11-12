<?php

/**
 * Demo Articles Menu
 *
 * Display choice of articles search by Author, Date, or all articles.
 * Example of a very simple MenuController, implementing complex behaviour through $items array.
 */

namespace service\controllers;

class ArticlesMenuController extends \MiniPaviFwk\controllers\MenuController
{
    public function __construct($context, $params = [])
    {
        $items = [
            1 => ["Tous les articles", 'all', [], \service\helpers\DataHelper::getAllArticlesIds(), 'articles-list'],
            2 => [
                "Articles par auteur", 'author',
                \service\helpers\DataHelper::getCriterias('author'), [], 'articles-search'
            ],
            3 => [
                "Articles par date", 'date',
                \service\helpers\DataHelper::getCriterias('date'), [], 'articles-search'
            ],
            4 => ["Aucun critère", 'xxx', [], [], 'articles-search'],
            5 => ["Aucun article", 'all', [], [], 'articles-list'],
        ];

        // We initialize our context for articles, to avoid repeating it on multiple places
        $context['articles'] = [
            'search_type' => 'all',
            'search_criteria' => '',
            'search_page' => 0,
            'search_results' => [],
            'list_page' => 1,
            'list_results' => [],
            'view_id' => 0,
            'view_page' => 1,
        ];

        parent::__construct(1, null, $items, $context, $params);
    }

    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->effaceLigne00()
        ->page("articles")
        ->ecritVideotex($this->menuDisplayItemList());
        return $videotex->getoutput();
    }

    public function menuDisplayItem(int $choice_number, int $rank, int|string $item_key, mixed $item_value): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->position(4 + $rank * 2, 1)
        ->inversionDebut()->ecritUnicode(" $choice_number ")->inversionFin()
        ->ecritUnicode(' ' . $item_value[0]);
        return $videotex->getoutput();
    }

    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd(
            \MiniPaviFwk\helpers\ValidationHelper::ENVOI
            | \MiniPaviFwk\helpers\ValidationHelper::SOMMAIRE
            | \MiniPaviFwk\helpers\ValidationHelper::REPETITION,
            24,
            28,
            3,
            true,
            '.'
        );
    }

    public function menuSelectionAction(int|string $item_key, mixed $item_value): ?\MiniPaviFwk\actions\Action
    {
        $this->context['articles']['search_type'] = $item_value[1];
        $this->context['articles']['search_results'] = $item_value[2];
        $this->context['articles']['list_results'] = $item_value[3];
        return new \MiniPaviFwk\actions\PageAction($this->context, $item_value[4]);
    }

    public function choixSommaire(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-sommaire');
    }

    public function choixETOILESommaire(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-sommaire');
    }
}
