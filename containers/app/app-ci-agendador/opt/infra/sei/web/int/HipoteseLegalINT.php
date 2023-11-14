<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/10/2013 - criado por mga
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class HipoteseLegalINT extends InfraINT {

  public static function montarSelectNomeBaseLegal($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $strStaNivelAcesso){
    $objHipoteseLegalDTO = new HipoteseLegalDTO();
    $objHipoteseLegalDTO->retNumIdHipoteseLegal();
    $objHipoteseLegalDTO->retStrNome();
    $objHipoteseLegalDTO->retStrBaseLegal();
    
    if ($strValorItemSelecionado!=null){
      $objHipoteseLegalDTO->setBolExclusaoLogica(false);
      $objHipoteseLegalDTO->adicionarCriterio(array('SinAtivo','IdHipoteseLegal'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objHipoteseLegalDTO->setStrStaNivelAcesso($strStaNivelAcesso);
        
    $objHipoteseLegalDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
    $objHipoteseLegalDTO->setOrdStrBaseLegal(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objHipoteseLegalRN = new HipoteseLegalRN();
    $arrObjHipoteseLegalDTO = $objHipoteseLegalRN->listar($objHipoteseLegalDTO);

    foreach($arrObjHipoteseLegalDTO as $objHipoteseLegalDTO){
      $objHipoteseLegalDTO->setStrNome(self::formatarHipoteseLegal($objHipoteseLegalDTO->getStrNome(),$objHipoteseLegalDTO->getStrBaseLegal()));
    }
    
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjHipoteseLegalDTO, 'IdHipoteseLegal', 'Nome');
  }
  
  public static function formatarHipoteseLegal($strNome, $strBaseLegal){
    return $strNome.' ('.$strBaseLegal.')';
  }
  
}
?>