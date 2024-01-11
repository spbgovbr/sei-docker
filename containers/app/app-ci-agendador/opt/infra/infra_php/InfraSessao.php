<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/06/2006 - criado por MGA
*
* @package infra_php
*/


abstract class InfraSessao {
	private static $objInfraSessaoDTO;
  private $strHashInterno = null;
  private $strHashUsuario = null;
  private $bolHabilitada = null;
  private $bolValidarLinks = null;
  private $bolLoginUnificado = false;
  private $arrParametrosRepasse = null;
  
  public abstract function getStrSiglaOrgaoSistema();	
  public abstract function getStrSiglaSistema();
  public abstract function getStrPaginaLogin();
  public function getStrSipWSDL(){return null;}
  public function getStrSipChaveAcesso(){return null;}

  public function getStrLinkTrocarUnidade(){
    return null;
  }
  
  public function getObjInfraAuditoria(){
    return null;
  }

  public function getObjInfraIBanco(){
    return null;
  }
  
  public function inicializarSessao(){}

	public function __construct($bolHabilitada=true, $bolValidarLinks=true){
	  self::$objInfraSessaoDTO = null;
 		$this->setBolHabilitada($bolHabilitada);
	  $this->setBolValidarLinks($bolValidarLinks);
    $this->validarSessao();
    $this->trocarUnidadeAtual();
    $this->verificarInicializacao();
	}

	public static function isBolCarregada($strSiglaOrgaoSistema, $strSiglaSistema){
    return isset($_SESSION['INFRA_SESSAO'][$strSiglaOrgaoSistema][$strSiglaSistema]);
  }

	private function verificarInicializacao(){
    if ($this->getAtributo('infra_inicializou')==''){
      $this->inicializarSessao();
      $this->setAtributo('infra_inicializou','1');
    }   
	}
	
	public function setBolHabilitada($bolHabilitada){
	  $this->bolHabilitada = $bolHabilitada;
	}
	
	public function isBolHabilitada(){
	  return $this->bolHabilitada;
	}

	public function setBolLoginUnificado($bolLoginUnificado){
	  $this->bolLoginUnificado = $bolLoginUnificado;
	}
	
	public function isBolLoginUnificado(){
	  return $this->bolLoginUnificado;
	}
	
	public function setBolValidarLinks($bolValidarLinks){
	  $this->bolValidarLinks = $bolValidarLinks;
	}

	public function getBolValidarLinks(){
	  return $this->bolValidarLinks;
	}
	
	public function getStrPrefixoCookie(){
	  return str_replace(' ','_',$this->getStrSiglaOrgaoSistema().'_'.$this->getStrSiglaSistema().'_'.$this->getStrSiglaUsuario());
	}
	
	public function trocarUnidadeAtual(){
	  
	  if ($this->isBolHabilitada() && $this->getObjInfraSessaoDTO()!==null){
	    
	    //Nomes de cookies não podem ter espaços em branco
	    $strChaveCookie = $this->getStrPrefixoCookie().'_unidade_atual';
	    
	    //Se trocou na combo
  	  if (isset($_POST['selInfraUnidades'])){
  	    
  	    $this->setAtributo('infra_trocou_unidade',2);
  	     
  	    //seta na sessão
  	    $this->getObjInfraSessaoDTO()->setNumIdUnidadeAtual($_POST['selInfraUnidades']);  
  	    
  	    if ($this->getNumIdUnidadeAtual()!=null){
  	      
  	      //tenta gravar banco de dados
  	      if ($this->isBolHabilitada() && $this->getObjInfraIBanco()!=null){
  	        try{
  	          $objInfraDadoUsuario = new InfraDadoUsuario($this);
  	          $objInfraDadoUsuario->setValor('INFRA_UNIDADE_ATUAL',$this->getNumIdUnidadeAtual());
  	        }catch(Exception $e){
  	          //seta no cookie
  	          setcookie($strChaveCookie, $_POST['selInfraUnidades'], time()+60*60*24*365);
  	        }
  	      }else{
  	        //seta no cookie
  	        setcookie($strChaveCookie, $_POST['selInfraUnidades'], time()+60*60*24*365);
  	      }
  	    } 

  	    $url = $this->getStrUltimaPagina();
  	    if ($url!=''){
  	      header('Location: '.$this->assinarLink($url));
  	      die;
  	    }
  	    
  	  //Senão se a unidade ainda não esta configurada
  	  }else if ($this->getNumIdUnidadeAtual()===null){
  	    
     	  $this->setAtributo('infra_trocou_unidade',0);

     	  //tenta recuperar do banco de dados
     	  if ($this->isBolHabilitada() && $this->getObjInfraIBanco()!=null){
     	    try{
     	      $objInfraDadoUsuario = new InfraDadoUsuario($this);
     	      $this->getObjInfraSessaoDTO()->setNumIdUnidadeAtual($objInfraDadoUsuario->getValor('INFRA_UNIDADE_ATUAL'));
     	    }catch(Exception $e){}  
     	  } 

     	  if ($this->getNumIdUnidadeAtual()==null){
     	    
       	  //tenta recuperar do cookie
       	  if (isset($_COOKIE[$strChaveCookie])){
    	      $this->getObjInfraSessaoDTO()->setNumIdUnidadeAtual($_COOKIE[$strChaveCookie]);
    	    }
    	     
    	     //Se a do cookie não existe, então pega a primeira unidade no array da sessao   
    	     if ($this->getNumIdUnidadeAtual()==null){
    	       $arrIdUnidades = array_keys($this->getArrUnidades());
    	       if ($arrIdUnidades!==null){
      	       if (count($arrIdUnidades)>0){
      	         $this->getObjInfraSessaoDTO()->setNumIdUnidadeAtual($arrIdUnidades[0]);
      	       }
    	       }
    	     }
    	     
    	     if ($this->isBolHabilitada() && $this->getNumIdUnidadeAtual()!=null && $this->getObjInfraIBanco()!=null){
    	       try{
      	       $objInfraDadoUsuario = new InfraDadoUsuario($this);
      	       $objInfraDadoUsuario->setValor('INFRA_UNIDADE_ATUAL',$this->getNumIdUnidadeAtual());
    	       }catch(Exception $e){}  	       
    	     }
     	  }
  	     
  	  }else{
     	  if ($this->getAtributo('infra_trocou_unidade')==2){
     	    $this->setAtributo('infra_trocou_unidade',1);
     	    
     	    if ($this->getStrLinkTrocarUnidade()!=''){
    	      header('Location: '.$this->assinarLink($this->getStrLinkTrocarUnidade()));
    	      die;
     	    }
     	    
     	  }else{
     	    $this->setAtributo('infra_trocou_unidade',0);
     	  }
  	  }
	  }	  
	}

