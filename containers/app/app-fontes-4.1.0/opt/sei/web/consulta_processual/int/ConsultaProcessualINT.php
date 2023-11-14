<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/03/2023 - criado por mgb29
*
*/

require_once dirname(__FILE__).'/../../SEI.php';

class ConsultaProcessualINT extends InfraINT {

  public static function montarSelectStaCriterio($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objConsultaProcessualRN = new ConsultaProcessualRN();
    $arrObjInfraValorStaDTO = $objConsultaProcessualRN->listarValoresCriterios();
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjInfraValorStaDTO, 'StaValor', 'Descricao');
  }
}
