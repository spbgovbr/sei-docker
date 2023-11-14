<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
*/

require_once dirname(__FILE__).'/../../SEI.php';

class ConsultaProcessualBD extends InfraBD {

  public function __construct(InfraIBanco $objInfraIBanco){
  	 parent::__construct($objInfraIBanco);
  }

}
?>