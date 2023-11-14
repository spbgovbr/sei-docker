<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/01/2007 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class MenuINT extends InfraINT {

  public static function montarSelectNome(
    $strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdSistema = '') {
    $objMenuDTO = new MenuDTO();
    $objMenuDTO->retNumIdMenu();
    $objMenuDTO->retStrNome();
    $objMenuDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);


    if ($numIdSistema !== '') {
      $objMenuDTO->setNumIdSistema($numIdSistema);
    }

    $objMenuRN = new MenuRN();
    $arrObjMenuDTO = $objMenuRN->listar($objMenuDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjMenuDTO, 'IdMenu', 'Nome');
  }

}

?>