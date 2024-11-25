<?php

/**
 * Demo for the ImageHelper
 *
 * - Images aalphamosaïques
 */

namespace service\controllers;

class DemoImageHelperController extends \MiniPaviFwk\controllers\MultipageController
{
    public function __construct(array $context, array $params = [])
    {
        parent::__construct($context['image_page'], 8, $context, $params);
    }

    public function multipageSavePageNumber(int $page_num): void
    {
        $this->context['image_page'] = $page_num;
    }

    public function ecran(): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();
        $videotex
        ->page("demo-controller")
        ->position(3, 31)
        ->ecritUnicode("Page " . $this->multipage_page_num . "/" . $this->multipage_nb_pages);

        $videotex->ecritVideotex(
            $this->displayDemoImage($this->multipage_page_num - 1, $this->multipage_page_num <= 4)
        );

        $videotex->position(24, 8)->inversionDebut()->ecritUnicode(" SUITE | RETOUR | SOMMAIRE ")->inversionFin();
        return $videotex->getOutput();
    }

    public function getCmd(): array
    {
        return \MiniPaviFwk\cmd\ZoneSaisieCmd::createMiniPaviCmd(null, 24, 40, 1, false);
    }

    public function choixSommaire(): ?\MiniPaviFwk\actions\Action
    {
        return new \MiniPaviFwk\actions\PageAction($this->context, 'demo-sommaire');
    }

    private function displayDemoImage(int $image_num, bool $relative): string
    {
        $videotex = new \MiniPaviFwk\helpers\VideotexHelper();

        // Create the images, first bath in relative position then centered, second in absolute position
        $gdImage = imagecreatefromjpeg(\SERVICE_DIR . '/images/example' . ($image_num % 4) . '.jpg');
        if ($relative) {
            // 4 firsts created in relative position and centered
            list($videotex_image, $lignes, $cols) =
                \MiniPaviFwk\helpers\ImageHelper::imageToAlphamosaic($gdImage, 20, 40);
            $videotex->position(3, 1)->ecritUnicode("$lignes lignes x $cols cols");

            // Center the image for output!
            $ligne = 4 + floor((20 - $lignes) / 2.0);
            $col = 1 + floor((40 - $cols) / 2.0);
            $videotex
            ->position($ligne, $col);
        } else {
            // 4 lasts in absolute position
            list($videotex_image, $lignes, $cols) =
                \MiniPaviFwk\helpers\ImageHelper::imageToAlphamosaic($gdImage, 16, 32, false, 4, 3);
            $videotex->position(3, 1)->ecritUnicode("$lignes lignes x $cols cols");
        }
        $videotex->ecritVideotex($videotex_image);

        return $videotex->getOutput();
    }
}