	public function isBolTrocouUnidade(){
	  if ($this->getAtributo('infra_trocou_unidade')>0){
	    return true;
	  }
	  return false;
	} 
	
	public function validarSessao(){

	  if (!$this->isBolHabilitada()){
	    return;
	  }
	  
	  try {
	    
	    //se vindo do SIP força recarga da sessão
	    if (isset($_GET['infra_sip']) && $_GET['infra_sip']=='true'){
	      
	      if (!$this->carregarSessao()){
          header('Location: '.$this->gerarLinkLogin());
			    die;	        
	      }
	      
	    }else{
	    
	      //procura objeto de sessão para o sistema
  	    $objInfraSessaoDTO = $this->getObjInfraSessaoDTO();
      
    	  if ($objInfraSessaoDTO===null){

          if (isset($_GET['infra_hash'])){

  				  //verifica se o link foi assinado por outro sistema
  				  if (!$this->validarLinkAssinadoExterno()){
      				header('Location: '.$this->gerarLinkLogin());
      			  die;
  				  }

  				}else{

            $strLinkLogin = $this->gerarLinkLogin();

            $strLinkUri = $this->removerParametrosLink($_SERVER['REQUEST_URI']);

            if ($strLinkUri!='/' && !InfraString::isBolVazia($strLinkUri)) {

              if (strpos($strLinkLogin, '?') === false) {
                $strLinkLogin .= '?';
              } else {
                $strLinkLogin .= '&';
              }

              $strLinkLogin .= 'infra_url=' . base64_encode($strLinkUri);
            }

            header('Location: '.$strLinkLogin);
  			    die;	        
  				}
    	  } else{
    	    
    	    //Forçar a saída de quem estiver logado
    	    /*
    	    if ($objInfraSessaoDTO->getNumVersaoInfraSip()!==VERSAO_INFRA){
    	      $this->sair();
    	    }
    	    */

          if (InfraUtil::compararVersoes($objInfraSessaoDTO->getNumVersaoSip(), '<', '2.0.3')){
            $this->sair();
          }


    	  } 
    	  
	    }
  	  //InfraDebug::getInstance()->gravar('#'.session_id());
	  }catch(InfraException $e){
	    //InfraDebug::getInstance()->gravarInfra('Erro validando sessão:'.$e->getStrDescricao());
			header('Location: '.$this->gerarLinkLogin().'&msg='.str_replace("\n",' ',$e->getStrDescricao()));
			die;
	  }catch(Exception $e){
	    //InfraDebug::getInstance()->gravarInfra('Erro validando sessão:'.$e->getMessage());
			header('Location: '.$this->gerarLinkLogin().'&msg='.str_replace("\n",' ',$e->__toString()));
			die;
	  }
	}

	private function carregarSessao(){

	    $this->destruirSessao();
	    
  		//carrega sessao
  	  $this->verificarPostLogin('id_login','Identificador de login não informado.');
      $this->verificarPostLogin('id_sistema','Identificador do Sistema não informado.');
      $this->verificarPostLogin('id_usuario','Identificador do Usuário não informado.');
      
      $objInfraSip = new InfraSip($this);

			if(($obj = $objInfraSip->validarLogin($_GET['id_login'], $_GET['id_sistema'], $_GET['id_usuario'], $this->gerarHashAgente()))==null){
	      return false;
	    }

			$this->setObjInfraSessaoDTO($this->gerarObjInfraSessaoDTO($obj));
			
			return true;
	}
		
  private function validarLinkAssinadoExterno(){

    $objInfraSip = new InfraSip($this);
    if (($obj = $objInfraSip->loginUnificado($_SERVER['REQUEST_URI'], $this->gerarHashAgente()))==null){
      return false;
    }

    $this->setBolLoginUnificado(true);
		$this->setObjInfraSessaoDTO($this->gerarObjInfraSessaoDTO($obj));
			
    return true;
  }
  
