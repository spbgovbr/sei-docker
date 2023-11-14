<?php
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 12/03/2013 - criado por MGA
 *
 */

//require_once 'Infra.php';

class LogInfra
{

    private static $instance = null;

    public static function getInstance()
    {
        return self::$instance;
    }

    public static function setObjInfraLog($objInfraLog)
    {
        self::$instance = $objInfraLog;
    }
}

