<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 18/05/2006 - criado por MGA
 *
 * @package infra_php
 */


class InfraException extends Exception {
  private $objException = null;
  private $strDetalhes = null;
  private $bolPermitirGravacaoLog = null;
	private $strStaTipoLog = null;
  private $arrObjInfraValidacao = null;
  private $strTrace = null;

  
  public function __construct($strDescricao=null, $e=null, $strDetalhes=null, $bolPermitirGravacaoLog=null, $strStaTipoLog = null) {
    
    parent::__construct();
    
    $this->setStrDescricao(null);
    $this->setObjException(null);
    $this->setStrDetalhes(null);
    //$this->setBolPermitirGravacaoLog(null);
		//$this->setStrStaTipoLog($strStaTipoLog);
    $this->setArrObjInfraValidacao(null);
    $this->setStrTrace('');
    
    //Se passou pelo menos um parâmetro
    if ( $strDescricao!=null || $e!=null || $strDetalhes!=null || $bolPermitirGravacaoLog!==null || $strStaTipoLog!==null){
    	
    	//Se recebeu uma InfraException apenas repassa os dados originais
      if ( $e instanceof InfraException){
        $this->setArrObjInfraValidacao($e->getArrObjInfraValidacao());
      	$this->setStrDescricao($e->getStrDescricao());
      	$this->setObjException($e->getObjException());
      	$this->setStrDetalhes($e->getStrDetalhes());
      	
      	//se passou um boolean 
      	if (is_bool($bolPermitirGravacaoLog)){
      	  $this->setBolPermitirGravacaoLog($bolPermitirGravacaoLog);
      	}else{
      	  $this->setBolPermitirGravacaoLog($e->isBolPermitirGravacaoLog());
      	}

				//se não passou nulo
				if ($strStaTipoLog!==null) {
					$this->setStrStaTipoLog($strStaTipoLog);
				}else{
					$this->setStrStaTipoLog($e->getStrStaTipoLog());
				}

      	$this->setStrTrace($e->getStrTrace());
      }else if ($e instanceof SoapFault )	{
        if (self::getTipoInfraException($e) == 'INFRA_VALIDACAO'){
          $this->adicionarValidacao($e->faultstring);
          $this->setStrDescricao($strDescricao);
          $this->setObjException(null);
          $this->setStrDetalhes($strDetalhes);
          $this->setBolPermitirGravacaoLog($bolPermitirGravacaoLog);
					$this->setStrStaTipoLog($strStaTipoLog);
          $this->setStrTrace(null);
        }else{
          $this->setArrObjInfraValidacao(null);
          $this->setStrDescricao($strDescricao);
          $this->setObjException($e);
          $this->setStrDetalhes($strDetalhes);
          $this->setBolPermitirGravacaoLog($bolPermitirGravacaoLog);
					$this->setStrStaTipoLog($strStaTipoLog);
          $this->setStrTrace($e->getTraceAsString());
        }
      }else{
      	
        //Texto específico repassado
        if ($strDescricao!==null){
          $this->setStrDescricao($strDescricao);
        }else if ($e!==null){
          //Nao passou descricao assume texto da exception
          $this->setStrDescricao($e);
        }else{
        	$this->setStrDescricao('Erro não identificado.');
        }
        
        $this->setStrDetalhes($strDetalhes);
        $this->setBolPermitirGravacaoLog($bolPermitirGravacaoLog);
				$this->setStrStaTipoLog($strStaTipoLog);
        $this->setObjException($e);
        $this->setArrObjInfraValidacao(null);
        
        if ($e!==null){
          $this->setStrTrace($e->getTraceAsString());  	
        }else{
          //Nao passou excecao assume trace desta excecao
          $this->setStrTrace($this->getTraceAsString());
        }
      }
    }
  }

	public function adicionarValidacao($strDescricao, $strAtributo=null){
		$objInfraValidacaoDTO = new InfraValidacaoDTO();
		$objInfraValidacaoDTO->setStrDescricao($strDescricao);
		$objInfraValidacaoDTO->setStrAtributo($strAtributo);
		if ($this->arrObjInfraValidacao==null){
			$this->arrObjInfraValidacao = array();
		}
	  $this->arrObjInfraValidacao[]=$objInfraValidacaoDTO;
	}

	public function contemValidacoes() {
	    if ( $this->arrObjInfraValidacao != null ){
	        if ( count($this->arrObjInfraValidacao) > 0 ){
	            return true;
	        }
	    }
	    return false;
	}
	
