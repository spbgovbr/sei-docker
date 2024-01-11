<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class EditalEliminacaoConteudoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'edital_eliminacao_conteudo';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdEditalEliminacaoConteudo', 'id_edital_eliminacao_conteudo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdAvaliacaoDocumental', 'id_avaliacao_documental');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdEditalEliminacao', 'id_edital_eliminacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuarioInclusao', 'id_usuario_inclusao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Inclusao', 'dth_inclusao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuarioInclusao', 'u.nome', 'usuario u');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuarioInclusao', 'u.sigla', 'usuario u');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdEditalEliminacaoEditalEliminacao', 'ee.id_edital_eliminacao', 'edital_eliminacao ee');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'StaSituacaoEditalEliminacao', 'ee.sta_edital_eliminacao', 'edital_eliminacao ee');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdProcedimentoAvaliacaoDocumental', 'ad.id_procedimento', 'avaliacao_documental ad');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'StaSituacaoAvaliacaoDocumental', 'ad.sta_avaliacao', 'avaliacao_documental ad');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdUnidadeGeradoraProtocolo', 'pt.id_unidade_geradora', 'protocolo pt');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdOrgaoUnidadeGeradoraProtocolo', 'u.id_orgao', 'unidade u');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgaoUnidadeGeradoraProtocolo', 'o.sigla', 'orgao o');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloProcedimentoFormatado','pt.protocolo_formatado','protocolo pt');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdTipoProcedimentoProcedimento','pc.id_tipo_procedimento','procedimento pc');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeTipoProcedimento','tp.nome','tipo_procedimento tp');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'QtdArquivamentosRemanescentes');

    $this->configurarPK('IdEditalEliminacaoConteudo',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdEditalEliminacao', 'edital_eliminacao ee', 'ee.id_edital_eliminacao');
    $this->configurarFK('IdUsuarioInclusao', 'usuario u', 'u.id_usuario');
    $this->configurarFK('IdAvaliacaoDocumental', 'avaliacao_documental ad', 'ad.id_avaliacao_documental');
    $this->configurarFK('IdProcedimentoAvaliacaoDocumental', 'protocolo pt', 'pt.id_protocolo');
    $this->configurarFK('IdProcedimentoAvaliacaoDocumental', 'procedimento pc', 'pc.id_procedimento');
    $this->configurarFK('IdTipoProcedimentoProcedimento', 'tipo_procedimento tp', 'tp.id_tipo_procedimento');
    $this->configurarFK('IdUnidadeGeradoraProtocolo', 'unidade u', 'u.id_unidade');
    $this->configurarFK('IdOrgaoUnidadeGeradoraProtocolo', 'orgao o', 'o.id_orgao');


  }
}
