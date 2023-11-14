<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 01/07/2008 - criado por fbv
*
* Versão do Gerador de Código: 1.19.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelUnidadeTipoContatoINT extends InfraINT {

  public static function montarSelectSiglaUnidadeRI1202($numIdTipoContato, &$strSelUnidadesAlteracao, &$strSelUnidadesConsulta){
    
    $objRelUnidadeTipoContatoDTO = new RelUnidadeTipoContatoDTO();
    $objRelUnidadeTipoContatoDTO->retNumIdUnidade();
    $objRelUnidadeTipoContatoDTO->retStrSiglaUnidade();
    $objRelUnidadeTipoContatoDTO->retStrStaAcesso();
    $objRelUnidadeTipoContatoDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);
    $objRelUnidadeTipoContatoDTO->setNumIdTipoContato($numIdTipoContato);
    
    $objRelUnidadeTipoContatoRN = new RelUnidadeTipoContatoRN();
    $arrObjRelUnidadeTipoContatoDTO = $objRelUnidadeTipoContatoRN->listarRN0547($objRelUnidadeTipoContatoDTO);

    $arrAlteracao = array();
    $arrConsulta = array();
    foreach($arrObjRelUnidadeTipoContatoDTO as $objRelUnidadeTipoContatoDTO){
      if ($objRelUnidadeTipoContatoDTO->getStrStaAcesso()==TipoContatoRN::$TA_ALTERACAO){
        $arrAlteracao[] = $objRelUnidadeTipoContatoDTO;
      }else{
        $arrConsulta[] = $objRelUnidadeTipoContatoDTO;
      }
    }

    $strSelUnidadesAlteracao = parent::montarSelectArrInfraDTO(null,null,null, $arrAlteracao, 'IdUnidade', 'SiglaUnidade');
    $strSelUnidadesConsulta = parent::montarSelectArrInfraDTO(null,null,null, $arrConsulta, 'IdUnidade', 'SiglaUnidade');
  }
}
?>