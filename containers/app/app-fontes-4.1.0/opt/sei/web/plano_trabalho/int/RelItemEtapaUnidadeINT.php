<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 29/09/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class RelItemEtapaUnidadeINT extends InfraINT {

  public static function montarSelectUnidade($IdItemEtapa) {
    $objRelItemEtapaUnidadeDTO = new RelItemEtapaUnidadeDTO();
    $objRelItemEtapaUnidadeDTO->retNumIdUnidade();
    $objRelItemEtapaUnidadeDTO->retStrSiglaUnidade();
    $objRelItemEtapaUnidadeDTO->retStrDescricaoUnidade();
    $objRelItemEtapaUnidadeDTO->setNumIdItemEtapa($IdItemEtapa);
    $objRelItemEtapaUnidadeDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelItemEtapaUnidadeRN = new RelItemEtapaUnidadeRN();
    $arrObjRelItemEtapaUnidadeDTO = $objRelItemEtapaUnidadeRN->listar($objRelItemEtapaUnidadeDTO);

    foreach ($arrObjRelItemEtapaUnidadeDTO as $objRelItemEtapaUnidadeDTO) {
      $objRelItemEtapaUnidadeDTO->setStrSiglaUnidade(UnidadeINT::formatarSiglaDescricao($objRelItemEtapaUnidadeDTO->getStrSiglaUnidade(), $objRelItemEtapaUnidadeDTO->getStrDescricaoUnidade()));
    }

    return parent::montarSelectArrInfraDTO(null, null, null, $arrObjRelItemEtapaUnidadeDTO, 'IdUnidade', 'SiglaUnidade');
  }
}
