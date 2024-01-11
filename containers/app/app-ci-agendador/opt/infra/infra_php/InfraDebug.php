<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 21/06/2006 - criado por MGA
 *
 * @package infra_php
 */
 
class InfraDebug {
 	private static $instance = null;
 	private $bolFlagSessao = null;
	private static $bolProcessar = false;
 	
 	private static $POS_CONTADOR = 0;
 	private static $POS_MENSAGENS = 1;
 	private static $POS_INFRA = 2;
 	private static $POS_ECHO = 3;
 	private static $POS_LIGADO = 4;
 	
 	public static function getInstance(){ 
    if (self::$instance == null) {
      self::$instance = new InfraDebug();
    } 
    return self::$instance; 
	} 
	
	public function __construct(){
		
		$this->bolFlagSessao = true;
		
		if (!isset($_SESSION['INFRA_DEBUG'])){
		  $_SESSION['INFRA_DEBUG'] = array();
			$_SESSION['INFRA_DEBUG'][self::$POS_CONTADOR] = 0;
			$_SESSION['INFRA_DEBUG'][self::$POS_MENSAGENS] = '';
			$_SESSION['INFRA_DEBUG'][self::$POS_INFRA] = false;
			$_SESSION['INFRA_DEBUG'][self::$POS_ECHO] = false;
			$_SESSION['INFRA_DEBUG'][self::$POS_LIGADO] = false;
		}else {
			$this->setBolProcessar();
		}
  }

	private function setBolProcessar(){
		self::$bolProcessar = ($_SESSION['INFRA_DEBUG'][self::$POS_LIGADO] || $_SESSION['INFRA_DEBUG'][self::$POS_ECHO]);
	}

	public static function isBolProcessar(){
		return self::$bolProcessar;
	}

	public function setBolDebugInfra($bolDebugInfra){
  	$_SESSION['INFRA_DEBUG'][self::$POS_INFRA] = $bolDebugInfra;
  } 
  
  public function setBolEcho($bolEcho){
  	$_SESSION['INFRA_DEBUG'][self::$POS_ECHO] = $bolEcho;
		$this->setBolProcessar();
  }

  public function setBolLigado($bolLigado){
  	$_SESSION['INFRA_DEBUG'][self::$POS_LIGADO] = $bolLigado;
		$this->setBolProcessar();
  }

  public function isBolDebugInfra(){
  	return $_SESSION['INFRA_DEBUG'][self::$POS_INFRA];
  } 
  
  public function isBolEcho(){
  	return $_SESSION['INFRA_DEBUG'][self::$POS_ECHO];
  }

  public function isBolLigado(){
  	return $_SESSION['INFRA_DEBUG'][self::$POS_LIGADO];
  }

 	public function gravarInfra($str) {

		if (self::$bolProcessar && @$_SESSION['INFRA_DEBUG'][self::$POS_INFRA] === true) {

			$_SESSION['INFRA_DEBUG'][self::$POS_CONTADOR]++;

			if (@$_SESSION['INFRA_DEBUG'][self::$POS_LIGADO] === true) {
				$_SESSION['INFRA_DEBUG'][self::$POS_MENSAGENS] .= $this->formatarTexto($str);
			}

			if (@$_SESSION['INFRA_DEBUG'][self::$POS_ECHO] === true) {
        if (!InfraUtil::isBolLinhaDeComando()) {
          echo nl2br($this->formatarTexto($str));
          flush();
        }else{
          echo $this->formatarTexto($str);
        }
			}
		}
 	}

	public function gravar($str) {

		if (self::$bolProcessar) {

			$_SESSION['INFRA_DEBUG'][self::$POS_CONTADOR]++;

			if (@$_SESSION['INFRA_DEBUG'][self::$POS_LIGADO] === true) {
				$_SESSION['INFRA_DEBUG'][self::$POS_MENSAGENS] .= $this->formatarTexto($str);
			}

			if (@$_SESSION['INFRA_DEBUG'][self::$POS_ECHO] === true) {
        if (!InfraUtil::isBolLinhaDeComando()) {
          echo nl2br($this->formatarTexto($str));
          flush();
        }else{
          echo $this->formatarTexto($str);
        }
			}
		}
 	}
 	
 	public function ler(){
 		return $_SESSION['INFRA_DEBUG'][self::$POS_MENSAGENS];
 	}
 	
 	public function limpar(){
		$_SESSION['INFRA_DEBUG'][self::$POS_CONTADOR] = 0;
 		$_SESSION['INFRA_DEBUG'][self::$POS_MENSAGENS] = '';
 	}
 	
 	private function formatarTexto($str){
 	  
		$strRet = '';
		if ($this->bolFlagSessao){
		  $strRet .= "\n------------------------------------------------------------------------------\n";
			$this->bolFlagSessao=false;
		}
 		$strRet .= str_pad($_SESSION['INFRA_DEBUG'][self::$POS_CONTADOR], 5, '0', STR_PAD_LEFT).' - '.str_replace('<br>',"\n",$str)."\n";
		return $strRet;
 	}
 	
 	public function getStrDebug(){
    return $_SESSION['INFRA_DEBUG'][self::$POS_MENSAGENS];
 	}
}
?>