<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/09/2013 - criado por mga
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class SecaoImprensaNacionalINT extends InfraINT {

  public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdVeiculoImprensaNacional=''){
    $objSecaoImprensaNacionalDTO = new SecaoImprensaNacionalDTO();
    $objSecaoImprensaNacionalDTO->retNumIdSecaoImprensaNacional();
    $objSecaoImprensaNacionalDTO->retStrNome();

    if ($numIdVeiculoImprensaNacional!==''){
      $objSecaoImprensaNacionalDTO->setNumIdVeiculoImprensaNacional($numIdVeiculoImprensaNacional);
    }

    $objSecaoImprensaNacionalDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objSecaoImprensaNacionalRN = new SecaoImprensaNacionalRN();
    $arrObjSecaoImprensaNacionalDTO = $objSecaoImprensaNacionalRN->listar($objSecaoImprensaNacionalDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjSecaoImprensaNacionalDTO, 'IdSecaoImprensaNacional', 'Nome');
  }
}
?>