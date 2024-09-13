<?php
/**
 * Provides string unicode manipulation functions
 */

namespace helpers;


function mb_ucfirst($string)
{
    return \mb_strtoupper(\mb_substr($string, 0, 1)) . \mb_substr($string, 1);
}

