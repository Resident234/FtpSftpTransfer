<?php
/**
 * Created by PhpStorm.
 * User: GSU
 * Date: 18.05.2018
 * Time: 13:06
 */

namespace FileTransfer\Protocol\Authentication;

abstract class Authentication
{

    protected $connect;
    protected $username;

    public function setConnect($connect)
    {
        $this->connect = $connect;
    }

    public function authenticate()
    {
        throw new \Exception('Method "authenticate" must be defined');
    }
}