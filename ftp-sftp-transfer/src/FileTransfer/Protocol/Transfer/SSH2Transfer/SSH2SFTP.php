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
        parent::__construct($host, $auth, $port);
        $this->sftp = ssh2_ftp($this->conn);
    }

    public function __call($func, $args)
    {
        $func = 'ssh2_sftp_' . $func;
        if (function_exists($func)) {
            array_unshift($args, $this->sftp);
            return call_user_func_array($func, $args);
        } else {
            throw new Exception($func . ' is not a valid SFTP function.');
        }
    }
}