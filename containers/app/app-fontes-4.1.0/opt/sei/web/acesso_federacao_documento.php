<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4Є REGIГO
*
* 08/05/2012 - criado por mga
*
* Versгo do Gerador de Cуdigo: 1.13.1
*
* Versгo no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore'));

  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  $arrComandos = array();

  switch($_GET['acao']){

    case 'documento_consulta_federacao':

      $strTitulo = 'Consulta de Documento do SEI Federaзгo';

      $objAcessoFederacaoDTO = new AcessoFederacaoDTO();
      $objAcessoFederacaoDTO->setStrIdInstalacaoFederacaoRem($_GET['id_instalacao_federacao']);
      $objAcessoFederacaoDTO->setStrIdProcedimentoFederacao($_GET['id_procedimento_federacao']);
      $objAcessoFederacaoDTO->setStrIdDocumentoFederacao($_GET['id_documento_federacao']);

      $objAcessoFederacaoRN = new AcessoFederacaoRN();
      $strResultado = $objAcessoFederacaoRN->visualizarDocumento($objAcessoFederacaoDTO);

      header('Location: '.$strResultado);
      die;

	  default:
	    throw new InfraException("Aзгo '".$_GET['acao']."' nгo reconhecida.");
  }

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}
PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo);
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>