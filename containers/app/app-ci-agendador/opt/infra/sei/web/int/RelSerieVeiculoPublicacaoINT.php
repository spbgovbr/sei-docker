<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/07/2013 - criado por mkr@trf4.jus.br
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelSerieVeiculoPublicacaoINT extends InfraINT {

  public static function montarSelectIdSerie($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdSerie='', $numIdVeiculoPublicacao=''){
    $objRelSerieVeiculoPublicacaoDTO = new RelSerieVeiculoPublicacaoDTO();
    $objRelSerieVeiculoPublicacaoDTO->retNumIdSerie();
    $objRelSerieVeiculoPublicacaoDTO->retNumIdVeiculoPublicacao();
    $objRelSerieVeiculoPublicacaoDTO->retNumIdSerie();

    if ($numIdSerie!==''){
      $objRelSerieVeiculoPublicacaoDTO->setNumIdSerie($numIdSerie);
    }

    if ($numIdVeiculoPublicacao!==''){
      $objRelSerieVeiculoPublicacaoDTO->setNumIdVeiculoPublicacao($numIdVeiculoPublicacao);
    }

    $objRelSerieVeiculoPublicacaoDTO->setOrdNumIdSerie(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelSerieVeiculoPublicacaoRN = new RelSerieVeiculoPublicacaoRN();
    $arrObjRelSerieVeiculoPublicacaoDTO = $objRelSerieVeiculoPublicacaoRN->listar($objRelSerieVeiculoPublicacaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjRelSerieVeiculoPublicacaoDTO, 'array(\'IdSerie\',\'IdVeiculoPublicacao\')', 'IdSerie');
  }
  
  public static function montarSelectIdVeiculoPublicacao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdSerie=''){    
    $objRelSerieVeiculoPublicacaoDTO = new RelSerieVeiculoPublicacaoDTO(true);
    $objRelSerieVeiculoPublicacaoDTO->retTodos();
    
    if ($numIdSerie!==''){
      $objRelSerieVeiculoPublicacaoDTO->setNumIdSerie($numIdSerie);
    }
  
     
    $objRelSerieVeiculoPublicacaoDTO->setOrdNumIdVeiculoPublicacao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelSerieVeiculoPublicacaoRN = new RelSerieVeiculoPublicacaoRN();
    $arrObjRelSerieVeiculoPublicacaoDTO = $objRelSerieVeiculoPublicacaoRN->listar($objRelSerieVeiculoPublicacaoDTO);
    
    //echo 's: '.$numIdSerie.'<br/>';
    //print_r($arrObjRelSerieVeiculoPublicacaoDTO);    
        
  
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjRelSerieVeiculoPublicacaoDTO, 'IdVeiculoPublicacao', 'NomeVeiculoPublicacao');
  }
}
?>