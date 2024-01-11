<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class RelSistemaUnidadeUsuarioINT extends InfraINT {

  public static function montarSelectIdSistema($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objRelSistemaUnidadeUsuarioDTO = new RelSistemaUnidadeUsuarioDTO();
    $objRelSistemaUnidadeUsuarioDTO->retNumIdSistema();
    $objRelSistemaUnidadeUsuarioDTO->setOrdNumIdSistema(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelSistemaUnidadeUsuarioRN = new RelSistemaUnidadeUsuarioRN();
    $arrObjRelSistemaUnidadeUsuarioDTO = $objRelSistemaUnidadeUsuarioRN->listar($objRelSistemaUnidadeUsuarioDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado,$arrObjRelSistemaUnidadeUsuarioDTO, '', 'IdSistema');
  }
}
?>