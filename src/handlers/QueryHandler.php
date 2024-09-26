<?php

/**
 * Provides basic Query Handling Logic
 *
 * Notice that the optional service Middleware.php (services/{serviceName}/QueryHandler.php) will extend this class
 */

namespace MiniPaviFwk\handlers;

class QueryHandler
{
    public static function queryLogic(): array
    {
        // @TODO reorganize, including commands returned (see index.php)
        // @TODO introduce DIRECTCALL_CMD/RELAY here
        // Maybe use indirection to static::queryHandlingXxxx() for each fctn except fctn keys?

        DEBUG && trigger_error("QueryLogic fctn = " . \MiniPavi\MiniPaviCli::$fctn);
        if (\MiniPavi\MiniPaviCli::$fctn == 'FIN' || \MiniPavi\MiniPaviCli::$fctn == 'FCTN?') {
            static::queryDcx(\MiniPaviFwk\handlers\SessionHandler::class);
            exit;
        }

        // DIRECTCNX, DIRECTCALLFAILED, DIRECTCALLENDED, BGCALL, BGCALL-SIMU
        if (\MiniPavi\MiniPaviCli::$fctn == 'DIRECTCNX') {
            static::queryDirectCnx();
            exit;
        }

        if (\MiniPavi\MiniPaviCli::$fctn == 'DIRECTCALLFAILED') {
            static::queryDirectCallFailed();
            exit;
        }

        if (\MiniPavi\MiniPaviCli::$fctn == 'DIRECTCALLENDED') {
            static::queryDirectCallEnded();
            exit;
        }

        if (\MiniPavi\MiniPaviCli::$fctn == 'BGCALL') {
            static::queryBgCall();
            exit;
        }

        if (\MiniPavi\MiniPaviCli::$fctn == 'BGCALL-SIMU') {
            static::queryBgCallSimu();
            exit;
        }

        if (\MiniPavi\MiniPaviCli::$fctn == 'CNX' || \MiniPavi\MiniPaviCli::$fctn == 'DIRECTCNX') {
            $action = static::queryCnx();
        } elseif (\MiniPavi\MiniPaviCli::$fctn == 'DIRECT') {
            $cmd = static::queryDirect();
            $nextPage = static::getNextPageUrl();
            \MiniPavi\MiniPaviCli::send("", $nextPage, "", true, $cmd, false);
            exit;
        } else {
            $action = static::queryInput();
        }

        $controller = static::getController($action);
        $cmd = static::getCmd($controller);
        $context = static::getControllerContext($controller);
        $output = static::getActionOutput($action);
        $nextPage = static::getNextPageUrl();

        return [$action, $controller, $cmd, $context, $output, $nextPage];
    }

    protected static function queryCnx(): \MiniPaviFwk\actions\Action
    {
        trigger_error("fctn: CNX");

        // Page d'accueil
        DEBUG && trigger_error("Accueil du service");
        return new \MiniPaviFwk\actions\AccueilAction(DEFAULT_CONTROLLER, DEFAULT_XML_FILE, []);
    }

    protected static function queryDcx(string $sessionHandlerClassName): void
    {
        trigger_error("fctn : DCX");
        $sessionHandlerClassName::destroySession();
    }

    protected static function queryDirect(): void
    {
        trigger_error("fctn : DIRECT");
        // Generic support for Direct calls
        if (!empty($_SESSION['DIRECTCALL_RELAY'])) {
            $relay = $_SESSION['DIRECTCALL_RELAY'];
            unset($_SESSION['DIRECTCALL_RELAY']);
            trigger_error("fctn : DIRECT - Relay : " . print_r($relay, true));
            \MiniPavi\MiniPaviCli::send($relay['output'], $relay['nextPage'], "", true, $relay['cmd']);
        }
        exit(0);
    }

    protected static function queryDirectCnx(): void
    {
        // Meant to be overriden when needed!
        trigger_error("fctn : DIRECTCNX - Unsupported by default QueryHandler");
        // Nothing to answer, no further action to take.
        exit(0);
    }

    protected static function queryDirectCallFailed(): void
    {
        // Meant to be overriden when needed!
        trigger_error("fctn : DIRECTCALLFAILED - Unsupported by default QueryHandler");
        // Nothing to answer, no further action to take.
        exit(0);
    }