  private function gerarObjInfraSessaoDTO($obj){
    
		$objInfraSessaoDTO = new InfraSessaoDTO();
		$objInfraSessaoDTO->setNumIdOrgaoSistema($obj->numIdOrgaoSistema);
		$objInfraSessaoDTO->setStrSiglaOrgaoSistema($obj->strSiglaOrgaoSistema);
		$objInfraSessaoDTO->setStrDescricaoOrgaoSistema($obj->strDescricaoOrgaoSistema);
		$objInfraSessaoDTO->setStrSiglaSistema($obj->strSiglaSistema);
		$objInfraSessaoDTO->setNumIdSistema($obj->numIdSistema);
		$objInfraSessaoDTO->setStrSiglaOrgaoUsuario($obj->strSiglaOrgaoUsuario);
		$objInfraSessaoDTO->setStrDescricaoOrgaoUsuario($obj->strDescricaoOrgaoUsuario);
		$objInfraSessaoDTO->setNumIdOrgaoUsuario($obj->numIdOrgaoUsuario);
		$objInfraSessaoDTO->setNumIdContextoUsuario($obj->numIdContextoUsuario);
		$objInfraSessaoDTO->setNumIdUsuario($obj->numIdUsuario);
		$objInfraSessaoDTO->setStrIdOrigemUsuario($obj->strIdOrigemUsuario);
		$objInfraSessaoDTO->setStrSiglaUsuario($obj->strSiglaUsuario);
		$objInfraSessaoDTO->setStrNomeUsuario($obj->strNomeUsuario);
    $objInfraSessaoDTO->setStrNomeRegistroCivilUsuario($obj->strNomeRegistroCivilUsuario);
    $objInfraSessaoDTO->setStrNomeSocialUsuario($obj->strNomeSocialUsuario);
		$objInfraSessaoDTO->setArrUnidadesPadrao($obj->arrUnidadesPadrao);
		$objInfraSessaoDTO->setStrHashInterno($obj->strHashInterno);
		$objInfraSessaoDTO->setStrHashUsuario($obj->strHashUsuario);
    $objInfraSessaoDTO->setBol2Fatores($obj->bol2Fatores);
    $objInfraSessaoDTO->setDthUltimoLogin($obj->dthUltimoLogin);
		
		if (is_array($obj->arrOrgaos)){
		  $arr = array();
		  foreach($obj->arrOrgaos as $item){
		    $arr[$item[InfraSip::$WS_LOGIN_ORGAO_ID]] = $item;
		  }
		  $obj->arrOrgaos = $arr;  
		}
		$objInfraSessaoDTO->setArrOrgaos($obj->arrOrgaos);

    if (is_array($obj->arrPermissoes)){

      foreach($obj->arrPermissoes as $key => $permissao){
        if (is_array($permissao[InfraSip::$WS_LOGIN_PERMISSAO_RECURSOS])){
          $arr = array();
          foreach($permissao[InfraSip::$WS_LOGIN_PERMISSAO_RECURSOS] as $recurso){
            $arr[$recurso] = 0;
          }
          $obj->arrPermissoes[$key][InfraSip::$WS_LOGIN_PERMISSAO_RECURSOS] = $arr;
        }
      }

      $arrUnidadesOrdenadas = array();

		  $arr = array();
  		  
	    foreach($obj->arrPermissoes as $permissao){
	      foreach($permissao[InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES] as $arrUnidade){
          $arrUnidadesOrdenadas[$arrUnidade[InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_ID]] = $arrUnidade[InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_SIGLA];
          $arr[$arrUnidade[InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_ID]] = $arrUnidade;
	      }
      }

	    asort($arrUnidadesOrdenadas);
	      
	    foreach(array_keys($arrUnidadesOrdenadas) as $numIdUnidade){
        $arrUnidadesOrdenadas[$numIdUnidade] = $arr[$numIdUnidade];
	    }

      foreach($obj->arrPermissoes as $key => $permissao){
        $arr = array();
        foreach($permissao[InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES] as $unidade){
          $arr[$unidade[InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_ID]] = 0;
        }
        $obj->arrPermissoes[$key][InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES] = $arr;
      }

      $objInfraSessaoDTO->setArrPermissoes($obj->arrPermissoes);
      $objInfraSessaoDTO->setArrUnidades($arrUnidadesOrdenadas);

	  }else{
      $objInfraSessaoDTO->setArrPermissoes(array());
      $objInfraSessaoDTO->setArrUnidades(array());
    }

		$objInfraSessaoDTO->setStrPaginaInicial($obj->strPaginaInicial);
		
		$objInfraSessaoDTO->setStrSiglaOrgaoUsuarioEmulador($obj->strSiglaOrgaoUsuarioEmulador);
		$objInfraSessaoDTO->setStrDescricaoOrgaoUsuarioEmulador($obj->strDescricaoOrgaoUsuarioEmulador);
		$objInfraSessaoDTO->setNumIdOrgaoUsuarioEmulador($obj->numIdOrgaoUsuarioEmulador);
		$objInfraSessaoDTO->setNumIdUsuarioEmulador($obj->numIdUsuarioEmulador);
		$objInfraSessaoDTO->setStrSiglaUsuarioEmulador($obj->strSiglaUsuarioEmulador);
		$objInfraSessaoDTO->setStrNomeUsuarioEmulador($obj->strNomeUsuarioEmulador);
		
		$objInfraSessaoDTO->setNumVersaoSip($obj->numVersaoSip);
		$objInfraSessaoDTO->setNumVersaoInfraSip($obj->numVersaoInfraSip);
		$objInfraSessaoDTO->setNumTimestampLogin(time());
		
		session_regenerate_id(true);

    if (InfraDebug::isBolProcessar()) {
      InfraDebug::getInstance()->gravarInfra('ID do Órgão do Sistema: ' . $objInfraSessaoDTO->getNumIdOrgaoSistema());
      InfraDebug::getInstance()->gravarInfra('Sigla do Órgão do Sistema: ' . $objInfraSessaoDTO->getStrSiglaOrgaoSistema());
      InfraDebug::getInstance()->gravarInfra('Descrição do Órgão do Sistema: ' . $objInfraSessaoDTO->getStrDescricaoOrgaoSistema());
      InfraDebug::getInstance()->gravarInfra('ID do Sistema: ' . $objInfraSessaoDTO->getNumIdSistema());
      InfraDebug::getInstance()->gravarInfra('Sigla do Sistema: ' . $objInfraSessaoDTO->getStrSiglaSistema());
      InfraDebug::getInstance()->gravarInfra('ID do Órgão do Usuário: ' . $objInfraSessaoDTO->getNumIdOrgaoUsuario());
      InfraDebug::getInstance()->gravarInfra('Sigla do Órgão do Usuário: ' . $objInfraSessaoDTO->getStrSiglaOrgaoUsuario());
      InfraDebug::getInstance()->gravarInfra('Descrição do Órgão do Usuário: ' . $objInfraSessaoDTO->getStrDescricaoOrgaoUsuario());
      InfraDebug::getInstance()->gravarInfra('ID do Contexto do Usuário: ' . $objInfraSessaoDTO->getNumIdContextoUsuario());
      InfraDebug::getInstance()->gravarInfra('ID do Usuário: ' . $objInfraSessaoDTO->getNumIdUsuario());
      InfraDebug::getInstance()->gravarInfra('Sigla do Usuário: ' . $objInfraSessaoDTO->getStrSiglaUsuario());
      InfraDebug::getInstance()->gravarInfra('Nome do Usuário: ' . $objInfraSessaoDTO->getStrNomeUsuario());
      InfraDebug::getInstance()->gravarInfra('Nome Registro Civil do Usuário: ' . $objInfraSessaoDTO->getStrNomeRegistroCivilUsuario());
      InfraDebug::getInstance()->gravarInfra('Nome Social do Usuário: ' . $objInfraSessaoDTO->getStrNomeSocialUsuario());
      InfraDebug::getInstance()->gravarInfra('ID Origem Usuário: ' . $objInfraSessaoDTO->getStrIdOrigemUsuario());
      InfraDebug::getInstance()->gravarInfra('ID do Órgão do Usuário Emulador: ' . $objInfraSessaoDTO->getNumIdOrgaoUsuarioEmulador());
      InfraDebug::getInstance()->gravarInfra('Sigla do Órgão do Usuário Emulador: ' . $objInfraSessaoDTO->getStrSiglaOrgaoUsuarioEmulador());
      InfraDebug::getInstance()->gravarInfra('Descrição do Órgão do Usuário Emulador: ' . $objInfraSessaoDTO->getStrDescricaoOrgaoUsuarioEmulador());
      InfraDebug::getInstance()->gravarInfra('ID do Usuário Emulador: ' . $objInfraSessaoDTO->getNumIdUsuarioEmulador());
      InfraDebug::getInstance()->gravarInfra('Sigla do Usuário Emulador: ' . $objInfraSessaoDTO->getStrSiglaUsuarioEmulador());
      InfraDebug::getInstance()->gravarInfra('Nome do Usuário Emulador: ' . $objInfraSessaoDTO->getStrNomeUsuarioEmulador());
      InfraDebug::getInstance()->gravarInfra('Hash Usuário: ' . $objInfraSessaoDTO->getStrHashUsuario());
      InfraDebug::getInstance()->gravarInfra('Hash Interno: ' . $objInfraSessaoDTO->getStrHashInterno());
      InfraDebug::getInstance()->gravarInfra('2 Fatores: ' . $objInfraSessaoDTO->getBol2Fatores());
      $strUnidadesPadrao = '';
      foreach ($objInfraSessaoDTO->getArrUnidadesPadrao() as $unidadePadrao) {
        if ($strUnidadesPadrao != '') {
          $strUnidadesPadrao .= ', ';
        }
        $strUnidadesPadrao .= $unidadePadrao[InfraSip::$WS_LOGIN_UNIDADE_PADRAO_SIGLA] . ' (' . $unidadePadrao[InfraSip::$WS_LOGIN_UNIDADE_PADRAO_ID] . ')';
      }
      InfraDebug::getInstance()->gravarInfra('Unidades Padrão: ' . $strUnidadesPadrao);


      $strOrgaos = '';
      foreach ($objInfraSessaoDTO->getArrOrgaos() as $orgao) {
        if ($strOrgaos != '') {
          $strOrgaos .= ', ';
        }
        $strOrgaos .= $orgao[InfraSip::$WS_LOGIN_ORGAO_SIGLA] . ' / ' . $orgao[InfraSip::$WS_LOGIN_ORGAO_DESCRICAO] . ' (' . $orgao[InfraSip::$WS_LOGIN_ORGAO_ID] . ')';
      }
      InfraDebug::getInstance()->gravarInfra('Órgãos: ' . $strOrgaos);

      InfraDebug::getInstance()->gravarInfra('Versão SIP: ' . $objInfraSessaoDTO->getNumVersaoSip() . ' (InfraPHP ' . $objInfraSessaoDTO->getNumVersaoInfraSip() . ')');
    }

		return $objInfraSessaoDTO;
  }
		
