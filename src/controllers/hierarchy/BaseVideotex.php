<?php

/**
 * Provides basic Videotex Controller instanciation and Context Management
 */

namespace MiniPaviFwk\controllers\hierarchy;

class BaseVideotex
{
    // Context of this controller, it coulld manipulate it the way it want
    protected $context;

    public function __construct($context, $params = [])
    {
        // @TODO Stack managed here, including retrieving params!
        $context['params'] = $params;
        $context['controller'] = $this::class;
        $this->context = $context;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function entree(): void
    {
        // Hook called when entering this controller for the first time
        // called by PageAction(), ControllerAction(), AccueilAction()
        trigger_error("BaseVideotex::entree()", E_USER_NOTICE);
    }
}
