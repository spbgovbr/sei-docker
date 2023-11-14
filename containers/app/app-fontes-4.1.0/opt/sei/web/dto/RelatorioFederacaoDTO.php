<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 18/06/2012 - criado por mga
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelatorioFederacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'acesso_federacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdAcessoFederacao', 'id_acesso_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdInstalacaoFederacaoRem', 'id_instalacao_federacao_rem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdOrgaoFederacaoRem', 'id_orgao_federacao_rem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdUnidadeFederacaoRem', 'id_unidade_federacao_rem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdInstalacaoFederacaoDest', 'id_instalacao_federacao_dest');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdOrgaoFederacaoDest', 'id_orgao_federacao_dest');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdUnidadeFederacaoDest', 'id_unidade_federacao_dest');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdProcedimentoFederacao', 'id_procedimento_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Liberacao', 'dth_liberacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'IdProtocoloFederacaoProtocoloFederacao', 'id_protocolo_federacao', 'protocolo_federacao');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloFormatadoPesquisaProtocoloFederacao', 'protocolo_formatado_pesquisa', 'protocolo_federacao');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloFormatadoPesqInvProtocoloFederacao', 'protocolo_formatado_pesq_inv', 'protocolo_federacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'StaProtocoloProtocolo', 'sta_protocolo', 'protocolo');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloFormatadoPesquisaProtocolo', 'protocolo_formatado_pesquisa', 'protocolo');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloFormatadoPesqInvProtocolo', 'protocolo_formatado_pesq_inv', 'protocolo');

    //Pesquisa
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'IdOrgaoFederacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'ProtocoloFormatado');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'StaSentido');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'Inicio');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'Fim');


    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ,'ProtocoloFederacaoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ,'ProtocoloDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjAcessoFederacaoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjOrgaoFederacaoDTO');

    $this->configurarPK('IdAcessoFederacao',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdProcedimentoFederacao', 'protocolo_federacao', 'id_protocolo_federacao', InfraDTO::$TIPO_FK_OBRIGATORIA, InfraDTO::$FILTRO_FK_WHERE);
    $this->configurarFK('IdProtocoloFederacaoProtocoloFederacao', 'protocolo', 'id_protocolo_federacao', InfraDTO::$TIPO_FK_OBRIGATORIA, InfraDTO::$FILTRO_FK_WHERE);

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }

}
?>