  private function verificarPostLogin($strCampo,$strMsg){
    if (!isset($_GET[$strCampo]) || InfraString::isBolVazia($_GET[$strCampo])){
			header('Location: '.$this->gerarLinkLogin().'&msg='.$strMsg);
			die;
    }
  }
	
  public function setObjInfraSessaoDTO($objInfraSessaoDTO){
    
    $this->validarObjInfraSessaoDTO($objInfraSessaoDTO);      
    
    //Se ainda não tem variavel de sessao
    if (!isset($_SESSION['INFRA_SESSAO'])){
      $_SESSION['INFRA_SESSAO'] = array();
    }

    if (!isset($_SESSION['INFRA_SESSAO'][$objInfraSessaoDTO->getStrSiglaOrgaoSistema()])){
      $_SESSION['INFRA_SESSAO'][$objInfraSessaoDTO->getStrSiglaOrgaoSistema()] = array();
    }

    $_SESSION['INFRA_SESSAO'][$objInfraSessaoDTO->getStrSiglaOrgaoSistema()][$objInfraSessaoDTO->getStrSiglaSistema()] = $objInfraSessaoDTO;

    self::$objInfraSessaoDTO = $objInfraSessaoDTO;
  }

  public function getObjInfraSessaoDTO(){
    if (self::$objInfraSessaoDTO==null){
      if (isset($_SESSION['INFRA_SESSAO'][$this->getStrSiglaOrgaoSistema()][$this->getStrSiglaSistema()])){
        self::$objInfraSessaoDTO = $_SESSION['INFRA_SESSAO'][$this->getStrSiglaOrgaoSistema()][$this->getStrSiglaSistema()];
      }
    }
    return self::$objInfraSessaoDTO;
  }

  private function validarObjInfraSessaoDTO($objInfraSessaoDTO){
      
    if (!is_object($objInfraSessaoDTO)){
      $this->sair($this->gerarLinkLogin(),'Sessão inválida.');
      die;
    }

    if (!$objInfraSessaoDTO instanceof InfraSessaoDTO) {
      $this->sair($this->gerarLinkLogin(),'Objeto de sessão inválido.');
      die;
    }

    $arrPermissoes = $objInfraSessaoDTO->getArrPermissoes();
	
    if (!is_array($arrPermissoes)){
			$this->sair($this->gerarLinkLogin(),'Permissões não foram carregadas.');
			die;
		}
	
		if (count($arrPermissoes)==0){
			$this->sair($this->gerarLinkLogin(),'Usuário não possui permissões neste sistema.');
			die;
		}
  }

  public function gerarHashInterno()	{
    if ($this->strHashInterno==null){
      $objInfraSessaoDTO = $this->getObjInfraSessaoDTO();
      if ($objInfraSessaoDTO!=null) {
        $this->strHashInterno = $objInfraSessaoDTO->getStrHashInterno();
      }
    }
    return $this->strHashInterno;
  }

  public function gerarHashUsuario()	{
    if ($this->strHashUsuario==null){
      $objInfraSessaoDTO = $this->getObjInfraSessaoDTO();
      if ($objInfraSessaoDTO!=null) {
        $this->strHashUsuario = $objInfraSessaoDTO->getStrHashUsuario();
      }
    }
    return $this->strHashUsuario;
  }
  
  public function gerarHashExterno($str){
    return hash('SHA256',$str.$this->gerarHashInterno());
  }
  
  //necessário ser estático devido ao login no SIP
	public static function gerarHashAgente(){
    return hash('SHA512','#'.$_SERVER['HTTP_USER_AGENT'].'#');
	}

  public function assinarLink($strLink){

	  if ($this->arrParametrosRepasse!=null){

	    $strParametrosRepasse = '';
	    foreach($this->arrParametrosRepasse as $strParametro){
        if(isset($_GET[$strParametro])){
          $strParametrosRepasse .= '&'.$strParametro.'='.$_GET[$strParametro];
        }
      }

      if (strpos($strLink,'?')===false){
	      $strParametrosRepasse[0] = '?';
      }

      $numPosAncora = strpos($strLink,'#');
      if ($numPosAncora!==false){
        $strLink = substr($strLink,0,$numPosAncora).$strParametrosRepasse.substr($strLink,$numPosAncora);
      }else{
        $strLink .= $strParametrosRepasse;
      }
    }

    if (!$this->isBolHabilitada() || !$this->getBolValidarLinks()){
      return $strLink;
    }

    $strLink = urldecode($strLink);

    //retira ancora do link
    $strAncora ='';
    $numPosAncora = strpos($strLink,'#');
    if ($numPosAncora!==false){
      $strNovoLink = substr($strLink,0,$numPosAncora);
      $strAncora = substr($strLink,$numPosAncora);
      $strLink = $strNovoLink;
    }

    $strLink = $this->removerParametrosLink($strLink);

    if ($this->getNumIdSistema()!=null){
      $numPosParam = strpos($strLink,'?');
      if ($numPosParam===false){
        $strLink .= '?infra_sistema='.$this->getNumIdSistema();
      }else{
        $strLink .= '&infra_sistema='.$this->getNumIdSistema();
      }
    }

    if ($this->getNumIdUnidadeAtual()!=null){
      $numPosParam = strpos($strLink,'?');
      if ($numPosParam===false){
        $strLink .= '?infra_unidade_atual='.$this->getNumIdUnidadeAtual();
      }else{
        $strLink .= '&infra_unidade_atual='.$this->getNumIdUnidadeAtual();
      }
    }

    //Gera hash se tiver parametros no link
    $numPosParam = strpos($strLink,'?');
    if ($numPosParam!==false){
      $strParam = substr($strLink, $numPosParam+1);
      $strLink .= '&infra_hash='.$this->gerarHashExterno($strParam).((substr($strLink,0,4)=='http')?$this->gerarHashUsuario():'');
    }

    //Se tem ancora coloca no final (não entrou no calculo do hash)
    if ($strAncora!=''){
      $strLink .= $strAncora;
    }

    return $strLink;
  }

