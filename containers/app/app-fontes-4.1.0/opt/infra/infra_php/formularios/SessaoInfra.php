<?php
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 08/11/2006 - criado por MGA
 *
 */

//require_once 'Infra.php';

class SessaoInfra
{

    private static $instance = null;

    public static function getInstance()
    {
        return self::$instance;
    }

    public static function setObjInfraSessao($objInfraSessao)
    {
        self::$instance = $objInfraSessao;
    }

}

