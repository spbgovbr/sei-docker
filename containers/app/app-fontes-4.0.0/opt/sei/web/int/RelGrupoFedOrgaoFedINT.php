<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/12/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelGrupoFedOrgaoFedINT extends InfraINT {

  public static function montarSelectGrupo($numIdGrupoFederacao){

    $objRelGrupoFedOrgaoFedDTO = new RelGrupoFedOrgaoFedDTO();
    $objRelGrupoFedOrgaoFedDTO->retStrIdOrgaoFederacao();
    $objRelGrupoFedOrgaoFedDTO->retStrSiglaOrgaoFederacao();
    $objRelGrupoFedOrgaoFedDTO->retStrDescricaoOrgaoFederacao();
    $objRelGrupoFedOrgaoFedDTO->retStrSiglaInstalacaoFederacao();
    $objRelGrupoFedOrgaoFedDTO->setNumIdGrupoFederacao($numIdGrupoFederacao);
    $objRelGrupoFedOrgaoFedDTO->setOrdStrSiglaOrgaoFederacao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelGrupoFedOrgaoFedRN = new RelGrupoFedOrgaoFedRN();
    $arrObjRelGrupoFedOrgaoFedDTO = $objRelGrupoFedOrgaoFedRN->listar($objRelGrupoFedOrgaoFedDTO);

    foreach($arrObjRelGrupoFedOrgaoFedDTO as $objRelGrupoFedOrgaoFedDTO){
      $objRelGrupoFedOrgaoFedDTO->setStrSiglaOrgaoFederacao(OrgaoFederacaoINT::formatarIdentificacao($objRelGrupoFedOrgaoFedDTO->getStrSiglaOrgaoFederacao(),$objRelGrupoFedOrgaoFedDTO->getStrDescricaoOrgaoFederacao(),$objRelGrupoFedOrgaoFedDTO->getStrSiglaInstalacaoFederacao()));
    }

    return parent::montarSelectArrInfraDTO(null, null, null, $arrObjRelGrupoFedOrgaoFedDTO, 'IdOrgaoFederacao', 'SiglaOrgaoFederacao');
  }
}
