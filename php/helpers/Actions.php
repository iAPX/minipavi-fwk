<?php

/**
 * Actions
 *
 * - Go to a page by its name, either by {Pagename}Controller if exists or by XmlController with params
 * - Go to a new Controller with optional params, handle arbo stack through context
 * - Output a string, without changing controller nor params
 */

namespace helpers;

interface ActionInterface
{
    public function getController(): \helpers\VideotexController;
    public function getOutput(): string;
}


abstract class Action implements ActionInterface
{
    protected \helpers\VideotexController $controller;
    protected string $output = '';

    public function getController(): \helpers\VideotexController
    {
        return $this->controller;
    }

    public function getOutput(): string
    {
        return $this->output;
    }
}


class AccueilAction extends Action
{
    public function __construct($defaultControllerName, $defaultXMLfilename, $context)
    {
        DEBUG && trigger_error("Action: Accueil");
        if (empty($defaultControllerName)) {
            // XML default file
            $context['xml_filename'] = $defaultXMLfilename;
            $context['xml_page'] = false;
            $this->controller = new XmlController($context);
        } else {
            // Default service controller
            $this->controller = new $defaultControllerName($context);
        }
        $this->output = $this->controller->ecran();
    }
}


class PageAction extends Action
{
    public function __construct(array $context, string $pagename, string $xmlfilename = '')
    {
        DEBUG && trigger_error("Action: Changement de page - " . $pagename);
        $context['xml_page'] = $pagename;
        // Enjoy ;)
        !empty($xmlfilename) && $context['xml_filename'] = $xmlfilename;

        // Enable Overriding by \service\{Pagename}Controlle. XmlController or a VideotexController
        $overriderControllerName = "\service\\" . ucfirst(strtolower($pagename)) . 'Controller';
        if (class_exists($overriderControllerName)) {
            DEBUG && trigger_error("Action: Controleur surcharge - " . $overriderControllerName);
            $this->controller = new $overriderControllerName($context);
        } else {
            $this->controller = new XmlController($context);
        }
        $this->output = $this->controller->ecran();
    }
}


class ControllerAction extends Action
{
    public function __construct(string $newControllerName, array $context, array $params = [])
    {
        // @TODO rewrite, it's buggy
        // Here we have the stack management and the context management!
        DEBUG && trigger_error("Action: Nouveau controleur - " . $newController::class);
        $this->controller = new ($newController)($this->context, $this->params);
        $this->output = $this->controller->ecran();
    }
}


class OutputAction extends Action
{
    public function __construct(\helpers\VideotexController $thisController, string $output)
    {
        DEBUG && trigger_error("Action: Sortie de chaine - " . mb_strlen($output) . " code points.");
        $this->controller = $thisController;
        $this->output = $output;
    }
}


class Ligne00Action extends Action
{
    public function __construct(\helpers\VideotexController $thisController, string $output)
    {
        DEBUG && trigger_error("Action: Ligne 00 - " . mb_strlen($output) . " code points.");
        $this->controller = $thisController;
        $this->output = \MiniPavi\MiniPaviCli::writeLine0($output);
    }
}


class RepetitionAction extends Action
{
    public function __construct(\helpers\VideotexController $thisController)
    {
        DEBUG && trigger_error("Action: Repetition");
        $this->controller = $thisController;
        $this->output = $thisController->ecran();
    }
}

class DeconnexionAction extends Action
{
    // Close the access to the service
    public function __construct(string $output = '', string $ligne00 = 'Déconnexion service.')
    {
        DEBUG && trigger_error("Action: Deconnexion");
        $this->controller = new \helpers\DeconnexionController([]);
        $this->output = $output . \MiniPavi\MiniPaviCli::writeLine0($ligne00) . "\e9g";     // Escape 9 g = deconnexion
    }
}
