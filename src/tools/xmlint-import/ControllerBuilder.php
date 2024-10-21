<?php

/**
 * Controller Builder class
 */

class ControllerBuilder
{
    public string $classname = '';
    public string $xml_page = '';
    public string $ecran = '';
    public string $validation = '';
    public string $cmd = '';
    public string $choix = '';


    public function __construct(string $classname, string $xml_page)
    {
        $this->classname = $classname;
        $this->xml_page = $xml_page;
    }

    public function createEcran(string $ecran): void
    {
        $this->ecran = $ecran;
    }

    public function createValidation(string $validation_code): void
    {
        $this->validation = $validation_code;
    }

    public function createCmd(string $cmd_code): void
    {
        $this->cmd = $cmd_code;
    }

    public function createChoix(string $choix_code): void
    {
        $this->choix = $choix_code;
    }

    public function buildController(): string
    {
        $code = <<<EOF
<?php

/**
 * Handle the $this->xml_page XML Page
 */

namespace service\controllers;

class $this->classname extends \MiniPaviFwk\controllers\VideotexController
{
$this->ecran
$this->validation
$this->cmd
$this->choix
}

EOF;

        return $code;
    }
}
