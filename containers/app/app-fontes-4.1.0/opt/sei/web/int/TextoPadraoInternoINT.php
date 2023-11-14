<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/05/2012 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class TextoPadraoInternoINT extends InfraINT {

  public static function montarSelectSigla($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objTextoPadraoInternoDTO = new TextoPadraoInternoDTO();
    $objTextoPadraoInternoDTO->retNumIdTextoPadraoInterno();
    $objTextoPadraoInternoDTO->retStrNome();
    $objTextoPadraoInternoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objTextoPadraoInternoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

    $objTextoPadraoInternoRN = new TextoPadraoInternoRN();
    $arrObjTextoPadraoInternoDTO = $objTextoPadraoInternoRN->listar($objTextoPadraoInternoDTO);
    
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTextoPadraoInternoDTO, 'IdTextoPadraoInterno', 'Nome');
  }
  
  public static function obterDados($dblIdTextoPadrao){

    $ret = null;
    
    $objTextoPadraoInternoDTO = new TextoPadraoInternoDTO();
    $objTextoPadraoInternoDTO->retStrConteudo();
    $objTextoPadraoInternoDTO->setNumIdTextoPadraoInterno($dblIdTextoPadrao);

    $objTextoPadraoInternoRN = new TextoPadraoInternoRN();
    $objTextoPadraoInternoDTO = $objTextoPadraoInternoRN->consultar($objTextoPadraoInternoDTO);
    
    if ($objTextoPadraoInternoDTO!=null){
    
      $strConteudo = $objTextoPadraoInternoDTO->getStrConteudo();
      //$strConteudo = html_entity_decode(strip_tags($strConteudo));
      $strConteudo = html_entity_decode(strip_tags($strConteudo), ENT_COMPAT, 'ISO-8859-1');
      
      $objTextoPadraoInternoDTO->setStrConteudo($strConteudo);
      
      $ret = $objTextoPadraoInternoDTO;
    }

    return $ret; 
  }

  public static function autoCompletarNome($strPalavrasPesquisa){

    $objTextoPadraoInternoDTO = new TextoPadraoInternoDTO();
    $objTextoPadraoInternoDTO->setNumMaxRegistrosRetorno(50);
    $objTextoPadraoInternoDTO->retNumIdTextoPadraoInterno();
    $objTextoPadraoInternoDTO->retStrNome();
    $objTextoPadraoInternoDTO->setStrNome($strPalavrasPesquisa);
    InfraString::tratarPalavrasPesquisaDTO($objTextoPadraoInternoDTO,"Nome");
    $objTextoPadraoInternoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    $objTextoPadraoInternoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objTextoPadraoInternoRN = new TextoPadraoInternoRN();
    $arrObjTextoPadraoInternoDTO = $objTextoPadraoInternoRN->listar($objTextoPadraoInternoDTO);

    return $arrObjTextoPadraoInternoDTO;
  }
}
?>