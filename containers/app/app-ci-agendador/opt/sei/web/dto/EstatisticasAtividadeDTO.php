<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/11/2010 - criado por mga
*
* Versão do Gerador de Código: 1.17.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class EstatisticasAtividadeDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'atividade';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdAtividade',
                                   'id_atividade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdProtocolo',
                                   'id_protocolo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'Abertura',
                                   'dth_abertura');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'Conclusao',
                                   'dth_conclusao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdTarefa',
                                   'id_tarefa');

                                              
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdOrgaoUnidade',
                                              'ua.id_orgao',
                                              'unidade ua');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUnidade',
                                              'ua.sigla',
                                              'unidade ua');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoUnidade',
                                              'ua.descricao',
                                              'unidade ua');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaOrgaoUnidade',
                                              'oa.sigla',
                                              'orgao oa');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,
                                              'InclusaoProtocolo',
                                              'dta_inclusao',
                                              'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              'IdProtocoloProtocolo',
                                              'id_protocolo',
                                              'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdUnidadeGeradoraProtocolo',
                                              'id_unidade_geradora',
                                              'protocolo');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'ProtocoloFormatadoProtocolo',
                                              'protocolo_formatado',
                                              'protocolo');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaNivelAcessoGlobalProtocolo',
                                              'sta_nivel_acesso_global',
                                              'protocolo');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdTipoProcedimentoProcedimento',
                                              'id_tipo_procedimento',
                                              'procedimento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeTipoProcedimento',
                                              'nome',
                                              'tipo_procedimento');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdOrgaoUnidadeGeradoraProtocolo',
                                              'up.id_orgao',
                                              'unidade up');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'Inicio');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'Fim');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinConcluidos');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'Dias');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'MesConclusao');
	  $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'TempoAberto');
	  
    
    $this->configurarFK('IdProtocolo', 'protocolo', 'id_protocolo');
    $this->configurarFK('IdProtocoloProtocolo', 'procedimento', 'id_procedimento');
    $this->configurarFK('IdUnidade', 'unidade ua', 'ua.id_unidade');
    $this->configurarFK('IdOrgaoUnidade', 'orgao oa', 'oa.id_orgao');
    $this->configurarFK('IdTipoProcedimentoProcedimento', 'tipo_procedimento', 'id_tipo_procedimento');
    $this->configurarFK('IdUnidadeGeradoraProtocolo', 'unidade up', 'up.id_unidade');
  }
}
?>