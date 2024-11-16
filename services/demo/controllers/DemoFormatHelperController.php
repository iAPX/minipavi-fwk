<?php

/**
 * Demo for the FormatHelper
 *
 * - formatTitle
 */

namespace service\controllers;

class DemoFormatHelperController extends \MiniPaviFwk\controllers\MultipageController
{
    public function __construct(array $context, array $params = [])
    {
        parent::__construct($context['format_page'], 4, $context, $params);
    }

    public function multipageSavePageNumber(int $page_num): void
    {
        $this->context['format_page'] = $page_num;
    }

    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->page("demo-controller")
        ->position(3, 31)
        ->ecritUnicode("Page " . $this->multipage_page_num . "/" . $this->multipage_nb_pages);

        switch ($this->context['format_page']) {
            case 1:
                $videotex->ecritVideotex($this->formatTitleExample());
                break;
            case 2:
            case 3:
            case 4:
                $videotex->ecritVideotex($this->formatMultipageRawTextExample($this->context['format_page'] - 1));
                break;
            default:
                $videotex->position(12, 7)->doubleHauteur()->ecritUnicode("Page intentionnellement vide!");
                break;
        }

        $videotex->position(24, 8)->inversionDebut()->ecritUnicode(" SUITE | RETOUR | SOMMAIRE ")->inversionFin();
        return $videotex->getOutput();
    }

    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd(null, 24, 40, 1, false);
    }

    public function choixSommaire(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-sommaire');
    }

    private function formatTitleExample(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->position(3, 1)
        ->ecritUnicode("formatTitle()");

        $videotex->ecritVideotex(
            \MiniPaviFwk\helpers\FormatHelper::formatTitle(
                "Titre centré en Double Taille, 2 lignes",
                5,
                2,
                0,
                0,
                "blanc",
                " ...",
                "blanc",
                \MiniPaviFwk\helpers\FormatHelper::ALIGN_CENTER,
                \MiniPaviFwk\helpers\FormatHelper::ATTRIBUTE_DOUBLE_TAILLE
            )
        );

        $videotex
        ->position(9, 1)->inversionDebut()->ecritUnicode(" X ")->inversionFin();
        $videotex->ecritVideotex(
            \MiniPaviFwk\helpers\FormatHelper::formatTitle(
                "Titre sur trois lignes, aligné à gauche, retrait de 4 puis 2 cars et"
                . " une continuation en blanc. Lorem Ipsum Doloret sit amet",
                9,
                3,
                2,
                2,
                "magenta",
                " ...",
                "blanc",
                \MiniPaviFwk\helpers\FormatHelper::ALIGN_LEFT,
                \MiniPaviFwk\helpers\FormatHelper::ATTRIBUTE_DEFAULT,
                4
            )
        );

        $videotex->ecritVideotex(
            \MiniPaviFwk\helpers\FormatHelper::formatTitle(
                "Titre inversé aligné à droite, un mot par ligne",
                13,
                9,
                40,
                0,
                "blanc",
                "",
                "blanc",
                \MiniPaviFwk\helpers\FormatHelper::ALIGN_RIGHT,
                \MiniPaviFwk\helpers\FormatHelper::ATTRIBUTE_INVERSION
            )
        );

        $videotex->ecritVideotex(
            \MiniPaviFwk\helpers\FormatHelper::formatTitle(
                "Titre sur trois lignes en DOUBLE TAILLE!",
                18,
                3,
                0,
                0,
                "jaune",
                "",
                "blanc",
                \MiniPaviFwk\helpers\FormatHelper::ALIGN_LEFT,
                \MiniPaviFwk\helpers\FormatHelper::ATTRIBUTE_DOUBLE_TAILLE
            )
        );

        return $videotex->getOutput();
    }

    private function formatMultipageRawTextExample(int $numpage): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->position(3, 1)
        ->ecritUnicode("formatMultipageRawText() $numpage/3");

        $article = \service\helpers\DataHelper::getArticleById(2);
        $formatted_pages = \MiniPaviFwk\helpers\FormatHelper::formatMultipageRawText($article['content'], 18);

        $videotex
        ->position(5, 1)
        ->couleurTexte("magenta")
        ->ecritVideotex($formatted_pages[$numpage - 1]);

        return $videotex->getOutput();
    }
}
