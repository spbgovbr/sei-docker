<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 02/12/2013 - criado por mkr@trf4.jus.br
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class UnidadePublicacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'unidade_publicacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidadePublicacao',
                                   'id_unidade_publicacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUnidade',
                                              'sigla',
                                              'unidade');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoUnidade',
                                              'descricao',
                                              'unidade');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdOrgaoUnidade',
                                              'id_orgao',
                                              'unidade');
        

    $this->configurarPK('IdUnidadePublicacao',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');
  }
}
?>