<?php
/**
 * Created by PhpStorm.
 * User: GSU
 * Date: 28.10.2018
 * Time: 22:48
 */

namespace FileTransfer;

class Helper
{
    public static function camelCaseToText($strInput)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $strInput, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return ucfirst(implode(' ', $ret));
    }
}