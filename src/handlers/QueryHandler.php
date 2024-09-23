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
        if (\MiniPavi\MiniPaviCli::$fctn == 'FIN' || \MiniPavi\MiniPaviCli::$fctn == 'FCTN?') {
            $action = self::queryDcx(\MiniPaviFwk\handlers\SessionHandler::class);
            exit;
        }

        if (\MiniPavi\MiniPaviCli::$fctn == 'CNX' || \MiniPavi\MiniPaviCli::$fctn == 'DIRECTCNX') {
            $action = self::queryCnx();
        } else {
            $action = self::queryInput();
        }

        $controller = self::getController($action);
        $cmd = self::getCmd($controller);
        $context = self::getControllerContext($controller);
        $output = self::getActionOutput($action);
        $nextPage = self::getNextPageUrl();

        return [$action, $controller, $cmd, $context, $output, $nextPage];
    }

    public static function queryCnx(): \MiniPaviFwk\actions\Action
    {
        trigger_error("fctn: CNX");

        // Page d'accueil
        DEBUG && trigger_error("Accueil du service");
        return new \MiniPaviFwk\actions\AccueilAction(DEFAULT_CONTROLLER, DEFAULT_XML_FILE, []);
    }

    public static function queryDcx(string $sessionHandlerClassName): \MiniPaviFwk\actions\Action
    {
        trigger_error("fctn : DCX");
        $sessionHandlerClassName::destroySession();
        // Nothing to answer, no further action to take.
        exit(0);
    }

    public static function queryInput(): \MiniPaviFwk\actions\Action
    {
        DEBUG && trigger_error("fctn : " . \MiniPavi\MiniPaviCli::$fctn . " - " . \MiniPavi\MiniPaviCli::$content[0]);

        $context = self::getSessionContext();

        $controller = self::getNewController($context);

        list($touche, $message) = self::getToucheAndMessage();

        if (count($message) > 1) {
            // We detect multiline saisie usingg a side-effect: minipavi always send one entry per line
            return self::getControllerMessageAction($controller, $message, $touche);
        }
        return self::getControllerSaisieAction($controller, $message[0], $touche);
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
