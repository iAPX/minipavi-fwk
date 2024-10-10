<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\helpers\VideotexHelper;


class VideotexHelperTest extends TestCase
{
    public function test()
    {
        $videotex = new VideotexHelper();

        $this->assertEquals($videotex, $videotex->position(1, 2));
        $this->assertEquals($videotex, $videotex->curseurVisible());
        $this->assertEquals($videotex, $videotex->curseurInvisible());
        $this->assertEquals($videotex, $videotex->texteClignote());
        $this->assertEquals($videotex, $videotex->texteFixe());
        $this->assertEquals($videotex, $videotex->souligneDebut());
        $this->assertEquals($videotex, $videotex->souligneFin());
        $this->assertEquals($videotex, $videotex->inversionDebut());
        $this->assertEquals($videotex, $videotex->inversionFin());
        $this->assertEquals($videotex, $videotex->ecritUnicode('Accentué'));
        $this->assertEquals($videotex, $videotex->ecritVideotex('Videotex'));
        $this->assertEquals($videotex, $videotex->couleurTexte('rouge'));
        $this->assertEquals($videotex, $videotex->couleurFond('bleu'));
        $this->assertEquals($videotex, $videotex->doubleTaille());
        $this->assertEquals($videotex, $videotex->doubleHauteur());
        $this->assertEquals($videotex, $videotex->doubleLargeur());
        $this->assertEquals($videotex, $videotex->tailleNormale());
        $this->assertEquals($videotex, $videotex->effaceFinDeLigne());
        $this->assertEquals($videotex, $videotex->modeGraphique());
        $this->assertEquals($videotex, $videotex->modeTexte());
        $this->assertEquals($videotex, $videotex->effaceEcran());
        $this->assertEquals($videotex, $videotex->afficheDateParis());
        $this->assertEquals($videotex, $videotex->afficheHeureParis());
        $this->assertEquals($videotex, $videotex->repeteCaractere('a', 3));
        $this->assertEquals($videotex, $videotex->afficheRectangleInverse(1, 2, 3, 4, 'vert'));
    }
}
