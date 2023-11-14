<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/03/2014 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelSerieAssuntoINT extends InfraINT {

  public static function conjuntoPorCodigo($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdSerie){
    
    $objRelSerieAssuntoDTO = new RelSerieAssuntoDTO();
    $objRelSerieAssuntoDTO->retNumIdAssunto();
    $objRelSerieAssuntoDTO->retStrCodigoEstruturadoAssunto();
    $objRelSerieAssuntoDTO->retStrDescricaoAssunto();
    $objRelSerieAssuntoDTO->setNumIdSerie($numIdSerie);
    $objRelSerieAssuntoDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelSerieAssuntoRN = new RelSerieAssuntoRN();
    $arrObjRelSerieAssuntoDTO = $objRelSerieAssuntoRN->listar($objRelSerieAssuntoDTO);

    foreach($arrObjRelSerieAssuntoDTO as $objRelSerieAssuntoDTO){
      $objRelSerieAssuntoDTO->setStrCodigoEstruturadoAssunto(AssuntoINT::formatarCodigoDescricaoRI0568($objRelSerieAssuntoDTO->getStrCodigoEstruturadoAssunto(),$objRelSerieAssuntoDTO->getStrDescricaoAssunto()));
    }
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjRelSerieAssuntoDTO, 'IdAssunto', 'CodigoEstruturadoAssunto');
  }
}
?>