<?php

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