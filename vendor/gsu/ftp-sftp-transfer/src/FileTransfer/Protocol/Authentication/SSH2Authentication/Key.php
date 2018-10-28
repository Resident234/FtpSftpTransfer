<?php
/**
 * Created by PhpStorm.
 * User: GSU
 * Date: 18.05.2018
 * Time: 13:07
 */

namespace FileTransfer\Protocol\Authentication\SSH2Authentication;

class Key extends \FileTransfer\Protocol\Authentication\Authentication
{

    protected $publicKey;
    protected $privateKey;

    public function __construct($username, $publicKey, $privateKey)
    {
        if(!$username || !is_string($username)) {
            throw new \Exception(Helper::camelCaseToText("Authentication") . ' error: incorrect ' . Helper::camelCaseToText("username"));
        }

        if(!$publicKey || !is_string($publicKey)) {
            throw new \Exception(Helper::camelCaseToText("Authentication") . ' error: incorrect ' . Helper::camelCaseToText("publicKey"));
        }

        if(!$privateKey || !is_string($privateKey)) {
            throw new \Exception(Helper::camelCaseToText("Authentication") . ' error: incorrect ' . Helper::camelCaseToText("privateKey"));
        }

        $this->username = $username;
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    public function authenticate($passphrase = '')
    {
        if(!is_string($passphrase)) {
            throw new \Exception(Helper::camelCaseToText("Authentication") . ' error: incorrect ' . Helper::camelCaseToText("passphrase"));
        }

        return ssh2_auth_pubkey_file(
            $this->connect,
            $this->username,
            $this->publicKey,
            $this->privateKey,
            $passphrase
        );
    }
}