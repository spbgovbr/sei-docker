<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 29/09/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class RelItemEtapaDocumentoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return 'rel_item_etapa_documento';
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'IdDocumento', 'id_documento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdItemEtapa', 'id_item_etapa');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'ProtocoloDTO');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeItemEtapa', 'nome', 'item_etapa');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdEtapaTrabalhoItemEtapa', 'id_etapa_trabalho', 'item_etapa');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeEtapaTrabalho', 'nome', 'etapa_trabalho');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdPlanoTrabalhoEtapaTrabalho', 'id_plano_trabalho', 'etapa_trabalho');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomePlanoTrabalho', 'nome', 'plano_trabalho');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdDocumentoDocumento', 'id_documento', 'documento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdProcedimentoDocumento', 'id_procedimento', 'documento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdSerieDocumento', 'id_serie', 'documento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeSerie', 'nome', 'serie');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdUnidadeGeradoraProtocolo', 'id_unidade_geradora', 'protocolo');

    $this->configurarPK('IdDocumento', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdItemEtapa', InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdItemEtapa', 'item_etapa', 'id_item_etapa');
    $this->configurarFK('IdEtapaTrabalhoItemEtapa', 'etapa_trabalho', 'id_etapa_trabalho');
    $this->configurarFK('IdPlanoTrabalhoEtapaTrabalho', 'plano_trabalho', 'id_plano_trabalho');
    $this->configurarFK('IdDocumento', 'documento', 'id_documento');
    $this->configurarFK('IdDocumentoDocumento', 'protocolo', 'id_protocolo');
    $this->configurarFK('IdSerieDocumento', 'serie', 'id_serie');
  }
}
