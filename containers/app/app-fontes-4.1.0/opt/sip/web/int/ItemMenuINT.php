<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/01/2007 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class ItemMenuINT extends InfraINT {

  public static function montarSelectRotulo(
    $strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMenu = '', $numIdSistema = '', $numIdMenuPai = '', $numIdItemMenuPai = '', $strIdRecurso = '') {
    $objItemMenuDTO = new ItemMenuDTO();
    $objItemMenuDTO->retNumIdItemMenu();
    $objItemMenuDTO->retStrRotulo();
    $objItemMenuDTO->setOrdStrRotulo(InfraDTO::$TIPO_ORDENACAO_ASC);


    if ($numIdMenu !== '') {
      $objItemMenuDTO->setNumIdMenu($numIdMenu);
    }

    if ($numIdSistema !== '') {
      $objItemMenuDTO->setNumIdSistema($numIdSistema);
    }

    if ($numIdMenuPai !== '') {
      $objItemMenuDTO->setNumIdMenuPai($numIdMenuPai);
    }

    if ($numIdItemMenuPai !== '') {
      $objItemMenuDTO->setNumIdItemMenuPai($numIdItemMenuPai);
    }

    if ($strIdRecurso !== '') {
      $objItemMenuDTO->setStrIdRecurso($strIdRecurso);
    }

    $objItemMenuRN = new ItemMenuRN();
    $arrObjItemMenuDTO = $objItemMenuRN->listar($objItemMenuDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjItemMenuDTO, 'IdItemMenu', 'Rotulo');
  }

  public static function montarSelectRamificacao(
    $strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMenu) {
    $objItemMenuDTO = new ItemMenuDTO();
    $objItemMenuDTO->setNumIdMenu($numIdMenu);
    $objItemMenuRN = new ItemMenuRN();
    $arrObjItemMenuDTO = $objItemMenuRN->listarHierarquia($objItemMenuDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjItemMenuDTO, 'IdItemMenu', 'Ramificacao');
  }

  public static function montarSelectRamificacaoOutros(
    $strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdMenu, $numIdItemMenu) {
    //Carrega todos os itens EXCETO o passado como parametro
    $objItemMenuDTO = new ItemMenuDTO();
    $objItemMenuDTO->setNumIdMenu($numIdMenu);
    $objItemMenuRN = new ItemMenuRN();
    $arrObjItemMenuDTO = $objItemMenuRN->listarHierarquia($objItemMenuDTO);

    $arrFinal = array();
    foreach ($arrObjItemMenuDTO as $itemMenu) {
      if ($itemMenu->getNumIdItemMenu() !== $numIdItemMenu) {
        $arrFinal[] = $itemMenu;
      }
    }

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrFinal, 'IdItemMenu', 'Ramificacao');
  }

}

?>