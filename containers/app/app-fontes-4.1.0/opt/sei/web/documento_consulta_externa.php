<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
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

  SessaoSEIExterna::getInstance()->validarLink();

  PaginaSEIExterna::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  $objAcessoExternoDTO = new AcessoExternoDTO();

  if (isset($_GET['id_acesso_externo'])) {
    $objAcessoExternoDTO->setNumIdAcessoExterno($_GET['id_acesso_externo']);
  }

  if (isset($_GET['id_procedimento'])){
    $objAcessoExternoDTO->setDblIdProcedimento($_GET['id_procedimento']);
  }

  $objAcessoExternoDTO->setDblIdProtocoloConsulta($_GET['id_documento']);

  $objAcessoExternoRN = new AcessoExternoRN();
  $objAcessoExternoDTO = $objAcessoExternoRN->consultarProcessoAcessoExterno($objAcessoExternoDTO);
  $objProcedimentoDTO = $objAcessoExternoDTO->getObjProcedimentoDTO();

  $objDocumentoDTO = null;

  foreach ($objProcedimentoDTO->getArrObjRelProtocoloProtocoloDTO() as $objRelProtocoloProtocoloDTO) {
    if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO &&
        $objRelProtocoloProtocoloDTO->getStrSinAcessoBasico()=='S' &&
        $objRelProtocoloProtocoloDTO->getDblIdProtocolo2() == $_GET['id_documento']){

      $objDocumentoDTO = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

		  break;
		}
	}

	if ($objDocumentoDTO == null){
		throw new InfraException('Documento não encontrado.');
	}

	if ($objDocumentoDTO->getStrSinEliminadoProtocolo()=='S'){
    die('Este documento foi eliminado.');
  }

	DocumentoINT::download($objDocumentoDTO, SessaoSEIExterna::getInstance(), 'documento_consulta_externa.php?id_acesso_externo='.$_GET['id_acesso_externo'].'&id_documento='.$_GET['id_documento']);

  $objDocumentoDTOAuditoria = new DocumentoDTO();
  $objDocumentoDTOAuditoria->setDblIdProcedimento($objDocumentoDTO->getDblIdProcedimento());
  $objDocumentoDTOAuditoria->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
  $objDocumentoDTOAuditoria->setStrProtocoloDocumentoFormatado($objDocumentoDTO->getStrProtocoloDocumentoFormatado());

  AuditoriaSEI::getInstance()->auditar('documento_consulta_externa', __FILE__, $objDocumentoDTOAuditoria);

}catch(Throwable $e){

  if ($e instanceof InfraException && $e->contemValidacoes()){
    die($e->__toString());
  }

  if (!($e instanceof InfraException) || $e->isBolPermitirGravacaoLog()) {
    try {
      LogSEI::getInstance()->gravar(InfraException::inspecionar($e));
    } catch (Exception $e2) {}
  }

  PaginaSEIExterna::getInstance()->processarExcecao($e);
}
?>