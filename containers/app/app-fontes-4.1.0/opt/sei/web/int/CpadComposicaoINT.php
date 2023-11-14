<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/11/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class CpadComposicaoINT extends InfraINT {

  public static function montarSelectIdUsuario($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdCpadVersao='', $numIdUsuario='', $numIdCargo=''){
    $objCpadComposicaoDTO = new CpadComposicaoDTO();
    $objCpadComposicaoDTO->retNumIdCpadComposicao();
    $objCpadComposicaoDTO->retNumIdUsuario();

    if ($numIdCpadVersao!==''){
      $objCpadComposicaoDTO->setNumIdCpadVersao($numIdCpadVersao);
    }

    if ($numIdUsuario!==''){
      $objCpadComposicaoDTO->setNumIdUsuario($numIdUsuario);
    }

    if ($numIdCargo!==''){
      $objCpadComposicaoDTO->setNumIdCargo($numIdCargo);
    }

    $objCpadComposicaoDTO->setOrdNumIdUsuario(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objCpadComposicaoRN = new CpadComposicaoRN();
    $arrObjCpadComposicaoDTO = $objCpadComposicaoRN->listar($objCpadComposicaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjCpadComposicaoDTO, 'IdCpadComposicao', 'IdUsuario');
  }
}
