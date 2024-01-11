<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/04/2012 - criado por mga
*
*/

require_once dirname(__FILE__).'/../../SEI.php';

class ParametrosEditorDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ,'DocumentoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ,'UnidadeDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjContatoDTODestinatarios');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'Tags');
  }
}
?>