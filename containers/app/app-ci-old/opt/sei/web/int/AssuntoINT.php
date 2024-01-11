<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AssuntoINT extends InfraINT {

  public static function montarSelectCodigoEstruturado($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objAssuntoDTO = new AssuntoDTO();
    $objAssuntoDTO->retNumIdAssunto();
    $objAssuntoDTO->retStrCodigoEstruturado();
    $objAssuntoDTO->setOrdStrCodigoEstruturado(InfraDTO::$TIPO_ORDENACAO_ASC);


    $objAssuntoRN = new AssuntoRN();
    $arrObjAssuntoDTO = $objAssuntoRN->listarRN0247($objAssuntoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjAssuntoDTO, 'IdAssunto', 'CodigoEstruturado');
  }
  
  public static function formatarCodigoDescricaoRI0568($strCodigoEstruturado, $strDescricao){
    return $strCodigoEstruturado.' - '.$strDescricao;
  }
  
  public static function autoCompletarAssuntosRI1223($strPalavrasPesquisa){
    
    $objAssuntoDTO = new AssuntoDTO();
    $objAssuntoDTO->retNumIdAssunto();
    $objAssuntoDTO->retStrCodigoEstruturado();
    $objAssuntoDTO->retStrDescricao();
    
    $objAssuntoDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);
    $objAssuntoDTO->setStrSinEstrutural('N');
    $objAssuntoDTO->setStrSinAtualTabelaAssuntos('S');

    $objAssuntoDTO->setNumMaxRegistrosRetorno(50);
    
    $objAssuntoRN = new AssuntoRN();
    $arrObjAssuntoDTO = $objAssuntoRN->pesquisarRN0246($objAssuntoDTO);
    
    foreach($arrObjAssuntoDTO as $objAssuntoDTO){
      $objAssuntoDTO->setStrCodigoEstruturado(AssuntoINT::formatarCodigoDescricaoRI0568($objAssuntoDTO->getStrCodigoEstruturado(),$objAssuntoDTO->getStrDescricao()));
    }
    
    return $arrObjAssuntoDTO;
  }

  public static function autoCompletarAssuntosMapeamento($strPalavrasPesquisa,$numIdTabelaAssuntos){

    $objAssuntoDTO = new AssuntoDTO();
    $objAssuntoDTO->setBolExclusaoLogica(false);
    $objAssuntoDTO->retNumIdAssunto();
    $objAssuntoDTO->retStrCodigoEstruturado();
    $objAssuntoDTO->retStrDescricao();
    $objAssuntoDTO->setStrSinEstrutural('N');
    $objAssuntoDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);
    $objAssuntoDTO->setNumIdTabelaAssuntos($numIdTabelaAssuntos);

    $objAssuntoDTO->setNumMaxRegistrosRetorno(50);

    $objAssuntoRN = new AssuntoRN();
    $arrObjAssuntoDTO = $objAssuntoRN->pesquisarRN0246($objAssuntoDTO);

    foreach($arrObjAssuntoDTO as $objAssuntoDTO){
      $objAssuntoDTO->setStrCodigoEstruturado(AssuntoINT::formatarCodigoDescricaoRI0568($objAssuntoDTO->getStrCodigoEstruturado(),$objAssuntoDTO->getStrDescricao()));
    }

    return $arrObjAssuntoDTO;
  }

  public static function montarSelectTrocaTipoProcedimento($numIdTipoProcedimento, $arrIdAssuntos){
    
    $objRelTipoProcedimentoAssuntoDTO = new RelTipoProcedimentoAssuntoDTO();
    $objRelTipoProcedimentoAssuntoDTO->retNumIdAssunto();
    $objRelTipoProcedimentoAssuntoDTO->setNumIdTipoProcedimento($numIdTipoProcedimento);
    $objRelTipoProcedimentoAssuntoDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);
  
    $objRelTipoProcedimentoAssuntoRN = new RelTipoProcedimentoAssuntoRN();
    $arrIdAssuntosSugestao = InfraArray::converterArrInfraDTO($objRelTipoProcedimentoAssuntoRN->listarRN0192($objRelTipoProcedimentoAssuntoDTO),'IdAssunto');
    
    $arr = array_unique(array_merge($arrIdAssuntos,$arrIdAssuntosSugestao));
    
    $arrObjAssuntoDTO = array();
    if (InfraArray::contar($arr)){
      $objAssuntoDTO = new AssuntoDTO();
      $objAssuntoDTO->setBolExclusaoLogica(false);
      $objAssuntoDTO->retNumIdAssunto();
      $objAssuntoDTO->retStrCodigoEstruturado();
      $objAssuntoDTO->retStrDescricao();
      $objAssuntoDTO->setNumIdAssunto($arr,InfraDTO::$OPER_IN);
      $objAssuntoDTO->setOrdStrCodigoEstruturado(InfraDTO::$TIPO_ORDENACAO_ASC);
      
      $objAssuntoRN = new AssuntoRN();
      $arrObjAssuntoDTO = $objAssuntoRN->listarRN0247($objAssuntoDTO);
    }    
  
    foreach($arrObjAssuntoDTO as $dto){
      $dto->setStrDescricao(AssuntoINT::formatarCodigoDescricaoRI0568($dto->getStrCodigoEstruturado(),$dto->getStrDescricao()));
    }
  
    return parent::montarSelectArrInfraDTO(null, null, null, $arrObjAssuntoDTO, 'IdAssunto', 'Descricao');
  }

  public static function montarSelectTrocaSerie($numIdSerie, $arrIdAssuntos){
  
    $objRelSerieAssuntoDTO = new RelSerieAssuntoDTO();
    $objRelSerieAssuntoDTO->retNumIdAssunto();
    $objRelSerieAssuntoDTO->setNumIdSerie($numIdSerie);
    $objRelSerieAssuntoDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);
  
    $objRelSerieAssuntoRN = new RelSerieAssuntoRN();
    $arrIdAssuntosSugestao = InfraArray::converterArrInfraDTO($objRelSerieAssuntoRN->listar($objRelSerieAssuntoDTO),'IdAssunto');
  
    $arr = array_unique(array_merge($arrIdAssuntos,$arrIdAssuntosSugestao));
  
    $arrObjAssuntoDTO = array();
    if (InfraArray::contar($arr)){
      $objAssuntoDTO = new AssuntoDTO();
      $objAssuntoDTO->setBolExclusaoLogica(false);
      $objAssuntoDTO->retNumIdAssunto();
      $objAssuntoDTO->retStrCodigoEstruturado();
      $objAssuntoDTO->retStrDescricao();
      $objAssuntoDTO->setNumIdAssunto($arr,InfraDTO::$OPER_IN);
      $objAssuntoDTO->setOrdStrCodigoEstruturado(InfraDTO::$TIPO_ORDENACAO_ASC);
  
      $objAssuntoRN = new AssuntoRN();
      $arrObjAssuntoDTO = $objAssuntoRN->listarRN0247($objAssuntoDTO);
    }
  
    foreach($arrObjAssuntoDTO as $dto){
      $dto->setStrDescricao(AssuntoINT::formatarCodigoDescricaoRI0568($dto->getStrCodigoEstruturado(),$dto->getStrDescricao()));
    }
  
    return parent::montarSelectArrInfraDTO(null, null, null, $arrObjAssuntoDTO, 'IdAssunto', 'Descricao');
  }
  
}
?>