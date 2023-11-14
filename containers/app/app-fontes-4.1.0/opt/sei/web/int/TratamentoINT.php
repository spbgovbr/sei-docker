<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/12/2007 - criado por fbv
*
* Versão do Gerador de Código: 1.10.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class TratamentoINT extends InfraINT {

  public static function montarSelectExpressaoRI0467($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objTratamentoDTO = new TratamentoDTO();
    $objTratamentoDTO->retNumIdTratamento();
    $objTratamentoDTO->retStrExpressao();
    $objTratamentoDTO->setOrdStrExpressao(InfraDTO::$TIPO_ORDENACAO_ASC);


    $objTratamentoRN = new TratamentoRN();
    $arrObjTratamentoDTO = $objTratamentoRN->listarRN0318($objTratamentoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTratamentoDTO, 'IdTratamento', 'Expressao');
  }
}
?>