<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class EditalEliminacaoConteudoINT extends InfraINT {

  public static function montarSelectIdEditalEliminacaoConteudo($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdAvaliacaoDocumental='', $numIdEditalEliminacao='', $numIdUsuarioInclusao=''){
    $objEditalEliminacaoConteudoDTO = new EditalEliminacaoConteudoDTO();
    $objEditalEliminacaoConteudoDTO->retNumIdEditalEliminacaoConteudo();
    $objEditalEliminacaoConteudoDTO->retNumIdEditalEliminacaoConteudo();

    if ($numIdAvaliacaoDocumental!==''){
      $objEditalEliminacaoConteudoDTO->setNumIdAvaliacaoDocumental($numIdAvaliacaoDocumental);
    }

    if ($numIdEditalEliminacao!==''){
      $objEditalEliminacaoConteudoDTO->setNumIdEditalEliminacao($numIdEditalEliminacao);
    }

    if ($numIdUsuarioInclusao!==''){
      $objEditalEliminacaoConteudoDTO->setNumIdUsuarioInclusao($numIdUsuarioInclusao);
    }

    $objEditalEliminacaoConteudoDTO->setOrdNumIdEditalEliminacaoConteudo(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objEditalEliminacaoConteudoRN = new EditalEliminacaoConteudoRN();
    $arrObjEditalEliminacaoConteudoDTO = $objEditalEliminacaoConteudoRN->listar($objEditalEliminacaoConteudoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjEditalEliminacaoConteudoDTO, 'IdEditalEliminacaoConteudo', 'IdEditalEliminacaoConteudo');
  }
}
