<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/03/2015 - criado por mga
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelSituacaoUnidadeINT extends InfraINT {

  public static function montarSelectUnidades($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdSituacao){
    $objRelSituacaoUnidadeDTO = new RelSituacaoUnidadeDTO();
    $objRelSituacaoUnidadeDTO->retNumIdUnidade();
    $objRelSituacaoUnidadeDTO->retStrSiglaUnidade();
    $objRelSituacaoUnidadeDTO->retStrDescricaoUnidade();

    $objRelSituacaoUnidadeDTO->setNumIdSituacao($numIdSituacao);

    $objRelSituacaoUnidadeDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelSituacaoUnidadeRN = new RelSituacaoUnidadeRN();
    $arrObjRelSituacaoUnidadeDTO = $objRelSituacaoUnidadeRN->listar($objRelSituacaoUnidadeDTO);

    foreach($arrObjRelSituacaoUnidadeDTO as $objRelSituacaoUnidadeDTO){
      $objRelSituacaoUnidadeDTO->setStrSiglaUnidade(UnidadeINT::formatarSiglaDescricao($objRelSituacaoUnidadeDTO->getStrSiglaUnidade(),$objRelSituacaoUnidadeDTO->getStrDescricaoUnidade()));
    }

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjRelSituacaoUnidadeDTO, 'IdUnidade', 'SiglaUnidade');
  }

}
?>