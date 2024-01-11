<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/10/2019 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class AtributoOuvidoriaDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Id');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Nome');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Titulo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Valor');
  }
}
?>