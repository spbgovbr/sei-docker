<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 23/09/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class ItemEtapaDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return 'item_etapa';
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdItemEtapa', 'id_item_etapa');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdEtapaTrabalho', 'id_etapa_trabalho');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Ordem', 'ordem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeEtapaTrabalho', 'nome', 'etapa_trabalho');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAtivoEtapaTrabalho', 'sin_ativo', 'etapa_trabalho');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdPlanoTrabalhoEtapaTrabalho', 'id_plano_trabalho', 'etapa_trabalho');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomePlanoTrabalho', 'nome', 'plano_trabalho');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAtivoPlanoTrabalho', 'sin_ativo', 'plano_trabalho');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelItemEtapaUnidadeDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelItemEtapaSerieDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelItemEtapaDocumentoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjAndamentoPlanoTrabalhoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinUnidadeAcesso');

    $this->configurarPK('IdItemEtapa', InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarExclusaoLogica('SinAtivo', 'N');

    $this->configurarFK('IdEtapaTrabalho', 'etapa_trabalho', 'id_etapa_trabalho');
    $this->configurarFK('IdPlanoTrabalhoEtapaTrabalho', 'plano_trabalho', 'id_plano_trabalho');
  }
}
