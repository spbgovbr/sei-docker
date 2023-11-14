<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 17/04/2020 - criado por CJY
 *
 * @package infra_php
 */


abstract class InfraPaginaEsquema3 extends InfraPaginaEsquema2 {

  public static $ESQUEMA_AZUL_CELESTE = 'azul_celeste';
  public static $ESQUEMA_AZUL_CLARO = 'azul_claro';
  public static $ESQUEMA_CEREJA = 'cereja';
  public static $ESQUEMA_VERMELHO = 'vermelho';
  public static $ESQUEMA_VERDE_MAR = 'verde_mar';
  public static $ESQUEMA_VERDE_FLORESTA = 'verde_floresta';
  public static $ESQUEMA_PRETO = 'preto';
  public static $ESQUEMA_ROXO = 'roxo';



  private $bolAutoRedimensionar = true;
  protected $bolMontouBarraComandosSuperior = false;
  private $bolMontouBarraLocalizacao = false;

  public function __construct(){
    parent::__construct();
  }

  public function listarEsquemas(){
    $arr = array(
      self::$ESQUEMA_AZUL_CELESTE => 'Azul Celeste',
      self::$ESQUEMA_AZUL_CLARO => 'Azul Claro',
      self::$ESQUEMA_CEREJA => 'Cereja',
      self::$ESQUEMA_VERMELHO => 'Vermelho',
      self::$ESQUEMA_VERDE_FLORESTA => 'Verde Floresta',
      self::$ESQUEMA_VERDE_MAR => 'Verde Mar',
      self::$ESQUEMA_PRETO => 'Preto (Alto Contraste)',
      self::$ESQUEMA_ROXO => 'Roxo',
    );

    asort($arr);

    return $arr;
  }

  public function getStrLogoSistema(){return null;}
  public function getStrComplementoSistema(){return null;}
  public function getStrSiglaSistema(){return null;}
  public function getStrSiglaOrgao(){return null;}
  public function getStrTextoBarraSuperior(){return null;}
  public function getCorBarraSuperior(){return null;}
  public function getStrTextoBarraSistema(){return null;}
  public function getCorBarraSistema(){return null;}

  protected function getNumVersaoCalendario() {
    return 2;
  }

  public function getNumIdOrgaoBarraTribunal(){
    if ($this->getObjInfraSessao()!=null){
      return $this->getObjInfraSessao()->getNumIdOrgaoSistema();
    }
    return 1;
  }

  public function setBolAutoRedimensionar($bolAutoRedimensionar){
    $this->bolAutoRedimensionar = $bolAutoRedimensionar;
  }

  public function getBolAutoRedimensionar(){
    return $this->bolAutoRedimensionar;
  }

  public function getBolExibirBotaoMenuMovel(){
    return true;
  }

  public function getArrStrAcoesBarraSistema(){}

  public function obterTipoMenu(){
    return self::$MENU_BOOTSTRAP;
  }

  public function adicionarJQuery() {
    return true;
  }

  public function getDiretoriosIconesMenu(){
    return array('menu');
  }

  public function getArquivoCssGlobal(){
    return 'infra-global-esquema-3.css';
  }

  public function getArquivoCssEsquemaGlobal(){
    return 'infra-esquema-3.css';
  }

  public function getArquivoCssEsquemaLocal(){
    return null;
  }

  public function getArquivoCssBootstrap(){
    return 'bootstrap/bootstrap.min.css';
  }

  public function getArquivoCssMenuBootstrap(){
    return 'bootstrap/menu-bootstrap.css';
  }

  public function getArquivoJavaScriptPagina() {
    return 'InfraPaginaEsquema3.js';
  }

