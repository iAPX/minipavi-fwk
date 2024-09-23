<?php

/**
 * Action to redirect to another service by it's URL
 *
 * Enable to have an output and a wait time in seconds before displaying the next service homepage.
 * The wait time is in seconds, and is only active if output is not empty.
 */

namespace MiniPaviFwk\actions;

class SwitchServiceAction extends Action
{
    public function __construct(
        string $newServiceName,
        string $output = "",
        int $waitSeconds = 0
    ) {
        DEBUG && trigger_error("Action: Switch to service - " . $newServiceName);

        // Wait time in seconds translated to \00 output!
        $waitOutput = !empty($output) ? str_repeat("\00", $waitSeconds * 120) : '';

        // Reinit the Session, protect against data leakage
        \MiniPaviFwk\handlers\SessionHandler::destroySession();
        \MiniPaviFwk\handlers\SessionHandler::startSession();
        $_SESSION['service'] = $newServiceName;
        $_SESSION['context'] = [];

        // Act now and quit directly!
        $newUrl = \MiniPaviFwk\handlers\QueryHandler::getNextPageUrl();

        \MiniPavi\MiniPaviCli::send($output . $waitOutput, $newUrl, '', true, null, 'yes-cnx');
        exit(0);
    }
}
