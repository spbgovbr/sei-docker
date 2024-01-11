<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 09/07/2019 - criado por MGA
 *
 */
 
 require_once dirname(__FILE__).'/SEI.php';
 
 class PaginaSEIFederacao extends InfraPaginaEsquema3
 {
   private static $instance = null;
   private static $strMenu = null;

   public static function getInstance()
   {
     if (self::$instance == null) {
       self::$instance = new PaginaSEIFederacao();
     }
     return self::$instance;
   }

   public function __construct()
   {
     SeiINT::validarHttps();
     parent::__construct();
     $this->setTipoPagina(parent::$TIPO_PAGINA_SIMPLES);
   }

   public function getStrNomeSistema()
   {
     return ConfiguracaoSEI::getInstance()->getValor('PaginaSEI', 'NomeSistema');
   }

   public function isBolProducao()
   {
     return ConfiguracaoSEI::getInstance()->getValor('SEI', 'Producao');
   }

   public function getNumVersao(){
     return md5(str_replace(' ','-',SEI_VERSAO . '-' . parent::getNumVersao()));
   }

   public function getArquivoCssEsquemaLocal(){
     if ($this->getStrEsquemaCores() == self::$ESQUEMA_PRETO){
       return 'infra-esquema-3.css';
     }
   }

   public function validarHashTabelas(){
     return true;
   }

   public function getStrLogoSistema(){
     return PaginaSEI::getInstance()->getStrLogoSistema();
   }

   public function getStrMenuSistema(){
     return null;
   }

   public function getArrStrAcoesSistema(){
     return null;
   }

   public function getObjInfraSessao() {
     //return SessaoSEIFederacao::getInstance();
     return null;
   }

   public function getObjInfraLog() {
     return LogSEI::getInstance();
     //return null;
   }

   public function abrirHead($strAtributos = '')
   {
     parent::abrirHead($strAtributos);
     SeiINT::montarHeaderFavicon('favicon');
   }

   public function montarLinkMenu(){
     return '';
   }

   public function getBolMontarIconeMenu(){
     return false;
   }

   public function montarBotaoVoltarExcecao(){
     return '';
   }

   public function montarBotaoFecharExcecao(){
     return '';
   }

   public function permitirXHTML() {
     return false;
   }

   public function gerarLinkLogin(){
     //return 'processo_acesso_externo_consulta.php?';
     die;
   }

   public function adicionarJQuery(){
     return true;
   }

 }
?>