<?php

/**
 * Offers a YES/no choice
 */

function YESno(string $prompt = "Continue? (Y/n) "): bool
{
    echo "\n";
    echo $prompt;
    $saisie = mb_strtolower(trim(readline()));
    echo "\n";
    if ($saisie === 'y' || $saisie === 'yes' || $saisie === '') {
        return true;
    } else {
        return false;
    }
}
