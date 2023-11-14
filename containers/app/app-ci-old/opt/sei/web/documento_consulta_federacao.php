<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4Њ REGIУO
*
* 15/09/2008 - criado por marcio_db
*
*
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  
  session_start();
  
  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  $objAcaoFederacaoDTO = SessaoSEIFederacao::getInstance()->validarLink();

  $strTitulo = 'Consulta de Documento do SEI Federaчуo na Instalaчуo '.SessaoSEIFederacao::getInstance()->getStrSiglaInstalacaoFederacaoLocal();

  $objVisualizarProcessoFederacaoDTO = new VisualizarProcessoFederacaoDTO();
  $objVisualizarProcessoFederacaoDTO->setStrIdProcedimentoFederacao($objAcaoFederacaoDTO->getStrIdProcedimentoFederacao());
  $objVisualizarProcessoFederacaoDTO->setStrIdDocumentoFederacao($objAcaoFederacaoDTO->getStrIdDocumentoFederacao());
  $objVisualizarProcessoFederacaoDTO->setStrSinProtocolos('S');
  $objVisualizarProcessoFederacaoDTO->setStrSinAndamentos('N');

  $objAcessoFederacaoRN = new AcessoFederacaoRN();
  $objVisualizarProcessoFederacaoDTORet = $objAcessoFederacaoRN->consultarProcesso($objVisualizarProcessoFederacaoDTO);

  $objProcedimentoDTO = $objVisualizarProcessoFederacaoDTORet->getObjProcedimentoDTO();

  $objDocumentoDTO = $objProcedimentoDTO->getArrObjRelProtocoloProtocoloDTO()[0]->getObjProtocoloDTO2();

  DocumentoINT::download($objDocumentoDTO, null, null);

  $objAcessoFederacaoDTOAuditoria = new AcessoFederacaoDTO();
  $objAcessoFederacaoDTOAuditoria->setStrIdProcedimentoFederacao($objAcaoFederacaoDTO->getStrIdProcedimentoFederacao());
  $objAcessoFederacaoDTOAuditoria->setStrIdDocumentoFederacao($objAcaoFederacaoDTO->getStrIdDocumentoFederacao());
  $objAcessoFederacaoDTOAuditoria->setDblIdProcedimento($objDocumentoDTO->getDblIdProcedimento());
  $objAcessoFederacaoDTOAuditoria->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());

  AuditoriaSEI::getInstance()->auditar('documento_consulta_federacao', __FILE__, $objAcessoFederacaoDTOAuditoria);
  die;

}catch(Exception $e){
  PaginaSEIFederacao::getInstance()->processarExcecao($e);
}
PaginaSEIFederacao::getInstance()->montarDocType();
PaginaSEIFederacao::getInstance()->abrirHtml();
PaginaSEIFederacao::getInstance()->abrirHead();
PaginaSEIFederacao::getInstance()->montarMeta();
PaginaSEIFederacao::getInstance()->montarTitle(PaginaSEIFederacao::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEIFederacao::getInstance()->montarStyle();
PaginaSEIFederacao::getInstance()->montarJavaScript();
PaginaSEIFederacao::getInstance()->fecharHead();
PaginaSEIFederacao::getInstance()->abrirBody($strTitulo);
PaginaSEIFederacao::getInstance()->montarAreaDebug();
PaginaSEIFederacao::getInstance()->fecharBody();
PaginaSEIFederacao::getInstance()->fecharHtml();
?>