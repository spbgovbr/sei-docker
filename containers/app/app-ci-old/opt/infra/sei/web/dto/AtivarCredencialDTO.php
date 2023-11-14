<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/06/2016 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class AtivarCredencialDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
  	$this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUsuario');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjProcedimentoDTO');
  }
}
?>