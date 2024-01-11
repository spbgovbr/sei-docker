<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 01/08/2011 - criado por MGA
 *
 */
 
require_once dirname(__FILE__).'/SEI.php';
 
class NavegadorSEI extends InfraNavegador {
 	
 	private static $instance = null;
 	
 	public static function getInstance() 
	{ 
	    if (NavegadorSEI::$instance == null) { 
        NavegadorSEI::$instance = new NavegadorSEI(BancoSEI::getInstance());
	    } 
	    return NavegadorSEI::$instance; 
	} 
	
  public function getNumTipoPK(){
		return InfraDTO::$TIPO_PK_NATIVA;
	}
}
?>