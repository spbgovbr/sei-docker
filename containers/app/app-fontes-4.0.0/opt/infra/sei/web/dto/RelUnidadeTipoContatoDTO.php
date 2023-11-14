<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/04/2008 - criado por mga
*
* Versão do Gerador de Código: 1.14.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelUnidadeTipoContatoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_unidade_tipo_contato';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                  'IdRelUnidadeTipoContato',
                                  'id_rel_unidade_tipo_contato');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdTipoContato',
                                   'id_tipo_contato');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'StaAcesso',
                                  'sta_acesso');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeTipoContato',
                                              'nome',
                                              'tipo_contato');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinAtivoTipoContato',
                                              'sin_ativo',
                                              'tipo_contato');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'EnderecoUnidade',
                                              'endereco',
                                              'unidade');
                                              
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUnidade',
                                              'sigla',
                                              'unidade');

    $this->configurarPK('IdRelUnidadeTipoContato',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdTipoContato', 'tipo_contato', 'id_tipo_contato');
    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');
  }
}
?>