<?php

/**
 * Demonstrate output from ecran() on a Videotex Controller (non XML)
 */

namespace service\controllers;

class DemoEcranController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        $videotex
        ->effaceLigne00()
        ->page("demo-controller")
        ->page("demo-choix-sommaire")
        ->ecritUnicodeLigne00("Ligne 00 ...");

        // Démo mode rouleau et retour en mode statique
        $videotex
        ->position(3, 1)->ecritUnicode("Mode rouleau ")
        ->position(1, 1)->modeRouleau();
        for ($i = 0; $i <= 20; $i++) {
            $videotex->ecritVideotex(chr(11) . str_repeat(chr(0), 4));
        }
        $videotex->position(24, 1)->effaceFinDeLigne()->ecritUnicode("Mode statique");
        for ($i = 0; $i <= 20; $i++) {
            $videotex->ecritVideotex(chr(10) . str_repeat(chr(0), 4));
        }
        $videotex->modeStatique();
        //// return $videotex->getOutput();

        $videotex
        ->position(3, 1)->ecritUnicode("Affichage par ecran() et ecritUnicode()")
        ->position(4, 1)->curseurVisible()->ecritUnicode("curseurVisible() ")
        ->position(5, 1)->souligneDebut()->ecritUnicode(" souligneDebut()")
        ->souligneFin()->ecritUnicode(" souligneFin()")
        ->position(6, 1)->texteClignote()->ecritUnicode("texteClignote()")->texteFixe()->ecritUnicode(" texteFixe()")
        ->position(7, 1)->inversionDebut()->ecritUnicode("inversionDebut()")->inversionFin()
        ->ecritUnicode(" inversionFin()")
        ->position(8, 1)->couleurTexte("vert")->couleurFond("rouge")->ecritUnicode("couleurTexte() + couleurFond()")
        ->position(10, 1)->doubleHauteur()->ecritUnicode("doubleHauteur()/")
        ->tailleNormale()->ecritUnicode(" tailleNormale()")
        ->position(10, 16)->ecritUnicode("*")        // Emulator sanity test
        ->position(12, 1)->doubleTaille()->ecritUnicode("doubleTaille()/")
        ->position(12, 29)->doublelargeur()->ecritUnicode("*")        // Emulator sanity test
        ->position(13, 1)->doublelargeur()->ecritUnicode("doublelargeur()/")
        ->position(13, 31)->ecritUnicode("*")        // Emulator sanity test
        ->position(14, 1)->texteClignote()->ecritUnicode("texteClignote()")->texteFixe()->ecritUnicode(" texteFixe()")

        // Animation to demonstrate End of Line deletion
        ->position(15, 1)->ecritUnicode("effaceFinDeLigne()oihfeoihfihfiuw")
        ->position(16, 1)->couleurTexte("noir")->ecritUnicode("feqhfiuhnfiuhbiqhbibhfiqwbhfqwiubfqwibqwfifquwibfqw")
        ->position(16, 1)->couleurTexte("noir")->ecritUnicode("feqhfiuhnfiuhbiqhbibhfiqwbhfqwiubfqwibqwfifquwibfqw")
        ->position(16, 1)->couleurTexte("noir")->ecritUnicode("feqhfiuhnfiuhbiqhbibhfiqwbhfqwiubfqwibqwfifquwibfqw")
        ->position(16, 1)->couleurTexte("noir")->ecritUnicode("feqhfiuhnfiuhbiqhbibhfiqwbhfqwiubfqwibqwfifquwibfqw")
        ->position(15, 19)->effacefindeligne()

        ->position(16, 1)->ecritUnicode("modeGraphique() ")->modeGraphique()->ecritUnicode("feifwevw")->modeTexte()
        ->ecritUnicode(" modeTexte()")
        ->position(17, 1)->ecritUnicode("afficheDateParis() : ")->afficheDateParis()
        ->position(18, 1)->ecritUnicode("afficheHeureParis() : ")->afficheHeureParis()
        ->position(19, 1)->ecritUnicode("repeteCaractere()")->repeteCaractere(".", 63)->repeteCaractere("", 63)
        ->position(19, 21)->ecritUnicode("rectangle() : ")->afficheRectangleInverse(20, 21, 16, 3, "magenta")

        // Name of the Controller, demo or ecritUnicodeCentre()
        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->ecritUnicode(" ")
        ->ecritUnicodeCentre(23, end(explode('\\', $this::class)), "\x1B\x40")

        // Code PIN for the WebMedia from another device
        ->position(24, 1)->ecritUnicode("WebMedia PIN : ")->inversionDebut()->ecritPIN()->inversionFin()

        ->getOutput();
        return $videotex->getOutput();
    }

    public function choixSommaire(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-sommaire');
    }

    public function choixRetour(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-sommaire');
    }
}
