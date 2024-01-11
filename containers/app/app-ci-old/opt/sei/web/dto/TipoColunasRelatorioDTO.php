<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class TipoColunasRelatorioDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'ColunaNome');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'ColunaAtributo');

  }
}
?>