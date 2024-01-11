<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class EditalEliminacaoINT extends InfraINT {

  public static function montarSelectIdEditalEliminacao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $dblIdProcedimento='', $dblIdDocumento=''){
    $objEditalEliminacaoDTO = new EditalEliminacaoDTO();
    $objEditalEliminacaoDTO->retNumIdEditalEliminacao();
    $objEditalEliminacaoDTO->retNumIdEditalEliminacao();

    if ($dblIdProcedimento!==''){
      $objEditalEliminacaoDTO->setDblIdProcedimento($dblIdProcedimento);
    }

    if ($dblIdDocumento!==''){
      $objEditalEliminacaoDTO->setDblIdDocumento($dblIdDocumento);
    }

    $objEditalEliminacaoDTO->setOrdNumIdEditalEliminacao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objEditalEliminacaoRN = new EditalEliminacaoRN();
    $arrObjEditalEliminacaoDTO = $objEditalEliminacaoRN->listar($objEditalEliminacaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjEditalEliminacaoDTO, 'IdEditalEliminacao', 'IdEditalEliminacao');
  }

  public static function montarSelectStaEditalEliminacao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objEditalEliminacaoRN = new EditalEliminacaoRN();

    $arrObjEditalEliminacaoEditalEliminacaoDTO = $objEditalEliminacaoRN->listarValoresEditalEliminacao();

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjEditalEliminacaoEditalEliminacaoDTO, 'StaEditalEliminacao', 'Descricao');

  }

  public static function montarSelectTipoProcedimento($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdEditalEliminacao){

    $objEditalEliminacaoConteudoDTO = new EditalEliminacaoConteudoDTO();
    $objEditalEliminacaoConteudoDTO->setDistinct(true);
    $objEditalEliminacaoConteudoDTO->retNumIdTipoProcedimentoProcedimento();
    $objEditalEliminacaoConteudoDTO->retStrNomeTipoProcedimento();
    $objEditalEliminacaoConteudoDTO->setNumIdEditalEliminacao($numIdEditalEliminacao);
    $objEditalEliminacaoConteudoDTO->setOrdStrNomeTipoProcedimento(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objEditalEliminacaoConteudoRN = new EditalEliminacaoConteudoRN();
    $arrObjEditalEliminacaoConteudoDTO = $objEditalEliminacaoConteudoRN->listar($objEditalEliminacaoConteudoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjEditalEliminacaoConteudoDTO, 'IdTipoProcedimentoProcedimento', 'NomeTipoProcedimento');
  }
}
