<?
  require_once dirname(__FILE__).'/../SEI.php';
  
  class PaginaConsultaProcessual extends InfraPaginaEsquema3 {

    private static $instance = null;
    private static $strMenu = null;

    public static function getInstance() {
      if (self::$instance == null) {
        self::$instance = new PaginaConsultaProcessual();
      }
      return self::$instance;
    }

    public function __construct(){
      SeiINT::validarHttps();
      parent::__construct();
    }

    public function getStrNomeSistema() {
      return ConfiguracaoSEI::getInstance()->getValor('PaginaSEI', 'NomeSistema');
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
          if (($arrMenuIntegracao = $seiModulo->executar('montarMenuConsultaProcessual')) != null) {
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
      return null;
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
      return SessaoConsultaProcessual::getInstance();
    }

    public function getObjInfraLog(){
      return LogSEI::getInstance();
    }

    public function montarLinkMenu(){
      return '';
    }

    public function getStrTextoBarraSuperior(){
      return $this->getObjInfraSessao()->getStrDescricaoOrgao();
    }

    public function getStrLogoSistema(){
      $strRet = '<img src="../svg/sei_barra.svg" title="Sistema Eletrônico de Informações"/>';
      if (($strComplemento = ConfiguracaoSEI::getInstance()->getValor('PaginaSEI', 'NomeSistemaComplemento',false))!=null){
        $strRet .= '<span class="infraTituloLogoSistema">'.$strComplemento.'</span>';
      }
      return $strRet;
    }

    public function abrirHead($strAtributos=''){
      parent::abrirHead($strAtributos);
      SeiINT::montarHeaderFavicon('../favicon');
    }

    public function getDiretorioCssLocal(){
      return '../css';
    }

    public function processarExcecao($e, $bolLimparParametrosLog = false){

      BancoSEI::setBolReplica(false);

      parent::processarExcecao($e, $bolLimparParametrosLog);
    }

  }
?>