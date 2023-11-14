<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/07/2019 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class UsuarioFederacaoBD extends InfraBD {

  public function __construct(InfraIBanco $objInfraIBanco){
  	 parent::__construct($objInfraIBanco);
  }

}
