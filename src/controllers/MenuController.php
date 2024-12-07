<?php

/**
 * Menu Controller
 *
 * Simplify menu creation, including multipage menu creation
 * Handles Menu display, pagination, and menu choices.
 */

namespace MiniPaviFwk\Controllers;


// BUGFIX v1.1.1 for php 8.2 w/ help from @ludosevilla - temporary ugly fix until I figure out a better solution!
require_once "src/controllers/MultipageController.php";


class MenuController extends MultipageController
{
    private ?int $menu_items_per_page = null;
    private array $menu_items = [];

    public function __construct(int $page_num, ?int $items_per_page, array $items, array $context, array $params = [])
    {
        $this->menu_items_per_page = $items_per_page;
        $this->menu_items = $items;
        if ($items_per_page) {
            $nb_pages = intdiv(count($items) - 1, $this->menu_items_per_page) + 1;
        } else {
            $nb_pages = 1;
        }
        parent::__construct($page_num, $nb_pages, $context, $params);
    }

    public function toucheEnvoi(string $saisie): ?\MiniPaviFwk\actions\Action
    {
        $item_num = (int) $saisie;
        if ($item_num > 0 && $item_num <= count($this->menu_items)) {
            $item_key = array_keys($this->menu_items)[$item_num - 1];
            $item_value = $this->menu_items[$item_key];
            return $this->menuSelectionAction($item_key, $item_value);
        }
        return null;
    }

    public function menuSelectionAction(int|string $item_key, mixed $item_value): ?\MiniPaviFwk\actions\Action
    {
        trigger_error("MenuController::menuSelectionAction() not implemented in " . $this::class, E_USER_ERROR);
    }

    protected function menuDisplayItemList(): string
    {
        $output = $this->menuDisplayPagination($this->multipage_page_num, $this->multipage_nb_pages);
        if (count($this->menu_items) > 0) {
            // Ensure display of each item of the current page
            $item_keys = array_keys($this->menu_items);

            if ($this->menu_items_per_page) {
                $first_item = ($this->multipage_page_num - 1) * $this->menu_items_per_page;
                $nb_items = $this->menu_items_per_page;
            } else {
                $first_item = 0;
                $nb_items = count($this->menu_items) + 1;
            }

            for ($i = 0; $i < $nb_items; $i++) {
                $item_index = $first_item + $i;
                if (isset($item_keys[$item_index])) {
                    $item_key = $item_keys[$item_index];
                    $item_value = $this->menu_items[$item_key];
                    $rank = $i;
                    $choice_number = $item_index + 1;
                    $output .= $this->menuDisplayItem($choice_number, $rank, $item_key, $item_value);
                }
            }
        } else {
            return "0 items";
        }
        return $output;
    }

    public function menuDisplayPagination(int $page_num, int $nb_pages): string
    {
        // Default without displayed pagination, override if needed, error on multipage (pagination should be handled)
        if ($nb_pages > 1) {
            trigger_error("MenuController::menuDisplayPagination() not implemented in " . $this::class, E_USER_ERROR);
        }
        return '';
    }

    public function menuDisplayItem(int $choice_number, int $rank, int| string $item_key, mixed $item_value): string
    {
        // Only present to remind to implement this method on the service controller.
        trigger_error("MenuController::menuDisplayItem() not implemented in " . $this::class, E_USER_ERROR);
    }

    protected function errorFirstPage(): string
    {
        // Overridable to change the error message
        return $this->menu_items_per_page ? 'Première page du menu!' : '';
    }

    protected function errorLastPage(): string
    {
        // Overridable to change the error message
        return $this->menu_items_per_page ? 'Dernière page du menu!' : '';
    }
}
