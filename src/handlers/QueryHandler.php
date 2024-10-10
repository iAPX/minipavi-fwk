<?php

/**
 * Provides basic Query Handling Logic
 *
 * Notice that the optional service Middleware.php (services/{serviceName}/QueryHandler.php) will extend this class
 */

namespace MiniPaviFwk\handlers;

use MiniPavi\MiniPaviCli;
use MiniPaviFwk\actions\Action;
use MiniPaviFwk\actions\AccueilAction;
use MiniPaviFwk\controllers\VideotexController;
use MiniPaviFwk\handlers\SessionHandler;
use MiniPaviFwk\helpers\ConstantHelper;

class QueryHandler
{
    public static function queryLogic(): array
    {
        // Handles Minitel Type
        $dcrs_minitel_types_prefixes = ['Cu', 'Bu', 'Cw', 'Cv', 'Bv', 'Cz', 'Bz', 'Ay', 'Em'];
        if (in_array(substr(MiniPaviCli::$versionMinitel, 0, 2), $dcrs_minitel_types_prefixes)) {
            $_SESSION['is_drcs'] = true;
        }

        trigger_error("QueryLogic fctn = " . MiniPaviCli::$fctn, E_USER_NOTICE);
        if (MiniPaviCli::$fctn == 'FIN' || MiniPaviCli::$fctn == 'FCTN?') {
            static::queryDcx();
            exit;
        }

        // DIRECTCALLFAILED, DIRECTCALLENDED, BGCALL, BGCALL-SIMU
        if (MiniPaviCli::$fctn == 'DIRECTCALLFAILED') {
            static::queryDirectCallFailed();
            exit;
        }

        if (MiniPaviCli::$fctn == 'DIRECTCALLENDED') {
            static::queryDirectCallEnded();
            exit;
        }

        if (MiniPaviCli::$fctn == 'BGCALL') {
            static::queryBgCall();
            exit;
        }

        if (MiniPaviCli::$fctn == 'BGCALL-SIMU') {
            static::queryBgCallSimu();
            exit;
        }

        if (MiniPaviCli::$fctn == 'CNX' || MiniPaviCli::$fctn == 'DIRECTCNX') {
            $action = static::queryCnx();
        } elseif (MiniPaviCli::$fctn == 'DIRECT') {
            $cmd = static::queryDirect();
            $nextPage = static::getNextPageUrl();
            MiniPaviCli::send("", $nextPage, "", true, $cmd, false);
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

    public static function directCall(
        Action $action,
        VideotexController $controller,
        array $cmd,
        array $context,
        string $output,
        string $nextPage
    ): array {
        if (!empty($_SESSION['DIRECTCALL_CMD'])) {
            trigger_error("DIRECTCALL_CMD : " . print_r($_SESSION['DIRECTCALL_CMD'], true), E_USER_NOTICE);
            $_SESSION['DIRECTCALL_RELAY'] = [
                'output' => $output,
                'nextPage' => $nextPage,
                'cmd' => $cmd,
            ];
            trigger_error("DIRECTCALL_CMD-Relay créé:" . print_r($_SESSION['DIRECTCALL_RELAY'], true), E_USER_NOTICE);

            $output = "";
            $nextPage = "";
            $cmd = $_SESSION['DIRECTCALL_CMD'];
            unset($_SESSION['DIRECTCALL_CMD']);
            $directCall = 'yes';
        } else {
            $directCall = false;
        }

        return [$action, $controller, $cmd, $context, $output, $nextPage, $directCall];
    }

    protected static function queryCnx(): Action
    {
        trigger_error("fctn: CNX");

        // Page d'accueil
        trigger_error("Accueil du service", E_USER_NOTICE);
        return new AccueilAction(\service\DEFAULT_CONTROLLER, \service\DEFAULT_XML_FILE, []);
    }

    protected static function queryDcx(): void
    {
        trigger_error("fctn : DCX");
        $session_handler = ConstantHelper::getConstValueByName(
            'SESSION_HANDLER_CLASSNAME',
            SessionHandler::class
        );
        $session_handler::destroySession();
    }

    protected static function queryDirect(): void
    {
        trigger_error("fctn : DIRECT");

        // Generic support for Direct calls
        if (!empty($_SESSION['DIRECTCALL_RELAY'])) {
            $relay = $_SESSION['DIRECTCALL_RELAY'];
            unset($_SESSION['DIRECTCALL_RELAY']);
            trigger_error("fctn : DIRECT - Relay : " . print_r($relay, true), E_USER_NOTICE);
            MiniPaviCli::send($relay['output'], $relay['nextPage'], "", true, $relay['cmd']);
        }
        exit(0);
    }

    protected static function queryDirectCallFailed(): void
    {
        // Meant to be overriden when needed!
        trigger_error("fctn : DIRECTCALLFAILED - Unsupported by default QueryHandler", E_USER_WARNING);
        // Nothing to answer, no further action to take.
        exit(0);
    }

    protected static function queryDirectCallEnded(): void
    {
        // Meant to be overriden when needed!
        trigger_error("fctn : DIRECTCALLENDED - Unsupported by default QueryHandler", E_USER_WARNING);
        // Nothing to answer, no further action to take.
        exit(0);
    }

    protected static function queryBgCall(): void
    {
        // Meant to be overriden when needed!
        trigger_error("fctn : BGCALL - Unsupported by default QueryHandler", E_USER_WARNING);
        // Nothing to answer, no further action to take.
        exit(0);
    }

    protected static function queryBgCallSimu(): void
    {
        // Meant to be overriden when needed!
        trigger_error("fctn : BGCALL-SIMU - Unsupported by default QueryHandler", E_USER_WARNING);
        // Nothing to answer, no further action to take.
        exit(0);
    }

    protected static function queryInput(): Action
    {
        trigger_error("fctn : " . MiniPaviCli::$fctn . " - " . MiniPaviCli::$content[0]);

        $context = static::getSessionContext();

        $controller = static::getNewController($context);

        list($touche, $message) = static::getToucheAndMessage();

        if (count($message) > 1) {
            // We detect multiline saisie usingg a side-effect: minipavi always send one entry per line
            return static::getControllerMessageAction($controller, $message, $touche);
        }

        // Protect in case of an error from my side with DIRECT call.
        if (count($message) == 0) {
            trigger_error("Empty message ?!?!?", E_USER_WARNING);
            $message = [''];
        }
        return static::getControllerSaisieAction($controller, $message[0], $touche);
    }

    protected static function getSessionContext(): array
    {
        trigger_error("Load session context : " . print_r($_SESSION, true), E_USER_NOTICE);
        $session_handler = ConstantHelper::getConstValueByName(
            'SESSION_HANDLER_CLASSNAME',
            SessionHandler::class
        );
        return $session_handler::getContext();
    }

    protected static function getNewController(array $context): VideotexController
    {
        trigger_error("Controller : " . $context['controller'], E_USER_NOTICE);
        return new ($context['controller'])($context);
    }

    protected static function getToucheAndMessage()
    {
        $touche = MiniPaviCli::$fctn;
        $message = @MiniPaviCli::$content;
        trigger_error("Touche & saisie/message : " . $touche . " / " . print_r($message, true), E_USER_NOTICE);
        return [$touche, $message];
    }

    protected static function getControllerMessageAction(
        VideotexController $controller,
        array $message,
        string $touche
    ): Action {
        return $controller->getMessageAction($message, $touche);
    }

    protected static function getControllerSaisieAction(
        VideotexController $controller,
        string $saisie,
        string $touche
    ): Action {
        return $controller->getSaisieAction($saisie, $touche);
    }

    public static function getController(
        Action $action
    ): VideotexController {
        return $action->getController();
    }

    public static function getCmd(VideotexController $controller): array
    {
        return $controller->getCmd();
    }

    public static function getControllerContext(VideotexController $controller): array
    {
        // Here the context, as modified by the new Controller
        return $controller->getContext();
    }

    public static function getActionOutput(Action $action): string
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
        trigger_error("Next page URL : " . $nextPage, E_USER_NOTICE);
        return $nextPage;
    }
}
