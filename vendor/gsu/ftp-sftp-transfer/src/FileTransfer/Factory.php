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
        if (!array_key_exists($type, $this->arTypeListTransferProtocols)) {
            throw new \InvalidArgumentException("$type is not valid connection type");
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


/*
class Factory {
    private $connection;
    public function getConnection($type, $login, $password, $host, $port = 21)
    {

        $className = "\\FileTransfer\\" . ucfirst($type);
        if(!class_exists($className)){
            throw new \Exception("Unknown protocol.\n");
        }
        // Код немного напоминает корявенький "недоснглтон")) полезной эта проверка будет если соединение которое пытаемся получить уже открыто.
        if( $this->connection instanceof $className &&
            $this->connection->getLogin() == $login &&
            $this->connection->getPassword() == $password &&
            $this->connection->getHost() == $host &&
            $this->connection->getPort() == $port
        ){
            return $this->connection;
        }else{
            return $this->connection = new $className($host, $login, $password, $port);
        }
    }
} */


/*
class Factory
{
    protected $typeList;

    public function __construct(){
        $this->typeList = array(
            'ssh' => __NAMESPACE__ . '\SSH',
            'ftp' => __NAMESPACE__ . '\FTP'
        );
    }

    public function getConnection($type, $user, $pass, $hostname, $port = false){

        if (!array_key_exists($type, $this->typeList)) {
            throw new \InvalidArgumentException("$type is not valid connection type");
        }
        $className = $this->typeList[$type];
        return new $className($user, $pass, $hostname, $port);
    }
}*/