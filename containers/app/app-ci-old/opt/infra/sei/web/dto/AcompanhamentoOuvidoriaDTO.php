<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/07/2013 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class AcompanhamentoOuvidoriaDTO extends InfraDTO {

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
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
        'IdUnidadeOrigem',
        'id_unidade_origem');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
        'Abertura',
        'dth_abertura');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
        'IdTarefa',
        'id_tarefa');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'SiglaUnidadeOrigem', 
        'uo.sigla',
        'unidade uo');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'DescricaoUnidadeOrigem',
        'uo.descricao',
        'unidade uo');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdOrgaoUnidadeOrigem',
        'uo.id_orgao',
        'unidade uo');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'SiglaUnidade',
        'u.sigla',
        'unidade u');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'DescricaoUnidade',
        'u.descricao',
        'unidade u');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdOrgaoUnidade',
        'u.id_orgao',
        'unidade u');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
        'IdProtocoloProtocolo',
        'id_protocolo',
        'protocolo');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'StaNivelAcessoGlobalProtocolo',
        'sta_nivel_acesso_global',
        'protocolo');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'ProtocoloFormatadoProtocolo',
        'protocolo_formatado',
        'protocolo');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdTipoProcedimentoProcedimento',
        'id_tipo_procedimento',
        'procedimento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'StaOuvidoriaProcedimento',
        'sta_ouvidoria',
        'procedimento');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'SinOuvidoriaTipoProcedimento',
        'sin_ouvidoria',
        'tipo_procedimento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'NomeTipoProcedimento',
        'nome',
        'tipo_procedimento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdTipoProcedimento',
        'id_tipo_procedimento',
        'tipo_procedimento');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'Inicio');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'Fim');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinTramitacaoOuvidoria');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjTipoProcedimentoDTO');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdEstatisticas');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'GraficoGeral');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'GraficoPorTipo');
    
    
    $this->configurarPK('IdAtividade', InfraDTO::$TIPO_PK_NATIVA);
    
    $this->configurarFK('IdProtocolo', 'protocolo', 'id_protocolo');
    $this->configurarFK('IdUnidade', 'unidade u', 'u.id_unidade');
    $this->configurarFK('IdUnidadeOrigem', 'unidade uo', 'uo.id_unidade');
    $this->configurarFK('IdProtocoloProtocolo', 'procedimento', 'id_procedimento');
    $this->configurarFK('IdTipoProcedimentoProcedimento', 'tipo_procedimento', 'id_tipo_procedimento');
    
  }
}
?>