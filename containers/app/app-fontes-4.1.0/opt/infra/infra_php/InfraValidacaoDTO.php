<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 18/05/2006 - criado por MGA
 *
 * @package infra_php
 */


class InfraValidacaoDTO
{
    private $strAtributo;
    private $strDescricao;

    public function getStrAtributo()
    {
        return $this->strAtributo;
    }

    public function getStrDescricao()
    {
        return $this->strDescricao;
    }

    public function setStrAtributo($strAtributo)
    {
        $this->strAtributo = $strAtributo;
    }

    public function setStrDescricao($strDescricao)
    {
        $this->strDescricao = $strDescricao;
    }
}

