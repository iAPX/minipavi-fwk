<?php

/**
 * Demonstrate output from ecran() on a Videotex Controller (non XML)
 */

namespace service\controllers;

class DemoEcranCodeController extends \MiniPaviFwk\controllers\VideotexController
{
    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        // Redo the same demo as in demoxml-ecran page in demo.xml
        $vdt = $videotex

        // Simulate Videotex page loading as in <affiche><url>
        ->effaceLigne00()
        ->ecritVideotex(file_get_contents(SERVICE_DIR . "vdt/demo-controller-page.vdt"))
        ->ecritVideotex(file_get_contents(SERVICE_DIR . "vdt/demo-choix-code.vdt"))

        ->position(3, 1)->ecritUnicode("Affichage par ecran() et ecritUnicode()")
        ->position(4, 1)->curseurVisible()->ecritUnicode("curseurVisible() ")
        ->position(5, 1)->souligneDebut()->ecritUnicode(" souligneDebut()")
        ->souligneFin()->ecritUnicode(" souligneFin()")
        ->position(6, 1)->texteClignote()->ecritUnicode("texteClignote()")->texteFixe()->ecritUnicode(" texteFixe()")
        ->position(7, 1)->inversionDebut()->ecritUnicode("inversionDebut()")->inversionFin()
        ->ecritUnicode(" inversionFin()")
        ->position(8, 1)->couleurTexte("vert")->couleurFond("rouge")->ecritUnicode("couleurTexte() + couleurFond()")
        ->position(10, 1)->doubleHauteur()->ecritUnicode("doubleHauteur() ")->tailleNormale()
        ->ecritUnicode("tailleNormale()")
        ->position(12, 1)->doubleTaille()->ecritUnicode("doubleTaille()")
        ->position(13, 1)->doublelargeur()->ecritUnicode("doublelargeur()")
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
        ->position(18, 21)->ecritUnicode("rectangle() : ")->afficheRectangleInverse(19, 21, 16, 4, "magenta")

        // Name of the Controller
        ->position(23, 1)->effaceFinDeLigne()->couleurFond("vert")->couleurTexte('noir')
        ->ecritUnicode(" " . end(explode('\\', $this::class)))

        ->getOutput();
        return $vdt;
    }

    public function toucheSommaire(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, "demoxml-sommaire", "demo");
    }

    public function toucheRetour(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, "demoxml-ecran-code", "demo");
    }
}
