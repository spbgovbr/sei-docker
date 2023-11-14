<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/09/2014 - criado por bcu
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoUnidadeINT extends InfraINT {

  public static function montarSelectNomeUnidade($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objGrupoUnidadeDTO = new GrupoUnidadeDTO();
    $objGrupoUnidadeDTO->retNumIdGrupoUnidade();
    $objGrupoUnidadeDTO->retStrNome();
    $objGrupoUnidadeDTO->setStrStaTipo(GrupoUnidadeRN::$TGU_UNIDADE);
    $objGrupoUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

    if ($strValorItemSelecionado!=null){
      $objGrupoUnidadeDTO->setBolExclusaoLogica(false);
      $objGrupoUnidadeDTO->adicionarCriterio(array('SinAtivo','IdGrupoUnidade'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objGrupoUnidadeDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objGrupoUnidadeRN = new GrupoUnidadeRN();
    $arrObjGrupoUnidadeDTO = $objGrupoUnidadeRN->listar($objGrupoUnidadeDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjGrupoUnidadeDTO, 'IdGrupoUnidade', 'Nome');
  }

  public static function montarSelectNomeInstitucional($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdUnidade=''){
    $objGrupoUnidadeDTO = new GrupoUnidadeDTO();
    $objGrupoUnidadeDTO->retNumIdGrupoUnidade();
    $objGrupoUnidadeDTO->retStrNome();
    $objGrupoUnidadeDTO->setStrStaTipo(GrupoUnidadeRN::$TGU_INSTITUCIONAL);

    if ($numIdUnidade!==''){
      $objGrupoUnidadeDTO->setNumIdUnidade($numIdUnidade);
    }

    if ($strValorItemSelecionado!=null){
      $objGrupoUnidadeDTO->setBolExclusaoLogica(false);
      $objGrupoUnidadeDTO->adicionarCriterio(array('SinAtivo','IdGrupoUnidade'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objGrupoUnidadeDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objGrupoUnidadeRN = new GrupoUnidadeRN();
    $arrObjGrupoUnidadeDTO = $objGrupoUnidadeRN->listar($objGrupoUnidadeDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjGrupoUnidadeDTO, 'IdGrupoUnidade', 'Nome');
  }

  public static function montarSelectStaTipo($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objGrupoUnidadeRN = new GrupoUnidadeRN();

    $arrObjTipoGrupoUnidadeDTO = $objGrupoUnidadeRN->listarValoresTipo();

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTipoGrupoUnidadeDTO, 'StaTipo', 'Descricao');

  }
}
?>