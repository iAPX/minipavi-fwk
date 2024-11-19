<?php

/**
 * Handles Display and Command
 */

namespace MiniPaviFwk\controllers\hierarchy;

use MiniPaviFwk\cmd\ZoneSaisieCmd;

class DisplayVideotex extends BaseVideotex
{
    public function ecran(): string
    {
        // Overriden in sub-classes
        return chr(12) . "*** Ecran() absent. ***";
    }

    public function getCmd(): array
    {
        // Default, overridable in sub-classes, to simplify code.
        return ZoneSaisieCmd::createMiniPaviCmd();
    }
}
