<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 23/05/2006 - criado por MGA
 *
 */
 
 //require_once 'Infra.php';
 
 class PaginaInfra {
	 
 	private static $instance = null;
 	
 	public static function getInstance(){ 
	    return self::$instance; 
	} 

	public static function setObjInfraPagina($objInfraPagina){
     self::$instance = $objInfraPagina;	  
	}
 }
 
?>