<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/11/2011 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class AcessoINT extends InfraINT {
	
  public static function pesquisarCredenciaisProcesso($numIdUsuario, $numIdUnidade, $dblIdProtocolo){

    $objAcessoDTO = new AcessoDTO();
    $objAcessoDTO->setDblIdProtocolo($dblIdProtocolo);
    $objAcessoDTO->setNumIdUsuario($numIdUsuario);
    $objAcessoDTO->setNumIdUnidade($numIdUnidade);
    $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_CREDENCIAL_PROCESSO);
    
    $objAcessoRN = new AcessoRN();
     	
  	return array('Total' => $objAcessoRN->contar($objAcessoDTO));
  }
	
}
?>