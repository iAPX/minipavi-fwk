<?php

/**
 * Entry point for the MiniPavi Web Server
 */

require_once "vendor/autoload.php";
require_once "service/config.php";
require_once "src/strings/mb_ucfirst.php";

// Autoload
spl_autoload_register(function ($class) {
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    DEBUG && trigger_error("autoload : class $class on file $file");
    if (file_exists($file)) {
        require $file;
        return true;
    }
    return false;
});

// Disable session cookies, session is identified through minipavi's uniqueId
ini_set('session.use_cookies', '0');
ini_set('session.use_only_cookies', '0');

error_reporting(E_USER_NOTICE | E_USER_WARNING);
ini_set('display_errors', 0);

try {
    $vdt = '';        // Contenu à envoyer au Minitel de l'utilisateur
    $cmd = null;      // Commande à executer au niveau de MiniPAVI
    $directCall = false;

    MiniPavi\MiniPaviCli::start();
    session_id(MiniPavi\MiniPaviCli::$uniqueId);
    session_start();

    // Fin du sketch!
    if (MiniPavi\MiniPaviCli::$fctn == 'FIN' || \MiniPavi\MiniPaviCli::$fctn == 'FCTN?') {
        // Deconnexion
        trigger_error("DCX");
        session_destroy();
        exit;
    }

    $vdt = '';
    if (MiniPavi\MiniPaviCli::$fctn == 'CNX' || \MiniPavi\MiniPaviCli::$fctn == 'DIRECTCNX') {
        trigger_error("CNX");

        $context = [];

        // Page d'accueil
        DEBUG && trigger_error("Accueil du service");
        $vdt = \MiniPavi\MiniPaviCli::clearScreen() . PRO_MIN . PRO_LOCALECHO_OFF;
        $action = new \MiniPaviFwk\actions\AccueilAction(DEFAULT_CONTROLLER, DEFAULT_XML_FILE, $context);
    } else {
        DEBUG && trigger_error("CMD : " . \MiniPavi\MiniPaviCli::$fctn . " - " . \MiniPavi\MiniPaviCli::$content[0]);

        $context = $_SESSION['context'];
        DEBUG && trigger_error("Load session context : " . print_r($_SESSION, true));

        DEBUG && trigger_error("Controller : " . $context['controller']);
        $controller = new ($context['controller'])($context);

        $touche = \MiniPavi\MiniPaviCli::$fctn;
        $saisie = @\MiniPavi\MiniPaviCli::$content[0];
        DEBUG && trigger_error("Touche & saisie : " . $touche . " / \"" . $saisie . "\"");

        $action = $controller->getAction($saisie, $touche);
    }

    $controller = $action->getController();
    $zonesaisie = $controller->zonesaisie();
    $validation = $controller->validation();
    $vdt .= $action->getOutput();

    // @TODO zonesaisie should build it, and handle both InputText and InputMultiline gracefully
    $cmd = MiniPavi\MiniPaviCli::createInputTxtCmd(
        $zonesaisie->col,
        $zonesaisie->ligne,
        $zonesaisie->longueur,
        $validation->getKeyMask(),
        $zonesaisie->curseur,
        $zonesaisie->spaceChar,
        $zonesaisie->replacementChar,
        $zonesaisie->prefill,
    );

    // Send back the context, it's unsafe!
    $context = $controller->getContext();
    $_SESSION['context'] = $context;
    DEBUG && trigger_error("Save context : " . print_r($context, true));


    // Url à appeller lors de la prochaine saisie utilisateur (ou sans attendre si directCall=true)
    if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
        $prot = 'https';
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
        $prot = 'https';
    } elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
        $prot = 'https';
    } elseif (isset($_SERVER['SERVER_PORT']) && intval($_SERVER['SERVER_PORT']) === 443) {
        $prot = 'https';
    } else {
        $prot = 'http';
    }

    $nextPage = $prot . "://" . $_SERVER['HTTP_HOST'] . "" . $_SERVER['PHP_SELF'];

    MiniPavi\MiniPaviCli::send($vdt, $nextPage, "", true, $cmd, $directCall);
} catch (Exception $e) {
    throw new Exception('Erreur MiniPavi ' . $e->getMessage());
}

trigger_error("fin");
