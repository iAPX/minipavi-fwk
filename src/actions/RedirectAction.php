<?php

/**
 * Action to redirect to another service by it's URL
 *
 * Enable to have an output and a wait time in seconds before displaying the next service homepage.
 * The wait time is in seconds, and is only active if output is not empty.
 */

namespace MiniPaviFwk\actions;

use MiniPavi\MiniPaviCli;
use MiniPaviFwk\helpers\ConstantHelper;
use MiniPaviFwk\handlers\SessionHandler;

class RedirectAction extends Action
{
    public function __construct(string $newUrl, string $videotexOutput = "", int $waitSeconds = 0)
    {
        trigger_error("Action: Redirect - " . $newUrl, E_USER_NOTICE);
        $this->controller = null;
        $this->output = "";

        // Reinit the Session, protect against data leakage, enable local Redirect too.
        $session_handler = ConstantHelper::getConstValueByName(
            'SESSION_HANDLER_CLASSNAME',
            SessionHandler::class
        );
        $session_handler::destroySession();
        $session_handler::startSession();
        $_SESSION['context'] = [];

        // Wait time in seconds translated to \00 output!
        $waitOutput = !empty($videotexOutput) ? str_repeat("\00", $waitSeconds * 120) : '';

        // Act now and quit directly!
        MiniPaviCli::send($videotexOutput . $waitOutput, $newUrl, '', true, null, 'yes-cnx');
    }
}
