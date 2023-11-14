<?
  require_once dirname(__FILE__).'/../SEI.php';
  
  class PaginaPublicacoes extends InfraPaginaEsquema3 {
    
    private static $instance = null;
    private static $strMenu = null;
    
    public static function getInstance() {
      if (self::$instance == null) {
        self::$instance = new PaginaPublicacoes();
      }
      return self::$instance;
    }
    
    public function __construct(){
      SeiINT::validarHttps();
      parent::__construct();
    }

    public function getStrNomeSistema() {
      return 'Publicações Eletrônicas';
    }
    
    public function isBolProducao() {
      return ConfiguracaoSEI::getInstance()->getValor('SEI','Producao');
    }

    public function getNumVersao(){
      return md5(str_replace(' ','-',SEI_VERSAO . '-' . parent::getNumVersao()));
    }

    public function getNumVersaoCache(){
      return CACHE_VERSAO;
    }

    public function getArquivoCssEsquemaLocal(){
      if ($this->getStrEsquemaCores() == self::$ESQUEMA_PRETO){
        return 'infra-esquema-3.css';
      }
    }

    public function validarHashTabelas(){
      return true;
    }

    public function getBolExibirBotaoMenuMovel(){
      return false;
    }

    public function getStrMenuSistema() {

      if (self::$strMenu === null) {

        global $SEI_MODULOS;

        $arrMenu = array();

        foreach ($SEI_MODULOS as $seiModulo) {
          if (($arrMenuIntegracao = $seiModulo->executar('montarMenuPublicacoes')) != null) {
            foreach ($arrMenuIntegracao as $strMenuIntegracao) {
              $arrMenu[] = $strMenuIntegracao;
            }
          }
        }

        if (count($arrMenu)) {
          self::$strMenu = parent::montarMenuArray($arrMenu);
        }
      }

      return self::$strMenu;
    }
    
    public function getArrStrAcoesSistema() {
      $arrStrAcoes = null;
      if ($this->getStrMenuSistema()!=null) {
        $arrStrAcoes = array();
        $arrStrAcoes[] = $this->montarLinkMenuTexto();
      }
      return $arrStrAcoes;
    }

    public function getArrStrAcoesSistemaMovel(){
      if ($this->getStrMenuSistema()!=null) {
        $arrStrAcoes = array();
        $arrStrAcoes[] = $this->montarLinkMenuTexto(true);
      }
      return $arrStrAcoes;
    }

    public function getBolMontarIconeMenu(){
      return false;
    }

    public function permitirXHTML() {
			return false;
		}

    public function adicionarJQuery(){
      return true;
    }

    public function obterTipoMenu(){
      return self::$MENU_BOOTSTRAP;
    }

    public function getObjInfraSessao() {
      return SessaoPublicacoes::getInstance();
    }
    
    public function getObjInfraLog(){
	    return LogSEI::getInstance();
	  }
        
    public function montarLinkMenu(){
  	  return '';
  	}    
  	
  	public function getStrLogoSistema(){
      $strRet = '<img src="../svg/sei_barra.svg?'.$this->getNumVersao().'" title="Sistema Eletrônico de Informações"/>';
      if (($strComplemento = ConfiguracaoSEI::getInstance()->getValor('PaginaSEI', 'NomeSistemaComplemento',false))!=null){
        $strRet .= '<span class="infraTituloLogoSistema">'.$strComplemento.'</span>';
      }
      return $strRet;
	  }
  	  	
  	public function abrirHead($strAtributos=''){
  	  parent::abrirHead($strAtributos);
      SeiINT::montarHeaderFavicon('../favicon');
    }

  	public function getStrTextoBarraSuperior(){
  	  try{
  	
  	    $strDescricaoOrgao = '';
  	
  	    if (isset($_GET['id_orgao_publicacao'])){
  	
  	      $objOrgaoDTO = new OrgaoDTO();
  	      $objOrgaoDTO->retStrDescricao();
  	      $objOrgaoDTO->setNumIdOrgao($_GET['id_orgao_publicacao']);
  	       
  	      $objOrgaoRN = new OrgaoRN();
  	      $objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO);
  	       
  	      if ($objOrgaoDTO!=null){
  	        $strDescricaoOrgao = $objOrgaoDTO->getStrDescricao();
  	      }
  	    }
  	
  	    return $strDescricaoOrgao;
  	    	
  	  }catch(Exception $e){
  	    LogSEI::getInstance()->gravar('Erro montando página de publicação: '.$e->__toString()."\n".$e->getTraceAsString());
  	  }
  	  return null;
  	}  	
  	
	  public function getDiretorioCssLocal(){
		  return '../css';
	  }

    public function obterTipoJanelaLupas(){
      return self::$INFRA_LUPA_MODAL;
    }
  }
?>