<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/11/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class UsuarioINT extends InfraINT {

  public static function montarSelectSigla(
    $strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdOrgao = '') {
    $objUsuarioDTO = new UsuarioDTO();
    $objUsuarioDTO->retNumIdUsuario();

    if ($numIdOrgao !== '') {
      $objUsuarioDTO->setNumIdOrgao($numIdOrgao);
    }

    $objUsuarioDTO->retStrSigla();
    $objUsuarioDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objUsuarioRN = new UsuarioRN();
    $arrObjUsuarioDTO = $objUsuarioRN->listar($objUsuarioDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjUsuarioDTO, 'IdUsuario', 'Sigla');
  }

  public static function autoCompletarSiglaNome($strSigla, $numIdOrgao) {
    if ($strSigla == '') {
      return null;
    }
    $objUsuarioDTO = new UsuarioDTO();
    $objUsuarioDTO->retNumIdUsuario();
    $objUsuarioDTO->retStrSigla();
    $objUsuarioDTO->retStrNome();

    if ($numIdOrgao != '') {
      $objUsuarioDTO->setNumIdOrgao($numIdOrgao);
    }

    $objUsuarioDTO->adicionarCriterio(array('Sigla', 'Nome'), array(InfraDTO::$OPER_LIKE, InfraDTO::$OPER_LIKE), array('%' . $strSigla . '%', '%' . $strSigla . '%'), InfraDTO::$OPER_LOGICO_OR);

    $objUsuarioDTO->setNumMaxRegistrosRetorno(50);

    $objUsuarioDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);
    $objUsuarioRN = new UsuarioRN();
    return $objUsuarioRN->listar($objUsuarioDTO);
  }

  public static function autoCompletar($strSigla, $numIdOrgao) {
    if ($strSigla == '') {
      return null;
    }

    $objUsuarioDTO = new UsuarioDTO();
    $objUsuarioDTO->retNumIdUsuario();
    $objUsuarioDTO->retStrSigla();
    $objUsuarioDTO->retStrNome();
    $objUsuarioDTO->setNumIdOrgao($numIdOrgao);

    $objUsuarioDTO->adicionarCriterio(array('Sigla', 'Nome'), array(InfraDTO::$OPER_LIKE, InfraDTO::$OPER_LIKE), array('%' . $strSigla . '%', '%' . $strSigla . '%'), InfraDTO::$OPER_LOGICO_OR);

    $objUsuarioDTO->setNumMaxRegistrosRetorno(50);

    $objUsuarioDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);


    $objUsuarioRN = new UsuarioRN();
    $arrObjUsuarioDTO = $objUsuarioRN->listar($objUsuarioDTO);

    foreach ($arrObjUsuarioDTO as $objUsuarioDTO) {
      $objUsuarioDTO->setStrSigla(self::formatarSiglaNome($objUsuarioDTO->getStrSigla(), $objUsuarioDTO->getStrNome()));
    }
    return $arrObjUsuarioDTO;
  }

  public static function formatarSiglaNome($strSigla, $strNome) {
    return $strNome . ' (' . $strSigla . ')';
  }

  public static function formatarNomeSocial($strNome, $strNomeSocial) {
    if ($strNomeSocial == null) {
      return $strNome;
    } else {
      return $strNomeSocial . ' registrado(a) civilmente como ' . $strNome;
    }
  }

  public static function montarSelectSituacao(
    $strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado) {
    $arrObjInfraValorStaDTO = UsuarioRN::listarValoresSituacao();
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjInfraValorStaDTO, 'StaValor', 'Descricao');
  }
}

?>