  public function montarStyle(){
    $numVersao = $this->getNumVersao();
    $strDiretorioCssGlobal = $this->getDiretorioCssGlobal();
    $strDiretorioJavascriptGlobal = $this->getDiretorioJavaScriptGlobal();
    $numTipoBrowser = $this->getNumTipoBrowser();

    echo '<link href="'.$strDiretorioCssGlobal.'/infra-tooltip.css?'.$numVersao.'" rel="stylesheet" type="text/css" media="all" />
      <link href="'.$strDiretorioCssGlobal.'/infra-barra-progresso.css?'.$numVersao.'" rel="stylesheet" type="text/css" media="all" />'."\n";
    if ($numTipoBrowser==self::$TIPO_BROWSER_IE56 || $numTipoBrowser==self::$TIPO_BROWSER_IE7 || $numTipoBrowser==self::$TIPO_BROWSER_IE8){
      echo '<link href="'.$strDiretorioCssGlobal.'/infra-impressao-global-ie.css?'.$numVersao.'" rel="stylesheet" type="text/css" media="print" />'."\n";
    }else{
      echo '<link href="'.$strDiretorioCssGlobal.'/infra-impressao-global.css?'.$numVersao.'" rel="stylesheet" type="text/css" media="print" />'."\n";
    }
    if ($this->isBolNavegadorSafariIpad()){
      echo '<link href="'.$strDiretorioCssGlobal.'/infra-safari.css?'.$numVersao.'" rel="stylesheet" type="text/css" media="all" />'."\n";
    }
    echo '<link href="'.$strDiretorioCssGlobal.'/infra-ajax.css?'.$numVersao.'" rel="stylesheet" type="text/css" media="all" />
          <link href="'.$strDiretorioJavascriptGlobal.'/calendario/v'.$this->getNumVersaoCalendario().'/infra-calendario.css?'.$numVersao.'" rel="stylesheet" type="text/css" media="all" />
          <link href="'.$strDiretorioJavascriptGlobal.'/arvore/infra-arvore.css?'.$numVersao.'" rel="stylesheet" type="text/css" media="all" />
          <link href="'.$strDiretorioJavascriptGlobal.'/mapa/infra-mapa.css?'.$numVersao.'" rel="stylesheet" type="text/css" media="all" />
          ';

    if ($numTipoBrowser==self::$TIPO_BROWSER_IE56){
      echo '<link href="'.$strDiretorioCssGlobal.'/infra-ie56.css?'.$numVersao.'" rel="stylesheet" type="text/css" media="all" />'."\n";
    }else if ($numTipoBrowser==self::$TIPO_BROWSER_IE7){
      echo '<link href="'.$strDiretorioCssGlobal.'/infra-ie7.css?'.$numVersao.'" rel="stylesheet" type="text/css" media="all" />'."\n";
    }

    echo '<link href="'.$strDiretorioJavascriptGlobal.'/jquery/jquery-ui-1.11.1/jquery-ui.min.css?1.11.1" rel="stylesheet" type="text/css" media="all" />
      <link href="'.$strDiretorioJavascriptGlobal.'/jquery/jquery-ui-1.11.1/jquery-ui.structure.min.css?1.11.1" rel="stylesheet" type="text/css" media="all" />
      <link href="'.$strDiretorioJavascriptGlobal.'/jquery/jquery-ui-1.11.1/jquery-ui.theme.min.css?1.11.1" rel="stylesheet" type="text/css" media="all" />
      <link href="'.$strDiretorioJavascriptGlobal.'/multiple-select/multiple-select.min.css?' . $numVersao.'" rel="stylesheet" type="text/css" media="all" />
      <link href="'.$strDiretorioJavascriptGlobal.'/modal/jquery.modalLink-1.0.0.css?' . $numVersao.'" rel="stylesheet" type="text/css" media="all" />
      <link href="'.$strDiretorioCssGlobal.'/'.$this->getArquivoCssBootstrap().'?'.$numVersao.'" rel="stylesheet" type="text/css" media="all" />
      <link href="'.$strDiretorioCssGlobal.'/'.$this->getArquivoCssMenuBootstrap().'?'.$numVersao.'" rel="stylesheet" type="text/css" media="all" />
      <link href="'.$strDiretorioCssGlobal.'/'.$this->getArquivoCssGlobal().'?'.$numVersao.'" rel="stylesheet" type="text/css" media="all" />
      <link href="'.$this->getDiretorioEsquemasGlobal().'/'.$this->getStrEsquemaCores().'/'.$this->getArquivoCssEsquemaGlobal().'?'.$numVersao.'" rel="stylesheet" type="text/css" media="all" />      
      <link href="'.$this->getDiretorioCssLocal().'/infra-local-esquema-3.css?'.$numVersao.'" rel="stylesheet" type="text/css" media="all" />
    ';

    if ($this->getArquivoCssEsquemaLocal()!=null){
      echo '<link href="'.$this->getDiretorioEsquemasLocal().'/'.$this->getStrEsquemaCores().'/'.$this->getArquivoCssEsquemaLocal().'?'.$numVersao.'" rel="stylesheet" type="text/css" media="all" />
      ';
    }

    echo "<style>";

    if($this->getCorBarraSistema() != null){

      if (is_array($this->getCorBarraSistema()) && count($this->getCorBarraSistema()) == 2){

        $arrGradiente = $this->getCorBarraSistema();
        $cor1 = $arrGradiente[0];
        $cor2 = $arrGradiente[1];

        echo '
        .infraCorBarraSistema{
          background-color: unset;
          background-image: linear-gradient(to right ,'.$cor1.' , '.$cor2.' ) !important;
        }
        ';
      }else{
        echo '
        .infraCorBarraSistema{
          background-image: unset;
          background-color: '.$this->getCorBarraSistema().'  !important;
        }
      ';
      }
    }

    if($this->getCorBarraSuperior() != null){
      if (is_array($this->getCorBarraSuperior()) && count($this->getCorBarraSuperior()) == 2) {
        $arrGradiente = $this->getCorBarraSuperior();
        $cor1 = $arrGradiente[0];
        $cor2 = $arrGradiente[1];

        echo '
        .infraCorBarraSuperior{
          background-color: unset;
          background-image: linear-gradient(to right ,'.$cor1.' , '.$cor2.' )  !important;
        }
        ';
      }else {
        echo '
        .infraCorBarraSuperior{
          background-image: unset;
          background-color: '.$this->getCorBarraSuperior().' !important;
        }
      ';
      }
    }

    echo "</style>";


  }

  public function montarJavaScript()
  {
    $strDiretorioJavascriptGlobal = $this->getDiretorioJavaScriptGlobal();
    $numVersao = $this->getNumVersao();
    parent::montarJavaScript();
    echo '
      <script type="text/javascript" charset="utf-8" src="' . $strDiretorioJavascriptGlobal . '/bootstrap/bootstrap.min.js?' . $numVersao . '"></script>
      <script type="text/javascript" charset="utf-8" src="' . $strDiretorioJavascriptGlobal . '/bootstrap/infra-menu-bootstrap.js?' . $numVersao . '"></script>
      <script type="text/javascript" charset="utf-8" src="' . $strDiretorioJavascriptGlobal . '/touch/jquery.ui.touch-punch.min.js?' . $numVersao . '"></script>
      <script type="text/javascript" charset="utf-8" src="' . $strDiretorioJavascriptGlobal . '/hotkeys/jquery.hotkeys.js?' . $numVersao . '"></script>

    
      ';
  }

