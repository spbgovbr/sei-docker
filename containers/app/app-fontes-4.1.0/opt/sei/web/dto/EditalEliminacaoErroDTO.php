<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/07/2021 - criado por mgb29
*
* Versão do Gerador de Código: 1.43.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class EditalEliminacaoErroDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'edital_eliminacao_erro';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdEditalEliminacaoErro', 'id_edital_eliminacao_erro');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdEditalEliminacaoConteudo', 'id_edital_eliminacao_conteudo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Erro', 'dth_erro');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'TextoErro', 'texto_erro');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdEditalEliminacao', 'eec.id_edital_eliminacao', 'edital_eliminacao_conteudo eec');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdAvaliacaoDocumental', 'eec.id_avaliacao_documental', 'edital_eliminacao_conteudo eec');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdProcedimentoAvaliacaoDocumental', 'ad.id_procedimento', 'avaliacao_documental ad');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdProcedimentoProcedimento', 'id_procedimento', 'procedimento');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdTipoProcedimentoProcedimento', 'id_tipo_procedimento', 'procedimento');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'ProtocoloProcedimentoFormatado', 'protocolo_formatado', 'protocolo');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeTipoProcedimento', 'nome', 'tipo_procedimento');

    $this->configurarPK('IdEditalEliminacaoErro',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdEditalEliminacaoConteudo', 'edital_eliminacao_conteudo eec', 'eec.id_edital_eliminacao_conteudo');
    $this->configurarFK('IdAvaliacaoDocumental', 'avaliacao_documental ad', 'ad.id_avaliacao_documental');
    $this->configurarFK('IdProcedimentoAvaliacaoDocumental', 'procedimento', 'id_procedimento');
    $this->configurarFK('IdProcedimentoProcedimento', 'protocolo', 'id_protocolo');
    $this->configurarFK('IdTipoProcedimentoProcedimento', 'tipo_procedimento', 'id_tipo_procedimento');


  }
}
