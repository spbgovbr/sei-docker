<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/10/2011 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class FinalizarCredencialDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdTarefa');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjAtividadeDTO');
  }
} 
?>