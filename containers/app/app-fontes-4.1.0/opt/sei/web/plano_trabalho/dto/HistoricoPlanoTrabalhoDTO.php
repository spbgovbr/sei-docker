<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 10/10/2022 - criado por mgb29
 *
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class HistoricoPlanoTrabalhoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdPlanoTrabalho');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdProcedimento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdItemEtapa');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'StaHistorico');
  }
}

?>