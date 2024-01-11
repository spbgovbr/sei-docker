<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class TipoPermissaoINT extends InfraINT {

  public static function montarSelectDescricao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objTipoPermissaoDTO = new TipoPermissaoDTO();
    $objTipoPermissaoDTO->retNumIdTipoPermissao();

    $objTipoPermissaoDTO->retStrDescricao();
    $objTipoPermissaoDTO->setOrdStrDescricao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objTipoPermissaoRN = new TipoPermissaoRN();
    $arrObjTipoPermissaoDTO = $objTipoPermissaoRN->listar($objTipoPermissaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado,$arrObjTipoPermissaoDTO, 'IdTipoPermissao', 'Descricao');
  }
}
?>