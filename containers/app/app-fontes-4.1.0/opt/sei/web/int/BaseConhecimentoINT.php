<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/06/2010 - criado por fazenda_db
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class BaseConhecimentoINT extends InfraINT {

  public static function montarSelectIdBaseConhecimento($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdUnidade=''){
    $objBaseConhecimentoDTO = new BaseConhecimentoDTO();
    $objBaseConhecimentoDTO->retNumIdBaseConhecimento();
    $objBaseConhecimentoDTO->retNumIdBaseConhecimento();

    if ($numIdUnidade!==''){
      $objBaseConhecimentoDTO->setNumIdUnidade($numIdUnidade);
    }

    $objBaseConhecimentoDTO->setOrdNumIdBaseConhecimento(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objBaseConhecimentoRN = new BaseConhecimentoRN();
    $arrObjBaseConhecimentoDTO = $objBaseConhecimentoRN->listar($objBaseConhecimentoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjBaseConhecimentoDTO, 'IdBaseConhecimento', 'IdBaseConhecimento');
  }
  
  public static function montarTitulo($objBaseConhecimentoDTO){
     return SessaoSEI::getInstance()->getStrSiglaSistema().' / '.SessaoSEI::getInstance()->getStrSiglaOrgaoSistema().' - '.$objBaseConhecimentoDTO->getStrDescricao().' - '.$objBaseConhecimentoDTO->getStrSiglaUnidade();
  }
  
}
?>
