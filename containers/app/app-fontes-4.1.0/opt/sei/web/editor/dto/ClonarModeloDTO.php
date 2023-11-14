<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 02/12/2011 - criado por bcu
*
*
*/

require_once dirname(__FILE__).'/../../SEI.php';

class ClonarModeloDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
     $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdModeloOrigem');
		 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'NomeDestino');
  }
}
?>