  public function montarLinkSair($strLink = null, $strIcone = null) {
    if ($strLink!=null) {
      $strLinkMenu = $strLink;
    } else {
      $strLinkMenu = $this->getObjInfraSessao()->getStrPaginaLogin();
    }
    $str = '
    <div class="nav-item pr-2 media infraAcaoBarraSistema">
    <a class="align-self-center d-none d-md-block" id="lnkSairSistema" href="'.$strLinkMenu.'" title="Sair do Sistema"  tabindex="' . $this->getProxTabBarraSistema() . '">
      <img src="'.$this->getDiretorioSvgGlobal() . '/sair.svg?'.VERSAO_INFRA.'" height="24" width="24" class="infraImg" />
    </a>
    <span class=" nav-link d-flex d-md-none">
      <img src="'.$this->getDiretorioSvgGlobal() . '/sair.svg?'.VERSAO_INFRA.'" height="24" width="24"  class="infraImg" />
       <a id="lnkSairSistema" class="align-self-center text-white pl-1" href="'.$strLinkMenu.'" title="Sair do Sistema" >
        Sair
      </a>
    </span>
    </div>
 
    ';
    return $str;
  }

  public function montarLinkAjuda($strLink, $strIcone = null) {
    $str = '';
    if ($strLink!=null) {

      if ($strIcone==null) {
        $strIcone = $this->getIconeAjuda();
      }


      $str = '<a class="align-self-center" id="lnkAjudaSistema" href="' . $strLink . '" target="_blank" title="Ajuda"  tabindex="' . $this->getProxTabBarraSistema() . '"><img src="' . $strIcone . '" title="Ajuda" alt="Ajuda" class="infraImg" /></a>';
    }
    return $str;
  }

  public function montarLinkUsuario($strSigla = null, $strOrgao = null, $strNome = null, $strIcone = null) {
    $strLinkAcessos = '';
    if ($strSigla===null && $strOrgao===null && $strNome===null) {
      if ($this->getObjInfraSessao()!==null) {
        if ($this->getObjInfraSessao()->getStrSiglaUsuario()!=null) {
          $strSigla = $this->getObjInfraSessao()->getStrSiglaUsuario();
        }
        if ($this->getObjInfraSessao()->getStrSiglaOrgaoUsuario()!=null) {
          $strOrgao = $this->getObjInfraSessao()->getStrSiglaOrgaoUsuario();
        }
        if ($this->getObjInfraSessao()->getStrNomeUsuario()!=null) {
          $strNome = $this->getObjInfraSessao()->getStrNomeUsuario();
        }
        if ($this->getObjInfraSessao()->verificarPermissao('infra_acesso_usuario_listar')){
          $strLinkAcessos = 'href="'.$this->getObjInfraSessao()->assinarLink('controlador.php?acao=infra_acesso_usuario_listar').'"';
        }
      }
    }
    $strDados = '';
    $strSeparador = '';
    if ($strNome!==null) {
      $strDados .= $strNome;
    }
    if ($strSigla!==null || $strOrgao!==null) {
      $strDados .= ' (';
      if ($strSigla !== null) {
        $strDados .= $strSigla;
        $strSeparador = '/';
      }
      if ($strOrgao !== null) {
        $strDados .= $strSeparador.$strOrgao;
      }
      $strDados .= ')';
    }
    if ($strDados=='') {
      return '';
    }

    $strDados = InfraPagina::tratarHTML($strDados);

    return '
      <div class="nav-item d-md-flex infraAcaoBarraSistema">  
      <a class="align-self-center  d-none d-md-block" id="lnkUsuarioSistema" '.$strLinkAcessos.' title="' . $strDados . '" tabindex="' . $this->getProxTabBarraSistema() . '">
        <img src="'.$this->getDiretorioSvgGlobal() . '/usuario_topo.svg?'.VERSAO_INFRA.'" height="24" width="24" class="infraImg"  title="'.$strDados.'"  />
      </a>
      <span title="'.$strDados.'"  class=" nav-link   d-flex d-md-none" >
         <img src="'.$this->getDiretorioSvgGlobal() . '/usuario_topo.svg?'.VERSAO_INFRA.'" height="24" width="24" class="infraImg"  title="'.$strDados.'"  />
         <a class="align-self-center text-white pl-1" id="lnkUsuarioSistema" '.$strLinkAcessos.' title="' . $strDados . '" >
          '.$strDados.'
         </a>
      </span>
      </div>
      ';
  }

  public function montarLinkConfiguracao($strLink=null,$strIcone=null){


    if ($strLink==null){
      $strLink = 'controlador.php?acao=infra_configurar';
    }

    $objInfraSessao = $this->getObjInfraSessao();
    if ($objInfraSessao!=null){
      $arrParametrosRepasseLink = $objInfraSessao->getArrParametrosRepasseLink();
      $objInfraSessao->setArrParametrosRepasseLink(null);

      $strLink = $this->getObjInfraSessao()->assinarLink($strLink);

      $objInfraSessao->setArrParametrosRepasseLink($arrParametrosRepasseLink);
    }

    if ($strIcone==null){
      $strIcone = $this->getDiretorioSvgGlobal() . '/configuracao.svg?'.VERSAO_INFRA;
    }

    return '
    <div class="nav-item d-md-flex infraAcaoBarraSistema">
      <a class="align-self-center  d-none d-md-block" id="lnkConfiguracaoSistema" href="'.$strLink.'" title="Configurações do Sistema"  tabindex="' . $this->getProxTabBarraSistema() . '">
        <img src="'.$strIcone.'" height="24" width="24" class="infraImg" title="Configurações do Sistema"  />
      </a>
      <span class=" nav-link   d-flex d-md-none" >
         <img src="'.$strIcone.'" height="24" width="24" class="infraImg" title="Configurações do Sistema" />
         <a class="align-self-center text-white pl-1" id="lnkConfiguracaoSistema" href="'.$strLink.'" title="Configurações do Sistema" >
          Configurações
         </a>
      </span>
     </div>
      ';
  }

