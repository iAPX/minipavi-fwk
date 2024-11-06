<?php

/**
 * Import Validation from XML
 */

function xml_validation(ControllerBuilder $controller, \SimpleXMLElement $xml_entree): void
{
    // get Touches
    $touches = [];
    foreach ($xml_entree->validation as $element) {
        $touches[strtoupper((string) $element['touche'])] = (string) $element['touche'];
    }

    // Convert to Code
    $validation_helper = "\\MiniPaviFwk\\helpers\\ValidationHelper::";
    if (count($touches) === 0) {
        $touches_code = $validation_helper . "ALL";
    } else {
        $touches_code = $validation_helper . implode(" | $validation_helper", array_keys($touches));
    }

    $code = <<<EOF
\n
    public function validation(): int
    {
        return $touches_code;
    }
EOF;

    // Store into the controllerBuilder
    $controller->createValidation($code);
}
