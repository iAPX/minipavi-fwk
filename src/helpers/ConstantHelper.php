<?php

/**
 * Helper static methods to access constants hierarchically
 */

namespace MiniPaviFwk\helpers;

class ConstantHelper
{
    public static function getConstValueByName(string $constName, $defaultValue = null): ?string
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
