<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 29/09/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class RelItemEtapaUnidadeDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return 'rel_item_etapa_unidade';
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdItemEtapa', 'id_item_etapa');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeItemEtapa', 'nome', 'item_etapa');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUnidade', 'sigla', 'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoUnidade', 'descricao', 'unidade');

    $this->configurarPK('IdUnidade', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdItemEtapa', InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdItemEtapa', 'item_etapa', 'id_item_etapa');
    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');
  }
}
