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
}
