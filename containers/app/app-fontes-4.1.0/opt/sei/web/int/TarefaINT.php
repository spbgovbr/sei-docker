<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/07/2013 - criado por bcu
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class TarefaINT extends InfraINT {

  public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objTarefaDTO = new TarefaDTO();
    $objTarefaDTO->retNumIdTarefa();
    $objTarefaDTO->retStrNome();

    $objTarefaDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objTarefaRN = new TarefaRN();
    $arrObjTarefaDTO = $objTarefaRN->listar($objTarefaDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTarefaDTO, 'IdTarefa', 'Nome');
  }
}
?>