<?php

/**
 * Import Validation from XML
 */

function xml_validation(ControllerBuilder $controller, \SimpleXMLElement $xml_entree): void
{
    // get Touches
    $touches = [];
    foreach($xml_entree->validation as $element) {
        $touches[] = (string) $element['touche'];
    }
    if (count($touches) == 0) {
        // No touche, no code
        return;
    }

    // Convert to Code
    $touches_array = "'" . implode("', '", $touches) . "'";
    $touches_text = "[" . implode("], [", $touches) . "]";
    $code = <<<EOF
\n
    public function validation(): \MiniPaviFwk\Validation
    {
        // Allow $touches_text keys
        // Others could be added by VideotexController through introspection,
        // such as discovering touche*() or choix**() methods
        \$validation = parent::validation();
        \$validation->addValidKeys([$touches_array]);
        return \$validation;
    }
EOF;

    // Store into the controllerBuilder
    $controller->createValidation($code);
}
