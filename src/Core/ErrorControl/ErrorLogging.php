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
        if (is_array($bt) == false) {
            $bt = [0 => []];
        }
        $readIndex = 1;
        if (array_key_exists(1, $bt) == false) {
            $readIndex = 0;
        }
        if (array_key_exists($readIndex, $bt) == false) {
            return;
        }
        $bt = $bt[$readIndex];
        $bits = ["message" => $errorMessage];
        foreach (["file","function","class","line"] as $w) {
            if (array_key_exists($w, $bt) == true) {
                $bits[$w] = $bt[$w];
            }
        }
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
