<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\helpers\FormatHelper;

class FormatHelperTest extends TestCase
{
    public function testTitle()
    {
        $result = FormatHelper::formatTitle("Titre court", 5, 3, retrait: 3, couleur: 'jaune');
        $this->assertEquals("\x1F\x45\x44\x1B\x43Titre court", $result);

        $result = FormatHelper::formatTitle("A droite", 5, 3, alignement: FormatHelper::ALIGN_RIGHT, margeDroite: 2);
        $this->assertEquals("\x1F\x45\x5FA droite", $result);

        $result = FormatHelper::formatTitle(
            "Titre centré sur 3 lignes en DT",
            5,
            3,
            retrait: 3,
            margeDroite: 7,
            couleur: 'jaune',
            alignement: FormatHelper::ALIGN_CENTER,
            attributes: FormatHelper::ATTRIBUTE_DOUBLE_TAILLE
        );
        $expected = "\x1F\x45\x44\x1B\x43\x1B\x4FTitre centr\x19\x42\x65" . "\x1F\x47\x41\x1B\x43\x1B\x4Fsur 3 lignes en" . "\x1F\x49\x4E\x1B\x43\x1B\x4fDT";
        $this->assertEquals($expected, $result);
    }
}
