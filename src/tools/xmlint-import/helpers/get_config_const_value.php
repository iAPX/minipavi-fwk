<?php

/**
 * Replace informations
 */

function get_config_const_value(string $config, string $const_name): string
{
    // Find it
    $to_find = "const " . $const_name . " = ";
    $pos = strpos($config, $to_find);
    if ($pos === false) {
        die(
            "Don't find '$to_find' mandatory constant in Config.\n" .
            "please review your ./services/global-config.php file.\n" .
            "Abort. Nothing has been changed.\n"
        );
    }
    $const_part1 = $to_find;
    $config_part2 = ltrim(substr($config, $pos + strlen($to_find)));

    // Find the ;
    $pos2 = strpos($config_part2, ";");
    if ($pos2 === false) {
        die(
            "Malformed ./services/global-config.php file.\n" .
            "Abort. Nothing has been changed.\n"
        );
    }
    return trim(substr($config_part2, 0, $pos2));
}
