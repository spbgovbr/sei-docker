<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/10/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class AvaliacaoDocumentalINT extends InfraINT {

  public static function montarSelectIdProcedimento($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $dblIdProcedimento='', $numIdAssuntoProxy='', $numIdUsuario=''){
    $objAvaliacaoDocumentalDTO = new AvaliacaoDocumentalDTO();
    $objAvaliacaoDocumentalDTO->retDblIdProcedimento();
    $objAvaliacaoDocumentalDTO->retDblIdProcedimento();

    if ($dblIdProcedimento!==''){
      $objAvaliacaoDocumentalDTO->setDblIdProcedimento($dblIdProcedimento);
    }

    if ($numIdAssuntoProxy!==''){
      $objAvaliacaoDocumentalDTO->setNumIdAssuntoProxy($numIdAssuntoProxy);
    }

    if ($numIdUsuario!==''){
      $objAvaliacaoDocumentalDTO->setNumIdUsuario($numIdUsuario);
    }

    $objAvaliacaoDocumentalDTO->setOrdDblIdProcedimento(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objAvaliacaoDocumentalRN = new AvaliacaoDocumentalRN();
    $arrObjAvaliacaoDocumentalDTO = $objAvaliacaoDocumentalRN->listar($objAvaliacaoDocumentalDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjAvaliacaoDocumentalDTO, 'IdProcedimento', 'IdProcedimento');
  }

  public static function montarSelectStaAvaliacao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objAvaliacaoDocumentalRN = new AvaliacaoDocumentalRN();

    $arrObjAvaliacaoAvaliacaoDocumentalDTO = $objAvaliacaoDocumentalRN->listarValoresAvaliacao();

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjAvaliacaoAvaliacaoDocumentalDTO, 'StaAvaliacao', 'Descricao');

  }
}
