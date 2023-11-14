<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/09/2018 - criado por fbv@trf4.jus.br
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class ImportarRecursosDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
     $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdSistema');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdPerfil');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdMenu');
		 
		 //array com recursos e seus menus
		 $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'RecursosMenus');
  }
}
?>