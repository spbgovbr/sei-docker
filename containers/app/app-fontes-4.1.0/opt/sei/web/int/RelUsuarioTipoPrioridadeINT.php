<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/04/2023 - criado por cas84
*
* Versão do Gerador de Código: 1.43.2
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelUsuarioTipoPrioridadeINT extends InfraINT {

  public static function montarSelectIdTipoPrioridade($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdUnidade='', $numIdUsuario='', $numIdTipoPrioridade=''){
    $objRelUsuarioTipoPrioridadeDTO = new RelUsuarioTipoPrioridadeDTO();
    $objRelUsuarioTipoPrioridadeDTO->retNumIdUnidade();
    $objRelUsuarioTipoPrioridadeDTO->retNumIdUsuario();
    $objRelUsuarioTipoPrioridadeDTO->retNumIdTipoPrioridade();

    if ($numIdUnidade!==''){
      $objRelUsuarioTipoPrioridadeDTO->setNumIdUnidade($numIdUnidade);
    }

    if ($numIdUsuario!==''){
      $objRelUsuarioTipoPrioridadeDTO->setNumIdUsuario($numIdUsuario);
    }

    if ($numIdTipoPrioridade!==''){
      $objRelUsuarioTipoPrioridadeDTO->setNumIdTipoPrioridade($numIdTipoPrioridade);
    }

    $objRelUsuarioTipoPrioridadeDTO->setOrdNumIdTipoPrioridade(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelUsuarioTipoPrioridadeRN = new RelUsuarioTipoPrioridadeRN();
    $arrObjRelUsuarioTipoPrioridadeDTO = $objRelUsuarioTipoPrioridadeRN->listar($objRelUsuarioTipoPrioridadeDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjRelUsuarioTipoPrioridadeDTO, array('IdUnidade','IdUsuario','IdTipoPrioridade'), 'IdTipoPrioridade');
  }
}
