<?php

/**
 * Multipage Controller
 *
 * Handle paginated content, and non-paginated content with optional pagination.
 */

namespace MiniPaviFwk\Controllers;

// BUGFIX v1.1.1 for php 8.2 w/ help from @ludosevilla - temporary ugly fix until I figure out a better solution!
require_once "src/controllers/VideotexController.php";


class MultipageController extends VideotexController
{
    public int $multipage_page_num = 1;
    public int $multipage_nb_pages = 0;

    public function __construct(int $page_num, int $nb_pages, array $context, array $params = [])
    {
        parent::__construct($context, $params);

        // Context should be set for this to work!
        $this->multipage_page_num = $page_num;
        $this->multipage_nb_pages = $nb_pages < 1 ? 1 : $nb_pages;

        // Ensure page number is valid
        if ($this->multipage_page_num > $this->multipage_nb_pages) {
            $this->multipage_page_num = $this->multipage_nb_pages;
            $this->multipageSavePageNumber($this->multipage_page_num);
        } elseif ($this->multipage_page_num < 1) {
            $this->multipage_page_num = 1;
            $this->multipageSavePageNumber($this->multipage_page_num);
        }
    }

    public function multipageSavePageNumber(int $page_num): void
    {
        // Only present to remind to implement this method on the service controller.
        trigger_error(
            "MultipageController::multipageSavePageNumber() not implemented in " . $this::class,
            E_USER_ERROR
        );
    }

    public function choixSuite(): ?\MiniPaviFwk\actions\Action
    {
        if ($this->multipage_page_num >= $this->multipage_nb_pages) {
            return new \MiniPaviFwk\actions\Ligne00Action($this, $this->errorLastPage());
        }
        $this->multipage_page_num++;
        $this->multipageSavePageNumber($this->multipage_page_num);
        return $this->multipageRefreshPage();
    }

    public function choixRetour(): ?\MiniPaviFwk\actions\Action
    {
        if ($this->multipage_page_num <= 1) {
            return new \MiniPaviFwk\actions\Ligne00Action($this, $this->errorFirstPage());
        }
        $this->multipage_page_num--;
        $this->multipageSavePageNumber($this->multipage_page_num);
        return $this->multipageRefreshPage();
    }

    protected function multipageRefreshPage(): ?\MiniPaviFwk\actions\Action
    {
        // Use multipaqgeRefreshEcran() if implemented, else fallback to ecran()
        if (method_exists($this, 'multipageRefreshEcran')) {
            $output = $this->multipageRefreshEcran();
        } else {
            $output = $this->ecran();
        }
        return new \MiniPaviFwk\actions\VideotexOutputAction($this, $output);
    }

    protected function errorFirstPage(): string
    {
        // Overridable to change the error message
        return 'Pas de page précédente!';
    }

    protected function errorLastPage(): string
    {
        // Overridable to change the error message
        return 'Pas de page suivante!';
    }
}