  public function montarBarraLocalizacao($strLocalizacao){
    if(!InfraString::isBolVazia($strLocalizacao)) {
      $this->bolMontouBarraLocalizacao = true;
      echo '<div id="divInfraBarraLocalizacao" class="infraBarraLocalizacao">' . $strLocalizacao . '</div>' . "\n";
    }
  }

  public function montarBarraComandosSuperior($arrComandos) {
    echo '<input type="hidden" id="hdnInfraTipoPagina" name="hdnInfraTipoPagina" value="'.$this->getTipoPagina().'" />'."\n".
      '<div id="divInfraBarraComandosSuperior" class="infraBarraComandos">'."\n";

    if (is_array($arrComandos)){
      foreach($arrComandos as $comando){
        if (trim($comando)!=''){
          if (strpos($comando,'tabindex')===false){
            $comando = str_replace(' type=',' tabindex="'.$this->getProxTabBarraComandosSuperior().'" type=',$comando);
          }
          echo $comando.'&nbsp;'."\n";
        }
      }
    }
    echo '</div>'."\n";
    $this->bolMontouBarraComandosSuperior = true;
  }

  public function montarBarraComandosInferior($arrComandos, $bolForcarMontagem = false) {
    if (!$this->bolMontouTabela || $this->numMaxRegistrosTab > 15 || $bolForcarMontagem){
      echo '<div id="divInfraBarraComandosInferior" class="infraBarraComandos">'."\n".'<br />';
      if (is_array($arrComandos)){
        foreach($arrComandos as $comando){
          if (trim($comando)!=''){
            if (strpos($comando,'tabindex')===false){
              $comando = str_replace(' type=',' tabindex="'.$this->getProxTabBarraComandosInferior().'" type=',$comando);
            }
            echo $comando.'&nbsp;'."\n";
          }
        }
      }
      echo '</div>'."\n";
    }else{
      echo '<br /><br />';
    }
  }

  public function montarMeta(){
    parent::montarMeta();
    echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> ' . "\n";
  }


  public function montarBotaoDetalhesExcecao(){
    return '<input type="button" id="btnInfraDetalhesExcecao" name="btnInfraDetalhesExcecao" value="Exibir Detalhes" onclick="infraDetalhesExcecao();" class="infraButton" />';
  }

  public function montarBotaoVoltarExcecao(){
    return '<input id="btnInfraVoltarExcecao" name="btnInfraVoltarExcecao" type="button" value="Voltar" onclick="javascript:history.go(-1);" class="infraButton" />';
  }

  public function montarBotaoFecharExcecao(){
    return '<input id="btnInfraFecharExcecao" name="btnInfraFecharExcecao" type="button" value="Fechar" onclick="window.close();" class="infraButton" />';
  }


  public function montarSelectUnidades($bolMovel = false){
    $ret = '';
    $strSigla = '';
    $strDescricao = '';
    $bolMostrarTroca = false;

    $objInfraSessao = $this->getObjInfraSessao();
    if ($objInfraSessao!=null){
      $arrParametrosRepasseLink = $objInfraSessao->getArrParametrosRepasseLink();
      $objInfraSessao->setArrParametrosRepasseLink(null);

      $strLink = $this->getObjInfraSessao()->assinarLink('controlador.php?acao=infra_trocar_unidade');

      $objInfraSessao->setArrParametrosRepasseLink($arrParametrosRepasseLink);

      $strSigla = $objInfraSessao->getStrSiglaUnidadeAtual();
      $strDescricao = $objInfraSessao->getStrDescricaoUnidadeAtual();

      //if (InfraArray::contar($objInfraSessao->getArrUnidades()) > 1){
      $bolMostrarTroca = false;
      //}

      $ret .= ' 
                  <div class="input-group align-self-center ">
                  <a id="lnkInfraUnidade" onclick="window.location.href=\''.$strLink.'\';" class="form-control infraAcaoBarraConjugada" title="'.InfraPagina::tratarHTML($strDescricao).'" tabindex="' . $this->getProxTabBarraSistema() . '">'.InfraPagina::tratarHTML($strSigla).'</a>
                  ';

      if ($bolMostrarTroca) {
        $ret .= '<span class="input-group-btn">
                <span id="spnInfraUnidade" class="btn infraAcaoBarraConjugada">
                <img src="'.$this->getDiretorioSvgGlobal().'/trocar_unidade.svg?'.VERSAO_INFRA.'" width="20" height="20" onclick="window.location.href=\''.$strLink.'\';" title="Trocar Unidade" alt="Trocar Unidade" tabindex="' . $this->getProxTabBarraSistema() . '" class="infraImg" />
                </span>
                </span>';
      }

      $ret .=         '
             </div >           
          ';


      return '<div class=" nav-item px-1 '.($bolMovel ? 'd-flex' : 'd-none').' d-md-flex  py-md-0 py-2">'.$ret.'</div>';
    }else{
      return "";
    }


  }

