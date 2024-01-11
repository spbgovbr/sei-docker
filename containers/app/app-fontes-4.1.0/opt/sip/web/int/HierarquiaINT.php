<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class HierarquiaINT extends InfraINT {

  public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado) {
    $objHierarquiaDTO = new HierarquiaDTO();
    $objHierarquiaDTO->retNumIdHierarquia();

    $objHierarquiaDTO->retStrNome();
    $objHierarquiaDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objHierarquiaRN = new HierarquiaRN();
    $arrObjHierarquiaDTO = $objHierarquiaRN->listar($objHierarquiaDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjHierarquiaDTO, 'IdHierarquia', 'Nome');
  }
}

?>