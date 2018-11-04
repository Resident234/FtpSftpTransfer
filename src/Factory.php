<?php
/**
 * Created by PhpStorm.
 * User: GSU
 * Date: 18.05.2018
 * Time: 12:52
 */

namespace FileTransfer;

class Factory
{
    private $arTypeListTransferProtocols;
    private $arTypeListAuthenticationMethods;
    private $connection;

    public function __construct()
    {

        $this->arTypeListTransferProtocols = array(
            'ftp' => __NAMESPACE__ . '\Protocol\Transfer\FTP',
            'ssh' => __NAMESPACE__ . '\Protocol\Transfer\SSH2Transfer\SSH2SFTP'
        );

        $this->arTypeListAuthenticationMethods = array(
            'ftp' => __NAMESPACE__ . '\Protocol\Authentication\FTPAuthentication',
            'ssh' => __NAMESPACE__ . '\Protocol\Authentication\SSH2Authentication'
        );


    }


    public function getConnection($type, $user, $pass, $hostname, $port = false)
    {
        if(!$type || !is_string($type)) {
            throw new \Exception(Helper::camelCaseToText(__FUNCTION__) . ' error: incorrect ' . Helper::camelCaseToText("connectionType"));
        }

        if(!$user || !is_string($user)) {
            throw new \Exception(Helper::camelCaseToText(__FUNCTION__) . ' error: incorrect ' . Helper::camelCaseToText("connectionUser"));
        }

        if(!$pass || (!is_string($pass) && !is_array($pass))) {
            throw new \Exception(Helper::camelCaseToText(__FUNCTION__) . ' error: incorrect ' . Helper::camelCaseToText("connectionPass"));
        }

        if(!$hostname || (!is_string($hostname))) {
            throw new \Exception(Helper::camelCaseToText(__FUNCTION__) . ' error: incorrect ' . Helper::camelCaseToText("connectionHostname"));
        }

        if (!array_key_exists($type, $this->arTypeListTransferProtocols)) {
            throw new \InvalidArgumentException(Helper::camelCaseToText(__FUNCTION__) . "error: $type is not valid connection type");
        }

        $transferClassName = $this->arTypeListTransferProtocols[$type];
        $authenticationClassName = $this->arTypeListAuthenticationMethods[$type];

        if (is_array($pass)) {
            $authenticationClassName .= "\Key";
            $auth = new $authenticationClassName($user, $pass[0], $pass[1]);
        } else {
            $authenticationClassName .= "\Password";
            $auth = new $authenticationClassName($user, $pass);
        }

        $this->connection = new $transferClassName($hostname, $auth, $port);
        $this->connection->setDefaultParameters();
        $this->connection->setConnectionParameters($type, $user, $pass, $hostname);

        return $this->connection;

    }


}
