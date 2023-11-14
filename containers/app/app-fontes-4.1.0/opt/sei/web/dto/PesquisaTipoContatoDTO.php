<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 29/10/2015 - criado por marcio_db
 *
 */

require_once dirname(__FILE__).'/../SEI.php';

class PesquisaTipoContatoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'IdTipoContato');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'StaAcesso');
  }
}
?>