<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/05/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class AcessoFederacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'acesso_federacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdAcessoFederacao', 'id_acesso_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdInstalacaoFederacaoRem', 'id_instalacao_federacao_rem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdOrgaoFederacaoRem', 'id_orgao_federacao_rem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdUnidadeFederacaoRem', 'id_unidade_federacao_rem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdUsuarioFederacaoRem', 'id_usuario_federacao_rem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdInstalacaoFederacaoDest', 'id_instalacao_federacao_dest');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdOrgaoFederacaoDest', 'id_orgao_federacao_dest');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdUnidadeFederacaoDest', 'id_unidade_federacao_dest');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdUsuarioFederacaoDest', 'id_usuario_federacao_dest');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdProcedimentoFederacao', 'id_procedimento_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdDocumentoFederacao', 'id_documento_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Liberacao', 'dth_liberacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'MotivoLiberacao', 'motivo_liberacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Cancelamento', 'dth_cancelamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'MotivoCancelamento', 'motivo_cancelamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'StaTipo', 'sta_tipo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'IdInstalacaoFederacaoOrigem', 'pf_proc.id_instalacao_federacao', 'protocolo_federacao pf_proc');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaInstalacaoFederacaoOrigem', 'inst_orig.sigla', 'instalacao_federacao inst_orig');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoInstalacaoFederacaoOrigem', 'inst_orig.descricao', 'instalacao_federacao inst_orig');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaInstalacaoFederacaoRem', 'inst_rem.sigla', 'instalacao_federacao inst_rem');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoInstalacaoFederacaoRem', 'inst_rem.descricao', 'instalacao_federacao inst_rem');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgaoFederacaoRem', 'org_rem.sigla', 'orgao_federacao org_rem');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoOrgaoFederacaoRem', 'org_rem.descricao', 'orgao_federacao org_rem');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUnidadeFederacaoRem', 'uni_rem.sigla', 'unidade_federacao uni_rem');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoUnidadeFederacaoRem', 'uni_rem.descricao', 'unidade_federacao uni_rem');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaInstalacaoFederacaoDest', 'inst_dest.sigla', 'instalacao_federacao inst_dest');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoInstalacaoFederacaoDest', 'inst_dest.descricao', 'instalacao_federacao inst_dest');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgaoFederacaoDest', 'org_dest.sigla', 'orgao_federacao org_dest');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoOrgaoFederacaoDest', 'org_dest.descricao', 'orgao_federacao org_dest');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUnidadeFederacaoDest', 'uni_dest.sigla', 'unidade_federacao uni_dest');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoUnidadeFederacaoDest', 'uni_dest.descricao', 'unidade_federacao uni_dest');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'PalavrasPesquisa');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'StaTipoFiltroUnidades');

    //Auditoria
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdProcedimento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdDocumento');

    $this->configurarPK('IdAcessoFederacao',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdProcedimentoFederacao', 'protocolo_federacao pf_proc', 'pf_proc.id_protocolo_federacao');

    $this->configurarFK('IdInstalacaoFederacaoRem', 'instalacao_federacao inst_rem', 'inst_rem.id_instalacao_federacao');
    $this->configurarFK('IdOrgaoFederacaoRem', 'orgao_federacao org_rem', 'org_rem.id_orgao_federacao');
    $this->configurarFK('IdUnidadeFederacaoRem', 'unidade_federacao uni_rem', 'uni_rem.id_unidade_federacao');

    $this->configurarFK('IdInstalacaoFederacaoDest', 'instalacao_federacao inst_dest', 'inst_dest.id_instalacao_federacao');
    $this->configurarFK('IdOrgaoFederacaoDest', 'orgao_federacao org_dest', 'org_dest.id_orgao_federacao');
    $this->configurarFK('IdUnidadeFederacaoDest', 'unidade_federacao uni_dest', 'uni_dest.id_unidade_federacao');

    $this->configurarFK('IdInstalacaoFederacaoOrigem', 'instalacao_federacao inst_orig', 'inst_orig.id_instalacao_federacao');

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
