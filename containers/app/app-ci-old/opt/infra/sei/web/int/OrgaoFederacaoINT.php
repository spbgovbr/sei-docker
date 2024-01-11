<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/12/2019 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class OrgaoFederacaoINT extends InfraINT {

  public static function formatarIdentificacao($strSiglaOrgao, $strDescricaoOrgao, $strSiglaInstalacao){
    return $strSiglaOrgao.' - '.$strDescricaoOrgao.' ('.$strSiglaInstalacao.')';
  }
}
