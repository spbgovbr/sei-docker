<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 15/07/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../Sip.php';

class GrupoPerfilINT extends InfraINT {

  public static function montarSelectNome(
    $strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdSistema = '') {
    $objGrupoPerfilDTO = new GrupoPerfilDTO();
    $objGrupoPerfilDTO->retNumIdGrupoPerfil();
    $objGrupoPerfilDTO->retStrNome();

    if ($numIdSistema !== '') {
      $objGrupoPerfilDTO->setNumIdSistema($numIdSistema);
    }

    if ($strValorItemSelecionado != null) {
      $objGrupoPerfilDTO->setBolExclusaoLogica(false);
      $objGrupoPerfilDTO->adicionarCriterio(array('SinAtivo', 'IdGrupoPerfil'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array('S', $strValorItemSelecionado), InfraDTO::$OPER_LOGICO_OR);
    }

    $objGrupoPerfilDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objGrupoPerfilRN = new GrupoPerfilRN();
    $arrObjGrupoPerfilDTO = $objGrupoPerfilRN->listar($objGrupoPerfilDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjGrupoPerfilDTO, 'IdGrupoPerfil', 'Nome');
  }
}
