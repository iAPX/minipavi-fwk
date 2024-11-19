<?php

namespace Tests\Mocks;

class MockAction extends \MiniPaviFwk\actions\Action
{
    public string $mock_id;

    public function __construct(string $mock_id) {
        $this->mock_id = $mock_id;
    }
}
