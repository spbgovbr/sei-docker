<?php
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 05/04/2013 - criado por MGA
 *
 */

//require_once 'Infra.php';

class CacheInfra
{

    private static $instance = null;

    public static function getInstance()
    {
        return self::$instance;
    }

    public static function setObjInfraCache($objInfraCache)
    {
        self::$instance = $objInfraCache;
    }
}

