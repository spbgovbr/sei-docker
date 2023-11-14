<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class CoordenadorPerfilINT extends InfraINT {

  public static function montarSelectIdPerfil(
    $strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdPerfil = '', $numIdUsuario = '', $numIdSistema = '') {
    $objCoordenadorPerfilDTO = new CoordenadorPerfilDTO();

    if ($numIdPerfil !== '') {
      $objCoordenadorPerfilDTO->setNumIdPerfil($numIdPerfil);
    }

    if ($numIdUsuario !== '') {
      $objCoordenadorPerfilDTO->setNumIdUsuario($numIdUsuario);
    }

    if ($numIdSistema !== '') {
      $objCoordenadorPerfilDTO->setNumIdSistema($numIdSistema);
    }

    $objCoordenadorPerfilDTO->retNumIdPerfil();
    $objCoordenadorPerfilDTO->setOrdNumIdPerfil(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objCoordenadorPerfilRN = new CoordenadorPerfilRN();
    $arrObjCoordenadorPerfilDTO = $objCoordenadorPerfilRN->listar($objCoordenadorPerfilDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjCoordenadorPerfilDTO, '', 'IdPerfil');
  }
}

?>