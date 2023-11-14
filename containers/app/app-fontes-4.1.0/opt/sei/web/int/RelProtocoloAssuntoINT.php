<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/02/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelProtocoloAssuntoINT extends InfraINT {

  public static function conjuntoPorCodigoDescricaoRI0510($dblIdProtocolo){

    $objRelProtocoloAssuntoDTO = new RelProtocoloAssuntoDTO();
    $objRelProtocoloAssuntoDTO->setDistinct(true);
    $objRelProtocoloAssuntoDTO->retNumSequencia();
    $objRelProtocoloAssuntoDTO->retNumIdAssunto();
    $objRelProtocoloAssuntoDTO->retStrCodigoEstruturadoAssunto();
    $objRelProtocoloAssuntoDTO->retStrDescricaoAssunto();
    $objRelProtocoloAssuntoDTO->setDblIdProtocolo($dblIdProtocolo);
    $objRelProtocoloAssuntoDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelProtocoloAssuntoRN = new RelProtocoloAssuntoRN();
    $arrObjRelProtocoloAssuntoDTO = InfraArray::distinctArrInfraDTO($objRelProtocoloAssuntoRN->listarRN0188($objRelProtocoloAssuntoDTO),'IdAssunto');

    foreach($arrObjRelProtocoloAssuntoDTO as $dto){
      $dto->setStrDescricaoAssunto(AssuntoINT::formatarCodigoDescricaoRI0568($dto->getStrCodigoEstruturadoAssunto(),$dto->getStrDescricaoAssunto()));
    }

    return parent::montarSelectArrInfraDTO(null, null, null, $arrObjRelProtocoloAssuntoDTO, 'IdAssunto','DescricaoAssunto');
  }
}
?>