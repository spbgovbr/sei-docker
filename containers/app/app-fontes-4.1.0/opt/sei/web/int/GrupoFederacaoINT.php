<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/12/2019 - criado por bcu
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoFederacaoINT extends InfraINT {

  public static function montarSelectNomeUnidade($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objGrupoFederacaoDTO = new GrupoFederacaoDTO();
    $objGrupoFederacaoDTO->retNumIdGrupoFederacao();
    $objGrupoFederacaoDTO->retStrNome();
    $objGrupoFederacaoDTO->setStrStaTipo(GrupoFederacaoRN::$TGF_UNIDADE);
    $objGrupoFederacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

    if ($strValorItemSelecionado!=null){
      $objGrupoFederacaoDTO->setBolExclusaoLogica(false);
      $objGrupoFederacaoDTO->adicionarCriterio(array('SinAtivo','IdGrupoFederacao'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objGrupoFederacaoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objGrupoFederacaoRN = new GrupoFederacaoRN();
    $arrObjGrupoFederacaoDTO = $objGrupoFederacaoRN->listar($objGrupoFederacaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjGrupoFederacaoDTO, 'IdGrupoFederacao', 'Nome');
  }

  public static function montarSelectNomeInstitucional($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdUnidade=''){
    $objGrupoFederacaoDTO = new GrupoFederacaoDTO();
    $objGrupoFederacaoDTO->retNumIdGrupoFederacao();
    $objGrupoFederacaoDTO->retStrNome();
    $objGrupoFederacaoDTO->setStrStaTipo(GrupoFederacaoRN::$TGF_INSTITUCIONAL);

    if ($numIdUnidade!==''){
      $objGrupoFederacaoDTO->setNumIdUnidade($numIdUnidade);
    }

    if ($strValorItemSelecionado!=null){
      $objGrupoFederacaoDTO->setBolExclusaoLogica(false);
      $objGrupoFederacaoDTO->adicionarCriterio(array('SinAtivo','IdGrupoFederacao'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objGrupoFederacaoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objGrupoFederacaoRN = new GrupoFederacaoRN();
    $arrObjGrupoFederacaoDTO = $objGrupoFederacaoRN->listar($objGrupoFederacaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjGrupoFederacaoDTO, 'IdGrupoFederacao', 'Nome');
  }
}
?>