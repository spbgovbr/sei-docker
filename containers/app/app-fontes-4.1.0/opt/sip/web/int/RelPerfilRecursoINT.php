<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/01/2007 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class RelPerfilRecursoINT extends InfraINT {

  public static function montarSelectNomeRecurso(
    $strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdPerfil = '', $numIdRecurso = '', $numIdSistema = '') {
    $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO(true);

    if ($numIdPerfil !== '') {
      $objRelPerfilRecursoDTO->setNumIdPerfil($numIdPerfil);
    }

    if ($numIdRecurso !== '') {
      $objRelPerfilRecursoDTO->setNumIdRecurso($numIdRecurso);
    }

    if ($numIdSistema !== '') {
      $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);
    }

    $objRelPerfilRecursoDTO->retNumIdRecurso();
    $objRelPerfilRecursoDTO->retStrNomeRecurso();
    $objRelPerfilRecursoDTO->setOrdStrNomeRecurso(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
    $arrObjRelPerfilRecursoDTO = $objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjRelPerfilRecursoDTO, 'IdRecurso', 'NomeRecurso');
  }


  public static function montarSelectPerfisRecurso($numIdSistema, $numIdRecurso) {
    $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
    $objRelPerfilRecursoDTO->setDistinct(true);
    $objRelPerfilRecursoDTO->setNumIdRecurso($numIdRecurso);
    $objRelPerfilRecursoDTO->setNumIdSistema($numIdSistema);

    $objRelPerfilRecursoDTO->retNumIdPerfil();
    $objRelPerfilRecursoDTO->retStrNomePerfil();

    $objRelPerfilRecursoDTO->setOrdStrNomePerfil(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
    $arrObjRelPerfilRecursoDTO = $objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO);

    return parent::montarSelectArrInfraDTO(null, null, null, $arrObjRelPerfilRecursoDTO, 'IdPerfil', 'NomePerfil');
  }

}

?>