<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4Є REGIГO
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

  $strTitulo = 'Geraзгo de PDF de Processo do SEI Federaзгo na Instalaзгo '.SessaoSEIFederacao::getInstance()->getStrSiglaInstalacaoFederacaoLocal();

  $objInfraException = new InfraException();

  $arrIdProtocoloFederacao = explode(',',$objAcaoFederacaoDTO->getArrObjParametroAcaoFederacaoDTO()['id_protocolo_federacao']->getStrValor());

  if (count($arrIdProtocoloFederacao)==0){
    $objInfraException->lancarValidacao('Nenhum documento informado.');
  }

  $objVisualizarProcessoFederacaoDTO = new VisualizarProcessoFederacaoDTO();
  $objVisualizarProcessoFederacaoDTO->setStrIdProcedimentoFederacao($objAcaoFederacaoDTO->getStrIdProcedimentoFederacao());
  $objVisualizarProcessoFederacaoDTO->setStrIdDocumentoFederacao($arrIdProtocoloFederacao);
  $objVisualizarProcessoFederacaoDTO->setStrSinProtocolos('S');
  $objVisualizarProcessoFederacaoDTO->setStrSinAndamentos('N');

  $objAcessoFederacaoRN = new AcessoFederacaoRN();
  $objVisualizarProcessoFederacaoDTORet = $objAcessoFederacaoRN->consultarProcesso($objVisualizarProcessoFederacaoDTO);

  $objProcedimentoDTO = $objVisualizarProcessoFederacaoDTORet->getObjProcedimentoDTO();

  $objDocumentoRN = new DocumentoRN();

  $arrObjRelProtocoloProtocoloDTO = $objProcedimentoDTO->getArrObjRelProtocoloProtocoloDTO();

  $arrObjDocumentoDTO = array();

  foreach($arrIdProtocoloFederacao as $strIdProtocoloFederacao) {

    $numChaveProtocolo = null;
    foreach($arrObjRelProtocoloProtocoloDTO as $key => $objRelProtocoloProtocoloDTO){
      if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO && $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2()->getStrIdProtocoloFederacaoProtocolo()==$strIdProtocoloFederacao) {
        $numChaveProtocolo = $key;
      }
    }

    if ($numChaveProtocolo === null){
      throw new InfraException('Documento '.$strIdProtocoloFederacao.' nгo encontrado.');
    }

    $objRelProtocoloProtocoloDTO = $arrObjRelProtocoloProtocoloDTO[$numChaveProtocolo];
    $objDocumentoDTO = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

    if ($objDocumentoDTO->getStrSinPdf()=='N'){
      $objInfraException->adicionarValidacao('Documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().' nгo estб disponнvel para geraзгo de PDF.');
    }else {
      if ($objRelProtocoloProtocoloDTO->getStrSinAcessoBasico() == 'S') {
        $arrObjDocumentoDTO[] = $objDocumentoDTO;
      } else {
        if ($objDocumentoDTO->getStrStaEstadoProtocolo() == ProtocoloRN::$TE_DOCUMENTO_CANCELADO) {
          $objInfraException->adicionarValidacao('Documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().' cancelado.');
        } else {
          $objInfraException->adicionarValidacao('Sem acesso ao documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().'.');
        }
      }
    }

    unset($arrObjRelProtocoloProtocoloDTO[$numChaveProtocolo]);
  }

  $objInfraException->lancarValidacoes();

  $objAnexoDTO = $objDocumentoRN->gerarPdf($arrObjDocumentoDTO);

  SeiINT::download(null, null, $objAnexoDTO->getStrNome(), 'SEI-'.$objProcedimentoDTO->getStrProtocoloProcedimentoFormatado().'.pdf', 'attachment');

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