<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 12/11/2010 - criado por MGA
 *
 * @package infra_php
 */

class InfraFeedDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return null;
    }

    public function montar()
    {
        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Url');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'MimeType');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'MetaTags');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_BIN, 'Conteudo');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'CaminhoArquivo');
    }
}

