<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class RecursoINT extends InfraINT {

  public static function montarSelectDescricao(
    $strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdSistema = '') {
    $objRecursoDTO = new RecursoDTO();
    $objRecursoDTO->retNumIdRecurso();

    if ($numIdSistema !== '') {
      $objRecursoDTO->setNumIdSistema($numIdSistema);
    }

    $objRecursoDTO->retStrDescricao();
    $objRecursoDTO->setOrdStrDescricao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRecursoRN = new RecursoRN();
    $arrObjRecursoDTO = $objRecursoRN->listar($objRecursoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjRecursoDTO, 'IdRecurso', 'Descricao');
  }

  public static function montarSelectNome(
    $strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdSistema = '') {
    $objRecursoDTO = new RecursoDTO();
    $objRecursoDTO->retNumIdRecurso();
    $objRecursoDTO->retStrNome();

    if ($numIdSistema !== '') {
      $objRecursoDTO->setNumIdSistema($numIdSistema);
    }

    $objRecursoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRecursoRN = new RecursoRN();
    $arrObjRecursoDTO = $objRecursoRN->listar($objRecursoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjRecursoDTO, 'IdRecurso', 'Nome');
  }

  public static function autoCompletarNome($strNome, $numIdSistema) {
    if ($strNome == '') {
      return null;
    }
    $objRecursoDTO = new RecursoDTO();
    $objRecursoDTO->retNumIdRecurso();
    $objRecursoDTO->retStrNome();
    $objRecursoDTO->setStrNome($strNome . '%', InfraDTO::$OPER_LIKE);
    $objRecursoDTO->setNumIdSistema($numIdSistema);
    $objRecursoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
    $objRecursoRN = new RecursoRN();
    return $objRecursoRN->listar($objRecursoDTO);
  }


}

?>