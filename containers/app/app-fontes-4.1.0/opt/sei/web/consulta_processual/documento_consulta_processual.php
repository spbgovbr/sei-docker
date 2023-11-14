<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4Њ REGIУO
*
* 29/03/2023 - criado por mgb29
*
*/

try {
  require_once dirname(__FILE__) . '/../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoConsultaProcessual::getInstance()->validarLink();

  if (SessaoConsultaProcessual::getInstance()->getAtributo('CONSULTA_PROCESSUAL_HASH')==null){
    die;
  }

  PaginaConsultaProcessual::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  switch ($_GET['acao']) {
    case 'consulta_processual_documento':
      $strTitulo = 'Consulta Processual';
      break;

    default:
      throw new InfraException("Aчуo '" . $_GET['acao'] . "' nуo reconhecida.");
  }

  $objConsultaProcessualDTO = new ConsultaProcessualDTO();
  //$objConsultaProcessualDTO->setStrStaCriterioPesquisa(SessaoConsultaProcessual::getInstance()->getAtributo('CONSULTA_PROCESSUAL_CRITERIO_TIPO'));
  //$objConsultaProcessualDTO->setStrValorPesquisa(SessaoConsultaProcessual::getInstance()->getAtributo('CONSULTA_PROCESSUAL_CRITERIO_VALOR'));
  //$objConsultaProcessualDTO->setNumIdOrgaoUnidadeGeradora(SessaoConsultaProcessual::getInstance()->getAtributo('CONSULTA_PROCESSUAL_ORGAOS'));
  $objConsultaProcessualDTO->setDblIdProcedimento($_GET['id_procedimento']);
  $objConsultaProcessualDTO->setDblIdProtocoloConsulta($_GET['id_documento']);

  $objConsultaProcessualRN = new ConsultaProcessualRN();
  $objProcedimentoDTO = $objConsultaProcessualRN->consultarProcessoConsultaProcessual($objConsultaProcessualDTO);

  $objDocumentoDTO = null;

  foreach ($objProcedimentoDTO->getArrObjRelProtocoloProtocoloDTO() as $objRelProtocoloProtocoloDTO) {
    if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO && $objRelProtocoloProtocoloDTO->getDblIdProtocolo2() == $_GET['id_documento']){
      $objDocumentoDTO = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();
      break;
    }
  }

  if ($objDocumentoDTO == null){
    throw new InfraException('Documento nуo encontrado.');
  }

  if ($objDocumentoDTO->getStrSinEliminadoProtocolo()=='S'){
    die('Este documento foi eliminado.');
  }

  DocumentoINT::download($objDocumentoDTO, SessaoConsultaProcessual::getInstance(), null);

}catch(Throwable $e){

  if ($e instanceof InfraException && $e->contemValidacoes()){
    die($e->__toString());
  }

  PaginaConsultaProcessual::getInstance()->processarExcecao($e);
}
?>