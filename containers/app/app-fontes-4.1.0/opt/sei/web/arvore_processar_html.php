<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 01/03/2012 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id$
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

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'arvore_processar_html':
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
} 

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() );
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>

  body{
    text-align:left;
    margin:0;
  }

  #divArvoreAguarde {margin:0;display:block;text-align:center;display:none;}
  #imgArvoreAguarde {position:relative;top:50%;}

  #divArvoreConteudo {
  background-color:white;
<? if (PaginaSEI::getInstance()->isBolNavegadorSafariIpad()){?>
  overflow: scroll !important;
  -webkit-overflow-scrolling:touch;
<? }?>
  }


  #divInfraAreaGlobal {width:100% !important;}

  #divInfraAreaTelaD{
    display: flex!important;
    flex-direction: column!important;
    flex-grow: 1!important;
    padding:0px !important;
  }

  #divArvoreInformacao {
  }

  #divArvoreInformacao, #divArvoreInformacao a {
    font-size:.875rem;
  }


<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  var innerHtml = parent.HTML;
  $("#divArvoreHtml").html(innerHtml);
  parent.HTML = "";
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody("",'onload="inicializar();"');
?>
  <div id="divArvoreConteudo"  class="d-flex flex-row flex-grow-1" > <div id="divArvoreHtml"  class="w-100   d-flex"  ></div></div>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>