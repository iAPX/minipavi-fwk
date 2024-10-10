<?php

/**
 * Helper staaticc methods to access constants hierarchically
 */

namespace MiniPaviFwk\helpers;

class ConstantHelper
{
    public static function getConstValueByName(string $constName, $defaultValue = ""): string
    {
        if (defined('\\service\\' . $constName)) {
            return constant('\\service\\' . $constName);
        }
        if (defined('\\' . $constName)) {
            return constant('\\' . $constName);
        }

        return $defaultValue;
    }
}
