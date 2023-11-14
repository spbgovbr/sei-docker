<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 10/10/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class AtributoAndamPlanoTrabDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return 'atributo_andam_plano_trab';
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdAtributoAndamPlanoTrab', 'id_atributo_andam_plano_trab');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdAndamentoPlanoTrabalho', 'id_andamento_plano_trabalho');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Chave', 'chave');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Valor', 'valor');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdOrigem', 'id_origem');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdPlanoTrabalhoAndamentoPlanoTrabalho', 'id_plano_trabalho', 'andamento_plano_trabalho');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdProcedimentoAndamentoPlanoTrabalho', 'id_procedimento', 'andamento_plano_trabalho');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdTarefaPlanoTrabalhoAndamentoPlanoTrabalho', 'id_tarefa_plano_trabalho', 'andamento_plano_trabalho');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'StaSituacaoAndamentoPlanoTrabalho', 'sta_situacao', 'andamento_plano_trabalho');

    $this->configurarPK('IdAtributoAndamPlanoTrab', InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdAndamentoPlanoTrabalho', 'andamento_plano_trabalho', 'id_andamento_plano_trabalho');
  }
}