  public function montarMenuArray($arrMenu, $n = '') {
    $strMenu = "";

    $strMenu .= '  <div id="divInfraSidebarMenu" class="infraSidebarMenu flex-grow-1" >';

    $numLimite = is_array($arrMenu) ? count($arrMenu) : 0;
    if($numLimite > 10){
      $strMenu .= ' <div id="divInfraPesquisarMenu">
              <input type="text" id="txtInfraPesquisarMenu" class="infraPesquisarMenu infraText" onkeyup="infraFiltrarMenuBootstrap()" placeholder="Pesquisar no Menu (Alt + m)" title="Pesquisar no Menu (Alt + m)" />
            </div>';
    }else{
      $strMenu .= '<br>';
    }

    $objInfraSessao = $this->getObjInfraSessao();
    $arrParametrosRepasseLink = $objInfraSessao->getArrParametrosRepasseLink();
    $objInfraSessao->setArrParametrosRepasseLink(null);
    if ($numLimite>0) {
      $strMenu .= '<ul   id="infraMenu">' . "\n";
    }

    $strProximaLinha = '';
    $tab = 0;
    $bolIcone = false;
    $strIconeVazio = '<img src="'.$this->getDiretorioSvgGlobal().'/vazio.svg" width="24" height="24" />';

    for ($i = 0; $i<$numLimite; $i ++) {

      if ($i==0) {
        $strLinhaAtual = explode('^', $arrMenu[$i]);
      } else {
        $strLinhaAtual = $strProximaLinha;
      }
      //MONTA O LINK DE ACORDO COM O INÍCIO DA URL DO MENU
      //Por causa do MUMPS que dava conflito com os ^ da montagem do menu
      $strLinhaAtual[1] = str_replace('*', '^', $strLinhaAtual[1]);
      //Por causa do MUMPS que tem que abrir os programas numa div especifica
      $strTarget = '';
      if (isset($strLinhaAtual[4])) {
        $strTarget = ' target="' . $strLinhaAtual[4] . '"';
      }
      if ($strTarget==' target=""') {
        $strTarget = '';
      }

      $nivel = strlen($strLinhaAtual[0]);

      if (($i + 1)<$numLimite) {
        $strProximaLinha = explode('^', $arrMenu[$i + 1]);
        $dif = $nivel - strlen($strProximaLinha[0]);
      } else {
        $dif = $nivel - 1;
      }
      $strMenu .= '<li>';
      $strMenu .= '<a';
      $padding = ' style="padding-left:'.($tab == 0 ?  5 : $tab * ($nivel <= 2 ? 35 : 25)).'px" ';
      $strMenu .= $padding;
      if($dif > 0){
        $tab += -$dif;
      }else if($dif == -1){
        $tab -= $dif;
      }
      if (substr($strLinhaAtual[1], 0, 4)=='java') {
        $strMenu .= '  href="' . $strLinhaAtual[1] . '"';
      } else if (/*(substr($strLinhaAtual[1],0,4) == 'http') || */
        substr($strLinhaAtual[1], 0, 4)=='mail') {
        $strMenu .= ' href="' . $strLinhaAtual[1] . '"' . $strTarget;
      } else {
        $arrLink = explode("=", $strLinhaAtual[1]);
        if(count($arrLink) >  1){
          $link =$arrLink[1];
        }else{
          $link= '';
        }
        $arrLink = explode("&", $link);
        if(count($arrLink) >  1){
          $link =$arrLink[0];
        }
        if ($dif>=0 && $strLinhaAtual[1]!='#') {
          $strMenu .= ' link= "'.$link.'" href="' . $objInfraSessao->assinarLink($strLinhaAtual[1]) . '"';
          $strMenu .= $strTarget;
        } else {
          $strMenu .= ' link= "'.$link.'"  ';
        }
      }
      if (trim($strLinhaAtual[2])!='') {
        $strMenu .= ' title="' . str_replace('&amp;nbsp;', '&nbsp;', self::tratarHTML($strLinhaAtual[2])) . '"';
      }
      if($dif<0){
        $strMenu .= ' data-toggle="collapse" class="infraAnchorMenu" href="#submenu'.$i.'" role="button" aria-expanded="false" aria-controls="collapseMenu" ';
      }
      $strMenu .= '>';

      $strIcone = '';
      if (isset($strLinhaAtual[5]) && $strLinhaAtual[5]!='') {
        $strIcone = $strIconeVazio;
        foreach($this->getDiretoriosIconesMenu() as $strDir){
          if (file_exists($strDir.'/'.$strLinhaAtual[5])){
            $strIcone = '<img src="'.$strDir.'/'.$strLinhaAtual[5].'" width="24" height="24" />';
            $bolIcone = true;
            break;
          }
        }
      }else{
        if ($nivel == 1) {
          $strIcone = $strIconeVazio;
        }
      }

      $strMenu .= $strIcone;

      $strMenu .='<span>';
      $strMenu .= str_replace('&amp;nbsp;', '&nbsp;', self::tratarHTML($strLinhaAtual[3]));
      //Mesmo nivel fecha li
      if ($dif===0) {
        $strMenu .= '</span></a></li>' . "\n";
        //Nivel mais interno - abre ul
      } else if ($dif<0) {
        $strMenu .= '</span><img src="'.$this->getDiretorioImagensGlobal().'/menu_seta.png" class="infraImgSetaMenu" style="width:12px;" /></a>';
        $strMenu .= "\n" . '<ul class="collapse" id="submenu'.$i.'">' . "\n";
        //Nivel mais externo - fecha li-ul
      } else {
        $strMenu .= '</span></a>';
        while ($dif>0) {
          $strMenu .= '</li>' . "\n";
          $strMenu .= '</ul>' . "\n";
          $dif --;
        }
        $strMenu .= '</li>' . "\n";

      }
    }
    if ($numLimite>0) {
      $strMenu .= '</ul>' . "\n";
    }
    $objInfraSessao->setArrParametrosRepasseLink($arrParametrosRepasseLink);
    $strMenu .= '</div>';

    if(isset($_GET['acao'])){
      $strMenu .= "\n".'<script type="text/javascript">infraSetarMenuBootstrap("'.$_GET['acao'].'")</script>';
    }

    if (!$bolIcone){
      $strMenu = str_replace('<img src="'.$this->getDiretorioSvgGlobal().'/vazio.svg" width="24" height="24" />','', $strMenu);
    }

    $strMenu .= '<!--LOGO-->';
    $strMenu .= '<!--MODULOS-->';

    return $strMenu;
  }

