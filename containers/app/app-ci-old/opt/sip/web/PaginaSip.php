<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 23/05/2006 - criado por MGA
 *
 */
 
 require_once dirname(__FILE__).'/Sip.php';
 
 class PaginaSip extends InfraPaginaEsquema3 {
	 
 	private static $instance = null;
 	
 	public static function getInstance() 
	{ 
	    if (self::$instance == null) { 
        self::$instance = new PaginaSip();
	    } 
	    return self::$instance; 
	} 
	
	public function __construct(){
    parent::configurarHttps(ConfiguracaoSip::getInstance()->getValor('SessaoSip','https'));
	  parent::__construct(); 
	}

	public function getStrNomeSistema(){ 
		return ConfiguracaoSip::getInstance()->getValor('PaginaSip','NomeSistema');
	}
	
	public function isBolProducao(){
		return ConfiguracaoSip::getInstance()->getValor('Sip','Producao');
	}

  public function isBolRequerHttps(){
     return $this->isBolProducao();
  }

  public function validarHashTabelas(){
    return true;
  }

	public function getStrLogoSistema(){
	  $strRet = '<img src="imagens/sip_logo.png" title="Sistema de Permissões - Versão '.SIP_VERSAO.'"/>';
		if (($strComplemento = ConfiguracaoSip::getInstance()->getValor('PaginaSip','NomeSistemaComplemento',false))!=null){
      $strRet .= '<span class="infraTituloLogoSistema">'.$strComplemento.'</span>';
	  }
	  return $strRet;
	}

	public function getStrMenuSistema(){
		return parent::montarMenuSessao('Principal');
	}

   public function getArrStrAcoesSistemaMovel(){
     $arrStrAcoes = array();
     $arrStrAcoes[] = $this->montarSelectUnidades(true);
     return $arrStrAcoes;
   }

	public function getObjInfraSessao(){
	  return SessaoSip::getInstance();
	}
	
	public function getArrStrAcoesSistema(){
		$arrStrAcoes = array();
		$arrStrAcoes[] = parent::montarSelectUnidades();
		$arrStrAcoes[] = parent::montarLinkConfiguracao();
		$arrStrAcoes[] = parent::montarLinkUsuario();
		$arrStrAcoes[] = parent::montarLinkSair(SessaoSip::getInstance()->assinarLink('controlador.php?acao=sair'));
		return $arrStrAcoes;
	}
	
	public function getObjInfraLog(){
	  return LogSip::getInstance();
	}
	
  public function getNumVersao(){
    return SIP_VERSAO.'-'.parent::getNumVersao();
  }
		
	public function obterTiposMensagemExibicao(){
	  return self::$TIPO_MSG_AVISO | self::$TIPO_MSG_ERRO;
	}

	 public function permitirXHTML(){
		 return false;
	 }

   public function adicionarJQuery(){
     return true;
   }


	/*
 	public function getDiretorioJavaScriptGlobal(){
	  return '/infra/infra_js';
	}
  
	public function getDiretorioEsquemas(){
	  return '/infra/infra_css/esquemas'; 
	}
	
	public function getDiretorioCssGlobal(){
		return '/infra/infra_css';
	}	
	*/

 }


 
?>