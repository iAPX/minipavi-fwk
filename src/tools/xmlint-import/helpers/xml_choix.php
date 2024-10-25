<?php

/**
 * Import Choix from XML
 */

function xml_choix(ControllerBuilder $controller, \SimpleXMLElement $xml_choix): void
{
    $code_choix = "";
    foreach($xml_choix->saisie as $element) {
        $choix = (string) $element['choix'];
        $touche = (string) $element['touche'];
        $suivant = (string) $element['suivant'];
        $email = (string) $element['email'];

        // Format the names
        $formatted_saisie = ucfirst(mb_strtolower($choix));
        $cleaned_saisie = str_replace(['*', '#'], ['ETOILE', 'DIESE'], $formatted_saisie);
        $formatted_touche = ucfirst(mb_strtolower($touche));

        $method_name = 'choix' . $cleaned_saisie . $formatted_touche;
        $controller_suivant = pagename_to_controllername($suivant);

        if ($email) {
            $code_choix .= <<<EOF
\n
        // Saisie with email send to $email is not supported
EOF;
        } else {
            // Convert to Code
            $code_choix .= <<<EOF
\n
    public function $method_name(): ?\MiniPaviFwk\actions\Action
    {
        // Handle '$choix' + [$formatted_touche]
        return new \MiniPaviFwk\actions\ControllerAction(
            \service\controllers\\{$controller_suivant}::class,
            \$this->context
        );
    }
EOF;
        }
    }

    // Adds an error message in nonPropose();
    $error_message = (string) $xml_choix['defaut'];
    if (!empty($error_message)) {
        $clean_error_message = str_replace(['"', '\\'], ['\"', '\\\\'], $error_message);
        $code_choix .= <<<EOF
\n
    public function nonPropose(): ?\MiniPaviFwk\actions\Action
    {
        // Error message if choice is incorrect.
        return new \MiniPaviFwk\actions\Ligne00Action(\$this, "$clean_error_message");
    }
EOF;
    }

    $controller->createChoix($code_choix);
}
