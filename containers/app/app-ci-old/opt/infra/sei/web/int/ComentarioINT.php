<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/10/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class ComentarioINT extends InfraINT {

  public static function montarSelectIdComentario($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $dblIdProtocolo='', $numIdUnidade='', $numIdUsuario=''){
    $objComentarioDTO = new ComentarioDTO();
    $objComentarioDTO->retNumIdComentario();
    $objComentarioDTO->retNumIdComentario();

    if ($dblIdProtocolo!==''){
      $objComentarioDTO->setDblIdProtocolo($dblIdProtocolo);
    }

    if ($numIdUnidade!==''){
      $objComentarioDTO->setNumIdUnidade($numIdUnidade);
    }

    if ($numIdUsuario!==''){
      $objComentarioDTO->setNumIdUsuario($numIdUsuario);
    }

    $objComentarioDTO->setOrdNumIdComentario(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objComentarioRN = new ComentarioRN();
    $arrObjComentarioDTO = $objComentarioRN->listar($objComentarioDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjComentarioDTO, 'IdComentario', 'IdComentario');
  }
}
