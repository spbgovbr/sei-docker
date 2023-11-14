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

class CidadeINT extends InfraINT {

  public static function montarSelectIdCidadeNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdUf='', $numIdPais=ID_BRASIL){
    $objCidadeDTO = new CidadeDTO();
    $objCidadeDTO->retNumIdCidade();
    $objCidadeDTO->retStrNome();
    $objCidadeDTO->retStrSinCapital();
    $objCidadeDTO->setNumIdPais($numIdPais);

    if ($numIdPais==ID_BRASIL) {
      $objCidadeDTO->setNumIdUf($numIdUf);
      $objCidadeDTO->setNumTipoFkUf(InfraDTO::$TIPO_FK_OBRIGATORIA);
    }else{
      if ($numIdUf!='' && $numIdUf!='null') {
        $objCidadeDTO->setNumIdUf($numIdUf);
      }
    }

    $objCidadeDTO->setOrdStrSinCapital(InfraDTO::$TIPO_ORDENACAO_DESC);
    $objCidadeDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objCidadeRN = new CidadeRN();
    $arrObjCidadeDTO = $objCidadeRN->listarRN0410($objCidadeDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjCidadeDTO, 'IdCidade', 'Nome');
  }

  public static function montarSelectNomeNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $strSiglaUf, $numIdPais=ID_BRASIL){
    $objCidadeDTO = new CidadeDTO();
    //$objCidadeDTO->retNumIdCidade();
    $objCidadeDTO->retStrNome();
    $objCidadeDTO->setNumIdPais($numIdPais);

    if ($numIdPais==ID_BRASIL) {
      $objCidadeDTO->setNumTipoFkUf(InfraDTO::$TIPO_FK_OBRIGATORIA);
    }
    
    $objCidadeDTO->setOrdStrSinCapital(InfraDTO::$TIPO_ORDENACAO_DESC);
    $objCidadeDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    if ($strSiglaUf!==''){
      $objCidadeDTO->setStrSiglaUf($strSiglaUf);
    }

    $objCidadeRN = new CidadeRN();    
    $arrObjCidadeDTO = $objCidadeRN->listarRN0410($objCidadeDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjCidadeDTO, 'Nome', 'Nome');
  }
}
?>