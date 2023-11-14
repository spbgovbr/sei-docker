<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class RelHierarquiaUnidadeINT extends InfraINT {

  public static function montarSelectSiglaUnidade(
    $strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdHierarquia = '', $numIdUnidade = '', $numIdHierarquiaPai = '', $numIdUnidadePai = '') {
    $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO(true);

    if ($numIdUnidade !== '') {
      $objRelHierarquiaUnidadeDTO->setNumIdUnidade($numIdUnidade);
    }

    if ($numIdHierarquia !== '') {
      $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($numIdHierarquia);
    }

    if ($numIdHierarquiaPai !== '') {
      $objRelHierarquiaUnidadeDTO->setNumIdHierarquiaPai($numIdHierarquiaPai);
    }

    if ($numIdUnidadePai !== '') {
      $objRelHierarquiaUnidadeDTO->setNumIdUnidadePai($numIdUnidadePai);
    }

    $objRelHierarquiaUnidadeDTO->retNumIdUnidade();
    $objRelHierarquiaUnidadeDTO->retStrSiglaUnidade();
    $objRelHierarquiaUnidadeDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
    $arrObjRelHierarquiaUnidadeDTO = $objRelHierarquiaUnidadeRN->listar($objRelHierarquiaUnidadeDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjRelHierarquiaUnidadeDTO, 'IdUnidade', 'SiglaUnidade');
  }

  public static function montarSelectSiglaUnidadeOutras(
    $strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdHierarquia, $numIdUnidade) {
    //Carrega todas as unidades da hierarquia EXCETO a passada como parâmetro
    $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO(true);
    $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($numIdHierarquia);
    $objRelHierarquiaUnidadeDTO->retNumIdUnidade();
    $objRelHierarquiaUnidadeDTO->retStrSiglaUnidade();
    $objRelHierarquiaUnidadeDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
    $arrObjRelHierarquiaUnidadeDTO = $objRelHierarquiaUnidadeRN->listar($objRelHierarquiaUnidadeDTO);

    $arrFinal = array();
    foreach ($arrObjRelHierarquiaUnidadeDTO as $unidadeHierarquia) {
      if ($unidadeHierarquia->getNumIdUnidade() !== $numIdUnidade) {
        $arrFinal[] = $unidadeHierarquia;
      }
    }
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrFinal, 'IdUnidade', 'SiglaUnidade');
  }

  public static function montarSelectSiglaUnidadeNova(
    $strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdOrgaoUnidade, $numIdHierarquia) {
    $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
    $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($numIdHierarquia);
    $objRelHierarquiaUnidadeDTO->setNumIdOrgaoUnidade($numIdOrgaoUnidade);

    $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
    $arrUnidades = $objRelHierarquiaUnidadeRN->listarUnidadesNovas($objRelHierarquiaUnidadeDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrUnidades, 'IdUnidade', 'Sigla');
  }

  public static function autoCompletarRamificacao($strPalavrasPesquisa, $numIdHierarquia) {
    if ($strPalavrasPesquisa == '') {
      return null;
    }

    $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
    $objRelHierarquiaUnidadeDTO->setBolExclusaoLogica(false);
    $objRelHierarquiaUnidadeDTO->retNumIdUnidade();
    $objRelHierarquiaUnidadeDTO->retStrSiglaUnidade();
    $objRelHierarquiaUnidadeDTO->retStrDescricaoUnidade();
    $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($numIdHierarquia);

    $strPalavrasPesquisa = trim($strPalavrasPesquisa);
    $arrPalavrasPesquisa = explode(' ', $strPalavrasPesquisa);

    for ($i = 0; $i < count($arrPalavrasPesquisa); $i++) {
      $arrPalavrasPesquisa[$i] = '%' . $arrPalavrasPesquisa[$i] . '%';
    }

    if (count($arrPalavrasPesquisa) == 1) {
      $objRelHierarquiaUnidadeDTO->adicionarCriterio(array('SiglaUnidade'), array(InfraDTO::$OPER_LIKE), $arrPalavrasPesquisa[0], null, 'filtroSigla');
      $objRelHierarquiaUnidadeDTO->adicionarCriterio(array('DescricaoUnidade'), array(InfraDTO::$OPER_LIKE), $arrPalavrasPesquisa[0], null, 'filtroDescricao');
    } else {
      $objRelHierarquiaUnidadeDTO->adicionarCriterio(array_fill(0, count($arrPalavrasPesquisa), 'SiglaUnidade'), array_fill(0, count($arrPalavrasPesquisa), InfraDTO::$OPER_LIKE), $arrPalavrasPesquisa,
        array_fill(0, count($arrPalavrasPesquisa) - 1, InfraDTO::$OPER_LOGICO_OR), 'filtroSigla');

      $objRelHierarquiaUnidadeDTO->adicionarCriterio(array_fill(0, count($arrPalavrasPesquisa), 'DescricaoUnidade'), array_fill(0, count($arrPalavrasPesquisa), InfraDTO::$OPER_LIKE), $arrPalavrasPesquisa,
        array_fill(0, count($arrPalavrasPesquisa) - 1, InfraDTO::$OPER_LOGICO_AND), 'filtroDescricao');
    }

    $objRelHierarquiaUnidadeDTO->agruparCriterios(array('filtroSigla', 'filtroDescricao'), InfraDTO::$OPER_LOGICO_OR);

    $objRelHierarquiaUnidadeDTO->setNumMaxRegistrosRetorno(50);

    $objRelHierarquiaUnidadeDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
    $arrObjRelHierarquiaUnidadeDTO = $objRelHierarquiaUnidadeRN->listar($objRelHierarquiaUnidadeDTO);

    foreach ($arrObjRelHierarquiaUnidadeDTO as $objRelHierarquiaUnidadeDTO) {
      $objRelHierarquiaUnidadeDTO->setStrSiglaUnidade(UnidadeINT::formatarSiglaDescricao($objRelHierarquiaUnidadeDTO->getStrSiglaUnidade(), $objRelHierarquiaUnidadeDTO->getStrDescricaoUnidade()));
    }

    return $arrObjRelHierarquiaUnidadeDTO;
  }
}

?>