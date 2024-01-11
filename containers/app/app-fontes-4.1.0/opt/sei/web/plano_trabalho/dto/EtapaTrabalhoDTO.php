<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/09/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class EtapaTrabalhoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return 'etapa_trabalho';
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdEtapaTrabalho', 'id_etapa_trabalho');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdPlanoTrabalho', 'id_plano_trabalho');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Ordem', 'ordem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomePlanoTrabalho', 'nome', 'plano_trabalho');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAtivoPlanoTrabalho', 'sin_ativo', 'plano_trabalho');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjItemEtapaDTO');

    $this->configurarPK('IdEtapaTrabalho', InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarExclusaoLogica('SinAtivo', 'N');

    $this->configurarFK('IdPlanoTrabalho', 'plano_trabalho', 'id_plano_trabalho');
  }
}
