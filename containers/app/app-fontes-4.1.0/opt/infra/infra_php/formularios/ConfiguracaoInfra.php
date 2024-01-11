<?php
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 29/04/2013 - criado por MGA
 *
 */

//require_once 'Infra.php';

class ConfiguracaoInfra
{

    private static $instance = null;

    public static function getInstance()
    {
        return self::$instance;
    }

    public static function setObjInfraConfiguracao(InfraConfiguracao $objInfraConfiguracao)
    {
        self::$instance = $objInfraConfiguracao;
    }
}