    protected static function queryDirectCallEnded(): void
    {
        // Meant to be overriden when needed!
        trigger_error("fctn : DIRECTCALLENDED - Unsupported by default QueryHandler");
        // Nothing to answer, no further action to take.
        exit(0);
    }

    protected static function queryBgCall(): void
    {
        // Meant to be overriden when needed!
        trigger_error("fctn : BGCALL - Unsupported by default QueryHandler");
        // Nothing to answer, no further action to take.
        exit(0);
    }

    protected static function queryBgCallSimu(): void
    {
        // Meant to be overriden when needed!
        trigger_error("fctn : BGCALL-SIMU - Unsupported by default QueryHandler");
        // Nothing to answer, no further action to take.
        exit(0);
    }

    protected static function queryInput(): \MiniPaviFwk\actions\Action
    {
        trigger_error("fctn : " . \MiniPavi\MiniPaviCli::$fctn . " - " . \MiniPavi\MiniPaviCli::$content[0]);

        $context = static::getSessionContext();

        $controller = static::getNewController($context);

        list($touche, $message) = static::getToucheAndMessage();

        if (count($message) > 1) {
            // We detect multiline saisie usingg a side-effect: minipavi always send one entry per line
            return static::getControllerMessageAction($controller, $message, $touche);
        }

        // Protect in case of an error from my side with DIRECT call.
        if (count($message) == 0) {
            DEBUG && trigger_error("Empty message ?!?!?");
            $message = [''];
        }
        return static::getControllerSaisieAction($controller, $message[0], $touche);
    }

    protected static function getSessionContext(): array
    {
        DEBUG && trigger_error("Load session context : " . print_r($_SESSION, true));
        return \MiniPaviFwk\handlers\SessionHandler::getContext();
    }

    protected static function getNewController(array $context): \MiniPaviFwk\controllers\VideotexController
    {
        DEBUG && trigger_error("Controller : " . $context['controller']);
        return new ($context['controller'])($context);
    }

    protected static function getToucheAndMessage()
    {
        $touche = \MiniPavi\MiniPaviCli::$fctn;
        $message = @\MiniPavi\MiniPaviCli::$content;
        DEBUG && trigger_error("Touche & saisie/message : " . $touche . " / " . print_r($message, true));
        return [$touche, $message];
    }

    protected static function getControllerMessageAction(
        \MiniPaviFwk\controllers\VideotexController $controller,
        array $message,
        string $touche
    ): \MiniPaviFwk\actions\Action {
        return $controller->getMessageAction($message, $touche);
    }

    protected static function getControllerSaisieAction(
        \MiniPaviFwk\controllers\VideotexController $controller,
        string $saisie,
        string $touche
    ): \MiniPaviFwk\actions\Action {
        return $controller->getSaisieAction($saisie, $touche);
    }

    public static function getController(
        \MiniPaviFwk\actions\Action $action
    ): \MiniPaviFwk\controllers\VideotexController {
        return $action->getController();
    }

    public static function getCmd(\MiniPaviFwk\controllers\VideotexController $controller): array
    {
        return $controller->getCmd();
    }

    public static function getControllerContext(\MiniPaviFwk\controllers\VideotexController $controller): array
    {
        // Here the context, as modified by the new Controller
        return $controller->getContext();
    }

    public static function getActionOutput(\MiniPaviFwk\actions\Action $action): string
    {
        return $action->getOutput();
    }

    public static function getNextPageUrl(): string
    {
        // Url à appeller lors de la prochaine saisie utilisateur (ou sans attendre si directCall=true)
        if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
            $prot = 'https';
        } elseif (
            isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
            strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https'
        ) {
            $prot = 'https';
        } elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
            $prot = 'https';
        } elseif (isset($_SERVER['SERVER_PORT']) && intval($_SERVER['SERVER_PORT']) === 443) {
            $prot = 'https';
        } else {
            $prot = 'http';
        }

        $nextPage = $prot . "://" . $_SERVER['HTTP_HOST'] . "" . $_SERVER['PHP_SELF'];
        DEBUG && trigger_error("Next page URL : " . $nextPage);
        return $nextPage;
    }
}
