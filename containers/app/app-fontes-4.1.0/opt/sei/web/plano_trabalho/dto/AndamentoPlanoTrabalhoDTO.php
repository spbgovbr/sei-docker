<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 10/10/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class AndamentoPlanoTrabalhoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return 'andamento_plano_trabalho';
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdAndamentoPlanoTrabalho', 'id_andamento_plano_trabalho');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdPlanoTrabalho', 'id_plano_trabalho');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'IdProcedimento', 'id_procedimento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdTarefaPlanoTrabalho', 'id_tarefa_plano_trabalho');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuarioOrigem', 'id_usuario_origem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidadeOrigem', 'id_unidade_origem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Execucao', 'dth_execucao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaSituacao', 'sta_situacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomePlanoTrabalho', 'nome', 'plano_trabalho');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUnidadeOrigem', 'uo.sigla', 'unidade uo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoUnidadeOrigem', 'uo.descricao', 'unidade uo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuarioOrigem', 'sigla', 'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuarioOrigem', 'nome', 'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeTarefaPlanoTrabalho', 'nome', 'tarefa_plano_trabalho');


    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdEtapaTrabalho');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdItemEtapa');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'SituacaoAndamentoPlanoTrabalhoDTO');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjAtributoAndamPlanoTrabDTO');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelItemEtapaDocumentoDTO');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Descricao');

    $this->configurarPK('IdAndamentoPlanoTrabalho', InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdPlanoTrabalho', 'plano_trabalho', 'id_plano_trabalho');
    $this->configurarFK('IdUnidadeOrigem', 'unidade uo', 'uo.id_unidade');
    $this->configurarFK('IdUsuarioOrigem', 'usuario', 'id_usuario');
    $this->configurarFK('IdTarefaPlanoTrabalho', 'tarefa_plano_trabalho', 'id_tarefa_plano_trabalho');
  }
}
