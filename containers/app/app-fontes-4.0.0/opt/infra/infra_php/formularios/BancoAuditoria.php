<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 28/08/2018 - criado por MGA
 *
 */
 
 //require_once 'Infra.php';
 
 class BancoAuditoria {
	 
 	private static $instance = null;
 	
 	public static function getInstance(){ 
	    return self::$instance; 
	} 

	public static function setObjInfraIBanco($objInfraIBanco){
     self::$instance = $objInfraIBanco;	  
	}
 }
 
?>