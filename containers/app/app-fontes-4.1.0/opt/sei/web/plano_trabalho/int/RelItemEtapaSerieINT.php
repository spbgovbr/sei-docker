<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 29/09/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class RelItemEtapaSerieINT extends InfraINT {

  public static function montarSelectSerie($numIdItemEtapa) {
    $objRelItemEtapaSerieDTO = new RelItemEtapaSerieDTO();
    $objRelItemEtapaSerieDTO->retNumIdSerie();
    $objRelItemEtapaSerieDTO->retStrNomeSerie();
    $objRelItemEtapaSerieDTO->setNumIdItemEtapa($numIdItemEtapa);
    $objRelItemEtapaSerieDTO->setOrdStrNomeSerie(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelItemEtapaSerieRN = new RelItemEtapaSerieRN();
    $arrObjRelItemEtapaSerieDTO = $objRelItemEtapaSerieRN->listar($objRelItemEtapaSerieDTO);

    return parent::montarSelectArrInfraDTO(null, null, null, $arrObjRelItemEtapaSerieDTO, 'IdSerie', 'NomeSerie');
  }

  public static function montarSelectInclusaoDocumento($strPrimeiroItemValor, $strPrimeiroItemDescricao, &$strValorItemSelecionado, $numIdItemEtapa, &$numIdSerieSelecionada) {
    $objRelItemEtapaSerieDTO = new RelItemEtapaSerieDTO();
    $objRelItemEtapaSerieDTO->retNumIdSerie();
    $objRelItemEtapaSerieDTO->retStrNomeSerie();
    $objRelItemEtapaSerieDTO->setNumIdItemEtapa($numIdItemEtapa);
    $objRelItemEtapaSerieDTO->setOrdStrNomeSerie(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelItemEtapaSerieRN = new RelItemEtapaSerieRN();
    $arrObjRelItemEtapaSerieDTO = $objRelItemEtapaSerieRN->listar($objRelItemEtapaSerieDTO);

    if (count($arrObjRelItemEtapaSerieDTO) == 1 && $strValorItemSelecionado == null && !isset($_POST['selSerie'])) {
      $strValorItemSelecionado = $arrObjRelItemEtapaSerieDTO[0]->getNumIdSerie();
    }

    if (is_numeric($strValorItemSelecionado)) {
      $numIdSerieSelecionada = $strValorItemSelecionado;
    } else {
      $numIdSerieSelecionada = null;
    }

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjRelItemEtapaSerieDTO, 'IdSerie', 'NomeSerie');
  }

}
