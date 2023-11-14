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

class VocativoINT extends InfraINT {

  public static function montarSelectExpressaoRI0469($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objVocativoDTO = new VocativoDTO();
    $objVocativoDTO->retNumIdVocativo();
    $objVocativoDTO->retStrExpressao();
    $objVocativoDTO->setOrdStrExpressao(InfraDTO::$TIPO_ORDENACAO_ASC);


    $objVocativoRN = new VocativoRN();
    $arrObjVocativoDTO = $objVocativoRN->listarRN0310($objVocativoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjVocativoDTO, 'IdVocativo', 'Expressao');
  }
}
?>