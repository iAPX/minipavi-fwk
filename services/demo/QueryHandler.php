<?php

/**
 * Demo of an overriding QueryHandler, enabling behaviour modifications at the highest level
 *
 * It is returned by \MiniPaviFwk\handlers\ServiceHandler::getQueryHandler() if file exists.
 */

namespace service;

class QueryHandler extends \MiniPaviFwk\handlers\QueryHandler
{
}
