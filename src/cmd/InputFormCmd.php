<?php

/**
 * Handles the Input Form parameters
 */

namespace MiniPaviFwk\cmd;

use MiniPavi\MiniPaviCli;
use MiniPaviFwk\helpers\FormField;
use MiniPaviFwk\helpers\ValidationHelper;

class InputFormCmd extends Cmd
{
    public static function createMiniPaviCmd(
        ?int $validation = null,
        array $fields,
        bool $curseur = true,
        string $spaceChar = " ",
    ): array {
        $lignes = $cols = $longueurs = $prefills = [];
        foreach ($fields as $field) {
            $lignes[] = $field->ligne;
            $cols[] = $field->col;
            $longueurs[] = $field->longueur;
            $prefills[] = $field->prefill;
        }

        return MiniPaviCli::createInputFormCmd(
            $cols,
            $lignes,
            $longueurs,
            is_null($validation) ? ValidationHelper::FORM_ALL : $validation,
            $curseur,
            $spaceChar,
            $prefills,
        );
    }
}
