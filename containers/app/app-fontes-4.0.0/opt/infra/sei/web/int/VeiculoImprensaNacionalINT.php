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

class VeiculoImprensaNacionalINT extends InfraINT {

  public static function montarSelectSigla($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objVeiculoImprensaNacionalDTO = new VeiculoImprensaNacionalDTO();
    $objVeiculoImprensaNacionalDTO->retNumIdVeiculoImprensaNacional();
    $objVeiculoImprensaNacionalDTO->retStrSigla();

    $objVeiculoImprensaNacionalDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objVeiculoImprensaNacionalRN = new VeiculoImprensaNacionalRN();
    $arrObjVeiculoImprensaNacionalDTO = $objVeiculoImprensaNacionalRN->listar($objVeiculoImprensaNacionalDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjVeiculoImprensaNacionalDTO, 'IdVeiculoImprensaNacional', 'Sigla');
  }
}
?>