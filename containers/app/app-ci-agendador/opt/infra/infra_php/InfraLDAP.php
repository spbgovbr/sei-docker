<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 01/06/2006 - criado por MGA
 *
 * @package infra_php
 */
 
	class InfraLDAP {
		private $contexto;
		private $servidor;
		private $porta; 
		private $usuario;
		private $senha;
		private $conexao;
		private $debug;
		
		public static $LDAP_GRUPO_REDE = 0;
		public static $LDAP_DN = 1;

    public static $TIPO_LDAP = 'LDAP';
    public static $TIPO_AD = 'AD';

    public static $MSG_TIPO_AUTENTICACAO_INVALIDO = 'Tipo da autenticação inválido.';
    public static $MSG_USUARIO_SENHA_INVALIDA = 'Usuário ou Senha Inválida.';
    public static $MSG_USUARIO_SENHA_PESQUISA_INVALIDA = 'Usuário ou Senha de pesquisa inválida.';
    public static $MSG_FALHA_PESQUISA_USUARIO = 'Erro pesquisando usuário.';
    public static $MSG_CONTEXTO_DO_USUARIO_NAO_ENCONTRADO = 'Não foi possível determinar o contexto do usuário.';
    public static $MSG_USUARIO_NAO_ENCONTRADO = 'Usuário não existe no contexto.';
    public static $MSG_FALHA_CONEXAO = 'Não foi possível estabelecer conexão com o servidor de autenticação.';
    public static $MGS_ERRO_DESCONHECIDO = 'Erro desconhecido validando usuário.';
    
    public function __construct() {
    	$this->conexao = null;
    	$this->debug = false;
    }
    
    public function setBolDebug($bolDebug){
      $this->debug = $bolDebug;
    }
    
    //ABRE CONEXÃO
    private function abrirConexao(){


      if (InfraDebug::isBolProcessar()) {
        InfraDebug::getInstance()->gravarInfra('[InfraLDAP->abrirConexao]');
      }

      $this->conexao = ldap_connect($this->servidor,$this->porta);
      //InfraDebug::getInstance()->gravarInfra('[InfraLDAP->abrirConexao] 20');
    }
    
    //FECHA CONEXÃO
    private function fecharConexao(){

      if (InfraDebug::isBolProcessar()) {
        InfraDebug::getInstance()->gravarInfra('[InfraLDAP->fecharConexao]');
      }


      ldap_close($this->conexao);

      //InfraDebug::getInstance()->gravarInfra('[InfraLDAP->fecharConexao] 20');
    }
    
    //ADAPTA O FORMATO DO CONTEXTO
    private function inverterContexto($cont) {
      $contexto_normal = str_replace('/', ',', $cont);
      $contexto_vetor = explode(',', $contexto_normal);
      $contexto_invertido = $contexto_vetor[count($contexto_vetor)-1];
      for ($i=count($contexto_vetor)-2; $i>=0; $i--) {
	      $contexto_invertido .= ','.$contexto_vetor[$i];
      }
      return $contexto_invertido;
    }

    public function autenticar($usuario, $senha, $servidor, $porta=389){
    	try{
    		
    		$objInfraException = new InfraException();
    		
	      $this->usuario = $usuario;
	      $this->senha = $senha;
	      $this->servidor = $servidor;
	      $this->porta = $porta;
	      
	      $this->abrirConexao();
	    	
	    	try{
	    		
	    	  ldap_bind($this->conexao, $this->usuario, utf8_encode($this->senha));
	    	  
	    	}catch(Exception $e){
	    		
          if (strpos(strtoupper($e->__toString()),'INVALID CREDENTIALS')!==false){
            $objInfraException->lancarValidacao(self::$MSG_USUARIO_SENHA_INVALIDA);
          }
	    		
	    		throw $e;
	    	}
	    	
	    	$this->fecharConexao();
	    	
	    	$objInfraException->lancarValidacoes();
	    	
      }catch(Exception $e2){
        
        try{
           $this->fecharConexao();
        }catch(Exception $e3){}
      	
        if ($e2 instanceof InfraException){ 
          if ($e2->contemValidacoes()){
             throw $e2;
          }
        }
        
        //Nao repassar o objeto de exceção porque o PHP mostra a senha na trilha de processamento
        
        if (strpos(strtoupper($e2->__toString()),'CAN\'T CONTACT LDAP SERVER')!==false){
          $objInfraException->lancarValidacao('Não foi possível estabelecer conexão com o servidor de autenticação.');
        }
        
        $objInfraException->lancarValidacao('Erro desconhecido autenticando usuário.');  
      }
    }
    
    //VALIDA O USUÁRIO
    private function validarUsuario() {
    	
    	$ret = null;
    	
      try{
  			$objInfraException = new InfraException();


        if (InfraDebug::isBolProcessar()) {
          InfraDebug::getInstance()->gravarInfra('[InfraLDAP->validarUsuario]');
        }


        $pesquisa = @ldap_search($this->conexao, $this->contexto, 'cn='.$this->usuario);
        //InfraDebug::getInstance()->gravarInfra('[InfraLDAP->validarUsuario] 20');
        if (ldap_errno($this->conexao) == 0) {
        	//InfraDebug::getInstance()->gravarInfra('[InfraLDAP->validarUsuario] 30');
  				$entry = ldap_first_entry($this->conexao, $pesquisa);
  				//InfraDebug::getInstance()->gravarInfra('[InfraLDAP->validarUsuario] 40');
  				if (!empty($entry)) {
  					//BUSCA O CONTEXTO COMPLETO DO USUÁRIO
  					//InfraDebug::getInstance()->gravarInfra('[InfraLDAP->validarUsuario] 50');
  					
  					$attrs = ldap_get_attributes($this->conexao, $entry);
  					//InfraDebug::getInstance()->gravarInfra('[InfraLDAP->validarUsuario] 60');
  					$dominio = $attrs['aliasedObjectName'][0];
  					//TENTA VALIDAR A SENHA
  					if (!empty($dominio)) {
  						//InfraDebug::getInstance()->gravarInfra('[InfraLDAP->validarUsuario] 70');
  						try {
  							
  						  ldap_bind($this->conexao, $dominio, utf8_encode($this->senha));
  						  
  						  $ret = array();
  						  $ret[self::$LDAP_GRUPO_REDE] = $this->obterOuUsuario($dominio);
  						  $ret[self::$LDAP_DN] = $dominio;
  						  
  						  //InfraDebug::getInstance()->gravarInfra('[InfraLDAP->validarUsuario] 80');
  						} catch (Exception $e){
  							$objInfraException->adicionarValidacao(self::$MSG_USUARIO_SENHA_INVALIDA);
  						}
  					} else {
  						$objInfraException->adicionarValidacao('Não foi possível determinar o contexto LDAP do usuário.');
  					}
  				} else {
  					$objInfraException->adicionarValidacao('Usuário não existe no contexto LDAP.');
  				}
        } else {
  			  $objInfraException->adicionarValidacao('Conexão com o LDAP não foi estabelecida.');
        }
        //InfraDebug::getInstance()->gravarInfra('[InfraLDAP->validarUsuario] 90');
  	    $objInfraException->lancarValidacoes();
  	    //InfraDebug::getInstance()->gravarInfra('[InfraLDAP->validarUsuario] 100');

  	    return $ret;
  	  
      }catch(Exception $e2){
        
        if ($e2 instanceof InfraException){
          if ($e2->contemValidacoes()){
             throw $e2;
          }
        }
        
        //Nao repassar o objeto de exceção porque o PHP mostra a senha na trilha de processamento
        
        $objInfraException = new InfraException();
        
        if (strpos(strtoupper($e2->__toString()),'CAN\'T CONTACT LDAP SERVER')!==false){
          $objInfraException->lancarValidacao('Não foi possível estabelecer conexão com o servidor de autenticação.');
        }
        
        $objInfraException->lancarValidacao('Erro desconhecido validando usuário.');  
      }
    }

    private function validarUsuarioAD() {
    	
    	$ret = null;
    	
      try{
  			$objInfraException = new InfraException();
				try {


          if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraLDAP->validarUsuarioAD]');
          }


      		ldap_set_option($this->conexao, LDAP_OPT_PROTOCOL_VERSION, 3);
      		ldap_set_option($this->conexao, LDAP_OPT_REFERRALS, 0);

	      	//InfraDebug::getInstance()->gravarInfra('[InfraLDAP->validarUsuarioAD] 20');
				  ldap_bind($this->conexao, $this->usuario, utf8_encode($this->senha));

        	//InfraDebug::getInstance()->gravarInfra('[InfraLDAP->validarUsuarioAD] 30');
          $pesquisa = ldap_search($this->conexao, $this->contexto, 'userPrincipalName='.$this->usuario,array('distinguishedName'));
          
          if (ldap_errno($this->conexao) == 0) {
        	  
           	//InfraDebug::getInstance()->gravarInfra('[InfraLDAP->validarUsuarioAD] 40');
  				  $entry = ldap_first_entry($this->conexao, $pesquisa);
  				  
  				  if (!empty($entry)) {
  				    
           	  //InfraDebug::getInstance()->gravarInfra('[InfraLDAP->validarUsuarioAD] 50');
  					  $attrs = ldap_get_attributes($this->conexao, $entry);
  					  
 						  $ret = array();
					    $ret[self::$LDAP_GRUPO_REDE] = $this->obterOuUsuario($attrs['distinguishedName'][0]);
					    $ret[self::$LDAP_DN] = $attrs['distinguishedName'][0];
						  
  				  }
          }
          
				  //InfraDebug::getInstance()->gravarInfra('[InfraLDAP->validarUsuarioAD] 70');
				  
				} catch (Exception $e){
          //throw $e;
          if (strpos(strtoupper($e->__toString()),'INVALID CREDENTIALS')!==false){
            $objInfraException->lancarValidacao(self::$MSG_USUARIO_SENHA_INVALIDA);
          }
          
          if (strpos(strtoupper($e->__toString()),'CAN\'T CONTACT LDAP SERVER')!==false){
            $objInfraException->lancarValidacao('Não foi possível estabelecer conexão com o servidor de autenticação AD.');
          }
          
				  $objInfraException->lancarValidacao('Erro desconhecido validando usuário no AD.');  
				}
    	  
    	  //InfraDebug::getInstance()->gravarInfra('[InfraLDAP->validarUsuarioAD] 80');
    	  
    	  return $ret;
  	  
      }catch(Exception $e2){
        
        if ($e2 instanceof InfraException){
          if ($e2->contemValidacoes()){
             throw $e2;
          }
        }
        
        //Nao repassar o objeto de exceção porque o PHP mostra a senha na trilha de processamento
        $objInfraException = new InfraException();
        $objInfraException->lancarValidacao('Erro desconhecido validando usuário no LDAP/AD.');  
      }
    }
    
    //PESQUISA NO LDAP
    public function pesquisar($usuario, $senha, $servidor, $contexto, $porta=389){
      $ret = null;
      try{
        $this->usuario = $usuario;
        $this->senha = $senha;
        $this->servidor = $servidor;
        $this->porta = $porta;
        $this->contexto = $this->inverterContexto($contexto);
        $this->abrirConexao();
        $ret = $this->validarUsuario();
        $this->fecharConexao();
      }catch(Exception $e){
        try{
           $this->fecharConexao();
        }catch(Exception $e2){}
        throw $e;
      }
      return $ret;
    }
    
    public function pesquisarAD($usuario, $senha, $servidor, $contexto, $porta=389){
      $ret = null;
      try{
        $this->usuario = $usuario;
        $this->senha = $senha;
        $this->servidor = $servidor;
        $this->contexto = $contexto;
        $this->porta = $porta;
        $this->abrirConexao();
        $ret = $this->validarUsuarioAD();
        $this->fecharConexao();
      }catch(Exception $e){
        try{
           $this->fecharConexao();
        }catch(Exception $e2){}
        throw $e;
      }
      return $ret;
    }
  
    private function obterOuUsuario($strChaveLDAP){
      $arrChaveLDAP = explode(',', $strChaveLDAP);
      unset($arrChaveLDAP[0]);
      return self::formatarContexto(implode(',',$arrChaveLDAP));
    }
    
    public static function formatarContexto($strContexto){
      $ret = '';
      
      $arrContexto = explode(',',$strContexto);
      
      foreach($arrContexto as $itemContexto){
        if ($ret != ''){
          $ret .= ', ';
        }
        $arrItemContexto = explode('=', $itemContexto);
        $ret .= InfraString::transformarCaixaAlta(trim($arrItemContexto[0]).'='.trim($arrItemContexto[1]));
      }
      
      return $ret;
    }
    
  public function pesquisaAvancada($tipo, $servidor, $porta, $usuarioPesquisa, $senhaPesquisa, $contextoPesquisa, $atributoFiltro, $atributoRetorno, $usuario, $senha, $versao = 2){

    $objInfraException = new InfraException();
     
    try{
      
      $ret = null;
      
      if ($tipo!=self::$TIPO_AD && $tipo!=self::$TIPO_LDAP){
        throw new InfraException(self::$MSG_TIPO_AUTENTICACAO_INVALIDO);
      }

      $this->conexao = ldap_connect($servidor,$porta);
      
      ldap_set_option($this->conexao, LDAP_OPT_PROTOCOL_VERSION, $versao);
      ldap_set_option($this->conexao, LDAP_OPT_REFERRALS, 0);

      $bolAutenticou = false;
      
      if ($usuarioPesquisa!=null && $senhaPesquisa!=null) {
        try{
          ldap_bind($this->conexao, $usuarioPesquisa, utf8_encode($senhaPesquisa));
        }catch(Exception $e2){
          if (strpos(strtoupper($e2->__toString()),'INVALID CREDENTIALS')!==false){
            $this->tratarErro(self::$MSG_USUARIO_SENHA_PESQUISA_INVALIDA, $e2);
          }
          throw $e2;
        }          
      }elseif ($tipo==self::$TIPO_AD){
        try{
          ldap_bind($this->conexao, $usuario, utf8_encode($senha));
          $bolAutenticou = true;
        }catch(Exception $e2){
          throw $e2;
        }
      }
      
      if ($contextoPesquisa == null && $atributoFiltro == null && $atributoRetorno == null){
        
        if (!$bolAutenticou){
          ldap_bind($this->conexao, $usuario, utf8_encode($senha));
        }
        
      }else{
        
        $pesquisa = @ldap_search($this->conexao, $contextoPesquisa, $atributoFiltro.'='.$usuario,array($atributoRetorno));
        
        if (ldap_errno($this->conexao)) {
          //$objInfraException->lancarValidacao(self::$MSG_FALHA_PESQUISA_USUARIO);
          $objInfraException->lancarValidacao(self::$MSG_USUARIO_SENHA_INVALIDA);
        }
           
        $entry = ldap_first_entry($this->conexao, $pesquisa);
  
        if (empty($entry)) {
          //$objInfraException->lancarValidacao(self::$MSG_USUARIO_NAO_ENCONTRADO);
          $objInfraException->lancarValidacao(self::$MSG_USUARIO_SENHA_INVALIDA);
        }
  
        $attrs = ldap_get_attributes($this->conexao, $entry);
            	
        if ($atributoRetorno=='distinguishedName' && !isset($attrs['distinguishedName'])){
          $dominio = ldap_get_dn($this->conexao, $entry);
        }else{
          $dominio = $attrs[$atributoRetorno][0];
        }
  
        if (empty($dominio)) {
          //$objInfraException->lancarValidacao(self::$MSG_CONTEXTO_DO_USUARIO_NAO_ENCONTRADO);
          $objInfraException->lancarValidacao(self::$MSG_USUARIO_SENHA_INVALIDA);
        }
  
        if (!$bolAutenticou){
          ldap_bind($this->conexao, $dominio, utf8_encode($senha));
        }

        $dominio = utf8_decode($dominio);

        $ret = array();
        $ret[self::$LDAP_GRUPO_REDE] = $this->obterOuUsuario($dominio);
        $ret[self::$LDAP_DN] = $dominio;
      }
                  
      ldap_close($this->conexao);

  	  return $ret;
  	   	
    }catch(Exception $e2){

      try{ ldap_close($this->conexao); }catch(Exception $e3){}

      if ($e2 instanceof InfraException && $e2->contemValidacoes()){
        throw $e2;
      }
      
      //Nao repassar o objeto de exceção porque o PHP mostra a senha na trilha de processamento

      if (strpos(strtoupper($e2->__toString()),'CAN\'T CONTACT LDAP SERVER')!==false){
        $this->tratarErro(self::$MSG_FALHA_CONEXAO, $e2);
      }
      
      if (strpos(strtoupper($e2->__toString()),'INVALID CREDENTIALS')!==false){
        $this->tratarErro(self::$MSG_USUARIO_SENHA_INVALIDA, $e2);
      }
      
      if ($this->debug){
        throw $e2;
      }
      
      $objInfraException->lancarValidacao(self::$MGS_ERRO_DESCONHECIDO);
    }
  }
  
  private function tratarErro($strMsg, $e){
    if (!$this->debug){
      $objInfraException = new InfraException();
      $objInfraException->lancarValidacao($strMsg);
    }else{
      throw new InfraException($strMsg, $e);
    }
  }
  
}
?>