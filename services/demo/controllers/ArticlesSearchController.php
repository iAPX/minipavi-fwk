<?php

/**
 * Search articles by author or date
 *
 * Present a list of authors or dates to choose from, then sends to the list of selected articles
 */

namespace service\controllers;

class ArticlesSearchController extends \MiniPaviFwk\controllers\VideotexController
{
    private const CRITERIA_PER_PAGE = 5;

    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->effaceLigne00()
        ->page("articles");

        // Display the search type and choice
        switch ($this->context['articles']['search_type']) {
            case 'author':
                $videotex->position(3, 1)->ecritUnicode("Recherche par Auteur");
                break;
            case 'date':
                $videotex->position(3, 1)->ecritUnicode("Recherche par date de parution");
                break;
        }

        // Display the article list, separated for a better UX when scrolling list
        $videotex->ecritVideotex($this->displayCriteriaList());

        return $videotex->getOutput();
    }

    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd(null, 24, 28, 3, true, '.');
    }

    public function choixSommaire(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'articles-menu');
    }

    public function choixETOILESommaire(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-sommaire');
    }

    public function toucheEnvoi(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        $criteria_num = (int) $saisie;
        $criterias = array_keys($this->context['articles']['search_results']);

        if ($criteria_num > 0) {
            if (isset($criterias[$criteria_num - 1])) {
                $criteria_value = $criterias[$criteria_num - 1];
                $this->context['articles']['search_criteria'] = $criteria_value;
                $this->context['articles']['list_results'] =
                    \service\helpers\DataHelper::getArticlesIdsByCriteria(
                        $this->context['articles']['search_type'],
                        $criteria_value
                    );
                return new \MiniPaviFwk\actions\PageAction($this->context, 'articles-list');
            }
        }
        return new \MiniPaviFwk\actions\Ligne00Action($this, 'Critère non propposé!');
    }

    public function choixSuite(): ?\MiniPaviFwk\actions\Action
    {
        $criterias = $this->context['articles']['search_results'];
        $nb_pages = floor((count($criterias) - 1) / self::CRITERIA_PER_PAGE) + 1;
        if ($nb_pages <= 1) {
            // No pagination!
            return $this->nonPropose();
        }

        if ($this->context['articles']['search_page'] >= $nb_pages - 1) {
            // End
            return new \MiniPaviFwk\actions\Ligne00Action($this, 'Dernière page!');
        }
        $this->context['articles']['search_page']++;
        $output = $this->displayCriteriaList();
        return new \MiniPaviFwk\actions\VideotexOutputAction($this, $output);
    }

    public function choixRetour(): ?\MiniPaviFwk\actions\Action
    {

        $criterias = $this->context['articles']['search_results'];
        $nb_pages = floor((count($criterias) - 1) / self::CRITERIA_PER_PAGE) + 1;
        if ($nb_pages <= 1) {
            // No pagination!
            return $this->nonPropose();
        }

        if ($this->context['articles']['search_page'] == 0) {
            // First page
            return new \MiniPaviFwk\actions\Ligne00Action($this, 'Première page!');
        }

        $this->context['articles']['search_page']--;
        $output = $this->displayCriteriaList();
        return new \MiniPaviFwk\actions\VideotexOutputAction($this, $output);
    }

    private function displayCriteriaList(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $criterias = array_values($this->context['articles']['search_results']);

        // Clear the list and the ligne00
        $videotex->effaceLigne00();
        for ($ligne = 4; $ligne <= 22; $ligne++) {
            $videotex->position($ligne, 1)->effaceFinDeLigne();
        }

        // No criteria?!?
        if (count($criterias) === 0) {
            $videotex
            ->position(12, 1)->doubleTaille()->ecritUnicode("Aucun critère!!!")
            ->position(24, 1)->effaceFinDeLigne()
            ->position(24, 31)->inversionDebut()->ecritUnicode(" SOMMAIRE ")->inversionFin();
            return $videotex->getOutput();
        }

        // Don't go after the last page, or before the first one, sanity check
        $nb_pages = floor((count($criterias) - 1) / self::CRITERIA_PER_PAGE) + 1;
        $current_page = $this->context['articles']['search_page'];
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

        // Display the criteria
        $base = $current_page * self::CRITERIA_PER_PAGE;
        $ligne = 6;
        for ($dep = 0; $dep < self::CRITERIA_PER_PAGE; $dep++) {
            if (! isset($criterias[$base + $dep])) {
                // End of list
                break;
            }
            $criteria_name = $criterias[$base + $dep];

            $videotex
            ->position($ligne, 1)
            ->inversionDebut()->ecritUnicode(" " . substr(" " . ($base + $dep + 1), -2) . " ")->inversionFin()
            ->ecritUnicode(' ' . $criteria_name);

            $ligne += 3;
        }

        return $videotex->getOutput();
    }
}
