<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 06/10/2014 - criado por mga
 *
 */

require_once dirname(__FILE__).'/../SEI.php';

class RelGrupoUnidadeUnidadeINT extends InfraINT {

  public static function montarSelectUnidade($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdGrupoUnidade){

    $objRelGrupoUnidadeUnidadeDTO = new RelGrupoUnidadeUnidadeDTO();
    $objRelGrupoUnidadeUnidadeDTO->retNumIdUnidade();
    $objRelGrupoUnidadeUnidadeDTO->retStrSiglaUnidade();
    $objRelGrupoUnidadeUnidadeDTO->retStrDescricaoUnidade();
    $objRelGrupoUnidadeUnidadeDTO->setNumIdGrupoUnidade($numIdGrupoUnidade);
    $objRelGrupoUnidadeUnidadeDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelGrupoUnidadeUnidadeRN = new RelGrupoUnidadeUnidadeRN();

    $arrObjRelGrupoUnidadeUnidadeDTO = $objRelGrupoUnidadeUnidadeRN->listar($objRelGrupoUnidadeUnidadeDTO);

    foreach($arrObjRelGrupoUnidadeUnidadeDTO as $objRelGrupoUnidadeUnidadeDTO){
      $objRelGrupoUnidadeUnidadeDTO->setStrSiglaUnidade(UnidadeINT::formatarSiglaDescricao($objRelGrupoUnidadeUnidadeDTO->getStrSiglaUnidade(),$objRelGrupoUnidadeUnidadeDTO->getStrDescricaoUnidade()));
    }

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjRelGrupoUnidadeUnidadeDTO, 'IdUnidade', 'SiglaUnidade');
  }
}
?>