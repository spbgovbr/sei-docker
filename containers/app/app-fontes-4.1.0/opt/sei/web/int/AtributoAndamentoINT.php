<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/11/2009 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AtributoAndamentoINT extends InfraINT {

  public static function montarSelectNomeRI1372($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdAtividade=''){
    $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
    $objAtributoAndamentoDTO->retNumIdAtributoAndamento();
    $objAtributoAndamentoDTO->retStrNome();

    if ($numIdAtividade!==''){
      $objAtributoAndamentoDTO->setNumIdAtividade($numIdAtividade);
    }

    $objAtributoAndamentoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objAtributoAndamentoRN = new AtributoAndamentoRN();
    $arrObjAtributoAndamentoDTO = $objAtributoAndamentoRN->listarRN1367($objAtributoAndamentoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjAtributoAndamentoDTO, 'IdAtributoAndamento', 'Nome');
  }
}
?>