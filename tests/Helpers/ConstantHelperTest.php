<?php

use PHPUnit\Framework\TestCase;
use MiniPaviFwk\helpers\ConstantHelper;

class ConstantHelperTest extends TestCase
{
    public function test()
    {
        $result = ConstantHelper::getConstValueByName("MOCK_SERVICE_CONSTANT");
        $this->assertEquals("mock_service_constant_ok", $result);

        $result = ConstantHelper::getConstValueByName("MOCK_GLOBAL_CONSTANT");
        $this->assertEquals("mock_global_constant_ok", $result);

        $result = ConstantHelper::getConstValueByName("MISSING_CONSTANT_WITH_DEFAULT", "test_default");
        $this->assertEquals("test_default", $result);

        $result = ConstantHelper::getConstValueByName("MISSING_CONSTANT_WITHOUT_DEFAULT");
        $this->assertnull($result);
    }
}
