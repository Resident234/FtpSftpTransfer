<?php
/**
 * Created by PhpStorm.
 * User: GSU
 * Date: 18.05.2018
 * Time: 13:11
 */

namespace FileTransfer\Protocol\Transfer\SSH2Transfer;

class SSH2SCP extends FileTransfer\Protocol\Transfer\SSH2
{

    public function __construct($host, \FileTransfer\Protocol\Authentication\Authentication $auth, $port = 22)
    {
        $this->strFunctionPrefix = 'ssh2_scp_';
        parent::__construct($host, $auth, $port);
    }

    public function __call($func, $args)
    {
        $this->func = $func;
        $this->args = $args;

        $this->prepareArguments();

        if (function_exists($this->func)) {
            array_unshift($this->args, $this->conn);
            return call_user_func_array($this->func, $this->args);
        } else {
            throw new Exception($this->func . ' is not a valid SCP function.');
        }
    }
}