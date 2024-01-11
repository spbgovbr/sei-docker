<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/11/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class CpadINT extends InfraINT {

  public static function montarSelectSigla($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdOrgao=''){
    $objCpadDTO = new CpadDTO();
    $objCpadDTO->retNumIdCpad();
    $objCpadDTO->retStrSigla();

    if ($numIdOrgao!==''){
      $objCpadDTO->setNumIdOrgao($numIdOrgao);
    }

    if ($strValorItemSelecionado!=null){
      $objCpadDTO->setBolExclusaoLogica(false);
      $objCpadDTO->adicionarCriterio(array('SinAtivo','IdCpad'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objCpadDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objCpadRN = new CpadRN();
    $arrObjCpadDTO = $objCpadRN->listar($objCpadDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjCpadDTO, 'IdCpad', 'Sigla');
  }
}