  public function verificarLink($strLink=null){

    if ($strLink == null){
      $strLink = $_SERVER['REQUEST_URI'];
    }

    $strLink = urldecode($strLink);

    if (trim($strLink)==''){
      return false;
    }

    $numPosHash = strpos($strLink,'&infra_hash=');
    if ($numPosHash===false){
      return false;
    }

    $strHashLink = substr($strLink, $numPosHash + strlen('&infra_hash='));

    $numTamHash = strlen($strHashLink);

    if ($numTamHash != 64 && $numTamHash != 192){
      return false;
    }

    $numPosParam = strpos($strLink,'?');
    if ($numPosParam===false){
      return false;
    }

    $strParam = substr(str_replace('&infra_hash='.$strHashLink,'',$strLink), $numPosParam+1);

    $strHashRecalculado = $this->gerarHashExterno($strParam).(($numTamHash == 192)?$this->gerarHashUsuario():'');

    if ($strHashLink != $strHashRecalculado){
      return false;
    }
    
    return true;
  }  

  public function validarLink($strLink=null){

  	if (!$this->isBolHabilitada() || !$this->getBolValidarLinks()){
		  return;
		}

    if (InfraDebug::isBolProcessar()) {
      InfraDebug::getInstance()->gravarInfra('[InfraSessao->validarLink]');
    }

    if ($strLink == null){
      $strLink = $_SERVER['REQUEST_URI'];
    }

    $strLink = urldecode($strLink);

    if (trim($strLink)==''){
      return;
    }

    $numPosHash = strpos($strLink,'&infra_hash=');
    if ($numPosHash===false){
      if ($this->tratarLinkSemAssinatura($strLink)){
        header('Location: '.$this->assinarLink($strLink));
        die;
      }else {
        $this->sair($this->gerarLinkLogin(), 'Link sem assinatura.');
        die;
      }
    }

    $strHashLink = substr($strLink, $numPosHash + strlen('&infra_hash='));

    $numTamHash = strlen($strHashLink);

    if ($numTamHash != 64 && $numTamHash != 192) {
      $this->sair($this->gerarLinkLogin(), 'Tamanho de hash inválido.');
      die;
    }

    $numPosParam = strpos($strLink,'?');
    if ($numPosParam===false){
      $this->sair($this->gerarLinkLogin(),'Parâmetros não encontrados no Link.');
      die;
    }

    $strParam = substr(str_replace('&infra_hash='.$strHashLink,'',$strLink), $numPosParam+1);

    $strHashRecalculado = $this->gerarHashExterno($strParam).(($numTamHash == 192)?$this->gerarHashUsuario():'');

    if ($strHashLink != $strHashRecalculado){
      $this->sair($this->gerarLinkLogin(),urlencode('Hash inválido. ['.$strLink.']'));
      die;
    }
    
    //nao validar unidade atual se o link de entrada no sistema possui o atributo, pois neste caso é um link assinado por outro sistema e a unidade pode não corresponder
    if (isset($_GET['infra_sistema']) && isset($_GET['infra_unidade_atual']) && $_GET['infra_sistema']==$this->getNumIdSistema() && $_GET['infra_unidade_atual']!=$this->getNumIdUnidadeAtual()){

    	$strSiglaUnidadeAnterior = $this->getStrSiglaUnidade($_GET['infra_unidade_atual']);
    	
    	$strLink = $this->getStrLinkTrocarUnidade();
    	 
    	if ($strLink == null){
    	  $strLink = $this->getStrPaginaInicial();
    	}
    	
    	$strMsg = $this->getStrMsgDetectadaTrocaUnidade($this->getStrSiglaUnidade($_GET['infra_unidade_atual']), $this->getStrSiglaUnidadeAtual());
    	
    	if ($strMsg!=''){
	    	$posParam = strpos($strLink,'?');
	    	if ($posParam===false){
	    		$strLink .= '?';	
	    	}else{
	    		$strLink .= '&';
	    	}
	    	$strLink .= 'msg='.$strMsg;
    	}
    	
  	  header('Location: '.$this->assinarLink($strLink));
  	  die;
    }
  }

  public function tratarLinkSemAssinatura($strLink){
    return false;
  }

  public function getStrMsgDetectadaTrocaUnidade($strSiglaUnidadeAnterior, $strSiglaUnidadeAtual){
  	return 'Detectada troca de unidade '.$strSiglaUnidadeAnterior.' para '.$strSiglaUnidadeAtual.'.';
  }
  
  public function gerarLinkLogin(){
    $strPaginaLogin = $this->getStrPaginaLogin();
    
    if (strpos($strPaginaLogin,'?')===false){
      $strSeparador = '?';
    }else{
      $strSeparador = '&';
    }
    
    return $this->getStrPaginaLogin().$strSeparador.'sigla_orgao_sistema='.$this->getStrSiglaOrgaoSistema().'&sigla_sistema='.$this->getStrSiglaSistema();
  }

  public function setPropriedade($strChave, $strPropriedade, $strValor){
    $this->getObjInfraSessaoDTO()->setPropriedade($this->getStrSiglaSistema(), $strChave, $strPropriedade, $strValor);
  }

  public function getPropriedade($strChave, $strPropriedade){
    return $this->getObjInfraSessaoDTO()->getPropriedade($this->getStrSiglaSistema(),$strChave, $strPropriedade);
  }

  public function validarAuditarPermissao($strNomeRecurso, $strOperacao = null, $varParametro = null){
    $this->validarPermissao($strNomeRecurso);
    $this->getObjInfraAuditoria()->auditar($strNomeRecurso, $strOperacao, $varParametro);
  }
  
