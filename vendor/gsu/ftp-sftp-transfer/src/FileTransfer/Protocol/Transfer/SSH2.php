<?php
/**
 * SSH, SCP and SFTP connections using PHPs ssh2-functions
 *
 * @author Tuomas Angervuori <tuomas.angervuori@gmail.com>
 * @license http://opensource.org/licenses/LGPL-3.0 LGPL v3
 */

namespace FileTransfer\Protocol\Transfer;

class SSH2 extends Transfer
{

    protected $connect;

    public function __construct($host, \FileTransfer\Protocol\Authentication\SSH2 $auth, $port = 22)
    {
        if(!$host || !is_string($host)) {
            throw new \Exception(Helper::camelCaseToText("SSH2") . ' error: incorrect ' . Helper::camelCaseToText("host"));
        }

        if((!is_number($port))) {
            throw new \Exception(Helper::camelCaseToText("SSH2") . ' error: incorrect ' . Helper::camelCaseToText("port"));
        }

        if (!($auth instanceof \FileTransfer\Protocol\Authentication\SSH2)) {
            throw new \Exception(Helper::camelCaseToText("SSH2") . ' error: incorrect ' . Helper::camelCaseToText("auth"));
        }

        $this->connect = ssh2_connect($host, $port);
        $auth->setConnect($this->connect);
        if ($auth->authenticate() === false) {
            throw new \Exception('SSH2 login is invalid');
        }
    }

}