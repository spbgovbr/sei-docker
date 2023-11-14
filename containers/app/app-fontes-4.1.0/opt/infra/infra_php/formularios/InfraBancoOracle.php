<?php
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/07/2014 - criado por MGA
*
*/

//require_once dirname(__FILE__).'/Infra.php';


class InfraBancoOracle extends InfraOracle
{

    private $strServidor = '';
    private $strPorta = '';
    private $strBanco = '';
    private $strUsuario = '';
    private $strSenha = '';

    public static function newInstance($strServidor, $strPorta, $strBanco, $strUsuario, $strSenha)
    {
        $objInfraOracle = new InfraBancoOracle();
        $objInfraOracle->setServidor($strServidor);
        $objInfraOracle->setPorta($strPorta);
        $objInfraOracle->setBanco($strBanco);
        $objInfraOracle->setUsuario($strUsuario);
        $objInfraOracle->setSenha($strSenha);
        return $objInfraOracle;
    }

    public function setServidor($strServidor)
    {
        $this->strServidor = $strServidor;
    }

    public function getServidor()
    {
        return $this->strServidor;
    }

    public function setPorta($strPorta)
    {
        $this->strPorta = $strPorta;
    }

    public function getPorta()
    {
        return $this->strPorta;
    }

    public function setBanco($strBanco)
    {
        $this->strBanco = $strBanco;
    }

    public function getBanco()
    {
        return $this->strBanco;
    }

    public function setUsuario($strUsuario)
    {
        $this->strUsuario = $strUsuario;
    }

    public function getUsuario()
    {
        return $this->strUsuario;
    }

    public function setSenha($strSenha)
    {
        $this->strSenha = $strSenha;
    }

    public function getSenha()
    {
        return $this->strSenha;
    }
}