	public function validarPermissao($strNomeRecurso){
 		
		if (!$this->verificarPermissao($strNomeRecurso)){
		  
	    $numIdUnidade = $this->getNumIdUnidadeAtual();
	    $strSiglaUnidade = $this->getStrSiglaUnidadeAtual();
		  
			if ( $numIdUnidade !== null ){
			  throw new InfraException('Acesso negado a este recurso nesta unidade ('.$strNomeRecurso.' / '.$strSiglaUnidade.').');
			}else{
				throw new InfraException('Acesso negado a este recurso ('.$strNomeRecurso.')');
			}
	  }
	}

  public function verificarPermissao($strNomeRecurso){    
		
    if (!$this->isBolHabilitada()){
      return true;
    }
        
    $numIdUnidade = $this->getNumIdUnidadeAtual();
    
    if ($numIdUnidade===null){
      return false;
    }
    
		$arrPermissoes = $this->getObjInfraSessaoDTO()->getArrPermissoes();
		
		foreach($arrPermissoes as $permissao){
		  if (isset($permissao[InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES][$numIdUnidade])){
        if (!is_array($permissao[InfraSip::$WS_LOGIN_PERMISSAO_RECURSOS])){
           unset($_SESSION['INFRA_SESSAO']);
           unset($_SESSION['INFRA_PAGINA']);
           unset($_SESSION['INFRA_DEBUG']);
           header('Location: '.$this->gerarLinkLogin());
           die;
        }

        if (isset($permissao[InfraSip::$WS_LOGIN_PERMISSAO_RECURSOS][$strNomeRecurso])){
          return true;
        }
		  }
		}
  	return false;
  }
    
  public function sair($strLinkSair=null,$strMensagem=null){
    
	  //Se não foi passado um link para o sair
	  //assume a página de login da sessão
	  $strLink = '';
	  if ($strLinkSair===null){
	    $strLink = $this->gerarLinkLogin();
	  }else{
	    $strLink = $strLinkSair;
	  }

	  if ($strMensagem!=null){
	  	$strMsg = '&msg='.$strMensagem;
	  }

	  $this->destruirSessao();
	  
	  if ($this->getObjInfraSessaoDTO()!=null){
	    $objInfraSip = new InfraSip($this);
	    $objInfraSip->removerLogin($_SERVER['REQUEST_URI'],$this->getNumIdUsuario());
	    self::$objInfraSessaoDTO = null;
	  }
	   
	  header('Location: '.$strLink.$strMsg);
		die;
  }

  private function destruirSessao(){
    
    if (isset($_SESSION['INFRA_SESSAO'][$this->getStrSiglaOrgaoSistema()][$this->getStrSiglaSistema()])){
      unset($_SESSION['INFRA_SESSAO'][$this->getStrSiglaOrgaoSistema()][$this->getStrSiglaSistema()]);
    }
     
    //Destroi INFRA_PAGINA do Sistema
    if (isset($_SESSION['INFRA_PAGINA'][$this->getStrSiglaOrgaoSistema()][$this->getStrSiglaSistema()])){
      unset($_SESSION['INFRA_PAGINA'][$this->getStrSiglaOrgaoSistema()][$this->getStrSiglaSistema()]);
    }
    //Se não tem mais nenhum sistema neste órgão
    if (isset($_SESSION['INFRA_PAGINA'][$this->getStrSiglaOrgaoSistema()])){
      if (is_array($_SESSION['INFRA_PAGINA'][$this->getStrSiglaOrgaoSistema()])){
        if (count($_SESSION['INFRA_PAGINA'][$this->getStrSiglaOrgaoSistema()])==0){
          unset($_SESSION['INFRA_PAGINA'][$this->getStrSiglaOrgaoSistema()]);
        }
      }
    }
     
    //Destroi INFRA_ATRIBUTOS do Sistema
    if (isset($_SESSION['INFRA_ATRIBUTOS'][$this->getStrSiglaOrgaoSistema()][$this->getStrSiglaSistema()])){
      unset($_SESSION['INFRA_ATRIBUTOS'][$this->getStrSiglaOrgaoSistema()][$this->getStrSiglaSistema()]);
    }
    
    //Se não tem mais nenhum sistema neste órgão
    if (isset($_SESSION['INFRA_ATRIBUTOS'][$this->getStrSiglaOrgaoSistema()])){
      if (is_array($_SESSION['INFRA_ATRIBUTOS'][$this->getStrSiglaOrgaoSistema()])){
        if (count($_SESSION['INFRA_ATRIBUTOS'][$this->getStrSiglaOrgaoSistema()])==0){
          unset($_SESSION['INFRA_ATRIBUTOS'][$this->getStrSiglaOrgaoSistema()]);
        }
      }
    }
     
    //Destroi INFRA_DEBUG
    if (isset($_SESSION['INFRA_DEBUG'])){
      unset($_SESSION['INFRA_DEBUG']);
    }
     
  }

    /** Salva um atributo na $_SESSION. Útil para armazenar valores que sejam muito frequentemente
     *  consultados, para evitar que se fique indo no banco de dados a todo instante.
     * @param string $strNome
     * @param mixed $varValor
     */
    public function setAtributo($strNome, $varValor){
    
	  $strOrgao = $this->getStrSiglaOrgaoSistema();
	  $strSistema = $this->getStrSiglaSistema();
	  
		if (!isset($_SESSION['INFRA_ATRIBUTOS'])){
			$_SESSION['INFRA_ATRIBUTOS'] = array();
		}
		
		if (!isset($_SESSION['INFRA_ATRIBUTOS'][$strOrgao])){
			$_SESSION['INFRA_ATRIBUTOS'][$strOrgao] = array();
		}
		
		if (!isset($_SESSION['INFRA_ATRIBUTOS'][$strOrgao][$strSistema])){
			$_SESSION['INFRA_ATRIBUTOS'][$strOrgao][$strSistema] = array();
		}
		
		$_SESSION['INFRA_ATRIBUTOS'][$strOrgao][$strSistema][$strNome] = $varValor;
  }

	public function isSetAtributo($strNome){
	  
	  $strOrgao = $this->getStrSiglaOrgaoSistema();
	  $strSistema = $this->getStrSiglaSistema();
	  
		return isset($_SESSION['INFRA_ATRIBUTOS'][$strOrgao][$strSistema][$strNome]);
	}


    /** Obtém um atributo salvo na sessão ($_SESSION) através de prévio uso da função setAtributo()
     * @param string $strNome Nome do atributo desejado
     * @return mixed Valor do atributo - Pode ser array, string, etc. Importante colocar o tipo aqui para o Phpstorm entender.
     */
    public function getAtributo($strNome){
	  
	  $strOrgao = $this->getStrSiglaOrgaoSistema();
	  $strSistema = $this->getStrSiglaSistema();
	  
		if (!isset($_SESSION['INFRA_ATRIBUTOS'][$strOrgao][$strSistema][$strNome])){
			return '';
		}
		
		return $_SESSION['INFRA_ATRIBUTOS'][$strOrgao][$strSistema][$strNome];
	}
  
