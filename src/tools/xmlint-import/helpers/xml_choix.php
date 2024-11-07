<?php

/**
 * Import Choix from XML
 */

function xml_choix(ControllerBuilder $controller, \SimpleXMLElement $xml_choix): void
{
    // CCheck the saisies
    $is_zonesaisie = true;
    foreach ($xml_choix->saisie as $element) {
        if (isset($element['email'])) {
            $is_zonesaisie = false;
            break;
        }
    }

    if ($is_zonesaisie) {
        // ZoneSaisie
        $code_choix = "";
        foreach ($xml_choix->saisie as $element) {
            $choix = (string) $element['choix'];
            $touche = (string) $element['touche'];
            $suivant = (string) $element['suivant'];
            $email = (string) $element['email'];

            // Format the names
            $formatted_saisie = ucfirst(mb_strtolower($choix));
            $cleaned_saisie = str_replace(['*', '#'], ['ETOILE', 'DIESE'], $formatted_saisie);
            $formatted_touche = ucfirst(mb_strtolower($touche));

            $method_name = 'choix' . $cleaned_saisie . $formatted_touche;

            // Convert to Code
            $code_choix .= <<<EOF
\n
    public function $method_name(): ?\\MiniPaviFwk\\actions\\Action
    {
        // Handle '$choix' + [$formatted_touche]
        return new \\MiniPaviFwk\\actions\\PageAction(\$this->context, "$suivant");
    }
EOF;
        }
    } else {
        // ZoneMessage with email case
        $code_choix = "";
        foreach ($xml_choix->saisie as $element) {
            $choix = (string) $element['choix'];
            $touche = strtoupper((string) $element['touche']);
            $suivant = (string) $element['suivant'];
            $email = (string) $element['email'];

            // Convert to partial code
            $clean_choix = str_replace(['"', '\\'], ['\"', '\\\\'], $choix);
            if (! $email) {
                $code_choix .= <<<EOF
\n
        if (\$touche === "$touche") {
            // Handle [$touche]
            return new \MiniPaviFwk\actions\PageAction(\$this->context, "$suivant");
        }
EOF;
            } else {
                $sujet = (string) $element['sujet'];
                $msgok = (string) $element['msgok'];
                $clean_email = str_replace(['"', '\\'], ['\"', '\\\\'], $email);
                $clean_sujet = str_replace(['"', '\\'], ['\"', '\\\\'], $sujet);
                $clean_mgsok = str_replace(['"', '\\'], ['\"', '\\\\'], $msgok);

                $code_choix .= <<<EOF
\n
        if (\$touche === "$touche") {
            // Handle message + [$touche]
            // Send the email (basic implementation without "from" header), that won't work!
            \$message_string = implode("\\n", \$message);
            trigger_error("Complétez le code du contrôleur " . __CLASS__ . " pour envoyer un email", E_USER_WARNING);
            // mail("$clean_email", "$clean_sujet", \$message_string);

            // Use our DirectCall to display the ligne 00 message
            \$_SESSION["DIRECTCALL_CMD"] = \\MiniPaviFwk\\cmd\\PushServiceMsgCmd::createMiniPaviCmd(
                [\\MiniPavi\\MiniPaviCli::\$uniqueId],
                [\\MiniPavi\\MiniPaviCli::toG2("$clean_mgsok")]
            );
        
            // Eventually go to the next page
            return new \MiniPaviFwk\actions\PageAction(\$this->context, "$suivant");
        }

EOF;
            }
        }

    // Wrap the code into a message() function
        $code_choix = <<<EOF
\n
    public function message(string \$touche, array \$message): ?\\MiniPaviFwk\\actions\\Action
    {
$code_choix
        return null;
    }
EOF;
    }

    // Adds an error message in nonPropose() if specified;
    $error_message = (string) $xml_choix['defaut'];
    if (!empty($error_message)) {
        $clean_error_message = str_replace(['"', '\\'], ['\"', '\\\\'], $error_message);
        $code_choix .= <<<EOF
\n
    public function nonPropose(string \$touche, string \$saisie): ?\\MiniPaviFwk\\actions\\Action
    {
        // Error message if choice is incorrect.
        return new \\MiniPaviFwk\\actions\\Ligne00Action(\$this, "$clean_error_message");
    }
EOF;
    }

    $controller->createChoix($code_choix);
}
