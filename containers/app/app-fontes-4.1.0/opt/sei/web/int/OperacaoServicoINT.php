<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/09/2011 - criado por mga
*
* Versão do Gerador de Código: 1.31.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class OperacaoServicoINT extends InfraINT {

  public static function montarSelectStaOperacaoServico($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objOperacaoServicoRN = new OperacaoServicoRN();
    $arr = $objOperacaoServicoRN->listarValoresOperacaoServicoConfiguraveis();
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arr, 'StaOperacaoServico', 'Descricao');
  }

  public static function montarSelectOperacaoMonitoramento($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){

    $objOperacaoServicoRN = new OperacaoServicoRN();
    $arrObjTipoOperacaoServicoDTO = $objOperacaoServicoRN->listarValoresOperacaoServico();
    InfraArray::ordenarArrInfraDTO($arrObjTipoOperacaoServicoDTO,'Operacao',InfraArray::$TIPO_ORDENACAO_ASC);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTipoOperacaoServicoDTO, 'Operacao', 'Operacao');

  }

}
?>