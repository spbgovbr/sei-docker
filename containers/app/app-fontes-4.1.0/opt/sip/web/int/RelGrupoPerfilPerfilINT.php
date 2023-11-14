<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 15/07/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../Sip.php';

class RelGrupoPerfilPerfilINT extends InfraINT {

  public static function montarSelectGrupoPerfil($numIdSistema, $numIdPerfil) {
    $objRelGrupoPerfilPerfilDTO = new RelGrupoPerfilPerfilDTO();
    $objRelGrupoPerfilPerfilDTO->retNumIdSistema();
    $objRelGrupoPerfilPerfilDTO->retNumIdGrupoPerfil();
    $objRelGrupoPerfilPerfilDTO->retStrNomeGrupoPerfil();
    $objRelGrupoPerfilPerfilDTO->setStrSinAtivoGrupoPerfil('S');


    $objRelGrupoPerfilPerfilDTO->setNumIdSistema($numIdSistema);
    $objRelGrupoPerfilPerfilDTO->setNumIdPerfil($numIdPerfil);

    $objRelGrupoPerfilPerfilDTO->setOrdStrNomeGrupoPerfil(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelGrupoPerfilPerfilRN = new RelGrupoPerfilPerfilRN();
    $arrObjRelGrupoPerfilPerfilDTO = $objRelGrupoPerfilPerfilRN->listar($objRelGrupoPerfilPerfilDTO);

    foreach ($arrObjRelGrupoPerfilPerfilDTO as $objRelGrupoPerfilPerfilDTO) {
      $objRelGrupoPerfilPerfilDTO->setNumIdGrupoPerfil($objRelGrupoPerfilPerfilDTO->getNumIdGrupoPerfil() . '-' . $objRelGrupoPerfilPerfilDTO->getNumIdSistema());
    }

    return parent::montarSelectArrInfraDTO(null, null, null, $arrObjRelGrupoPerfilPerfilDTO, 'IdGrupoPerfil', 'NomeGrupoPerfil');
  }
}
