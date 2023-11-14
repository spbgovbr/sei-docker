<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 26/10/2006 - criado por MGA
 *
 */
 
 require_once dirname(__FILE__).'/Sip.php';
 
 class PaginaLogin extends InfraPaginaEsquema3 {

	private static $instance = null;
  private $objSistemaDTO = null;

   public static function getInstance()
	{ 
	    if (self::$instance == null) { 
        self::$instance = new PaginaLogin();
	    } 
	    return self::$instance; 
	} 

	public function __construct(){
	  parent::configurarHttps(ConfiguracaoSip::getInstance()->getValor('SessaoSip','https'));
	  parent::__construct();
	}

   public function getStrNomeSistema(){
		return $this->objSistemaDTO != null ? $this->objSistemaDTO->getStrDescricao() : "Sistema de Permissões";
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

	public function getStrMenuSistema(){
		return null;
	}

   public function getBolMontarIconeMenu() {
     return false;
   }

	public function getArrStrAcoesSistema(){
		return null;
	}
	
	public function getObjInfraSessao(){
	  return null;
	}
	
	public function getObjInfraLog(){
	  return LogSip::getInstance();
	}

   public function setObjSistemaDTO($objSistemaDTO){
     $this->objSistemaDTO = $objSistemaDTO;
   }

   public function getObjSistemaDTO(){
     return $this->objSistemaDTO;
   }

  public function getStrTextoBarraSuperior(){
 	  return $this->objSistemaDTO != null ? $this->objSistemaDTO->getStrDescricaoOrgao() : ConfiguracaoSip::getInstance()->getValor('SessaoSip','SiglaOrgaoSistema');
  }

  public function getStrSiglaSistema(){
    return ($this->objSistemaDTO != null ? $this->objSistemaDTO->getStrSigla() : ConfiguracaoSip::getInstance()->getValor("SessaoSip","SiglaSistema"));
  }

  public function getStrSiglaOrgao(){
   return ($this->objSistemaDTO != null ? $this->objSistemaDTO->getStrSiglaOrgao() : ConfiguracaoSip::getInstance()->getValor("SessaoSip","SiglaOrgaoSistema"));
  }

  public function getStrTextoBarraSistema(){
 	  return  '<h6 class="text-white font-weight-bold my-0">
              '.($this->objSistemaDTO != null ? $this->objSistemaDTO->getStrDescricao() : ConfiguracaoSip::getInstance()->getValor("SessaoSip","SiglaSistema")).'
            </h6>';
  }

   public function getStrEsquemaPadrao(){
     $strEsquema = null;
     if ($this->objSistemaDTO!=null){
       $strEsquema = $this->objSistemaDTO->getStrEsquemaLogin();
     }
     return $strEsquema;
   }

   public function adicionarJQuery(){
     return true;
   }
 }
?>