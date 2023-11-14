<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/06/2008 - criado por mga
*
* Versão do Gerador de Código: 1.19.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ObservacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'observacao';
  }

  public function montar() {
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdObservacao',
                                   'id_observacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdProtocolo',
                                   'id_protocolo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'IdxObservacao',
                                  'idx_observacao');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'SiglaUnidade',
                                             'sigla',
                                             'unidade');
                                             
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'DescricaoUnidade',
                                             'descricao',
                                             'unidade');
                                             
    $this->configurarPK('IdObservacao',InfraDTO::$TIPO_PK_NATIVA );

    $this->configurarFK('IdProtocolo', 'protocolo', 'id_protocolo');
    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');
  }
}
?>