<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/05/2019 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class AtributoInstalacaoINT extends InfraINT {

  public static function montarSelectIdAtributoInstalacao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdAndamentoInstalacao=''){
    $objAtributoInstalacaoDTO = new AtributoInstalacaoDTO();
    $objAtributoInstalacaoDTO->retNumIdAtributoInstalacao();
    $objAtributoInstalacaoDTO->retNumIdAtributoInstalacao();

    if ($numIdAndamentoInstalacao!==''){
      $objAtributoInstalacaoDTO->setNumIdAndamentoInstalacao($numIdAndamentoInstalacao);
    }

    $objAtributoInstalacaoDTO->setOrdNumIdAtributoInstalacao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objAtributoInstalacaoRN = new AtributoInstalacaoRN();
    $arrObjAtributoInstalacaoDTO = $objAtributoInstalacaoRN->listar($objAtributoInstalacaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjAtributoInstalacaoDTO, 'IdAtributoInstalacao', 'IdAtributoInstalacao');
  }
}
