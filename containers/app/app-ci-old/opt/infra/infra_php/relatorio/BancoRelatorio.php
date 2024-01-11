<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 23/03/2009 - criado por MGA
 *
 */
 
 require_once dirname(__FILE__).'/Relatorio.php';
 
 class BancoRelatorio {
	 
 	private static $instance = null;
 	
 	public static function getInstance(){ 
	    return self::$instance; 
	} 

	public static function setObjInfraIBanco($objInfraIBanco){
     self::$instance = $objInfraIBanco;	  
	}
 }
 
?>