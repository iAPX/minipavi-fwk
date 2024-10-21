<?php

/**
 * Transcode a XML Page name to a Controller name
 */

function pagename_to_controllername(string $pagename): string {
    $cleanControllerName = '';
    foreach (explode('-', $pagename) as $pagename_part) {
        $car = substr($pagename_part, 0, 1);
        if ($car < 'a' || $car > 'z') {
            // Not a letter, could not be ucfirsted, we change the dash '-' to underscore '_'
            $cleanControllerName .= '_';
        }
        $cleanControllerName .= \ucfirst(mb_strtolower($pagename_part));
    }
    $controller_name = $cleanControllerName . "Controller";
    return $controller_name;
}