	public function lancarValidacao($strDescricao, $strAtributo=null, $e=null){
    if ( $this->arrObjInfraValidacao != null ){
      unset($this->arrObjInfraValidacao);
    }
    $this->arrObjInfraValidacao = array();
	  $this->adicionarValidacao($strDescricao,$strAtributo);
    $this->setObjException($e);
	  $this->lancarValidacoes();
	}
	
  public function lancarValidacoes() {
  	if ($this->contemValidacoes()){
  	  throw $this;
  	}
  }
	
	public function getArrObjInfraValidacao() {
	    return $this->arrObjInfraValidacao;
	}

	public function setArrObjInfraValidacao($arrObjInfraValidacao) {
	    $this->arrObjInfraValidacao = $arrObjInfraValidacao;
	}
	
	public function getObjException() {
	    return $this->objException;
	}
	
	private function setObjException($e) {

		if ($e != null) {
			if (!is_object($e)) {
				throw new Exception('Exceção não é um objeto: ' . $e);
			}

			if (!$e instanceof Exception && !$e instanceof Throwable) {
				throw new Exception('Objeto não é uma exceção: ' . get_class($e));
			}
		}

		$this->objException = $e;
	}
	
	public function getStrDescricao() {
	    return $this->message;
	}
	
	private function setStrDescricao($strDescricao) {
		$this->message = $strDescricao;
	}

  public function getStrDetalhes() {
	    return $this->strDetalhes;
	}
	
	private function setStrDetalhes($strDetalhes) {
		$this->strDetalhes = $strDetalhes;
	}

  public function isBolPermitirGravacaoLog() {
    return $this->bolPermitirGravacaoLog;
	}
	
	private function setBolPermitirGravacaoLog($bolPermitirGravacaoLog) {
    $this->bolPermitirGravacaoLog = $bolPermitirGravacaoLog;  
	}

	public function getStrStaTipoLog() {
		return $this->strStaTipoLog;
	}

	private function setStrStaTipoLog($strStaTipoLog) {
		$this->strStaTipoLog = $strStaTipoLog;
	}

	private function setStrTrace($strTrace){
		$this->strTrace = $strTrace;
	}
	
	public function getStrTrace(){
		return str_replace('#','\\n',$this->strTrace);
	}
	
	public function __toString() {
	    $str = '';
	    if ( $this->arrObjInfraValidacao != null ) {
	        for ($i=0; $i < count($this->arrObjInfraValidacao); $i++) {
	            if ( strlen($str) > 0 ){
	              $str .= "\\n";    
	            }
	            $str .= $this->arrObjInfraValidacao[$i]->getStrDescricao();
	        }
	    } else if ( $this->message != null ) {
	      $str = $this->message;
	    } else if ( $this->objException != null ) {
	      $str = $this->objException;
	    }
	    return $str;
	}

	public static function getTipoInfraException(SoapFault $sf){
	  $strInfraTipoExcecao = 'INFRA_ERRO';
	  if (is_object($sf->detail)){
	    if (!is_array($sf->detail->item)){
	      if ($sf->detail->item->key == 'infra_tipo_excecao'){
	        $strInfraTipoExcecao = $sf->detail->item->value;  
	      }
	    }else{
	      $arrItens = $sf->detail->item;
	      foreach($arrItens as $item){
	        if ($item->key == 'infra_tipo_excecao'){
	          $strInfraTipoExcecao = $item->value;
	          break;
	        }
	      }
	    }
	  }
	  return $strInfraTipoExcecao;	  
	}
	
	public static function inspecionar($e){

		$ret = '';

		if ( $e instanceof InfraException && $e->contemValidacoes()){
        $ret .= "Validação:\n".$e->__toString()."\n\n";
        $ret .= "Trilha de Processamento:\n".$e->getTraceAsString()."\n\n";
		}else{
		
			if ($e->__toString()!=''){
			  $ret .= "Descrição:\n".$e->__toString()."\n\n";
			}
			
			if ($e->getMessage()!=''){
			  $ret .= "Mensagem:\n".$e->getMessage()."\n\n";
			}
			
			if ($e->getTraceAsString()!=''){
			  $ret .= "Trilha de Processamento:\n".$e->getTraceAsString()."\n\n";
			}
			 
			if ( $e instanceof InfraException ){
				
				if ($e->getStrDetalhes()!=''){
				  $ret .= "Detalhes:\n".$e->getStrDetalhes()."\n\n";
				}
				if ( $e->getObjException() != null){
					if ($e->getObjException()->__toString()!=''){
					  $ret .= "Descrição Original:\n".$e->getObjException()->__toString()."\n\n"; //texto da exceção original
					}
		  	}
				if ($e->getStrTrace()!=''){
					$ret .= "Trilha de Processamento Original:\n".$e->getStrTrace()."\n\n";
				}
			}
		}
		return $ret;
	}
}
?>