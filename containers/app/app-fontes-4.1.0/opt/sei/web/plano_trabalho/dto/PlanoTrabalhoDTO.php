<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/09/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class PlanoTrabalhoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return 'plano_trabalho';
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdPlanoTrabalho', 'id_plano_trabalho');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjTipoProcedimentoDTO');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelSeriePlanoTrabalhoDTO');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjEtapaTrabalhoDTO');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdProcedimento');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdSerie');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjAndamentoPlanoTrabalhoDTO');

    $this->configurarPK('IdPlanoTrabalho', InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarExclusaoLogica('SinAtivo', 'N');
  }
}
