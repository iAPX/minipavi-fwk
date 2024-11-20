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
        parent::__construct($context['image_page'], 5, $context, $params);
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
        ->ecritUnicode("Page " . $this->multipage_page_num . "/" . $this->multipage_nb_pages)
        ->position(4, 1);

        // Output the image
        $gdImage = imagecreatefromjpeg(\SERVICE_DIR . '/datas/example' . $this->context['image_page'] . '.jpg');
        list($videotex_image, $lignes, $colonnes) =
            \MiniPaviFwk\helpers\ImageHelper::imageToAlphamosaic($gdImage, 20, 40);
        $videotex->ecritVideotex($videotex_image);

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
}
