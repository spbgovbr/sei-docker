<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/09/2011 - criado por mga
*
* Versão do Gerador de Código: 1.31.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class OperacaoServicoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'operacao_servico';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdOperacaoServico',
                                   'id_operacao_servico');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdServico',
                                   'id_servico');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'StaOperacaoServico',
                                   'sta_operacao_servico');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdTipoProcedimento',
                                   'id_tipo_procedimento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSerie',
                                   'id_serie');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'IdentificacaoServico',
                                              'identificacao',
                                              'servico');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUnidade',
                                              'sigla',
                                              'unidade');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoUnidade',
                                              'descricao',
                                              'unidade');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeSerie',
                                              'nome',
                                              'serie');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeTipoProcedimento',
                                              'nome',
                                              'tipo_procedimento');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'DescricaoOperacaoServico');

    $this->configurarPK('IdOperacaoServico',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdServico', 'servico', 'id_servico');
    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdSerie', 'serie', 'id_serie', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdTipoProcedimento', 'tipo_procedimento', 'id_tipo_procedimento', InfraDTO::$TIPO_FK_OPCIONAL);
  }
}
?>