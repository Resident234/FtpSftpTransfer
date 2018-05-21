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
        parent::__construct($host, $auth, $port);
    }

    public function __call($func, $args)
    {
        $func = 'ssh2_scp_' . $func;
        if (function_exists($func)) {
            array_unshift($args, $this->conn);
            return call_user_func_array($func, $args);
        } else {
            throw new Exception($func . ' is not a valid SCP function.');
        }
    }
}