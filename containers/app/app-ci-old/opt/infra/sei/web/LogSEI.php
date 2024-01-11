<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 14/06/2006 - criado por MGA
 *
 */
 
require_once dirname(__FILE__).'/SEI.php';
 
class LogSEI extends InfraLog {
 	
 	private static $instance = null;
 	
 	public static function getInstance() 
	{ 
	    if (LogSEI::$instance == null) { 
        LogSEI::$instance = new LogSEI(BancoSEI::getInstance());
	    } 
	    return LogSEI::$instance; 
	}

	public function getNumTipoPK(){
		return InfraDTO::$TIPO_PK_NATIVA;
	}

	public function isBolTratarTipos(){
		return true;
	}
}
?>