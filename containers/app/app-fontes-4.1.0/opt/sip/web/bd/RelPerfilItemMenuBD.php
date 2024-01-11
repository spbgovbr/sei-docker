<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 21/03/2007 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class RelPerfilItemMenuBD extends InfraBD {

  public function __construct(InfraIBanco $objInfraIBanco) {
    parent::__construct($objInfraIBanco);
  }

}

?>