<?php

/**
 * Displays a single article
 */

namespace service\controllers;

class ArticleViewController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->effaceLigne00()
        ->page("article");

        // Display the Article
        $article_id = $this->context['articles']['view_id'];
        $article = \service\helpers\DataHelper::getArticleById($article_id);
        $author_name = \MiniPaviFwk\helpers\mb_ucfirst(
            \service\helpers\DataHelper::getAuthorNameById($article['author_id'])
        );
        $french_date = \service\helpers\DataHelper::dateToFrench($article['date']);

        $videotex
        ->position(3, 1)->ecritUnicode("Article #" . $article_id)
        ->position(5, 1)->ecritUnicode($article['title'])
        ->position(7, 1)->effaceFinDeLigne()->couleurFond('bleu')
        ->ecritUnicode(' Par @' . \MiniPaviFwk\helpers\mb_ucfirst($author_name) . ', le ')
        ->ecritUnicode($french_date)
        ->position(9, 1)->couleurTexte('magenta')
        ->ecritUnicode($article['content']);

        return $videotex->getoutput();
    }

    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd(null, 24, 20, 1, false, ' ');
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
