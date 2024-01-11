<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/11/2011 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id: EstiloINT.php 7875 2013-08-20 14:59:02Z bcu $
*/

require_once dirname(__FILE__).'/../../SEI.php';

class EstiloINT extends InfraINT {

  public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objEstiloDTO = new EstiloDTO();
    $objEstiloDTO->retNumIdEstilo();
    $objEstiloDTO->retStrNome();

    $objEstiloDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objEstiloRN = new EstiloRN();
    $arrObjEstiloDTO = $objEstiloRN->listar($objEstiloDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjEstiloDTO, 'IdEstilo', 'Nome');
  }
}
?>