<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4Є REGIГO
 * 
 * 01/06/2006 - criado por MGA
 *
 * @package infra_php
 */

   
abstract class InfraRN {
	
	private static $objInfraIBanco = null;

  public function __construct(){
  }
  
  protected abstract function inicializarObjInfraIBanco();
  	
  public function __call( $func, $args ) {

    if (InfraDebug::isBolProcessar()) {
      InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] ' . get_class($this) . '.' . $func);
    }

		$bolFlagInicializou = false;
		$bolFlagConexao = false;
		$bolManterConexaoAberta = false;
		$bolFlagTransacao = false;
		$bolFlagConectado = false;
		$bolFlagControlado = false;
		$strSufixo = '';
  	
  	try {
      //InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] 10 : '.$func);

  		if (method_exists($this, $func.'Conectado')) {
  			$bolFlagConectado = true;
  			$strSufixo = 'Conectado';
  		}

  		if (method_exists($this, $func.'Controlado')) {
  			$bolFlagControlado = true;
  			$strSufixo = 'Controlado';
  		}
  		
  		if (!$bolFlagConectado && !$bolFlagControlado) {
  			throw new InfraException('Mйtodo ['.get_class($this).'.'.$func.'] nгo encontrado.');
  		}
  		
  		if ($bolFlagConectado && $bolFlagControlado) {
  			throw new InfraException('Mйtodo ['.get_class($this).'.'.$func.'] existe como Conectado e Controlado simultaneamente.');
  		}
  		
     	//InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] 20');
     	
     	$objInfraIBanco = $this->inicializarObjInfraIBanco();

		  if ($objInfraIBanco===null){
		    throw new InfraException('Nгo foi possнvel inicializar o banco de dados para a classe '.get_class($this).'.');
		  }

	    $bolManterConexaoAberta = $objInfraIBanco->isBolManterConexaoAberta();

			if (self::$objInfraIBanco===null){
			  //InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] 30');
				$this->setObjInfraIBanco($objInfraIBanco);
				$bolFlagInicializou = true;
			}else if ($this->getObjInfraIBanco()->getIdBanco()!==$objInfraIBanco->getIdBanco()){

        if (InfraDebug::isBolProcessar()) {
          InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] Banco da InfraRN:' . $this->getObjInfraIBanco()->getIdBanco());
          InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] Banco da ' . get_class($this) . ':' . $objInfraIBanco->getIdBanco());
        }

	      throw new InfraException('Classe '.get_class($this).' esta configurada para um banco de dados diferente do utilizado pela classe InfraRN.');
			}
			
			//InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] 40');
			
			//Se jб tem conexгo aberta com este banco 
			if ($this->getObjInfraIBanco()->getIdConexao()!==null){
			  
			  //InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] 50');
			  
			  //Se a conexгo nгo й do mesmo banco ERRO
  			if ($this->getObjInfraIBanco()->getIdConexao()!==$this->getObjInfraIBanco()->getIdBanco()){
  			  
  			  //InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] Estava conectado com: '.$this->getObjInfraIBanco()->getIdConexao());
  			  //InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] Tentou abrir conexгo com: '.$this->getObjInfraIBanco()->getIdBanco());
  			  //throw new InfraException('Tentativa de abertura de conexгo com outro banco de dados sem fechar a conexгo anterior.');
  			  
  			  if ($this->getObjInfraIBanco()->isBolProcessandoTransacao()){
  			    throw new InfraException('Tentativa de abertura de conexгo com outro banco de dados sem fechar a transaзгo anterior.');
  			  }
  			  
  			  //fecha conexao com o banco anterior
  			  $this->getObjInfraIBanco()->fecharConexao();
  			  
  			  $bolFlagConexao = true;
  			  	
  			  //abre a conexгo
  			  $this->getObjInfraIBanco()->abrirConexao();
  			  
  			}
  			//InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] Reutilizando conexгo: '.$this->getObjInfraIBanco()->getIdConexao());
  			
			  
  			//somente abre transaзгo se: mйtodo controlado E o banco nгo esta em uma transaзгo 
  			if ($bolFlagControlado && !$this->getObjInfraIBanco()->isBolProcessandoTransacao()){
  			  
  			  //InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] 60');
  			  
  			  $this->getObjInfraIBanco()->abrirTransacao();
  			  $bolFlagTransacao = true;
  			}
  			
			}else{
			  
			  //InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] 70');
			  
			  $bolFlagConexao = true;
			  
			  //Sem conexгo aberta abre a conexгo 
				$this->getObjInfraIBanco()->abrirConexao();
				
				//abre transaзгo se for um controlado
				if ($bolFlagControlado){
  			  //InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] 80');
  				$this->getObjInfraIBanco()->abrirTransacao();
  				
  				$bolFlagTransacao = true;
				}
			}
			
     	//InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] 90');
			if (count($args)==0){
				$ret = call_user_func(array($this,$func.$strSufixo));
			}else if (count($args)==1){
			  $ret = call_user_func(array($this,$func.$strSufixo),$args[0]);	
			}else if (count($args)==2){
			  if ((is_object($args[1]) && get_class($args[1])=='InfraException') || $this instanceof InfraParametro || $this instanceof InfraDadoUsuario || $this instanceof InfraAuditoria || $this instanceof InfraLog){
			    $ret = call_user_func(array($this,$func.$strSufixo),$args[0],$args[1]);
			  }else{
			    throw new InfraException('Tipo invбlido para o segundo parвmetro do mйtodo ['.$func.'].');
			  }
		  }else{
			  throw new InfraException('Mйtodo ['.$func.'] chamado com mais de um parвmetro.');
		  }
	  	 
  		//InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] 100');
  		 
  		if ($bolFlagTransacao){
	      //InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] 110');
	    	$this->getObjInfraIBanco()->confirmarTransacao();
  		}
  		
	    if ($bolFlagConexao && !$bolManterConexaoAberta) {
    	  //InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] 120');
        $this->getObjInfraIBanco()->fecharConexao();
	    }
	    
	    if ($bolFlagInicializou){
     	  //InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] 130');
	      $this->setObjInfraIBanco(null);  
	    }

	    //InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] 140');
	    
	    return $ret;
	    
  	}catch(Exception $e){
  		//InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] 150');
  		if ($bolFlagTransacao===true){
  			try {
					if ($this->getObjInfraIBanco()!=null){
  				  //InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] 160');
	          $this->getObjInfraIBanco()->cancelarTransacao();
					}
  			}catch(Exception $e2){
					//Nao trata para evitar a perda do erro original   	
	      }  

        //InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] 170');
  		}
  		
  		if ($bolFlagConexao===true && !$bolManterConexaoAberta) {
  		
  			try {
					if ($this->getObjInfraIBanco()!=null){
						//InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] 180');
	          $this->getObjInfraIBanco()->fecharConexao();
					}
  			}catch(Exception $e3){
					//Nao trata para evitar a perda do erro original
	      }  
  		}
  		
  		if ($bolFlagInicializou){
  		  $this->setObjInfraIBanco(null);
  		}
  		
  		//InfraDebug::getInstance()->gravarInfra('[InfraRN->__call] 190');
  		throw new InfraException('Erro processando operaзгo '.$func.'.',$e);
  	}  
  }
	
  protected final function getObjInfraIBanco() {
    if (self::$objInfraIBanco!==null){
      return self::$objInfraIBanco;  
    }
  	return $this->inicializarObjInfraIBanco();
  }
    
  private function setObjInfraIBanco($objInfraIBanco) {
  	self::$objInfraIBanco = $objInfraIBanco;
  }
} 
?>