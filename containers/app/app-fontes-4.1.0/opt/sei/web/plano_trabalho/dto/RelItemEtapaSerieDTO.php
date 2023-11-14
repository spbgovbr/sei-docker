<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 29/09/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class RelItemEtapaSerieDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return 'rel_item_etapa_serie';
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdItemEtapa', 'id_item_etapa');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSerie', 'id_serie');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdEtapaTrabalhoItemEtapa', 'id_etapa_trabalho', 'item_etapa');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeItemEtapa', 'nome', 'item_etapa');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeSerie', 'nome', 'serie');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'StaAplicabilidadeSerie', 'sta_aplicabilidade', 'serie');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdPlanoTrabalhoEtapaTrabalho', 'id_plano_trabalho', 'etapa_trabalho');

    $this->configurarPK('IdItemEtapa', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdSerie', InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdItemEtapa', 'item_etapa', 'id_item_etapa');
    $this->configurarFK('IdEtapaTrabalhoItemEtapa', 'etapa_trabalho', 'id_etapa_trabalho');
    $this->configurarFK('IdSerie', 'serie', 'id_serie');
  }
}
