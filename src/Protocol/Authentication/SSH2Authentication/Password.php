<?php
/**
 * Created by PhpStorm.
 * User: GSU
 * Date: 18.05.2018
 * Time: 13:06
 */

namespace FileTransfer\Protocol\Authentication\SSH2Authentication;

class Password extends \FileTransfer\Protocol\Authentication\Authentication
{

    protected $password;

    public function __construct($username, $password)
    {
        if(!$username || !is_string($username)) {
            throw new \Exception(Helper::camelCaseToText("Authentication") . ' error: incorrect ' . Helper::camelCaseToText("username"));
        }

        if((!is_string($password))) {
            throw new \Exception(Helper::camelCaseToText("Authentication") . ' error: incorrect ' . Helper::camelCaseToText("password"));
        }

        $this->username = $username;
        $this->password = $password;
    }

    public function authenticate()
    {
        return ssh2_auth_password(
            $this->connect,
            $this->username,
            $this->password
        );
    }
}