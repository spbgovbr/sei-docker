<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/12/2007 - criado por fbv
*
* Versão do Gerador de Código: 1.10.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class CargoINT extends InfraINT {

  public static function montarSelectExpressaoRI0468($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objCargoDTO = new CargoDTO();
    $objCargoDTO->retNumIdCargo();
    $objCargoDTO->retStrExpressao();
    $objCargoDTO->setOrdStrExpressao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objCargoRN = new CargoRN();
    $arrObjCargoDTO = $objCargoRN->listarRN0302($objCargoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjCargoDTO, 'IdCargo', 'Expressao');
  }
  public static function autoCompletarExpressao($strPalavrasPesquisa){

    $objCargoDTO = new CargoDTO();
    $objCargoDTO->retNumIdCargo();
    $objCargoDTO->retStrExpressao();
    $objCargoDTO->setOrdStrExpressao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objCargoDTO->setStrExpressao('%'.$strPalavrasPesquisa.'%',InfraDTO::$OPER_LIKE);
    $objCargoDTO->setNumMaxRegistrosRetorno(50);

    $objCargoRN = new CargoRN();
    $arrObjCargoDTO = $objCargoRN->listarRN0302($objCargoDTO);

    return $arrObjCargoDTO;
  }
  public static function montarSelectExpressaoAssinatura($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdUnidade){
    
    
    if ($numIdUnidade!=''){
      $objAssinanteDTO = new AssinanteDTO();
      $objAssinanteDTO->retNumIdCargo();
      $objAssinanteDTO->setNumIdUnidade($numIdUnidade);
      $objAssinanteRN = new AssinanteRN();
      $arrObjAssinanteDTO = $objAssinanteRN->listarRN1339($objAssinanteDTO);
    }else{
      $arrObjAssinanteDTO = array();
    }
    
    
    $objCargoDTO = new CargoDTO();
    $objCargoDTO->retNumIdCargo();
    $objCargoDTO->retStrExpressao();
    
    if (count($arrObjAssinanteDTO)>0){
      $objCargoDTO->setNumIdCargo(InfraArray::converterArrInfraDTO($arrObjAssinanteDTO,'IdCargo'),InfraDTO::$OPER_NOT_IN);  
    }
    
    $objCargoDTO->setOrdStrExpressao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objCargoRN = new CargoRN();
    $arrObjCargoDTO = $objCargoRN->listarRN0302($objCargoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjCargoDTO, 'IdCargo', 'Expressao');
  }

  public static function montarSelectGenero($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $strStaGenero){

    $objCargoDTO = new CargoDTO();
    $objCargoDTO->retNumIdCargo();
    $objCargoDTO->retStrExpressao();
    if ($strStaGenero!=''){
      $objCargoDTO->adicionarCriterio(array('StaGenero','StaGenero'),
                                      array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL),
                                      array(null, $strStaGenero),
                                      InfraDTO::$OPER_LOGICO_OR);
    }
    $objCargoDTO->setOrdStrExpressao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objCargoRN = new CargoRN();
    $arrObjCargoDTO = $objCargoRN->listarRN0302($objCargoDTO);
    $r = parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjCargoDTO, 'IdCargo', 'Expressao');

    return $r;
  }






}
?>