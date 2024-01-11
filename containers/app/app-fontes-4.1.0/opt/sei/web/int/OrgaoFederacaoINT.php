<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/12/2019 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class OrgaoFederacaoINT extends InfraINT {

  public static function montarSelectSigla($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objOrgaoFederacaoDTO = new OrgaoFederacaoDTO();
    $objOrgaoFederacaoDTO->retStrIdOrgaoFederacao();
    $objOrgaoFederacaoDTO->retStrSigla();
    $objOrgaoFederacaoDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objOrgaoFederacaoRN = new OrgaoFederacaoRN();
    $arrObjOrgaoFederacaoDTO = $objOrgaoFederacaoRN->listar($objOrgaoFederacaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjOrgaoFederacaoDTO, 'IdOrgaoFederacao', 'Sigla');
  }

  public static function formatarIdentificacao($strSiglaOrgao, $strDescricaoOrgao, $strSiglaInstalacao){
    return $strSiglaOrgao.' - '.$strDescricaoOrgao.' ('.$strSiglaInstalacao.')';
  }
}
