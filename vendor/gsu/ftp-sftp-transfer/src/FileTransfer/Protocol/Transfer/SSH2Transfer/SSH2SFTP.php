<?php
/**
 * Created by PhpStorm.
 * User: GSU
 * Date: 18.05.2018
 * Time: 13:12
 */

namespace FileTransfer\Protocol\Transfer\SSH2Transfer;

class SSH2SFTP extends FileTransfer\Protocol\Transfer\SSH2
{

    protected $sftp;

    // new SSH2Password('username', 'password') or new SSH2Key('username', 'public_key', 'private_key')
    //$hostname, $user, $pass, $port
    public function __construct($host, \FileTransfer\Protocol\Authentication\Authentication $auth, $port = 22)
    {
        $this->strFunctionPrefix = 'ssh2_sftp_';
        parent::__construct($host, $auth, $port);
        $this->sftp = ssh2_ftp($this->conn);
    }

    public function __call($func, $args)
    {
        $this->func = $func;
        $this->args = $args;

        $this->prepareArguments();

        if (function_exists($this->func)) {
            array_unshift($this->args, $this->sftp);
            return call_user_func_array($this->func, $this->args);
        } else {
            throw new Exception($this->func . ' is not a valid SFTP function.');
        }
    }
}