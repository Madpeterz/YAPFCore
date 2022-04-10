<?php

namespace YAPFtest;

use PHPUnit\Framework\TestCase;

class coreErrorLoggingtest extends TestCase
{
    protected function setUp(): void
    {
        if(defined('THIS_IS_A_TEST_STRING') == false) {
            define('THIS_IS_A_TEST_STRING', "This is a test");
        }
    }
    public function test_last_error_message()
    {
        $_testingobject = new ErrorLoggingTestClass();
        $_testingobject->test_addError(__FILE__, __FUNCTION__, THIS_IS_A_TEST_STRING);
        $this->assertSame($_testingobject->getLastError(), "File: " . __FILE__ . " Function: " . __FUNCTION__ . " info: " . THIS_IS_A_TEST_STRING . "");
    }

    public function test_return_array()
    {
        $_testingobject = new ErrorLoggingTestClass();
        $result = $_testingobject->test_addError(__FILE__, __FUNCTION__, THIS_IS_A_TEST_STRING, []);
        $this->assertSame($result, ["status" => false,"message" => THIS_IS_A_TEST_STRING]);
    }

    public function testLastBasicErrorEmpty()
    {
        $_testingobject = new ErrorLoggingTestClass();
        $this->assertSame($_testingobject->getLastErrorBasic(), "");
    }

    public function testLastBasicErrorSet()
    {
        $_testingobject = new ErrorLoggingTestClass();
        $result = $_testingobject->test_addError(__FILE__, __FUNCTION__, THIS_IS_A_TEST_STRING);
        $this->assertSame($_testingobject->getLastErrorBasic(), THIS_IS_A_TEST_STRING);
    }

    public function test_return_array_extended()
    {
        $_testingobject = new ErrorLoggingTestClass();
        $result = $_testingobject->test_addError(__FILE__, __FUNCTION__, THIS_IS_A_TEST_STRING, ["why"]);
        $this->assertSame($result, ["why","status" => false,"message" => THIS_IS_A_TEST_STRING]);
        $result = $_testingobject->test_addError(__FILE__, __FUNCTION__, THIS_IS_A_TEST_STRING, ["popcorn" => "why"]);
        $this->assertSame($result, ["popcorn" => "why","status" => false,"message" => THIS_IS_A_TEST_STRING]);
    }

    public function test_enableConsoleErrors()
    {
        // need a system to capture the error_log so we can test that
        $_testingobject = new ErrorLoggingTestClass();
        $_testingobject->enableConsoleErrors();
        $_testingobject->test_addError(__FILE__,__FUNCTION__, "This is a test");
        $this->assertStringContainsString("This is a test",$_testingobject->getLastError(),"error message is missing or invaild");
    }
}
