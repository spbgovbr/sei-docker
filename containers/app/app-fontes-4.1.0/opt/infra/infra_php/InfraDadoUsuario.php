<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 30/05/2006 - criado por MGA
 *
 * @package infra_php
 */

/*
CREATE TABLE infra_dado_usuario (
	id_usuario            integer  NOT NULL ,
	nome                  varchar(50)  NOT NULL ,
	valor                 varchar(4000)  NULL 
);

ALTER TABLE infra_dado_usuario ADD CONSTRAINT  pk_infra_dado_usuario PRIMARY KEY (id_usuario  ASC,nome  ASC);

*/

class InfraDadoUsuario extends InfraRN
{

    public function __construct(InfraSessao $objInfraSessao)
    {
        SessaoInfra::setObjInfraSessao($objInfraSessao);
        BancoInfra::setObjInfraIBanco($objInfraSessao->getObjInfraIBanco());
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoInfra::getInstance();
    }

    protected function getValorConectado($strNome, $numIdUsuario = null)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraDadoUsuario->getStrValor] ' . $strNome);
        }

        if ($numIdUsuario == null) {
            $numIdUsuario = SessaoInfra::getInstance()->getNumIdUsuario();
        }

        if ($numIdUsuario == null) {
            throw new InfraException('Usuário não configurado na sessão.');
        }

        $objInfraDadoUsuarioDTO = new InfraDadoUsuarioDTO();
        $objInfraDadoUsuarioDTO->retStrValor();
        $objInfraDadoUsuarioDTO->setStrNome($strNome);
        $objInfraDadoUsuarioDTO->setNumIdUsuario($numIdUsuario);

        $objInfraDadoUsuarioRN = new InfraDadoUsuarioRN();
        $objInfraDadoUsuarioDTO = $objInfraDadoUsuarioRN->consultar($objInfraDadoUsuarioDTO);


        if ($objInfraDadoUsuarioDTO == null) {
            return null;
        }

        return $objInfraDadoUsuarioDTO->getStrValor();
    }

    protected function setValorControlado($strNome, $strValor, $numIdUsuario = null)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraDadoUsuario->setStrValor] ' . $strNome . ': ' . $strValor);
        }

        if ($numIdUsuario == null) {
            $numIdUsuario = SessaoInfra::getInstance()->getNumIdUsuario();
        }

        if ($numIdUsuario == null) {
            throw new InfraException('Usuário não configurado na sessão.');
        }

        if (strlen($strNome) > 50) {
            throw new InfraException('Nome do Dado do Usuário possui tamanho superior a 50 caracteres.');
        }

        if (strlen($strValor) > 4000) {
            throw new InfraException('Valor do Dado do Usuário possui tamanho superior a 4000 caracteres.');
        }

        $objInfraDadoUsuarioRN = new InfraDadoUsuarioRN();

        if (!$this->isSetValor($strNome, $numIdUsuario)) {
            $objInfraDadoUsuarioDTO = new InfraDadoUsuarioDTO();
            $objInfraDadoUsuarioDTO->setNumIdUsuario($numIdUsuario);
            $objInfraDadoUsuarioDTO->setStrNome($strNome);
            $objInfraDadoUsuarioDTO->setStrValor($strValor);
            $objInfraDadoUsuarioRN->cadastrar($objInfraDadoUsuarioDTO);
        } else {
            $objInfraDadoUsuarioDTO = new InfraDadoUsuarioDTO();
            $objInfraDadoUsuarioDTO->setNumIdUsuario($numIdUsuario);
            $objInfraDadoUsuarioDTO->setStrNome($strNome);
            $objInfraDadoUsuarioDTO->setStrValor($strValor);
            $objInfraDadoUsuarioRN->alterar($objInfraDadoUsuarioDTO);
        }
    }

    protected function isSetValorConectado($strNome, $numIdUsuario = null)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraDadoUsuario->isSetStrValor] ' . $strNome);
        }

        if ($numIdUsuario == null) {
            $numIdUsuario = SessaoInfra::getInstance()->getNumIdUsuario();
        }

        if ($numIdUsuario == null) {
            throw new InfraException('Usuário não configurado na sessão.');
        }

        $objInfraDadoUsuarioDTO = new InfraDadoUsuarioDTO();
        $objInfraDadoUsuarioDTO->retStrValor();
        $objInfraDadoUsuarioDTO->setNumIdUsuario($numIdUsuario);
        $objInfraDadoUsuarioDTO->setStrNome($strNome);

        $objInfraDadoUsuarioRN = new InfraDadoUsuarioRN();
        $objInfraDadoUsuarioDTO = $objInfraDadoUsuarioRN->consultar($objInfraDadoUsuarioDTO);

        return ($objInfraDadoUsuarioDTO != null);
    }

    protected function removerValorControlado($strNome, $numIdUsuario = null)
    {
        if ($numIdUsuario == null) {
            $numIdUsuario = SessaoInfra::getInstance()->getNumIdUsuario();
        }

        if ($this->isSetValor($strNome, $numIdUsuario)) {
            $objInfraDadoUsuarioDTO = new InfraDadoUsuarioDTO();
            $objInfraDadoUsuarioDTO->setNumIdUsuario($numIdUsuario);
            $objInfraDadoUsuarioDTO->setStrNome($strNome);

            $objInfraDadoUsuarioRN = new InfraDadoUsuarioRN();
            $objInfraDadoUsuarioRN->excluir(array($objInfraDadoUsuarioDTO));
        }
    }

    protected function removerValoresUsuarioControlado($numIdUsuario = null)
    {
        if ($numIdUsuario == null) {
            $numIdUsuario = SessaoInfra::getInstance()->getNumIdUsuario();
        }

        $objInfraDadoUsuarioDTO = new InfraDadoUsuarioDTO();
        $objInfraDadoUsuarioDTO->retNumIdUsuario();
        $objInfraDadoUsuarioDTO->retStrNome();
        $objInfraDadoUsuarioDTO->setNumIdUsuario($numIdUsuario);

        $objInfraDadoUsuarioRN = new InfraDadoUsuarioRN();
        $objInfraDadoUsuarioRN->excluir($objInfraDadoUsuarioRN->listar($objInfraDadoUsuarioDTO));
    }
}

