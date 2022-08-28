<?php

namespace YAPF\Core\ErrorControl;

abstract class ErrorLogging
{
    protected $myLastError = "";
    protected $myLastErrorBasic = "";
    protected bool $enableErrorConsole = false;
    /**
     * addError
     * see getLastError()
     * $flileHint = file error happened on [send __FILE__]
     * $functionHint = function name [send __FUNCTION__]
     * $errorMessage = sent error message
     * $arrayAddon = extended return fields (not processed out to error logs)
     * @return mixed[] [status =>  false, message =>  string]
     */
    protected function addError(
        string $errorMessage = "",
    ): void {
        $bt =  debug_backtrace();
        $readIndex = 1;
        if (array_key_exists(1, $bt) == false) {
            $readIndex = 0;
        }
        $bits = [
            "file" => $bt[$readIndex]['file'],
            "function" => $bt[$readIndex]['function'],
            "class" => $bt[$readIndex]['class'],
            "line" => $bt[$readIndex]['line'],
            "message" => $errorMessage,
        ];
        $this->myLastError = json_encode($bits);
        $this->myLastErrorBasic = $errorMessage;
        if (($this->enableErrorConsole == true) || (defined("ErrorConsole") == true)) {
            error_log($this->myLastError);
        }
    }
    public function enableConsoleErrors(): void
    {
        $this->enableErrorConsole = true;
    }
    public function getLastErrorBasic(): string
    {
        return $this->myLastErrorBasic;
    }
    public function getLastError(): string
    {
        return $this->myLastError;
    }
}
