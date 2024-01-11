<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 12/11/2007 - criado por MGA
 *
 */

require_once dirname(__FILE__).'/SEI.php';

class MailSEI {
	
 	private static $instance = null;
 	private $arrObjEmailDTO = null;

 	public static function getInstance() { 
	    if (self::$instance == null) { 
        self::$instance = new MailSEI();
	    } 
	    return self::$instance; 
	} 
 	 
	private function __construct(){
	  $this->limpar();
	}
	
	public function adicionar(EmailDTO $objEmailDTO){
 	  $this->arrObjEmailDTO[] = $objEmailDTO;
	}

	public function limpar(){
 	  $this->arrObjEmailDTO = array();
  }

	public function enviar(){

 	  if (InfraArray::contar($this->arrObjEmailDTO)) {
      EmailRN::processar($this->arrObjEmailDTO);
    }

    $this->limpar();
	}
}
?>