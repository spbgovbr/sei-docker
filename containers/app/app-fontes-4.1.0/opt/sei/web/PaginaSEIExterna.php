<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 12/11/2007 - criado por MGA
 *
 */
 
 require_once dirname(__FILE__).'/SEI.php';
 
 class PaginaSEIExterna extends InfraPaginaEsquema3
 {
   private static $instance = null;
   private static $strMenu = null;

   public static function getInstance()
   {
     if (self::$instance == null) {
       self::$instance = new PaginaSEIExterna();
     }
     return self::$instance;
   }

   public function __construct()
   {
     SeiINT::validarHttps();
     parent::__construct();
   }

   public function getStrSiglaSistema()
   {
     return 'SEI';
   }

   public function getStrNomeSistema()
   {
     return 'Sistema Eletrônico de Informações';
   }

   public function isBolProducao()
   {
     return ConfiguracaoSEI::getInstance()->getValor('SEI', 'Producao');
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

   public function getStrLogoSistema(){
     if ($_GET['acao']!='usuario_externo_logar') {
       $strRet = '<img src="svg/sei_barra.svg?'.$this->getNumVersao().'" title="Sistema Eletrônico de Informações"/>';
       if (($strComplemento = ConfiguracaoSEI::getInstance()->getValor('PaginaSEI', 'NomeSistemaComplemento',false))!=null){
         $strRet .= '<span class="infraTituloLogoSistema">'.$strComplemento.'</span>';
       }
       return $strRet;
     }
     return null;
   }

   public function getStrTextoBarraSuperior(){
     return $this->getObjInfraSessao()->getStrDescricaoOrgaoUsuarioExterno();
   }


   public function getStrMenuSistema(){

     global $SEI_MODULOS;

     if (SessaoSEIExterna::getInstance()->isBolAcaoSemLogin()){
       return null;
     }

     if(self::$strMenu===null) {

       if ($this->getObjInfraSessao()->getNumIdUsuarioExterno() != null) {
         $arrMenu = array();
         $arrMenu[] = '-^controlador_externo.php?acao=usuario_externo_controle_acessos^^Controle de Acessos Externos^';
         $arrMenu[] = '-^controlador_externo.php?acao=usuario_externo_alterar_senha^^Alterar Senha^';

         //adicionando itens de menu externo definidos em ponto de extensao dos modulos
         //variavel global, declarada e inicializada na classe SEI.php
         //ver exemplo na pagina procedimento_controlar.php
         foreach ($SEI_MODULOS as $seiModulo) {
           if (($arrMenuIntegracao = $seiModulo->executar('montarMenuUsuarioExterno')) != null) {
             foreach ($arrMenuIntegracao as $strMenuIntegracao) {
               $arrMenu[] = $strMenuIntegracao;
             }
           }
         }
         self::$strMenu = parent::montarMenuArray($arrMenu);
       }
     }

     return self::$strMenu;
   }

   public function getArrStrAcoesSistema()
   {
     $arrStrAcoes = array();

     if (!SessaoSEIExterna::getInstance()->isBolAcaoSemLogin() && $this->getObjInfraSessao()->getNumIdUsuarioExterno() != null) {

       if ($this->getStrMenuSistema()!=null) {
         $arrStrAcoes[] = $this->montarLinkMenuTexto();
       }

       $arrStrAcoes[] = $this->montarLinkUsuario($this->getObjInfraSessao()->getStrSiglaUsuarioExterno(), null, $this->getObjInfraSessao()->getStrNomeUsuarioExterno());
       $arrStrAcoes[] = parent::montarLinkSair(SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=usuario_externo_sair'));
     }
     return $arrStrAcoes;
   }

   public function getArrStrAcoesSistemaMovel(){
     $arrStrAcoes = array();
     if ($this->getObjInfraSessao()->getNumIdUsuarioExterno() != null) {
       $arrStrAcoes[] = $this->montarLinkMenuTexto(true);
     }
     return $arrStrAcoes;
   }

   public function getBolMontarIconeMenu(){
     return false;
   }

   public function getObjInfraSessao()
   {
     return SessaoSEIExterna::getInstance();
   }

   public function getObjInfraLog()
   {
     return LogSEI::getInstance();
     //return null;
   }

   public function abrirHead($strAtributos = '')
   {
     parent::abrirHead($strAtributos);
     SeiINT::montarHeaderFavicon('favicon');
   }

   public function montarLinkMenu()
   {
     return '';
   }

   public function montarBotaoVoltarExcecao()
   {
     return '';
   }

   public function montarBotaoFecharExcecao()
   {
     return '';
   }

   public function permitirXHTML()
   {
     return false;
   }

   public function gerarLinkLogin()
   {
     return 'processo_acesso_externo_consulta.php?';
   }

   public function montarStyle()
   {
    parent::montarStyle();
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

   public function adicionarJQuery(){
     return true;
   }

   public function obterTipoMenu(){
     return self::$MENU_BOOTSTRAP;
   }

   public function obterTipoJanelaLupas(){
     return self::$INFRA_LUPA_MODAL;
   }
 }
?>