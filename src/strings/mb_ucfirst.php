<?php

/**
 * Provides string unicode manipulation mb_ucfirst() function
 */

namespace MiniPaviFwk\strings;

function mb_ucfirst($string)
{
    return \mb_strtoupper(\mb_substr($string, 0, 1)) . \mb_substr($string, 1);
}
