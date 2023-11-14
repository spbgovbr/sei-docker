<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/11/2011 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id: ModeloINT.php 7875 2013-08-20 14:59:02Z bcu $
*/

require_once dirname(__FILE__).'/../../SEI.php';

class ModeloINT extends InfraINT {

  public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objModeloDTO = new ModeloDTO();
    $objModeloDTO->retNumIdModelo();
    $objModeloDTO->retStrNome();

    if ($strValorItemSelecionado!=null){
      $objModeloDTO->setBolExclusaoLogica(false);
      $objModeloDTO->adicionarCriterio(array('SinAtivo','IdModelo'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objModeloDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objModeloRN = new ModeloRN();
    $arrObjModeloDTO = $objModeloRN->listar($objModeloDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjModeloDTO, 'IdModelo', 'Nome');
  }
}
?>