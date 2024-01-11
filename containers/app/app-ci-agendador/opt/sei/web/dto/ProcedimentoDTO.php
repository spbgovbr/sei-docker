<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 31/01/2008 - criado por marcio_db
 * 15/06/2018 - cjy - ícone de acompanhamento no controle de processos
 *
 * Versão do Gerador de Código: 1.13.1
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__) . '/../SEI.php';

class ProcedimentoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return 'procedimento';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
      'IdProcedimento',
      'id_procedimento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
      'IdTipoProcedimento',
      'id_tipo_procedimento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
      'StaOuvidoria',
      'sta_ouvidoria');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
        'SinCiencia',
        'sin_ciencia');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'ProtocoloProcedimentoFormatado',
      'protocolo_formatado',
      'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'ProtocoloProcedimentoFormatadoPesquisa',
      'protocolo_formatado_pesquisa',
      'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'IdProtocoloFederacaoProtocolo',
        'id_protocolo_federacao',
        'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdUnidadeGeradoraProtocolo',
      'id_unidade_geradora',
      'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdUsuarioGeradorProtocolo',
      'id_usuario_gerador',
      'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'StaEstadoProtocolo',
      'sta_estado',
      'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,
      'GeracaoProtocolo',
      'dta_geracao',
      'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'DescricaoProtocolo',
      'descricao',
      'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'StaNivelAcessoLocalProtocolo',
      'sta_nivel_acesso_local',
      'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'StaNivelAcessoGlobalProtocolo',
      'sta_nivel_acesso_global',
      'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'StaNivelAcessoOriginalProtocolo',
      'sta_nivel_acesso_original',
      'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdHipoteseLegalProtocolo',
      'id_hipotese_legal',
      'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'StaGrauSigiloProtocolo',
      'sta_grau_sigilo',
      'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'NomeHipoteseLegal',
      'nome',
      'hipotese_legal');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'BaseLegalHipoteseLegal',
        'base_legal',
        'hipotese_legal');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdOrgaoUnidadeGeradoraProtocolo',
      'id_orgao',
      'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'SiglaUnidadeGeradoraProtocolo',
      'sigla',
      'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'DescricaoUnidadeGeradoraProtocolo',
      'descricao',
      'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'SiglaOrgaoUnidadeGeradoraProtocolo',
        'sigla',
        'orgao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'DescricaoOrgaoUnidadeGeradoraProtocolo',
        'descricao',
        'orgao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'NomeTipoProcedimento',
      'nome',
      'tipo_procedimento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'SinOuvidoriaTipoProcedimento',
      'sin_ouvidoria',
      'tipo_procedimento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'SinIndividualTipoProcedimento',
        'sin_individual',
        'tipo_procedimento');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinGerarPendencia');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'ProtocoloDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjAtividadeDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjDocumentoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjUnidadeDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'AnotacaoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjAcessoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'ObservacaoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjParticipanteDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelProtocoloProtocoloDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRetornoProgramadoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjAcompanhamentoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'ControlePrazoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'AndamentoSituacaoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjAndamentoMarcadorDTO');
    //$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjProcedimentoDTO');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinMontandoArvore');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinDocTodos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinDocPublicavel');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinDocPublicado');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinDocAnexos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinDocCircular');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinAnotacoes');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinAcompanhamentos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinObservacoes');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinSituacoes');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinMarcadores');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinConteudoEmail');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinTodos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinProcAnexados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinArquivamento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdDocumento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'DblIdProtocoloAssociado');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinControlePrazo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinComentarios');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinLinhaDireta');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinPdf');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinZip');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'VersaoAcessos');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'CodigoAcesso');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinAberto');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'Ano');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinCredencialProcesso');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinCredencialAssinatura');

    $this->configurarPK('IdProcedimento', InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdTipoProcedimento', 'tipo_procedimento', 'id_tipo_procedimento', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdProcedimento', 'protocolo', 'id_protocolo');
    $this->configurarFK('IdUnidadeGeradoraProtocolo', 'unidade', 'id_unidade');
    $this->configurarFK('IdOrgaoUnidadeGeradoraProtocolo', 'orgao', 'id_orgao');

    $this->configurarFK('IdHipoteseLegalProtocolo','hipotese_legal','id_hipotese_legal',InfraDTO::$TIPO_FK_OPCIONAL);

  }
}

?>