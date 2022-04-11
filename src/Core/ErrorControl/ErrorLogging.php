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
        $bits = [
            "file" => $bt[1]['file'],
            "function" => $bt[1]['function'],
            "class" => $bt[1]['class'],
            "line" => $bt[1]['line'],
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
