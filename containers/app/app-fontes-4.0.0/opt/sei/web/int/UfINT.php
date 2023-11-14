<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2007 - criado por mga
*
* Versão do Gerador de Código: 1.12.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class UfINT extends InfraINT {

  public static function montarSelectSiglaSigla($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdPais=ID_BRASIL){
    $objUfDTO = new UfDTO();
    $objUfDTO->retStrSigla();
    if ($numIdPais!='') {
      $objUfDTO->setNumIdPais($numIdPais);
    }
    $objUfDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);


    $objUfRN = new UfRN();
    $arrObjUfDTO = $objUfRN->listarRN0401($objUfDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjUfDTO, 'Sigla', 'Sigla');
  }

  public static function montarSelectSiglaNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdPais=ID_BRASIL){
    $objUfDTO = new UfDTO();
    $objUfDTO->retNumIdUf();
    $objUfDTO->retStrSigla();
    $objUfDTO->retStrNome();
    $objUfDTO->setNumIdPais($numIdPais);
    $objUfDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objUfRN = new UfRN();
    $arrObjUfDTO = $objUfRN->listarRN0401($objUfDTO);

    foreach($arrObjUfDTO as $objUfDTO){
      if(!InfraString::isBolVazia($objUfDTO->getStrSigla() )) {
        $objUfDTO->setStrSigla($objUfDTO->getStrSigla() . ' - ' . $objUfDTO->getStrNome());
      }else{
        $objUfDTO->setStrSigla($objUfDTO->getStrNome());
      }
    }
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjUfDTO, 'IdUf', 'Sigla');
  }

  public static function montarSelectSiglaRI0416($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdPais=ID_BRASIL){
    $objUfDTO = new UfDTO();
    $objUfDTO->retNumIdUf();
    $objUfDTO->retStrSigla();
    $objUfDTO->setNumIdPais($numIdPais);
    $objUfDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objUfRN = new UfRN();
    $arrObjUfDTO = $objUfRN->listarRN0401($objUfDTO);
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjUfDTO, 'IdUf', 'Sigla');
  }
}
?>