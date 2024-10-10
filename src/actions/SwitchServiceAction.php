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
use MiniPaviFwk\handlers\QueryHandler;
use MiniPaviFwk\handlers\ServiceHandler;
use MiniPaviFwk\handlers\SessionHandler;

class SwitchServiceAction extends Action
{
    public function __construct(
        string $newServiceName,
        string $output = "",
        int $waitSeconds = 0
    ) {
        trigger_error("Action: Switch to service - " . $newServiceName, E_USER_NOTICE);

        // Wait time in seconds translated to \00 output!
        $waitOutput = !empty($output) ? str_repeat("\00", $waitSeconds * 120) : '';

        // Reinit the Session, protect against data leakage
        $session_handler = ConstantHelper::getConstValueByName(
            'SESSION_HANDLER_CLASSNAME',
            SessionHandler::class
        );
        $session_handler::destroySession();
        $session_handler::startSession();
        $_SESSION['context'] = [];

        // Switch service
        $service_handler = ConstantHelper::getConstValueByName(
            'SERVICE_HANDLER_CLASSNAME',
            ServiceHandler::class
        );
        $service_handler::setServiceName($newServiceName);

        // Act now and quit directly!
        $query_handler = $service_handler::getQueryHandler();
        $newUrl = $query_handler::getNextPageUrl();

        MiniPaviCli::send($output . $waitOutput, $newUrl, '', true, null, 'yes-cnx');
        exit(0);
    }
}
