<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4Њ REGIУO
 * 
 * 12/11/2007 - criado por MGA
 *
 */
 
require_once dirname(__FILE__).'/SEI.php';
 
 class SessaoSEI extends InfraSessao {

  public static $USUARIO_SEI = 'SEI';
  public static $USUARIO_INTERNET = 'INTERNET';
  public static $USUARIO_INTRANET = 'INTRANET';
  public static $USUARIO_SIP = 'SIP';
  public static $USUARIO_EDOC = 'EDOC';

  public static $UNIDADE_TESTE = 'TESTE';
   
 	private static $instance = null;

 	//inicio - atributos para simular login
 	private $numIdOrgaoSistema = null;
 	private $strDescricaoOrgaoSistema = null;
 	private $numIdSistema = null;
 	private $strSiglaOrgaoUsuario = null;
  private $strDescricaoOrgaoUsuario = null;
 	private $numIdOrgaoUsuario = null;
 	private $numIdUsuario = null;
  private $strIdOrigemUsuario = null;
 	private $strSiglaUsuario = null;
 	private $strNomeUsuario = null;
 	private $numIdUnidadeAtual = null;
  private $strIdOrigemUnidadeAtual = null;
 	private $strSiglaUnidadeAtual = null;
 	private $numIdOrgaoUnidadeAtual = null;
 	private $strSiglaOrgaoUnidadeAtual = null;
  private $objServicoDTO = null;

 	//fim - atributos para simular login
 	

 	public static function getInstance($bolHabilitada=true,$bolValidarLinks=true) { 
	    if (self::$instance == null) { 
        self::$instance = new SessaoSEI($bolHabilitada,$bolValidarLinks);
	    }
	    return self::$instance; 
	}

	public function __construct($bolHabilitada,$bolValidarLinks){
	  parent::__construct($bolHabilitada,$bolValidarLinks);
	}
	
	public function trocarUnidadeAtual(){
 	  if ($this->isBolHabilitada()){
		  if ($this->isBolTrocouUnidade()){
		    //limpar campos salvos
        unset($_SESSION['INFRA_PAGINA']);
      }
			parent::trocarUnidadeAtual();
		}
	}
	
  public function getStrSiglaOrgaoSistema(){
		return ConfiguracaoSEI::getInstance()->getValor('SessaoSEI','SiglaOrgaoSistema');
	}
	
	public function getStrSiglaSistema(){
		return ConfiguracaoSEI::getInstance()->getValor('SessaoSEI','SiglaSistema');
	}
	
	public function getStrPaginaLogin(){
		return ConfiguracaoSEI::getInstance()->getValor('SessaoSEI','PaginaLogin');
	}
	
  public function getStrSipWsdl(){
		return ConfiguracaoSEI::getInstance()->getValor('SessaoSEI','SipWsdl');
  }

  public function getStrSipChaveAcesso(){
   return ConfiguracaoSEI::getInstance()->getValor('SessaoSEI','ChaveAcesso');
  }

  public function inicializarSessao(){
    
  	if ($this->getNumIdUsuarioEmulador()!=null){
  	  $this->sair(null,'Operaчуo nуo permitida.');
  	}

  	if (ConfiguracaoSEI::getInstance()->getValor('SessaoSEI','https')){
  	  $this->setStrPaginaInicial(str_replace('http://','https://',$this->getStrPaginaInicial()));
  	}
  }

  //inicio - funчѕes para simular um login
  public function setNumIdOrgaoSistema($numIdOrgaoSistema){
    $this->numIdOrgaoSistema = $numIdOrgaoSistema;
  }

  public function setStrDescricaoOrgaoSistema($strDescricaoOrgaoSistema){
     $this->strDescricaoOrgaoSistema = $strDescricaoOrgaoSistema;
   }

  public function setNumIdSistema($numIdSistema){
    $this->numIdSistema = $numIdSistema;
  }
  
  public function setStrSiglaOrgaoUsuario($strSiglaOrgaoUsuario){
    $this->strSiglaOrgaoUsuario = $strSiglaOrgaoUsuario;
  }

  public function setStrDescricaoOrgaoUsuario($strDescricaoOrgaoUsuario){
     $this->strDescricaoOrgaoUsuario = $strDescricaoOrgaoUsuario;
   }
  
  public function setNumIdOrgaoUsuario($numIdOrgaoUsuario){
    $this->numIdOrgaoUsuario = $numIdOrgaoUsuario;
  }
  
  public function setNumIdUsuario($numIdUsuario){
    $this->numIdUsuario = $numIdUsuario;
  }

  public function setStrIdOrigemUsuario($strIdOrigemUsuario){
     $this->strIdOrigemUsuario = $strIdOrigemUsuario;
   }
  
  public function setStrSiglaUsuario($strSiglaUsuario){
    $this->strSiglaUsuario = $strSiglaUsuario;
  }
  
  public function setStrNomeUsuario($strNomeUsuario){
    $this->strNomeUsuario = $strNomeUsuario;
  }
  
  public function setNumIdUnidadeAtual($numIdUnidadeAtual){
    $this->numIdUnidadeAtual = $numIdUnidadeAtual;
  }

  public function setStrIdOrigemUnidadeAtual($strIdOrigemUnidadeAtual){
    $this->strIdOrigemUnidadeAtual = $strIdOrigemUnidadeAtual;
  }

  public function setStrSiglaUnidadeAtual($strSiglaUnidade){
    $this->strSiglaUnidadeAtual = $strSiglaUnidade;
  }

  public function setNumIdOrgaoUnidadeAtual($numIdOrgaoUnidadeAtual){
    $this->numIdOrgaoUnidadeAtual = $numIdOrgaoUnidadeAtual;
  }

  public function setStrSiglaOrgaoUnidadeAtual($strSiglaOrgaoUnidadeAtual){
    $this->strSiglaOrgaoUnidadeAtual = $strSiglaOrgaoUnidadeAtual;
  }

  public function setStrDescricaoOrgaoUnidadeAtual($strDescricaoOrgaoUnidadeAtual){
     $this->strDescricaoOrgaoUnidadeAtual = $strDescricaoOrgaoUnidadeAtual;
   }
  
  public function getNumIdOrgaoSistema(){
     return (!$this->isBolHabilitada()) ? $this->numIdOrgaoSistema : parent::getNumIdOrgaoSistema();    
  }

  public function getStrDescricaoOrgaoSistema(){
    return (!$this->isBolHabilitada()) ? $this->strDescricaoOrgaoSistema : parent::getStrDescricaoOrgaoSistema();
  }

  public function getNumIdSistema(){
     return (!$this->isBolHabilitada()) ? $this->numIdSistema : parent::getNumIdSistema();        
  }
  
  public function getStrSiglaOrgaoUsuario(){
     return (!$this->isBolHabilitada()) ? $this->strSiglaOrgaoUsuario : parent::getStrSiglaOrgaoUsuario();        
  }

  public function getStrDescricaoOrgaoUsuario(){
     return (!$this->isBolHabilitada()) ? $this->strDescricaoOrgaoUsuario : parent::getStrDescricaoOrgaoUsuario();
   }
  
  public function getNumIdOrgaoUsuario(){
     return (!$this->isBolHabilitada()) ? $this->numIdOrgaoUsuario : parent::getNumIdOrgaoUsuario();        
  }
  
  public function getNumIdUsuario(){
     return (!$this->isBolHabilitada()) ? $this->numIdUsuario : parent::getNumIdUsuario();        
  }

  public function getStrIdOrigemUsuario(){
    return (!$this->isBolHabilitada()) ? $this->strIdOrigemUsuario : parent::getStrIdOrigemUsuario();
  }

  public function getStrSiglaUsuario(){
     return (!$this->isBolHabilitada()) ? $this->strSiglaUsuario : parent::getStrSiglaUsuario();        
  }
  
  public function getStrNomeUsuario(){
     return (!$this->isBolHabilitada()) ? $this->strNomeUsuario : parent::getStrNomeUsuario();        
  }
  
  public function getNumIdUnidadeAtual(){
    return (!$this->isBolHabilitada()) ? $this->numIdUnidadeAtual : parent::getNumIdUnidadeAtual();
  }

  public function getStrIdOrigemUnidadeAtual(){
     return (!$this->isBolHabilitada()) ? $this->strIdOrigemUnidadeAtual : parent::getStrIdOrigemUnidadeAtual();
   }
  
  public function getStrSiglaUnidadeAtual(){
    return (!$this->isBolHabilitada()) ? $this->strSiglaUnidadeAtual : parent::getStrSiglaUnidadeAtual();
  }
  
  public function getNumIdOrgaoUnidadeAtual(){
    return (!$this->isBolHabilitada()) ? $this->numIdOrgaoUnidadeAtual : parent::getNumIdOrgaoUnidadeAtual();
  }

  public function getStrSiglaOrgaoUnidadeAtual(){
    return (!$this->isBolHabilitada()) ? $this->strSiglaOrgaoUnidadeAtual : parent::getStrSiglaOrgaoUnidadeAtual();
  }

  public function getStrDescricaoOrgaoUnidadeAtual(){
     return (!$this->isBolHabilitada()) ? $this->strDescricaoOrgaoUnidadeAtual : parent::getStrDescricaoOrgaoUnidadeAtual();
   }
  
  //fim - funчѕes para simular um login

  public function getStrLinkTrocarUnidade(){
    
  	$arrUrl = explode('/',ConfiguracaoSEI::getInstance()->getValor('SEI','URL'));
  	
  	if (ConfiguracaoSEI::getInstance()->getValor('SessaoSEI','https')){
  	  if ($arrUrl[0]=='http'){
  	    $arrUrl[0] = 'https';
  	  }
  	}
  	
  	$strUrl =  $arrUrl[0].'//'.$arrUrl[2].$_SERVER['SCRIPT_NAME'];
  	
    if ($this->verificarPermissao('procedimento_controlar')){
      $strUrl .= '?acao=procedimento_controlar';
    }else{
      $strUrl .= '?acao=principal';
    }
    
    return $strUrl;
  }
  
  public function simularLogin($strUsuario = null, $strSiglaUnidade = null, $numIdUsuario = null, $numIdUnidade = null){
    try{
      
      SessaoSEI::getInstance(false);
      
      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      
      if ($strUsuario==null && $numIdUsuario==null){
      	throw new InfraException('Usuсrio nуo informado para simulaчуo de login.');
      }
      
      if ($strUsuario!=null){
        $numIdUsuario = $objInfraParametro->getValor('ID_USUARIO_'.$strUsuario);
      }
      
      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->setBolExclusaoLogica(false);
      $objUsuarioDTO->retNumIdUsuario();
      $objUsuarioDTO->retStrIdOrigem();
      $objUsuarioDTO->retStrSigla();
      $objUsuarioDTO->retStrNome();
      $objUsuarioDTO->retNumIdOrgao();
      $objUsuarioDTO->retStrSiglaOrgao();
      $objUsuarioDTO->retStrDescricaoOrgao();
      $objUsuarioDTO->setNumIdUsuario($numIdUsuario);
      
      $objUsuarioRN = new UsuarioRN();
      $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);

      if ($objUsuarioDTO==null){
      	if ($strUsuario!=null){
      	  throw new InfraException('Usuсrio ID_USUARIO_'.$strUsuario.' ['.$numIdUsuario.'] nуo encontrado.');
      	}else{
      		throw new InfraException('Usuсrio ['.$numIdUsuario.'] nуo encontrado.');
      	}
      }
      
      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->setBolExclusaoLogica(false);
      $objOrgaoDTO->retNumIdOrgao();
      $objOrgaoDTO->retStrDescricao();
      $objOrgaoDTO->setStrSigla($this->getStrSiglaOrgaoSistema());
      
      $objOrgaoRN = new OrgaoRN();
      $objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO);
      
      if ($objOrgaoDTO==null){
      	throw new InfraException('гrgуo '.$this->getStrSiglaOrgaoSistema().' nуo encontrado.');
      }
      
      
      //Sistema
      $this->setNumIdOrgaoSistema($objOrgaoDTO->getNumIdOrgao());
      $this->setStrDescricaoOrgaoSistema($objOrgaoDTO->getStrDescricao());
      if ($objInfraParametro->isSetValor('SEI_ID_SISTEMA')){
        $this->setNumIdSistema($objInfraParametro->getValor('SEI_ID_SISTEMA'));
      }else{
        $this->setNumIdSistema($objInfraParametro->getValor('ID_SISTEMA'));
      }
      
      //Usuсrio
      $this->setNumIdOrgaoUsuario($objUsuarioDTO->getNumIdOrgao());
      $this->setStrSiglaOrgaoUsuario($objUsuarioDTO->getStrSiglaOrgao());
      $this->setStrDescricaoOrgaoUsuario($objUsuarioDTO->getStrDescricaoOrgao());
      $this->setNumIdUsuario($objUsuarioDTO->getNumIdUsuario());
      $this->setStrIdOrigemUsuario($objUsuarioDTO->getStrIdOrigem());
      $this->setStrSiglaUsuario($objUsuarioDTO->getStrSigla());
      $this->setStrNomeUsuario($objUsuarioDTO->getStrNome());

      if ($strSiglaUnidade==null && $numIdUnidade==null){
      	throw new InfraException('Unidade nуo informada para simulaчуo de login.');
      }
      
      if ($strSiglaUnidade!=null){
	      $numIdUnidade = $objInfraParametro->getValor('ID_UNIDADE_'.$strSiglaUnidade);
      }
	
      $objUnidadeDTO = new UnidadeDTO();
      $objUnidadeDTO->setBolExclusaoLogica(false);
      $objUnidadeDTO->retNumIdUnidade();
      $objUnidadeDTO->retStrIdOrigem();
      $objUnidadeDTO->retStrSigla();
      $objUnidadeDTO->retNumIdOrgao();
      $objUnidadeDTO->retStrSiglaOrgao();
      $objUnidadeDTO->retStrDescricaoOrgao();
      $objUnidadeDTO->setNumIdUnidade($numIdUnidade);
      
      $objUnidadeRN = new UnidadeRN();
      $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);
      
      if ($objUnidadeDTO==null){
      	if ($strSiglaUnidade!=null){
      	  throw new InfraException('Unidade ID_UNIDADE_'.$strSiglaUnidade.' ['.$numIdUnidade.'] nуo encontrada.');
      	}else{
      		throw new InfraException('Unidade ['.$numIdUnidade.'] nуo encontrada.');
      	}
      }

      $this->setNumIdUnidadeAtual($numIdUnidade);
      $this->setStrIdOrigemUnidadeAtual($objUnidadeDTO->getStrIdOrigem());
      $this->setStrSiglaUnidadeAtual($objUnidadeDTO->getStrSigla());
      $this->setNumIdOrgaoUnidadeAtual($objUnidadeDTO->getNumIdOrgao());
      $this->setStrSiglaOrgaoUnidadeAtual($objUnidadeDTO->getStrSiglaOrgao());
      $this->setStrDescricaoOrgaoUnidadeAtual($objUnidadeDTO->getStrDescricaoOrgao());

    }catch(Exception $e){
      throw new InfraException('Erro simulando login.',$e);
    }
  }
  
  public function getObjInfraAuditoria(){
    return AuditoriaSEI::getInstance();
  }

  public function getObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

   public function getObjInfraLog(){
     return LogSEI::getInstance();
   }

   public function setObjServicoDTO(ServicoDTO $objServicoDTO){
    $this->objServicoDTO = $objServicoDTO;
  }

  public function getObjServicoDTO(){
    return $this->objServicoDTO;
  }

  public function tratarLinkSemAssinatura($strLink){
    global $SEI_MODULOS;

    $strPosControlador = strpos($strLink,'controlador.php?');

    if ($strPosControlador!==false){
      $strLink = substr($strLink, $strPosControlador);
    }

    if (preg_match('/^controlador.php\?acao=procedimento_trabalhar&id_procedimento=[0-9]+$/', $strLink) === 1) {
      return true;
    }

    if (preg_match('/^controlador.php\?acao=procedimento_trabalhar&id_procedimento=[0-9]+&id_documento=[0-9]+$/', $strLink) === 1) {
      return true;
    }

    if (preg_match('/^controlador.php\?acao=procedimento_trabalhar&protocolo_pesquisa=[0-9A-Z]+$/', $strLink) === 1) {
      return true;
    }

    foreach($SEI_MODULOS as $objModulo){
      if ($objModulo->executar('tratarLinkSemAssinatura', $strLink) === true){
        return true;
      }
    }

    return false;
  }
}
?>