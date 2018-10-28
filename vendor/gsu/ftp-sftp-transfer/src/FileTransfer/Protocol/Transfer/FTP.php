<?php
/**
 * FTP connections using PHPs ftp-functions
 *
 * @author Tuomas Angervuori <tuomas.angervuori@gmail.com>
 * @note Does not support connections through proxy
 * @license http://opensource.org/licenses/LGPL-3.0 LGPL v3
 */

namespace FileTransfer\Protocol\Transfer;

class FTP extends Transfer
{

    protected $connect;


    public function __construct($host, \FileTransfer\Protocol\Authentication\Authentication $auth, $port = 21)
    {
        if(!$host || !is_string($host)) {
            throw new \Exception(Helper::camelCaseToText("FTP") . ' error: incorrect ' . Helper::camelCaseToText("host"));
        }

        if((!is_number($port))) {
            throw new \Exception(Helper::camelCaseToText("FTP") . ' error: incorrect ' . Helper::camelCaseToText("port"));
        }

        if (!($auth instanceof \FileTransfer\Protocol\Authentication\Authentication)) {
            throw new \Exception(Helper::camelCaseToText("FTP") . ' error: incorrect ' . Helper::camelCaseToText("auth"));
        }

        $this->strFunctionPrefix = 'ftp_';
        $this->connect = ftp_connect($host, $port);
        $auth->setConnect($this->connect);
        if ($auth->authenticate() === false) {
            throw new \Exception('FTP login is invalid');
        }
    }

    public function __call($func, $args)
    {
        $this->func = $func;
        $this->args = $args;

        $this->prepareArguments();

        if (function_exists($this->func)) {

            array_unshift($this->args, $this->connect);
            $result = call_user_func_array($this->func, $this->args);

            if(!$result) throw new \Exception($this->func . ' runtime error');

            return $result;
        } else {
            throw new \Exception($this->func . ' is not a valid FTP function');
        }

    }

}
