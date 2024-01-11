<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/11/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class CpadAvaliacaoINT extends InfraINT {

  public static function montarSelectIdCpadAvaliacao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdCpadComposicao=''){
    $objCpadAvaliacaoDTO = new CpadAvaliacaoDTO();
    $objCpadAvaliacaoDTO->retNumIdCpadAvaliacao();
    $objCpadAvaliacaoDTO->retNumIdCpadAvaliacao();

    if ($numIdCpadComposicao!==''){
      $objCpadAvaliacaoDTO->setNumIdCpadComposicao($numIdCpadComposicao);
    }

    $objCpadAvaliacaoDTO->setOrdNumIdCpadAvaliacao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objCpadAvaliacaoRN = new CpadAvaliacaoRN();
    $arrObjCpadAvaliacaoDTO = $objCpadAvaliacaoRN->listar($objCpadAvaliacaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjCpadAvaliacaoDTO, 'IdCpadAvaliacao', 'IdCpadAvaliacao');
  }

  public static function montarSelectStaCpadAvaliacao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objCpadAvaliacaoRN = new CpadAvaliacaoRN();

    $arrObjCpadAvaliacaoCpadAvaliacaoDTO = $objCpadAvaliacaoRN->listarValoresCpadAvaliacao();

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjCpadAvaliacaoCpadAvaliacaoDTO, 'StaCpadAvaliacao', 'Descricao');

  }
}
