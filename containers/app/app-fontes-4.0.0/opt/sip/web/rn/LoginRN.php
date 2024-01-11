<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/10/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class LoginRN extends InfraRN {

  public static $TL_CADASTRADO = 'C';
  public static $TL_VALIDADO = 'V';
  public static $TL_REMOVIDO = 'R';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSip::getInstance();
  }

  public function listarValoresSituacao()
  {
    try {

      $objArrSituacaoLoginDTO = array();

      $objSituacaoLoginDTO = new SituacaoLoginDTO();
      $objSituacaoLoginDTO->setStrStaSituacao(self::$TL_CADASTRADO);
      $objSituacaoLoginDTO->setStrDescricao('Incompleto');
      $objArrSituacaoLoginDTO[] = $objSituacaoLoginDTO;

      $objSituacaoLoginDTO = new SituacaoLoginDTO();
      $objSituacaoLoginDTO->setStrStaSituacao(self::$TL_VALIDADO);
      $objSituacaoLoginDTO->setStrDescricao('Validado');
      $objArrSituacaoLoginDTO[] = $objSituacaoLoginDTO;

      $objSituacaoLoginDTO = new SituacaoLoginDTO();
      $objSituacaoLoginDTO->setStrStaSituacao(self::$TL_REMOVIDO);
      $objSituacaoLoginDTO->setStrDescricao('Finalizado');
      $objArrSituacaoLoginDTO[] = $objSituacaoLoginDTO;

      return $objArrSituacaoLoginDTO;

    } catch (Exception $e) {
      throw new InfraException('Erro listando valores de Situação de Login.', $e);
    }
  }

  protected function autenticarConectado(LoginDTO $objLoginDTO) {

    try{

      $objInfraException = new InfraException();
      
			if (InfraString::isBolVazia($objLoginDTO->getNumIdOrgaoUsuario())){
			  $objInfraException->lancarValidacao('Órgão do usuário não informado.');
			}
	
			if (InfraString::isBolVazia($objLoginDTO->getStrSiglaUsuario())){
				$objInfraException->lancarValidacao('Sigla do usuário não informada.');
			}
			
			if (InfraString::isBolVazia($objLoginDTO->getStrSenhaUsuario())){
				$objInfraException->lancarValidacao('Senha do usuário não informada.');
			}

			//Converte usuario para minusculas
			$strUsuario = strtolower(trim($objLoginDTO->getStrSiglaUsuario()));
			
			$bolEmulacao = false;
			
			if (strpos($strUsuario,'#')!==false){
			  $arr = explode('#',$strUsuario);

			  if (count($arr)!=3 || trim($arr[0])=='' || trim($arr[1])=='' || trim($arr[2])==''){
          $objInfraException->lancarValidacao('Dados para emulação incompletos, utilize: sigla_usuario_administrador#sigla_usuario_emulado#sigla_orgao_usuario_emulado');     			    
			  }
			  
	      $strUsuarioEmulador = trim($arr[0]);
	      $strUsuario = trim($arr[1]);
	      $strOrgaoUsuario = strtoupper(trim($arr[2]));

			  $objLoginDTO->setStrSiglaUsuario($strUsuarioEmulador);
			  
			  //Verifica se o usuário é administrador do sistema
        $this->buscarDadosSistema($objLoginDTO);
			  $this->buscarDadosUsuario($objLoginDTO);
			  
			  $objAdminstradorSistemaDTO = new AdministradorSistemaDTO();
			  $objAdminstradorSistemaDTO->setNumIdUsuario($objLoginDTO->getNumIdUsuario());
			  $objAdminstradorSistemaDTO->setNumIdSistema($objLoginDTO->getNumIdSistema());
			  $objAdminstradorSistemaRN = new AdministradorSistemaRN();
			  if ($objAdminstradorSistemaRN->contar($objAdminstradorSistemaDTO)==0){
			    $objInfraException->lancarValidacao('Usuário '.$strUsuarioEmulador.' não é administrador do sistema.');
			  }
			  
			  $bolEmulacao = true;
			}else{
			  $objLoginDTO->setStrSiglaUsuario($strUsuario);
        $this->buscarDadosUsuario($objLoginDTO);
			}

			if ($objLoginDTO->getStrSinBloqueadoUsuario()=='S'){
			  $objInfraParametro = new InfraParametro(BancoSip::getInstance());
        $strMsgBloqueado = $objInfraParametro->getValor('SIP_MSG_USUARIO_BLOQUEADO');
        $objInfraException->lancarValidacao($strMsgBloqueado);
      }

			//Obtem IP do LDAP para o Órgão
			$objOrgaoDTO = new OrgaoDTO();
			$objOrgaoDTO->retNumIdOrgao();
			$objOrgaoDTO->retStrSigla();
			$objOrgaoDTO->retStrSinAutenticar();
			$objOrgaoDTO->setNumIdOrgao($objLoginDTO->getNumIdOrgaoUsuario());
			
			$objOrgaoRN = new OrgaoRN();
			$objOrgaoDTO = $objOrgaoRN->consultar($objOrgaoDTO);

			if ($objOrgaoDTO->getStrSinAutenticar()=='N'){

        $objLoginDTO->setStrSinAutenticar('N');

			  if ($objLoginDTO->getStrSiglaUsuario() != $objLoginDTO->getStrSenhaUsuario()){
				  $objInfraException->lancarValidacao(InfraLDAP::$MSG_USUARIO_SENHA_INVALIDA);
				}

			}else{

        $objLoginDTO->setStrSinAutenticar('S');
			  
			  if (!method_exists(ConfiguracaoSip::getInstance(),'autenticar') || !ConfiguracaoSip::getInstance()->autenticar($objLoginDTO)){
					
	   			$objInfraLDAP = new InfraLDAP();
	   			
	   			$objRelOrgaoAutenticacaoDTO = new RelOrgaoAutenticacaoDTO();
	   			$objRelOrgaoAutenticacaoDTO->retNumIdServidorAutenticacao();
	   			$objRelOrgaoAutenticacaoDTO->setNumIdOrgao($objOrgaoDTO->getNumIdOrgao());
	   			$objRelOrgaoAutenticacaoDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);
	   			
	   			$objRelOrgaoAutenticacaoRN = new RelOrgaoAutenticacaoRN();
	   			$arrObjRelOrgaoAutenticacaoDTO = $objRelOrgaoAutenticacaoRN->listar($objRelOrgaoAutenticacaoDTO);
	   			
	   			if (count($arrObjRelOrgaoAutenticacaoDTO)==0){
	   			  $objInfraException->lancarValidacao('Nenhum servidor de autenticação configurado para o órgão.');
	   			}
	   			
	   			$objServidorAutenticacaoRN = new ServidorAutenticacaoRN();
	   			
	   			$numServidoresAutenticacao = count($arrObjRelOrgaoAutenticacaoDTO);
	   			
	   			for($i=0;$i<$numServidoresAutenticacao;$i++){
	   			
  	   			$objServidorAutenticacaoDTO = new ServidorAutenticacaoDTO();
  	   			$objServidorAutenticacaoDTO->retStrStaTipo();
  	   			$objServidorAutenticacaoDTO->retStrEndereco();
  	   			$objServidorAutenticacaoDTO->retNumPorta();
  	   			$objServidorAutenticacaoDTO->retNumVersao();
  	   			$objServidorAutenticacaoDTO->retStrSufixo();
  	   			$objServidorAutenticacaoDTO->retStrUsuarioPesquisa();
  	   			$objServidorAutenticacaoDTO->retStrSenhaPesquisa();
  	   			$objServidorAutenticacaoDTO->retStrContextoPesquisa();
  	   			$objServidorAutenticacaoDTO->retStrAtributoFiltroPesquisa();
  	   			$objServidorAutenticacaoDTO->retStrAtributoRetornoPesquisa();
  	   			$objServidorAutenticacaoDTO->setNumIdServidorAutenticacao($arrObjRelOrgaoAutenticacaoDTO[$i]->getNumIdServidorAutenticacao());
  	   			
  	   			$objServidorAutenticacaoDTO = $objServidorAutenticacaoRN->consultar($objServidorAutenticacaoDTO);

  	   			try{
  	   			  
    	   			$objInfraLDAP->pesquisaAvancada($objServidorAutenticacaoDTO->getStrStaTipo(),
                                             $objServidorAutenticacaoDTO->getStrEndereco(),
                                             $objServidorAutenticacaoDTO->getNumPorta(),
                                             $objServidorAutenticacaoDTO->getStrUsuarioPesquisa(),
                                             $objServidorAutenticacaoDTO->getStrSenhaPesquisa(),
                                             $objServidorAutenticacaoDTO->getStrContextoPesquisa(),
                                             $objServidorAutenticacaoDTO->getStrAtributoFiltroPesquisa(),
                                             $objServidorAutenticacaoDTO->getStrAtributoRetornoPesquisa(),
                                             (InfraString::isBolVazia($objServidorAutenticacaoDTO->getStrSufixo())?$objLoginDTO->getStrSiglaUsuario():$objLoginDTO->getStrSiglaUsuario().$objServidorAutenticacaoDTO->getStrSufixo()),
                                             $objLoginDTO->getStrSenhaUsuario(),
                                             $objServidorAutenticacaoDTO->getNumVersao());
    	   			
              //sair no primeiro que autenticar
              break;
    	   			 
  	   			}catch(Exception $e){
  	   			  
	  			    //se for o último servidor de autenticação associado
  	   			  if ($i == ($numServidoresAutenticacao-1)){
  	   			    throw $e;
  	   			  }
  	   			  
  	   			}
	   			}	   			
				}
			}

      if ($bolEmulacao){
        
      	//busca orgao do usuario emulado
        $objOrgaoDTO = new OrgaoDTO();
	      $objOrgaoDTO->retNumIdOrgao();
	      $objOrgaoDTO->setStrSigla($strOrgaoUsuario);
	      
	      $objOrgaoRN = new OrgaoRN();
	      $objOrgaoDTO = $objOrgaoRN->consultar($objOrgaoDTO); 
	      
	      if ($objOrgaoDTO==null){
          $objInfraException->lancarValidacao('Orgão do usuário emulado não encontrado.');     
	      }
			  
	      $objLoginDTO->setNumIdOrgaoUsuario($objOrgaoDTO->getNumIdOrgao());
	      
				//Busca ID do Usuario emulado
  			$objUsuarioDTO = new UsuarioDTO();
  			$objUsuarioDTO->retNumIdUsuario();
  			$objUsuarioDTO->retStrSigla();
  			$objUsuarioDTO->setStrSigla($strUsuario);
  			$objUsuarioDTO->setNumIdOrgao($objOrgaoDTO->getNumIdOrgao());			
  			$objUsuarioRN = new UsuarioRN();
  			$objUsuarioDTO = $objUsuarioRN->consultar($objUsuarioDTO);
  			if ($objUsuarioDTO===null){
  			  $objInfraException->lancarValidacao('Usuário emulado não encontrado no Sistema de Permissões.');
  			}
  			
  			$objLoginDTO->setStrSiglaUsuario($strUsuario);
  			$objLoginDTO->setNumIdUsuarioEmulador($objLoginDTO->getNumIdUsuario());
        $objLoginDTO->setNumIdUsuario($objUsuarioDTO->getNumIdUsuario());

      }else{
      	$objLoginDTO->setNumIdUsuarioEmulador(null);
      }									

    }catch(Exception $e){
    	
      if ($e instanceof InfraException && $e->contemValidacoes()){
        throw $e;
      }

      //try{
			//  LogSip::getInstance()->gravar(InfraException::inspecionar($e));
			//}catch(Exception $e2){}

			//Não mostra a exceção porque o erro do php mostra a senha do usuario
			$objInfraException->lancarValidacao("Erro autenticando usuário.");
    }
  }

	protected function buscarDadosSistemaConectado(LoginDTO $objLoginDTO){
	  try{
	    $objInfraException = new InfraException();

			if (InfraString::isBolVazia($objLoginDTO->getStrSiglaOrgaoSistema())){
				$objInfraException->lancarValidacao('Sigla do Órgão do Sistema não informada.');
			}
			
			if (InfraString::isBolVazia($objLoginDTO->getStrSiglaSistema())){
				$objInfraException->lancarValidacao('Sigla do Sistema não informada.');
			}
			
			//Busca ID do Órgão do Sistema
			$objOrgaoDTO = new OrgaoDTO();
			$objOrgaoDTO->retNumIdOrgao();
			$objOrgaoDTO->retStrDescricao();
			$objOrgaoDTO->setStrSigla($objLoginDTO->getStrSiglaOrgaoSistema());
			$objOrgaoRN = new OrgaoRN();
			$objOrgaoDTO = $objOrgaoRN->consultar($objOrgaoDTO);
			if ($objOrgaoDTO===null){
			  $objInfraException->lancarValidacao('Órgão \''.$objLoginDTO->getStrSiglaOrgaoSistema().'\' do Sistema não encontrado no Sistema de Permissões.');
			}
			$objLoginDTO->setNumIdOrgaoSistema($objOrgaoDTO->getNumIdOrgao());
			$objLoginDTO->setStrDescricaoOrgaoSistema($objOrgaoDTO->getStrDescricao());
			
			//Busca ID do Sistema
			$objSistemaDTO = new SistemaDTO();
			$objSistemaDTO->retNumIdSistema();
			$objSistemaDTO->retStrPaginaInicial();
			$objSistemaDTO->setStrSigla($objLoginDTO->getStrSiglaSistema());
			$objSistemaDTO->setNumIdOrgao($objLoginDTO->getNumIdOrgaoSistema());
			$objSistemaRN = new SistemaRN();
			$objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);
			if ($objSistemaDTO===null){
			  $objInfraException->lancarValidacao('Sistema \''.$objLoginDTO->getStrSiglaSistema().'\' não encontrado no Sistema de Permissões.');
			}
			
			$objLoginDTO->setNumIdSistema($objSistemaDTO->getNumIdSistema());
			$objLoginDTO->setStrPaginaInicialSistema($objSistemaDTO->getStrPaginaInicial());

			$objInfraException->lancarValidacoes();
			
    }catch(Exception $e){
      throw new InfraException("Erro buscando dados do sistema.",$e);
    }
			
	}

	protected function buscarDadosUsuarioConectado(LoginDTO $objLoginDTO){
	  try{
	    $objInfraException = new InfraException();

			if (InfraString::isBolVazia($objLoginDTO->getStrSiglaUsuario())){
				$objInfraException->lancarValidacao('Sigla do Usuário não informada.');
			}

      if (method_exists(ConfiguracaoSip::getInstance(),'validarUsuario')){
        ConfiguracaoSip::getInstance()->validarUsuario($objLoginDTO);
      }

			$objOrgaoRN = new OrgaoRN();

			$objOrgaoDTO = new OrgaoDTO();
			$objOrgaoDTO->retNumIdOrgao();
			$objOrgaoDTO->retStrSigla();
			$objOrgaoDTO->retStrDescricao();
			$objOrgaoDTO->setNumIdOrgao($objLoginDTO->getNumIdOrgaoUsuario());

			$objOrgaoDTO = $objOrgaoRN->consultar($objOrgaoDTO);
			if ($objOrgaoDTO===null){
			  $objInfraException->lancarValidacao('Órgão ['.$objLoginDTO->getNumIdOrgaoUsuario().'] do Usuário não encontrado no Sistema de Permissões.');
			}
			
			$objUsuarioDTO = new UsuarioDTO();
			$objUsuarioDTO->retNumIdUsuario();
      $objUsuarioDTO->retStrNome();
      $objUsuarioDTO->retStrSinBloqueado();
			$objUsuarioDTO->setStrSigla($objLoginDTO->getStrSiglaUsuario());
			$objUsuarioDTO->setNumIdOrgao($objLoginDTO->getNumIdOrgaoUsuario());			
			$objUsuarioRN = new UsuarioRN();
			$objUsuarioDTO = $objUsuarioRN->consultar($objUsuarioDTO);

			if ($objUsuarioDTO===null){
			  $objInfraException->lancarValidacao('Usuário \''.$objLoginDTO->getStrSiglaUsuario().' / '.$objOrgaoDTO->getStrSigla().'\' não encontrado no Sistema de Permissões.');
			}

      $objLoginDTO->setNumIdUsuario($objUsuarioDTO->getNumIdUsuario());
      $objLoginDTO->setStrSiglaOrgaoUsuario($objOrgaoDTO->getStrSigla());
      $objLoginDTO->setStrNomeUsuario($objUsuarioDTO->getStrNome());
      $objLoginDTO->setStrSinBloqueadoUsuario($objUsuarioDTO->getStrSinBloqueado());

			$objInfraException->lancarValidacoes();
			
    }catch(Exception $e){
      throw new InfraException("Erro buscando dados do usuário.",$e);
    }
			
	}
		
  protected function cadastrarControlado(LoginDTO $objLoginDTO){

    try{

		  $this->buscarDadosSistema($objLoginDTO);
		  $this->buscarDadosUsuario($objLoginDTO);

      $objLoginDTO->unSetStrSenhaUsuario();

      $dthLogin = InfraData::getStrDataHoraAtual();

      $objLoginDTO->setDthLogin($dthLogin);

			$objLoginDTO->setStrHashInterno(hash('SHA512',
                                                     mt_rand().
                                                     $objLoginDTO->__toString().
                                                     uniqid(mt_rand(), true)
          )
      );

			$objLoginDTO->setStrHashUsuario(hash('WHIRLPOOL',
                                                      uniqid(mt_rand(), true).
                                                      $objLoginDTO->__toString().
                                                      mt_rand()
          )
      );

			$objLoginDTO->setStrIdLogin(hash('SHA512',
                                                 mt_rand().
                                                 $objLoginDTO->__toString().
                                                 uniqid(mt_rand(), true)
          )
      );

      $objLoginDTO->setStrHashAgente(SessaoSip::gerarHashAgente());


      $strIp = substr($_SERVER['HTTP_CLIENT_IP'],0,39);
      $objLoginDTO->setStrHttpClientIp(InfraString::isBolVazia($strIp) ? null : $strIp);

      $strIp = substr($_SERVER['HTTP_X_FORWARDED_FOR'],0,39);
      $objLoginDTO->setStrHttpXForwardedFor(InfraString::isBolVazia($strIp) ? null : $strIp);

      $strIp = substr($_SERVER['REMOTE_ADDR'],0,39);
      $objLoginDTO->setStrRemoteAddr(InfraString::isBolVazia($strIp) ? null : $strIp);

      $strUserAgent = substr($_SERVER['HTTP_USER_AGENT'],0,500);
      $objLoginDTO->setStrUserAgent(InfraString::isBolVazia($strUserAgent) ? null : $strUserAgent);

      $objLoginDTO->setStrStaLogin(self::$TL_CADASTRADO);

      $objLoginBD = new LoginBD($this->getObjInfraIBanco());
      $ret = $objLoginBD->cadastrar($objLoginDTO);

			return $ret;
			
    }catch(Exception $e){
      throw new InfraException("Erro cadastrando dados de login.",$e);
    }
  }
	
	protected function validarControlado(LoginDTO $parObjLoginDTO){
		try{
		  
			$objLoginDTO = new LoginDTO();
			$objLoginDTO->retTodos(true);
			$objLoginDTO->setStrIdLogin($parObjLoginDTO->getStrIdLogin());
			$objLoginDTO->setNumIdSistema($parObjLoginDTO->getNumIdSistema());
			$objLoginDTO->setNumIdUsuario($parObjLoginDTO->getNumIdUsuario());
			$objLoginDTO->setStrStaLogin(self::$TL_CADASTRADO);
			
			$objLoginDTO = $this->consultar($objLoginDTO);
			
			if ($objLoginDTO!=null){

        //valida se o USER_AGENT é o mesmo do login (não pode mudar entre o login e a validacao)
        //if ($objLoginDTO->getStrHashAgente()!=$parObjLoginDTO->getStrHashAgente()){
        //$objInfraException->lancarValidacao('Agente de acesso ao login inválido.');
        //throw new InfraException('Agente de acesso ao login inválido.');
        //}

        $objLoginBD = new LoginBD($this->getObjInfraIBanco());

        $dthLimite = InfraData::calcularData(ConfiguracaoSip::getInstance()->getValor('Sip', 'TempoLimiteValidacaoLogin', false, 60),InfraData::$UNIDADE_SEGUNDOS,InfraData::$SENTIDO_ADIANTE,$objLoginDTO->getDthLogin());
				if (InfraData::compararDataHora($dthLimite, InfraData::getStrDataHoraAtual()) > 0){
					throw new InfraException('Tempo limite para validação do login esgotado.');
				}

        $objLoginDTOHistorico = new LoginDTO();
        $objLoginDTOHistorico->setNumMaxRegistrosRetorno(1);
        $objLoginDTOHistorico->retDthLogin();
        $objLoginDTOHistorico->setStrIdLogin($objLoginDTO->getStrIdLogin(),InfraDTO::$OPER_DIFERENTE);
        $objLoginDTOHistorico->setNumIdUsuario($objLoginDTO->getNumIdUsuario());
        $objLoginDTOHistorico->setNumIdSistema($objLoginDTO->getNumIdSistema());
        $objLoginDTOHistorico->setOrdDthLogin(InfraDTO::$TIPO_ORDENACAO_DESC);

        $objLoginDTOHistorico = $this->consultar($objLoginDTOHistorico);

        if ($objLoginDTOHistorico!=null) {
          $objLoginDTO->setDthUltimoLogin($objLoginDTOHistorico->getDthLogin());
        }else{
          $objLoginDTO->setDthUltimoLogin($objLoginDTO->getDthLogin());
        }

        $dto = new LoginDTO();
  			$dto->setStrIdLogin($objLoginDTO->getStrIdLogin());
  			$dto->setNumIdSistema($objLoginDTO->getNumIdSistema());
  			$dto->setNumIdUsuario($objLoginDTO->getNumIdUsuario());
			  $dto->setStrStaLogin(self::$TL_VALIDADO);
        $objLoginBD->alterar($dto);
			}

			return $objLoginDTO;
			
    }catch(Exception $e){
      throw new InfraException("Erro validando dados de login.",$e);
    }
	}
	
  protected function consultarConectado(LoginDTO $objLoginDTO){
    try {

       //Não valida permissão porque é acessado pelo procedimento de login
			 /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('login_consultar',__METHOD__,$objLoginDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objLoginBD = new LoginBD($this->getObjInfraIBanco());
      $ret = $objLoginBD->consultar($objLoginDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Login.',$e);
    }
  }
	
  protected function contarConectado(LoginDTO $objLoginDTO){
    try {

       //Não valida permissão porque é acessado pelo procedimento de login
			 /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('login_consultar',__METHOD__,$objLoginDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objLoginBD = new LoginBD($this->getObjInfraIBanco());
      $ret = $objLoginBD->contar($objLoginDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Login.',$e);
    }
  }
  
  protected function excluirControlado($arrObjLoginDTO){
    try {

       //Não valida permissão porque é acessado pelo procedimento de login
			 /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('login_excluir',__METHOD__,$arrObjLoginDTO);
			 /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();
			
      $objLoginBD = new LoginBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjLoginDTO);$i++){
        $objLoginBD->excluir($arrObjLoginDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Login.',$e);
    }
  }
	
  protected function listarConectado(LoginDTO $objLoginDTO) {
    try {

			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('login_listar',__METHOD__,$objLoginDTO);
			/////////////////////////////////////////////////////////////////


      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objLoginBD = new LoginBD($this->getObjInfraIBanco());
      $ret = $objLoginBD->listar($objLoginDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando logins.',$e);
    }
  }
  
  protected function logarControlado(LoginDTO $parObjLoginDTO) {
    try {
  		
  		if (($parObjLoginDTO = $this->validar($parObjLoginDTO))==null){
  		  return null;
  		}
      
      $objInfraSessaoDTO = $this->loginInterno($parObjLoginDTO);
      
      $objLoginDTOAuditoria = clone($parObjLoginDTO);
      $objLoginDTOAuditoria->setArrHierarquia(null);
      AuditoriaSip::getInstance()->auditar('login_padrao', __METHOD__, $objLoginDTOAuditoria);
      
      return $objInfraSessaoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro efetuando login.',$e);
    }
  }
  
  protected function loginUnificadoControlado(LoginDTO $parObjLoginDTO) {
    try {
  		
      $objInfraSessaoDTO = null;
      
      $strLink = $parObjLoginDTO->getStrLink();
      
      $numPosHash = strpos($strLink,'&infra_hash=');
      if ($numPosHash===false){
        //$objInfraException->lancarValidacao('Hash não localizado no link externo.');
        return null;
      }
  
      $strHashLink = substr($strLink, $numPosHash + strlen('&infra_hash='), 192);
      if (strlen($strHashLink)!=192){
        //$objInfraException->lancarValidacao('Tamanho do hash inválido no link externo.');
        return null;
      }
      
      $strHashUsuario = substr($strHashLink,64);
      
      
      $objLoginDTO = new LoginDTO();
      $objLoginDTO->retTodos(true);
      $objLoginDTO->setStrHashUsuario($strHashUsuario);
      $objLoginDTO->setDthLogin(InfraData::calcularData(12,InfraData::$UNIDADE_HORAS,InfraData::$SENTIDO_ATRAS,InfraData::getStrDataHoraAtual()),InfraDTO::$OPER_MAIOR_IGUAL);
      $objLoginDTO->setStrStaLogin(self::$TL_VALIDADO);
      
      $arrObjLoginDTO = $this->listar($objLoginDTO);

      $bolLinkValido = false;
      foreach($arrObjLoginDTO as $objLoginDTO){
        
        //InfraDebug::getInstance()->gravar($objLoginDTO->getStrHashInterno());

        if ($objLoginDTO->getStrSiglaOrgaoSistema()!=$parObjLoginDTO->getStrSiglaOrgaoSistema() || $objLoginDTO->getStrSiglaSistema()!=$parObjLoginDTO->getStrSiglaSistema()) {

          SessaoSip::getInstance()->setStrHashInterno($objLoginDTO->getStrHashInterno());
          SessaoSip::getInstance()->setStrHashUsuario($objLoginDTO->getStrHashUsuario());

          if (SessaoSip::getInstance()->verificarLink($parObjLoginDTO->getStrLink())) {
            $bolLinkValido = true;
            break;
          }
        }
      }
      
      if ($bolLinkValido && $objLoginDTO->getStrHashAgente()==$parObjLoginDTO->getStrHashAgente()){
          
        $objLoginDTO->setStrSiglaOrgaoSistema($parObjLoginDTO->getStrSiglaOrgaoSistema());
        $objLoginDTO->setNumIdOrgaoSistema(null);
        $objLoginDTO->setStrSiglaSistema($parObjLoginDTO->getStrSiglaSistema());
        $objLoginDTO->setNumIdSistema(null);
        
  		  $this->buscarDadosSistema($objLoginDTO);

        $objLoginDTO->setDthUltimoLogin($objLoginDTO->getDthLogin());

        $objInfraSessaoDTO = $this->loginInterno($objLoginDTO);
                
        AuditoriaSip::getInstance()->auditar('login_unificado', __METHOD__, $objLoginDTO);
      }
      
      return $objInfraSessaoDTO;
      
    }catch(Exception $e){
      throw new InfraException('Erro realizando login unificado.',$e);
    }
  }  
  
  private function loginInterno(LoginDTO $objLoginDTO){
    try{
      
      /*
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->limpar();
      
      $mi = memory_get_usage();
      $numSeg = InfraUtil::verificarTempoProcessamento();
      */

      $objInfraSessaoDTO = new InfraSessaoDTO();
			$objInfraSessaoDTO->setStrSiglaOrgaoSistema($objLoginDTO->getStrSiglaOrgaoSistema());
			$objInfraSessaoDTO->setStrDescricaoOrgaoSistema($objLoginDTO->getStrDescricaoOrgaoSistema());
			$objInfraSessaoDTO->setNumIdOrgaoSistema($objLoginDTO->getNumIdOrgaoSistema());
			$objInfraSessaoDTO->setStrSiglaSistema($objLoginDTO->getStrSiglaSistema());
			$objInfraSessaoDTO->setNumIdSistema($objLoginDTO->getNumIdSistema());
			$objInfraSessaoDTO->setStrPaginaInicial($objLoginDTO->getStrPaginaInicialSistema());
			$objInfraSessaoDTO->setStrSiglaOrgaoUsuario($objLoginDTO->getStrSiglaOrgaoUsuario());
			$objInfraSessaoDTO->setStrDescricaoOrgaoUsuario($objLoginDTO->getStrDescricaoOrgaoUsuario());
			$objInfraSessaoDTO->setNumIdOrgaoUsuario($objLoginDTO->getNumIdOrgaoUsuario());
			$objInfraSessaoDTO->setNumIdContextoUsuario(null);
			$objInfraSessaoDTO->setNumIdUsuario($objLoginDTO->getNumIdUsuario());
			$objInfraSessaoDTO->setStrSiglaUsuario($objLoginDTO->getStrSiglaUsuario());
			$objInfraSessaoDTO->setStrNomeUsuario($objLoginDTO->getStrNomeUsuario());
      $objInfraSessaoDTO->setStrNomeRegistroCivilUsuario($objLoginDTO->getStrNomeRegistroCivilUsuario());
      $objInfraSessaoDTO->setStrNomeSocialUsuario($objLoginDTO->getStrNomeSocialUsuario());
			$objInfraSessaoDTO->setStrIdOrigemUsuario($objLoginDTO->getStrIdOrigemUsuario());
			$objInfraSessaoDTO->setStrHashInterno($objLoginDTO->getStrHashInterno());
			$objInfraSessaoDTO->setStrHashUsuario($objLoginDTO->getStrHashUsuario());
      $objInfraSessaoDTO->setBol2Fatores($objLoginDTO->getStrIdCodigoAcesso()!=null);
			$objInfraSessaoDTO->setStrSiglaOrgaoUsuarioEmulador($objLoginDTO->getStrSiglaOrgaoUsuarioEmulador());
			$objInfraSessaoDTO->setStrDescricaoOrgaoUsuarioEmulador($objLoginDTO->getStrDescricaoOrgaoUsuarioEmulador());
			$objInfraSessaoDTO->setNumIdOrgaoUsuarioEmulador($objLoginDTO->getNumIdOrgaoUsuarioEmulador());
			$objInfraSessaoDTO->setNumIdUsuarioEmulador($objLoginDTO->getNumIdUsuarioEmulador());
			$objInfraSessaoDTO->setStrSiglaUsuarioEmulador($objLoginDTO->getStrSiglaUsuarioEmulador());
			$objInfraSessaoDTO->setStrNomeUsuarioEmulador($objLoginDTO->getStrNomeUsuarioEmulador());
      $objInfraSessaoDTO->setDthUltimoLogin($objLoginDTO->getDthUltimoLogin());
			$objInfraSessaoDTO->setNumVersaoSip(SIP_VERSAO);
			$objInfraSessaoDTO->setNumVersaoInfraSip(VERSAO_INFRA);
			$objInfraSessaoDTO->setArrUnidadesPadrao(array());

			//Carrega objeto de login com o objeto de sessão
			$objLoginDTO->setObjInfraSessaoDTO($objInfraSessaoDTO);
			$objLoginDTO->setArrHierarquia(null);
			
      $objPermissaoRN = new PermissaoRN();
      $objPermissaoRN->carregar($objLoginDTO);

      /*
      $mf = memory_get_usage();
      $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
	    InfraDebug::getInstance()->gravar('[LoginRN->logar] '.$numSeg.' s - '.($mf-$mi).' bytes');
	    LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());
	    */
      
      return $objInfraSessaoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro realizando login.',$e);
    }
  }
  
  protected function removerLoginControlado(LoginDTO $parObjLoginDTO) {
    try {

      $this->buscarDadosSistema($parObjLoginDTO);

      $objLoginDTO = new LoginDTO();
      $objLoginDTO->retStrIdLogin();
      $objLoginDTO->retNumIdUsuario();
      $objLoginDTO->retNumIdSistema();
      $objLoginDTO->retStrHashInterno();
      $objLoginDTO->retStrHashUsuario();
      $objLoginDTO->retStrHashAgente();

      $objLoginDTO->setNumIdSistema($parObjLoginDTO->getNumIdSistema());
      $objLoginDTO->setNumIdUsuario($parObjLoginDTO->getNumIdUsuario());
      $objLoginDTO->setOrdDthLogin(InfraDTO::$TIPO_ORDENACAO_DESC);
      
      $arrObjLoginDTO = $this->listar($objLoginDTO);

      $objLoginBD = new LoginBD(BancoSip::getInstance());

      foreach($arrObjLoginDTO as $objLoginDTO){

        SessaoSip::getInstance()->setStrHashInterno($objLoginDTO->getStrHashInterno());
        SessaoSip::getInstance()->setStrHashUsuario($objLoginDTO->getStrHashUsuario());
        
        if (SessaoSip::getInstance()->verificarLink($parObjLoginDTO->getStrLink())){

          AuditoriaSip::getInstance()->auditar('login_remover', __METHOD__, $objLoginDTO);

          $objLoginDTORemover = new LoginDTO();
          $objLoginDTORemover->setStrIdLogin($objLoginDTO->getStrIdLogin());
          $objLoginDTORemover->setNumIdSistema($objLoginDTO->getNumIdSistema());
          $objLoginDTORemover->setNumIdUsuario($objLoginDTO->getNumIdUsuario());
          $objLoginDTORemover->setStrStaLogin(self::$TL_REMOVIDO);

          $objLoginBD->alterar($objLoginDTORemover);

          break;
        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro removendo login.',$e);
    }
  }

  protected function listarAcessosConectado(LoginDTO $parObjLoginDTO){
    try{
      $objInfraException = new InfraException();

      if (InfraString::isBolVazia($parObjLoginDTO->getNumIdSistema())){
        $objInfraException->adicionarValidacao('Sistema não informado.');
      }

      if (InfraString::isBolVazia($parObjLoginDTO->getNumIdUsuario())){
        $objInfraException->adicionarValidacao('Usuário não informado.');
      }

      $objInfraException->lancarValidacoes();

      $objInfraParametro = new InfraParametro(BancoSip::getInstance());
      $numHistoricoUltimosAcessos = $objInfraParametro->getValor('SIP_NUM_HISTORICO_ULTIMOS_ACESSOS');

      if (!is_numeric($numHistoricoUltimosAcessos) || $numHistoricoUltimosAcessos <= 0){
        $numHistoricoUltimosAcessos = 10;
      }

      $objLoginDTO = new LoginDTO();
      $objLoginDTO->setNumMaxRegistrosRetorno($numHistoricoUltimosAcessos);
      $objLoginDTO->retStrUserAgent();
      $objLoginDTO->retDthLogin();
      $objLoginDTO->retStrHttpClientIp();
      $objLoginDTO->retStrHttpXForwardedFor();
      $objLoginDTO->retStrRemoteAddr();
      $objLoginDTO->setNumIdSistema($parObjLoginDTO->getNumIdSistema());
      $objLoginDTO->setNumIdUsuario($parObjLoginDTO->getNumIdUsuario());
      $objLoginDTO->setOrdDthLogin(InfraDTO::$TIPO_ORDENACAO_DESC);

      $arrObjLoginDTO = $this->listar($objLoginDTO);

      $ret = array();
      foreach($arrObjLoginDTO as $objLoginDTO){

        InfraNavegador::obterDados($objLoginDTO->getStrUserAgent(),$strIdentificacao,$strVersao);

        $ret[] = array(InfraSip::$WS_ACESSO_DATA_HORA => $objLoginDTO->getDthLogin(),
            InfraSip::$WS_ACESSO_NAVEGADOR =>  $strIdentificacao. ' '.$strVersao,
            InfraSip::$WS_ACESSO_IP => InfraUtil::getStrIpUsuario($objLoginDTO->getStrHttpClientIp(),$objLoginDTO->getStrHttpXForwardedFor(),$objLoginDTO->getStrRemoteAddr()));
      }

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro consultando acessos do usuário.',$e);
    }
  }
}
?>