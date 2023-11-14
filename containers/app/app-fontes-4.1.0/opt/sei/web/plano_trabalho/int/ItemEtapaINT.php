<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 23/09/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class ItemEtapaINT extends InfraINT {

  public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdEtapaTrabalho = '', $numIdPlanoTrabalho = '') {
    $objItemEtapaDTO = new ItemEtapaDTO();
    $objItemEtapaDTO->retNumIdItemEtapa();
    $objItemEtapaDTO->retStrNome();
    $objItemEtapaDTO->retNumOrdem();

    if ($numIdEtapaTrabalho !== '') {
      $objItemEtapaDTO->setNumIdEtapaTrabalho($numIdEtapaTrabalho);
    }

    if ($numIdPlanoTrabalho !== '') {
      $objItemEtapaDTO->setNumIdPlanoTrabalhoEtapaTrabalho($numIdPlanoTrabalho);
    }

    if ($strValorItemSelecionado != null) {
      $objItemEtapaDTO->setBolExclusaoLogica(false);
      $objItemEtapaDTO->adicionarCriterio(array('SinAtivo', 'IdItemEtapa'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array('S', $strValorItemSelecionado), InfraDTO::$OPER_LOGICO_OR);
    }

    $objItemEtapaDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objItemEtapaRN = new ItemEtapaRN();
    $arrObjItemEtapaDTO = $objItemEtapaRN->listar($objItemEtapaDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjItemEtapaDTO, 'IdItemEtapa', 'Nome');
  }
}