	public function removerAtributo($strNome){
	  $strOrgao = $this->getStrSiglaOrgaoSistema();
	  $strSistema = $this->getStrSiglaSistema();
	  
		if (isset($_SESSION['INFRA_ATRIBUTOS'][$strOrgao][$strSistema][$strNome])){
		  unset($_SESSION['INFRA_ATRIBUTOS'][$strOrgao][$strSistema][$strNome]);
		}
	}
	
  //Exporta métodos do DTO, senão as aplicacoes em tempo de desenvolvimento
  //sempre precisam testar se existe o DTO de sessão
  //Desta maneira as aplicações não precisam saber que existe o DTO de sessão
  public function getStrSiglaOrgaoUsuario(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getStrSiglaOrgaoUsuario();
    }
    return null;
  }

  public function getStrDescricaoOrgaoUsuario(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getStrDescricaoOrgaoUsuario();
    }
    return null;
  }
  
  public function getNumIdOrgaoUsuario(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getNumIdOrgaoUsuario();
    }
    return null;
  }
  
  public function getStrSiglaUsuario(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getStrSiglaUsuario();
    }
    return null;
  }

  public function getStrNomeUsuario(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getStrNomeUsuario();
    }
    return null;
  }

  public function getStrNomeRegistroCivilUsuario(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getStrNomeRegistroCivilUsuario();
    }
    return null;
  }

  public function getStrNomeSocialUsuario(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getStrNomeSocialUsuario();
    }
    return null;
  }

  public function getNumIdUsuario(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getNumIdUsuario();
    }
    return null;
  }
  
  public function getDblIdPessoaRh(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getStrIdOrigemUsuario();
    }
    return null;
  }

  public function getStrIdOrigemUsuario(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getStrIdOrigemUsuario();
    }
    return null;
  }

  public function getNumIdContextoUsuario(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getNumIdContextoUsuario();
    }
    return null;
  }

  public function getBol2Fatores(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getBol2Fatores();
    }
    return null;
  }

  public function getDthUltimoAcesso(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getDthUltimoLogin();
    }
    return null;
  }

  public function getNumIdOrgaoSistema(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getNumIdOrgaoSistema();
    }
    return null;
  }

  public function getStrDescricaoOrgaoSistema(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getStrDescricaoOrgaoSistema();
    }
    return null;
  }
  
  public function getNumIdSistema(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getNumIdSistema();
    }
    return null;
  }

  public function getStrSiglaOrgaoUsuarioEmulador(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getStrSiglaOrgaoUsuarioEmulador();
    }
    return null;
  }

  public function getStrDescricaoOrgaoUsuarioEmulador(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getStrDescricaoOrgaoUsuarioEmulador();
    }
    return null;
  }
  
  public function getNumIdOrgaoUsuarioEmulador(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getNumIdOrgaoUsuarioEmulador();
    }
    return null;
  }
  
  public function getStrSiglaUsuarioEmulador(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getStrSiglaUsuarioEmulador();
    }
    return null;
  }

  public function getStrNomeUsuarioEmulador(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getStrNomeUsuarioEmulador();
    }
    return null;
  }
  
  public function getNumIdUsuarioEmulador(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getNumIdUsuarioEmulador();
    }
    return null;
  }
  
  public function getArrUnidadesPadrao(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getArrUnidadesPadrao();
    }
    return null;
  }
  
  public function getNumIdUnidadeAtual(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getNumIdUnidadeAtual();
    }
    return null;
  }
  
  public function getStrSiglaUnidadeAtual(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      $arr = $objInfraSessaoDTO->getArrUnidades();
      return $arr[$objInfraSessaoDTO->getNumIdUnidadeAtual()][InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_SIGLA];
    }
    return null;
  }
  
  public function getStrDescricaoUnidadeAtual(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      $arr = $objInfraSessaoDTO->getArrUnidades();
      return $arr[$objInfraSessaoDTO->getNumIdUnidadeAtual()][InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_DESCRICAO];
    }
    return null;
  }
  
  public function getNumIdOrgaoUnidadeAtual(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      $arr = $objInfraSessaoDTO->getArrUnidades();
      return $arr[$objInfraSessaoDTO->getNumIdUnidadeAtual()][InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_ID_ORGAO];
    }
    return null;
  }

  public function getStrIdOrigemUnidadeAtual(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      $arr = $objInfraSessaoDTO->getArrUnidades();
      return $arr[$objInfraSessaoDTO->getNumIdUnidadeAtual()][InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_ID_ORIGEM];
    }
    return null;
  }

  public function getStrSiglaOrgaoUnidadeAtual(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      $arrUnidades = $objInfraSessaoDTO->getArrUnidades();
      $arrOrgaos = $objInfraSessaoDTO->getArrOrgaos();
      return $arrOrgaos[$arrUnidades[$objInfraSessaoDTO->getNumIdUnidadeAtual()][InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_ID_ORGAO]][InfraSip::$WS_LOGIN_ORGAO_SIGLA];
    }
    return null;
  }

  public function getStrDescricaoOrgaoUnidadeAtual(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      $arrUnidades = $objInfraSessaoDTO->getArrUnidades();
      $arrOrgaos = $objInfraSessaoDTO->getArrOrgaos();
      return $arrOrgaos[$arrUnidades[$objInfraSessaoDTO->getNumIdUnidadeAtual()][InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_ID_ORGAO]][InfraSip::$WS_LOGIN_ORGAO_DESCRICAO];
    }
    return null;
  }

  public function getStrSiglaUnidade($numIdUnidade){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      $arr = $objInfraSessaoDTO->getArrUnidades();
      if (!isset($arr[$numIdUnidade])){
        return null;
      }
      
      return $arr[$numIdUnidade][InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_SIGLA];	
    }
    return null;
  }

  public function getStrDescricaoUnidade($numIdUnidade){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      $arr = $objInfraSessaoDTO->getArrUnidades();
      if (!isset($arr[$numIdUnidade])){
        return null;
      }
      
      return $arr[$numIdUnidade][InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_DESCRICAO];	
    }
    return null;
  }
  
  public function getNumIdOrgaoUnidade($numIdUnidade){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      $arr = $objInfraSessaoDTO->getArrUnidades();
      if (!isset($arr[$numIdUnidade])){
        return null;
      }
      return $arr[$numIdUnidade][InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_ID_ORGAO];
    }
    return null;
  }

  public function getStrIdOrigemUnidade($numIdUnidade){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      $arr = $objInfraSessaoDTO->getArrUnidades();
      if (!isset($arr[$numIdUnidade])){
        return null;
      }
      return $arr[$numIdUnidade][InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_ID_ORIGEM];
    }
    return null;
  }

  public function getStrSiglaOrgaoUnidade($numIdUnidade){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      $arrUnidades = $objInfraSessaoDTO->getArrUnidades();
      if (!isset($arrUnidades[$numIdUnidade])){
        return null;
      }
      $arrOrgaos = $objInfraSessaoDTO->getArrOrgaos();
      return $arrOrgaos[$arrUnidades[$numIdUnidade][InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_ID_ORGAO]][InfraSip::$WS_LOGIN_ORGAO_SIGLA];
    }
    return null;
  }

  public function getStrDescricaoOrgaoUnidade($numIdUnidade){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      $arrUnidades = $objInfraSessaoDTO->getArrUnidades();
      if (!isset($arrUnidades[$numIdUnidade])){
        return null;
      }
      $arrOrgaos = $objInfraSessaoDTO->getArrOrgaos();
      return $arrOrgaos[$arrUnidades[$numIdUnidade][InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_ID_ORGAO]][InfraSip::$WS_LOGIN_ORGAO_DESCRICAO];
    }
    return null;
  }

  public function getStrPaginaInicial(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      $strLink = $objInfraSessaoDTO->getStrPaginaInicial();
      //if (strpos($strLink,'?')===false){
      //  $strLink .= '?sip=false';
      //}
      return $strLink;
    }
    return null;
  }

  public function getStrUltimaPagina(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getStrUltimaPagina();
    }
    return null;
  }

  public function setStrUltimaPagina($strUltimaPagina){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      $objInfraSessaoDTO->setStrUltimaPagina($strUltimaPagina);
    }
    return null;
  }

  public function setStrPaginaInicial($strPaginaInicial){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      $objInfraSessaoDTO->setStrPaginaInicial($strPaginaInicial);
    }
    return null;
  }
  
  public function getArrUnidades(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getArrUnidades();
    }
    return array();
  }

  public function getArrOrgaos(){
    if (($objInfraSessaoDTO = $this->getObjInfraSessaoDTO())!=null){
      return $objInfraSessaoDTO->getArrOrgaos();
    }
    return array();
  }

  public function setStrHashInterno($strHashInterno){
    $this->strHashInterno = $strHashInterno;
  }

  public function setStrHashUsuario($strHashUsuario){
    $this->strHashUsuario = $strHashUsuario;
  }

  public function getArrMenu($strNomeMenu, $numIdUnidade=null){
	  if ($this->getObjInfraSessaoDTO()!==null){

      if ($numIdUnidade===null){
        $numIdUnidade = $this->getNumIdUnidadeAtual();
      }

      $arrPermissoes = $this->getObjInfraSessaoDTO()->getArrPermissoes();

        foreach($arrPermissoes as $permissao){
          if (is_array($permissao[InfraSip::$WS_LOGIN_PERMISSAO_MENU])){
              if (isset($permissao[InfraSip::$WS_LOGIN_PERMISSAO_MENU][$strNomeMenu])){

                //Retorna o primeiro menu encontrado
                if ($numIdUnidade===null){
                  return $permissao[InfraSip::$WS_LOGIN_PERMISSAO_MENU][$strNomeMenu];
                }

                if (isset($permissao[InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES][$numIdUnidade])){
                  return $permissao[InfraSip::$WS_LOGIN_PERMISSAO_MENU][$strNomeMenu];
                }
            }
          }
        }
    }
    return array();
  }
  
	public function listarPermissoes() {
	  $strRet = '';
	  $objInfraSessaoDTO = $this->getObjInfraSessaoDTO();
	  if ($objInfraSessaoDTO !== null){
  		$strRet .= "\n";
  		$strRet .= $objInfraSessaoDTO->getStrSiglaOrgaoSistema().' ('.$objInfraSessaoDTO->getNumIdOrgaoSistema().')';
  		$strRet .= ' - ';
  		$strRet .= $objInfraSessaoDTO->getStrSiglaSistema().' ('.$objInfraSessaoDTO->getNumIdSistema().')';
  		
  		$arrPermissoes = $objInfraSessaoDTO->getArrPermissoes();
      $arrUnidades = $this->getArrUnidades();
  		if (is_array($arrPermissoes)){
    		foreach($arrPermissoes as $permissao){
    		  $strRet .= "\n";
    			$strRet .= "\n".'Unidades:';

    			if (is_array($permissao[InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES])){
   	        foreach(array_keys($permissao[InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES]) as $numIdUnidade){
    	    			$strRet .= "\n".$arrUnidades[$numIdUnidade][InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_SIGLA].' ('.$numIdUnidade.')';
     	      }
    			}
   	      
    			$strRet .= "\n".'Recursos:';
    			if (is_array($permissao[InfraSip::$WS_LOGIN_PERMISSAO_RECURSOS])){
      			foreach(array_keys($permissao[InfraSip::$WS_LOGIN_PERMISSAO_RECURSOS]) as $recurso){
      				$strRet .= "\n".$recurso;
      			}
    			}
    			
    			if (is_array($permissao[InfraSip::$WS_LOGIN_PERMISSAO_MENU])){
      			foreach($permissao[InfraSip::$WS_LOGIN_PERMISSAO_MENU] as $menu){
      			  $strRet .= "\n".'Menu:'.$menu;
      			  foreach($menu as $item){
      				  $strRet .= "\n".$item;
      			  }
      			}
    			}
    		} 
  		}
	  }
	  return $strRet;
	}

  private function removerParametrosLink($strLink){

    $arrRemover = array('?infra_sistema=',
        '&infra_sistema=',
        '?infra_unidade_atual=',
        '&infra_unidade_atual=',
        '&infra_hash=');

    foreach($arrRemover as $parametro){
      while(($numPosParametro = strpos($strLink, $parametro))!==false){
        $strNovoLink = substr($strLink,0,$numPosParametro);
        $strLink = substr($strLink,$numPosParametro+1);
        $posMais = strpos($strLink,'&');
        //Se tem algo após o parâmetro
        if ($posMais!==false){
          //Atribui o resto do link
          $strNovoLink .= substr($strLink,$posMais);
        }
        $strLink = $strNovoLink;
      }
    }
    
    return $strLink;
  }

  public function setArrParametrosRepasseLink($arrParametros){
    $this->arrParametrosRepasse = $arrParametros;
  }

  public function getArrParametrosRepasseLink(){
    return $this->arrParametrosRepasse;
  }
}
?>