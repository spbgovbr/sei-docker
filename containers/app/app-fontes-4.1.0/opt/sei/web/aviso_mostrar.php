<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/12/2020 - criado por mga
*
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    
    case 'aviso_mostrar':
      $strTitulo = '';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
  
  $arrComandos = array();

  $objAvisoDTO = new AvisoDTO();
  $objAvisoDTO->retTodos();
  $objAvisoRN = new AvisoRN();
  $objAvisoDTO->setNumIdAviso($_GET["id_aviso"]);
  $objAvisoDTO = $objAvisoRN->consultar($objAvisoDTO);

  $strLink = (InfraString::isBolVazia( $objAvisoDTO->getStrLink()) ? "" : 'href="'.$objAvisoDTO->getStrLink().'"');
  $strResultado = '<a '.$strLink.' target="_blank"><img src="data:image/png;base64,'.$objAvisoDTO->getStrImagem().'" title="'.$objAvisoDTO->getStrDescricao().'"/></a>'."\n";

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
} 

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - Aviso');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
#divAviso{
  text-align:center;
}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
}

function desabilitarAviso(chave){
  if (document.getElementById('chkNaoMostrarAviso').checked){
    infraCriarCookie('<?=PaginaSEI::getInstance()->getStrPrefixoCookie()?>_aviso', chave, 3650);
  }else{
    infraRemoverCookie('<?=PaginaSEI::getInstance()->getStrPrefixoCookie()?>_aviso');
  }
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();

?>
<body onload="inicializar();">
<form id="frmAvisoMostrar" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">

  <div id="divAviso">
    <?=$strResultado?>
    <br>
    <input type="checkbox" id="chkNaoMostrarAviso" name="chkNaoMostrarAviso" class="infraCheckbox" onclick="desabilitarAviso('<?=md5($objAvisoDTO->getStrImagem())?>');" />
    <label id="lblNaoMostrarAviso" for="chkNaoMostrarAviso" class="infraLabelCheckbox">Não exibir novamente</label>
  </div>
  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
</body>
<?
PaginaSEI::getInstance()->fecharHtml();
?>