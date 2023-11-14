<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/01/2007 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class ClonarHierarquiaDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
     $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdHierarquiaOrigem');
		 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'NomeDestino');
  }
}
?>