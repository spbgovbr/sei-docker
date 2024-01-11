<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/06/2021 - criado por mgb29
*
* Versão do Gerador de Código: 1.43.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelAvisoOrgaoINT extends InfraINT {

  public static function montarSelectOrgao($numIdAviso){

    $objRelAvisoOrgaoDTO = new RelAvisoOrgaoDTO();
    $objRelAvisoOrgaoDTO->retNumIdOrgao();
    $objRelAvisoOrgaoDTO->retStrSiglaOrgao();
    $objRelAvisoOrgaoDTO->setNumIdAviso($numIdAviso);
    $objRelAvisoOrgaoDTO->setOrdStrSiglaOrgao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelAvisoOrgaoRN = new RelAvisoOrgaoRN();
    $arrObjRelAvisoOrgaoDTO = $objRelAvisoOrgaoRN->listar($objRelAvisoOrgaoDTO);

    return parent::montarSelectArrInfraDTO(null,null,null,$arrObjRelAvisoOrgaoDTO, 'IdOrgao', 'SiglaOrgao');

  }
}
