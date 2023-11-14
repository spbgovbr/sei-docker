<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/01/2011 - criado por jonatas_db
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ControleInternoINT extends InfraINT {

  public static function montarSelectDescricaoRI0011($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objControleInternoDTO = new ControleInternoDTO();
    $objControleInternoDTO->retNumIdControleInterno();
    $objControleInternoDTO->retStrDescricao();

    $objControleInternoDTO->setOrdStrDescricao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objControleInternoRN = new ControleInternoRN();
    $arrObjControleInternoDTO = $objControleInternoRN->listar($objControleInternoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjControleInternoDTO, 'IdControleInterno', 'Descricao');
  }
  
  public static function montarSelectUnidades($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdControleInterno){
  	
    $objRelControleInternoUnidadeDTO = new RelControleInternoUnidadeDTO();
    $objRelControleInternoUnidadeDTO->setNumIdControleInterno($numIdControleInterno);
    $objRelControleInternoUnidadeDTO->retNumIdUnidade();
    $objRelControleInternoUnidadeDTO->retStrSiglaUnidade();
    $objRelControleInternoUnidadeDTO->retStrDescricaoUnidade();
    
    $objRelControleInternoUnidadeDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);
    
    $objRelControleInternoUnidadeRN = new RelControleInternoUnidadeRN();
    $arrObjRelControleInternoUnidadeDTO = $objRelControleInternoUnidadeRN->listar($objRelControleInternoUnidadeDTO);
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjRelControleInternoUnidadeDTO, 'IdUnidade', 'SiglaUnidade');
  }

  public static function montarSelectOrgaos($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdControleInterno){
  	
    $objRelControleInternoOrgaoDTO = new RelControleInternoOrgaoDTO();
    $objRelControleInternoOrgaoDTO->setNumIdControleInterno($numIdControleInterno);
    $objRelControleInternoOrgaoDTO->retNumIdOrgao();
    $objRelControleInternoOrgaoDTO->retStrSiglaOrgao();
    
    $objRelControleInternoOrgaoDTO->setOrdStrSiglaOrgao(InfraDTO::$TIPO_ORDENACAO_ASC);
    
    $objRelControleInternoOrgaoRN = new RelControleInternoOrgaoRN();
    $arrObjRelControleInternoOrgaoDTO = $objRelControleInternoOrgaoRN->listar($objRelControleInternoOrgaoDTO);
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjRelControleInternoOrgaoDTO, 'IdOrgao', 'SiglaOrgao');
  }

  public static function montarSelectTiposProcedimento($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdControleInterno){
  	
    $objRelControleInternoTipoProcDTO = new RelControleInternoTipoProcDTO();
    $objRelControleInternoTipoProcDTO->setNumIdControleInterno($numIdControleInterno);
    $objRelControleInternoTipoProcDTO->retNumIdTipoProcedimento();
    $objRelControleInternoTipoProcDTO->retStrNomeTipoProcedimento();
    
    $objRelControleInternoTipoProcDTO->setOrdStrNomeTipoProcedimento(InfraDTO::$TIPO_ORDENACAO_ASC);
    
    $objRelControleInternoTipoProcRN = new RelControleInternoTipoProcRN();
    $arrObjRelControleInternoTipoProcDTO = $objRelControleInternoTipoProcRN->listar($objRelControleInternoTipoProcDTO);
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjRelControleInternoTipoProcDTO, 'IdTipoProcedimento', 'NomeTipoProcedimento');
  }
  
  
  public static function montarSelectSeries($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdControleInterno){
  	
    $objRelControleInternoSerieDTO = new RelControleInternoSerieDTO();
    $objRelControleInternoSerieDTO->setNumIdControleInterno($numIdControleInterno);
    $objRelControleInternoSerieDTO->retNumIdSerie();
    $objRelControleInternoSerieDTO->retStrNomeSerie();
    
    $objRelControleInternoSerieDTO->setOrdStrNomeSerie(InfraDTO::$TIPO_ORDENACAO_ASC);
    
    $objRelControleInternoSerieRN = new RelControleInternoSerieRN();
    $arrObjRelControleInternoSerieDTO = $objRelControleInternoSerieRN->listar($objRelControleInternoSerieDTO);
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjRelControleInternoSerieDTO, 'IdSerie', 'NomeSerie');
  }
  
  
  
}
?>