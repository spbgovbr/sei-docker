<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/10/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class AvaliacaoDocumentalDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'avaliacao_documental';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdAvaliacaoDocumental', 'id_avaliacao_documental');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'IdProcedimento', 'id_procedimento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdAssuntoProxy', 'id_assunto_proxy');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdAssuntoOriginal', 'id_assunto');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaAvaliacao', 'sta_avaliacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'Avaliacao', 'dta_avaliacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'SiglaUsuario',
      'sigla',
      'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'NomeUsuario',
      'nome',
      'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdAssunto',
      'ass_pro.id_assunto',
      'assunto_proxy ass_pro');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'CodigoEstruturadoAssunto',
        'ass.codigo_estruturado',
        'assunto ass');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'DescricaoAssunto',
      'ass.descricao',
      'assunto ass');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'PrazoCorrente',
      'ass.prazo_corrente',
      'assunto ass');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'PrazoIntermediario',
      'ass.prazo_intermediario',
      'assunto ass');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'CodigoEstruturadoAssuntoOriginal',
        'ass_ori.codigo_estruturado',
        'assunto ass_ori');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'DescricaoAssuntoOriginal',
      'ass_ori.descricao',
      'assunto ass_ori');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdOrgao',
      'id_orgao',
      'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'ProtocoloFormatado',
      'protocolo_formatado',
      'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,
      'Geracao',
      'dta_geracao',
      'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdTipoProcedimento',
      'id_tipo_procedimento',
      'procedimento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'NomeTipoProcedimento',
      'nome',
      'tipo_procedimento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,
      'ConclusaoProcedimento',
      'dta_conclusao',
      'procedimento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdUnidadeGeradoraProtocolo',
      'id_unidade_geradora',
      'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdOrgaoGeradorProtocolo',
      'id_orgao_gerador',
      'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'StaDestinacaoAssuntoProxy',
      'ass.sta_destinacao',
      'assunto ass');


    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'GeracaoInicio');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'GeracaoFim');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'ConclusaoInicio');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'ConclusaoFim');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjRelProtocoloAssuntoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjCpadAvaliacaoDTO');


    $this->configurarPK('IdAvaliacaoDocumental',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdUsuario', 'usuario', 'id_usuario');
    $this->configurarFK('IdAssuntoOriginal', 'assunto ass_ori', 'ass_ori.id_assunto');
    $this->configurarFK('IdAssuntoProxy', 'assunto_proxy ass_pro', 'ass_pro.id_assunto_proxy');
    $this->configurarFK('IdAssunto', 'assunto ass', 'ass.id_assunto');
    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');
    $this->configurarFK('IdProcedimento', 'procedimento', 'id_procedimento');
    $this->configurarFK('IdProcedimento', 'protocolo', 'id_protocolo');
    $this->configurarFK('IdTipoProcedimento', 'tipo_procedimento', 'id_tipo_procedimento');
    $this->configurarFK('IdUnidadeGeradoraProtocolo', 'unidade uni_pro', 'uni_pro.id_unidade');
    $this->configurarFK('IdProcedimento', 'rel_protocolo_assunto rel_pro_ass', 'rel_pro_ass.id_protocolo', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);





  }
}