  public function montarMensagens(){

    if ($this->getBolExibirMensagens()) {
      if (!$this->bolMontouBarraComandosSuperior && !$this->bolMontouBarraLocalizacao) {
        parent::montarMensagens();
        return;
      }

      $strMensagens = '';
      if (isset($_GET['msg']) && !InfraString::isBolVazia($_GET['msg'])) {
        if ($_GET['msg'] != '') {
          $strMensagens .= '<div class="alert alert-primary" role="alert">'.str_replace(array('\n', "\n"), '<br>', self::tratarHTML($_GET['msg'])).'</div>'."\n";
        }
      }

      if ($this->getObjInfraSessao() == null) {
        if (!InfraString::isBolVazia($this->getStrMensagens())) {
          $strMensagens .= '<div class="alert alert-primary" role="alert">' . str_replace(array('\n', "\n"), '<br>', self::tratarHTML($this->getStrMensagens())) . '</div>' . "\n";
        }
      } else {

        $arrMensagens = $this->recuperarSessao('infra_global', InfraPagina::$POS_SES_MSG);

        if (is_array($arrMensagens)) {
          foreach ($arrMensagens as $arrMensagem) {
            if ($this->obterTiposMensagemExibicao() & $arrMensagem[InfraPagina::$POS_SES_MSG_TIPO]) {

              $strClassAlert = 'alert-primary';
              if ($arrMensagem[InfraPagina::$POS_SES_MSG_TIPO] == InfraPagina::$TIPO_MSG_INFORMACAO) {
                $strClassAlert = 'alert-info';
              } else if ($arrMensagem[InfraPagina::$POS_SES_MSG_TIPO] == InfraPagina::$TIPO_MSG_AVISO) {
                $strClassAlert = 'alert-warning';
              } else if ($arrMensagem[InfraPagina::$POS_SES_MSG_TIPO] == InfraPagina::$TIPO_MSG_ERRO) {
                $strClassAlert = 'alert-danger';
              }

              $strMensagens .= '<div class="alert '.$strClassAlert.' alert-dismissible  show" role="alert">'."\n".
                '<button type="button" class="close media h-100"  data-dismiss="alert" aria-label="Fechar">'."\n".
                '<span aria-hidden="true" class="align-self-center"><b>X</b></span>'."\n".
                '</button>'."\n".
                str_replace(array('\n', "\n"), '<br>', self::tratarHTML($arrMensagem[InfraPagina::$POS_SES_MSG_CONTEUDO]))."\n".
                '</div>'."\n";
            }
          }
        }
      }

      if ($strMensagens != '') {

        echo "\n".'<div id="divInfraMensagens" style="display: none;">'."\n".$strMensagens."\n".'</div>';

        $this->abrirJavaScript();
        //    echo "alert('asd ".$this->bolMontouBarraComandosSuperior."');";
        //   echo "alert('asds ".$this->bolMontouBarraLocalizacao."');";

        //   $bolMontouBarraComandosSuperior = false;
        //private $bolMontouBarraLocalizacao = false;
        if($this->bolMontouBarraComandosSuperior){
          echo '
              div = document.getElementById(\'divInfraBarraComandosSuperior\');
              ';
        }else if($this->bolMontouBarraLocalizacao){
          echo '
              div = document.getElementById(\'divInfraBarraLocalizacao\');
              ';
        }
        echo '
              div.parentNode.insertBefore(document.getElementById(\'divInfraMensagens\'), div.nextSibling);
              divInfraMensagens = document.getElementById(\'divInfraMensagens\');
              divInfraMensagens.style.display=\'block\';
              
              setTimeout(function(){
                  $(\'#divInfraMensagens\').addClass(\'fade-in\');
              },200)
              
              
          ';
        $this->fecharJavaScript();
      }
    }

    if ($this->getObjInfraSessao() != null) {
      $this->adicionarSessao('infra_global', self::$POS_SES_MSG, '');
    }
  }

  public function montarLinkMenuTexto($bolMovel = false){
    return '<div class="nav-item '.($bolMovel ? 'd-flex' : 'd-none').' d-md-flex mt-1  py-md-0 py-2"><a id="lnkInfraMenuSistema" onclick="infraClicarMenuBootstrap()" href="#" target="_self"  title="Exibir/Ocultar Menu do Sistema" tabindex="'.$this->getProxTabBarraSistema().'" class="nav-link align-self-center text-white font-weight-bold">Menu</a></div >';
  }

  public function getArrStrAcoesSistemaMovel(){
    return null;
  }


  public function abrirBody($strLocalizacao='',$strAtributos=''){

    //Esconder combos mostrar menu somente IE
    if ($this->getNumTipoBrowser()==self::$TIPO_BROWSER_IE56){
      $strAtributos = $this->complementarAtributo($strAtributos,'onload','infraProcessarMouseOver();');
    }

    echo '<body '.$strAtributos.'  >'."\n";
    echo '<button onclick="infraMoverParaTopo()" id="btnInfraTopo" exibido="false" class="infraButton infraCorBarraSistema" ><img src="'.$this->getDiretorioSvgGlobal().'/topo.svg?'.VERSAO_INFRA.'" title="Voltar ao Topo" alt="Voltar ao Topo"></button>'. "\n";
    echo '<div id="divInfraAreaGlobal" class="vh-100 vw-100 d-flex flex-column m-0 border-0" >' . "\n";

    if ($this->getTipoPagina()!=self::$TIPO_PAGINA_SIMPLES) {
      $strBotaoMenuMovel =  "";
      if($this->getTipoPagina()!=self::$TIPO_PAGINA_SEM_MENU ){
        $strBotaoMenuMovel = '
        <a class="navbar-toggler px-1 border-0 flex-grow-0 mr-3 align-self-center media" data-toggle="collapse" data-target="#divInfraBarraSistemaPadrao" aria-controls="divInfraBarraSistemaPadrao" aria-expanded="false" aria-label="Toggle navigation" title="Exibir/Ocultar Ações">
              <img class=" align-self-center infraImg"  width="24" height="24" src="'.$this->getDiretorioSvgGlobal() . '/menu_pontos_topo.svg?'.VERSAO_INFRA.'" />
            </a>
        ';
      }


      $strTextoBarraSuperior = null;
      if ($this->getStrTextoBarraSuperior()!=null){
        $strTextoBarraSuperior = self::tratarHTML(InfraString::transformarCaixaAlta($this->getStrTextoBarraSuperior()));
      }else{
        if ($this->getObjInfraSessao()!=null){
          $strTextoBarraSuperior = self::tratarHTML(InfraString::transformarCaixaAlta($this->getObjInfraSessao()->getStrDescricaoOrgaoSistema()));
        }
      }

      if ($this->getStrLogoSistema()!=null) {
        $strIdentificacaoSistema = $this->getStrLogoSistema();
      }else{
        $strIdentificacaoSistema = '<span id="spnInfraIdentificacaoSistema">'.self::tratarHTML($this->getStrNomeSistema()).'</span>';
      }

      $acoesSistema = $this->getArrStrAcoesSistema();
      $bolAcoesSistemas = ($acoesSistema != null && is_array($acoesSistema) && InfraArray::contar($acoesSistema));

      $acoesSistemaMovel = $this->getArrStrAcoesSistemaMovel();
      $bolAcoesSistemasMovel = ($acoesSistemaMovel != null && is_array($acoesSistemaMovel) && InfraArray::contar($acoesSistemaMovel));


      $imgMenuHamburguer = '<div class="nav-item media mr-3"><img id="lnkInfraMenuSistema" src="'.$this->getDiretorioSvgGlobal() . '/menu_topo.svg?'.VERSAO_INFRA.'" onclick="infraClicarMenuBootstrap()"  title="Exibir/Ocultar Menu do Sistema" alt="Exibir/Ocultar Menu do Sistema" tabindex="'.$this->getProxTabBarraSistema().'" class="align-self-center infraImg" /></div >';

      echo '
      <nav id="navInfraBarraNavegacao" class="  navbar navbar-expand-md infraBarraNavegacao infraCorBarraSistema p-0">
      
        <div id="divInfraBarraSistema" class="flex-column w-100 h-100 infraBarraSistema"  >
           <div style="height: 4px;"></div>
           <h6  class="pl-3 mb-0 mx-0 text-white d-none d-md-block infraCorBarraSuperior">' . $strTextoBarraSuperior . '</h6>
           <h6  class="pl-3 mb-0 mx-0 text-white d-md-none infraCorBarraSuperior">' . ($this->getStrSiglaOrgao() == null ? $strTextoBarraSuperior : $this->getStrSiglaOrgao()) . '</h6>

          <div id="divInfraBarraSistemaMovel" class="flex-row pb-0  pl-3 d-md-none media infraBarraSistemaMovel">
            <div class="d-flex flex-grow-1 infraBarraSistemaMovelE" >
               '.(!$this->getBolMontarIconeMenu() ? "" : $imgMenuHamburguer).'
               <div class="align-self-center mt-1">
                   <span id="spnInfraIdentificacaoSistema">' . ($this->getStrLogoSistema()!=null ? $this->getStrLogoSistema() : $this->getStrSiglaSistema()) . '</span>
               </div>
            </div>
            <div class="infraBarraSistemaMovelD d-flex flex-shrink-0">
              ' .($bolAcoesSistemasMovel ? implode($acoesSistemaMovel) : ""). '
              ' .($bolAcoesSistemas && $this->getBolExibirBotaoMenuMovel() ? $strBotaoMenuMovel : ""). '
            </div>
          </div>
          
          <div id="divInfraBarraSistemaPadrao" class="navbar p-0 infraCorBarraSistema  collapse navbar-collapse align-self-center infraBarraSistemaPadrao">
            <div id="divInfraBarraSistemaPadraoE" class="nav-link p-0 pl-3 d-none d-md-flex infraBarraSistemaPadraoE">
               '.(!$this->getBolMontarIconeMenu() || $this->getStrMenuSistema() == null ? "" : $imgMenuHamburguer).'
              <div class="align-self-center">' . $strIdentificacaoSistema . '</div>
            </div>
            <div id="divInfraBarraSistemaPadraoD" class="navbar-nav  flex-grow-1 justify-content-end infraBarraSistemaPadraoD">
                 ' .($bolAcoesSistemas ? implode($acoesSistema) : ""). '
            </div>
          </div>
        </div>
      </nav>
     ';
    }
    echo '<div id="divInfraAreaTela" style="min-height:0;"  class="w-100  flex-grow-1 d-flex flex-row  divInfraAreaTela'.($this->getTipoPagina()==self::$TIPO_PAGINA_SIMPLES ? "Simples" : "").'">'."\n";
    if ($this->getTipoPagina()==self::$TIPO_PAGINA_COMPLETA && $this->getStrMenuSistema()!=null) {
      $strStyle = '';
      if ($this->getStrCookieMenuMostrar()=='N') {
        $strStyle = ' infraAreaTelaEEscondeGrande infraAreaTelaEEscondePequeno ';
      }else{
        $strStyle = ' infraAreaTelaEExibeGrande infraAreaTelaEEscondePequeno ';
      }
// infraMenuAnimacao
      echo '<div id="divInfraAreaTelaE" class=" divInfraAreaTelaE d-flex flex-column ' . $strStyle . ' " >' . "\n";
      echo $this->getStrMenuSistema();
      echo'</div>' . "\n"; //infraAreaTelaE
    }
    echo '<div id="divInfraAreaTelaD"  class=" flex-grow-1 px-3" >'."\n";
    echo $this->montarBarraAcesso();
    echo  $this->montarBarraLocalizacao($strLocalizacao);
  }

  public function fecharBody() {
    echo '</div>' . "\n" . //infraAreaTelaD
      '</div>' . "\n" . //infraAreaTela
      '</div>' . "\n" . //infraAreaGlobal
      '<input type="hidden" id="hdnInfraPrefixoCookie" name="hdnInfraPrefixoCookie" value="' . $this->getStrPrefixoCookie() . '" />' . "\n" .
      '<div id="infraDivImpressao" class="infraImpressao"></div>' . "\n";

    echo '<div id="infraDivBootstrap-xs" class="d-none d-xs-block"></div>'."\n".
      '<div id="infraDivBootstrap-sm" class="d-none d-sm-block"></div>'."\n".
      '<div id="infraDivBootstrap-md" class="d-none d-md-block"></div>'."\n".
      '<div id="infraDivBootstrap-lg" class="d-none d-lg-block"></div>'."\n";

    $this->montarMensagens();
    echo '</body>' . "\n";
  }

  public function getBolMontarIconeMenu() {
    return true;
  }

  public function getIconeMenu(){
    return $this->getDiretorioSvgGlobal() . '/menu.svg';
  }

  public function getIconeUsuario(){
    return $this->getDiretorioSvgGlobal() . '/usuario.svg';
  }

  public function getIconeCheck(){
    return $this->getDiretorioSvgGlobal() . '/check.svg';
  }

  public function getIconeOrdenacaoColunaAcima(){
    return $this->getDiretorioSvgGlobal() . '/seta_acima.svg';
  }

  public function getIconeOrdenacaoColunaAcimaSelecionada(){
    return $this->getDiretorioSvgGlobal() . '/seta_acima_selecionada.svg';
  }

  public function getIconeOrdenacaoColunaAbaixo(){
    return $this->getDiretorioSvgGlobal() . '/seta_abaixo.svg';
  }

  public function getIconeOrdenacaoColunaAbaixoSelecionada(){
    return $this->getDiretorioSvgGlobal() . '/seta_abaixo_selecionada.svg';
  }

  public function getIconeExibir(){
    return $this->getDiretorioSvgGlobal() . '/exibir.svg';
  }

  public function getIconeOcultar(){
    return $this->getDiretorioSvgGlobal() . '/ocultar.svg';
  }

  public function getIconeConsultar(){
    return $this->getDiretorioSvgGlobal() . '/consultar.svg';
  }

  public function getIconeAlterar(){
    return $this->getDiretorioSvgGlobal() . '/alterar.svg';
  }

  public function getIconeClonar(){
    return $this->getDiretorioSvgGlobal() . '/clonar.svg';
  }

  public function getIconeExcluir(){
    return $this->getDiretorioSvgGlobal() . '/excluir.svg';
  }

  public function getIconeDesativar(){
    return $this->getDiretorioSvgGlobal() . '/desativar.svg';
  }

  public function getIconeReativar(){
    return $this->getDiretorioSvgGlobal() . '/reativar.svg';
  }

  public function getIconePesquisar(){
    return $this->getDiretorioSvgGlobal() . '/pesquisar.svg';
  }

  public function getIconeRemover(){
    return $this->getDiretorioSvgGlobal() . '/remover.svg';
  }

  public function getIconeMoverAbaixo(){
    return $this->getDiretorioSvgGlobal() . '/mover_abaixo.svg';
  }

  public function getIconeMoverAcima(){
    return $this->getDiretorioSvgGlobal() . '/mover_acima.svg';
  }

  public function getIconeCalendario(){
    return $this->getDiretorioSvgGlobal() . '/calendario.svg';
  }

  public function getIconePaginacaoPrimeira(){
    return $this->getDiretorioSvgGlobal() . '/paginacao_primeira.svg';
  }

  public function getIconePaginacaoAnterior(){
    return $this->getDiretorioSvgGlobal() . '/paginacao_anterior.svg';
  }

  public function getIconePaginacaoProxima(){
    return $this->getDiretorioSvgGlobal() . '/paginacao_proxima.svg';
  }

  public function getIconePaginacaoUltima(){
    return $this->getDiretorioSvgGlobal() . '/paginacao_ultima.svg';
  }

  public function getIconeTransportar(){
    return $this->getDiretorioSvgGlobal() . '/transportar.svg';
  }

  public function getIconeAjuda(){
    return $this->getDiretorioSvgGlobal() . '/ajuda.svg';
  }

  public function getIconeInformacao(){
    return $this->getDiretorioSvgGlobal() . '/informacao.svg';
  }

  public function getIconeMais(){
    return $this->getDiretorioSvgGlobal() . '/mais.svg';
  }

  public function getIconeMenos(){
    return $this->getDiretorioSvgGlobal() . '/menos.svg';
  }

  public function getIconeUpload(){
    return $this->getDiretorioSvgGlobal() . '/upload.svg';
  }

  public function getIconeDownload(){
    return $this->getDiretorioSvgGlobal() . '/download.svg';
  }

  public function getIconeMarcar(){
    return $this->getDiretorioSvgGlobal() . '/marcar.svg';
  }

  public function getIconeGrupo(){
    return $this->getDiretorioSvgGlobal() . '/grupo.svg';
  }

  public function getIconeAguardar(){
    return $this->getDiretorioSvgGlobal() . '/aguarde.svg';
  }

  public function getIconeMenuPontos(){
    return $this->getDiretorioSvgGlobal() . '/menu_pontos.svg';
  }

  public function getIconeVoltar(){
    return $this->getDiretorioSvgGlobal() . '/voltar.svg';
  }

  public function getIconeAnterior(){
    return $this->getDiretorioSvgGlobal() . '/anterior.svg';
  }

  public function getIconeProximo(){
    return $this->getDiretorioSvgGlobal() . '/proximo.svg';
  }
}
?>