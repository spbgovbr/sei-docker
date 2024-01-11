<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 29/09/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class RelItemEtapaDocumentoINT extends InfraINT {

  public static function montarSelectDocumento($numIdItemEtapa, $dblIdProcedimento) {
    $arrObjDocumentoDTO = array();

    $objRelItemEtapaDocumentoDTO = new RelItemEtapaDocumentoDTO();
    $objRelItemEtapaDocumentoDTO->retDblIdDocumento();
    $objRelItemEtapaDocumentoDTO->setNumIdItemEtapa($numIdItemEtapa);
    $objRelItemEtapaDocumentoDTO->setDblIdProcedimentoDocumento($dblIdProcedimento);
    $objRelItemEtapaDocumentoDTO->setOrdDblIdDocumento(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelItemEtapaDocumentoRN = new RelItemEtapaDocumentoRN();
    $arrObjRelItemEtapaDocumentoDTO = $objRelItemEtapaDocumentoRN->listar($objRelItemEtapaDocumentoDTO);

    if (count($arrObjRelItemEtapaDocumentoDTO)) {
      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retStrNomeSerie();
      $objDocumentoDTO->retStrNumero();
      $objDocumentoDTO->retStrNomeArvore();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->setDblIdDocumento(InfraArray::converterArrInfraDTO($arrObjRelItemEtapaDocumentoDTO, 'IdDocumento'), InfraDTO::$OPER_IN);
      $objDocumentoDTO->setNumIdUnidadeGeradoraProtocolo(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      $objDocumentoRN = new DocumentoRN();
      $arrObjDocumentoDTO = $objDocumentoRN->listarRN0008($objDocumentoDTO);

      foreach ($arrObjDocumentoDTO as $objDocumentoDTO) {
        $objDocumentoDTO->setStrNomeSerie(DocumentoINT::formatarIdentificacaoComProtocolo($objDocumentoDTO));
      }
    }

    return parent::montarSelectArrInfraDTO(null, null, null, $arrObjDocumentoDTO, 'IdDocumento', 'NomeSerie');
  }
}
