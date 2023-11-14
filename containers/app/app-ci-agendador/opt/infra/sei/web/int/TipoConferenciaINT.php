<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/11/2013 - criado por mga
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class TipoConferenciaINT extends InfraINT {

  public static function montarSelectDescricao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objTipoConferenciaDTO = new TipoConferenciaDTO();
    $objTipoConferenciaDTO->retNumIdTipoConferencia();
    $objTipoConferenciaDTO->retStrDescricao();

    if ($strValorItemSelecionado!=null){
      $objTipoConferenciaDTO->setBolExclusaoLogica(false);
      $objTipoConferenciaDTO->adicionarCriterio(array('SinAtivo','IdTipoConferencia'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objTipoConferenciaDTO->setOrdStrDescricao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objTipoConferenciaRN = new TipoConferenciaRN();
    $arrObjTipoConferenciaDTO = $objTipoConferenciaRN->listar($objTipoConferenciaDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTipoConferenciaDTO, 'IdTipoConferencia', 'Descricao');
  }
}
?>