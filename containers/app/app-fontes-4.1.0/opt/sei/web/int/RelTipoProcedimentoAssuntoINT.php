<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.12.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelTipoProcedimentoAssuntoINT extends InfraINT {

  public static function conjuntoPorCodigoRI0556($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdTipoProcedimento){

    $objRelTipoProcedimentoAssuntoDTO = new RelTipoProcedimentoAssuntoDTO();
    $objRelTipoProcedimentoAssuntoDTO->setDistinct(true);
    $objRelTipoProcedimentoAssuntoDTO->retNumIdAssunto();
    $objRelTipoProcedimentoAssuntoDTO->retNumSequencia();
    $objRelTipoProcedimentoAssuntoDTO->retStrCodigoEstruturadoAssunto();
    $objRelTipoProcedimentoAssuntoDTO->retStrDescricaoAssunto();
    $objRelTipoProcedimentoAssuntoDTO->setNumIdTipoProcedimento($numIdTipoProcedimento);
    $objRelTipoProcedimentoAssuntoDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelTipoProcedimentoAssuntoRN = new RelTipoProcedimentoAssuntoRN();
    $arrObjRelTipoProcedimentoAssuntoDTO = InfraArray::distinctArrInfraDTO($objRelTipoProcedimentoAssuntoRN->listarRN0192($objRelTipoProcedimentoAssuntoDTO),'IdAssunto');

    foreach($arrObjRelTipoProcedimentoAssuntoDTO as $objRelTipoProcedimentoAssuntoDTO){
      $objRelTipoProcedimentoAssuntoDTO->setStrCodigoEstruturadoAssunto(AssuntoINT::formatarCodigoDescricaoRI0568($objRelTipoProcedimentoAssuntoDTO->getStrCodigoEstruturadoAssunto(),$objRelTipoProcedimentoAssuntoDTO->getStrDescricaoAssunto()));
    }
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjRelTipoProcedimentoAssuntoDTO, 'IdAssunto', 'CodigoEstruturadoAssunto');
  }
}
?>