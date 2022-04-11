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
        $_testingobject->test_addError(THIS_IS_A_TEST_STRING);
        $jsonme = [
            "file" => "D:\\php\\YAPFCore\\tests\\core\\core_ErrorLogging_Test.php",
            "function" => "test_addError",
            "class" => "YAPFtest\\ErrorLoggingTestClass",
            "line" => 18,
            "message" => "This is a test",
        ];
        $this->assertSame(json_encode($jsonme), $_testingobject->getLastError(), "Incorrect error message");
    }

    public function testLastBasicErrorEmpty()
    {
        $_testingobject = new ErrorLoggingTestClass();
        $this->assertSame($_testingobject->getLastErrorBasic(), "");
    }

    public function testLastBasicErrorSet()
    {
        $_testingobject = new ErrorLoggingTestClass();
        $_testingobject->test_addError(THIS_IS_A_TEST_STRING);
        $this->assertSame($_testingobject->getLastErrorBasic(), THIS_IS_A_TEST_STRING);
    }

    public function test_enableConsoleErrors()
    {
        // need a system to capture the error_log so we can test that
        $_testingobject = new ErrorLoggingTestClass();
        $_testingobject->enableConsoleErrors();
        $_testingobject->test_addError("This is a test");
        $this->assertStringContainsString("This is a test",$_testingobject->getLastError(),"error message is missing or invaild");
    }
}
