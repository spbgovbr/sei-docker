<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 23/05/2006 - criado por MGA
 *
 * @package infra_php
 */
 
 /*
  class InfraTrataErros {

    public function __construct(){
  	  error_reporting(E_ALL);
      set_error_handler(array(&$this, 'infraGerarExcecao'), E_ALL);
    }
    
    public function infraGerarExcecao($errno, $errmsg, $filename, $linenum) {
      
      $strTipoErro = array (
                            E_ERROR           => "Error",
                            E_WARNING         => "Warning",
                            E_PARSE           => "Parsing Error",
                            E_NOTICE          => "Notice",
                            E_CORE_ERROR      => "Core Error",
                            E_CORE_WARNING    => "Core Warning",
                            E_COMPILE_ERROR   => "Compile Error",
                            E_COMPILE_WARNING => "Compile Warning",
                            E_USER_ERROR      => "User Error",
                            E_USER_WARNING    => "User Warning",
                            E_USER_NOTICE     => "User Notice",
                            E_STRICT          => "Runtime Notice"
                           );
  										 
  		
  		$msg = str_replace("\n",'<br />',$errmsg);
  		$msg = $strTipoErro[$errno].': '.$filename.' linha:'.$linenum.'.'."\n".'<br />'.$msg."\n";
  		if ($errno != E_NOTICE){

        InfraDebug::getInstance()->gravarInfra('[InfraTrataErros->infraGerarExcecao] 10: ' . $msg);

        throw new Exception($msg);

  		} else {

        InfraDebug::getInstance()->gravarInfra('[InfraTrataErros->infraGerarExcecao] 20: ' . $msg);

  		}
    }

    public function configurar() {
  	  error_reporting(E_ALL);
      set_error_handler(array(&$this, 'infraGerarExcecao'));
    }

  }
  
  $objInfraTrataErros = new InfraTrataErros();
 */
?>