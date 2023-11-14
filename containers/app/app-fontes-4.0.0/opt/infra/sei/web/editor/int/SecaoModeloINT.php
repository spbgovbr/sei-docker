<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/11/2011 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id: SecaoModeloINT.php 7875 2013-08-20 14:59:02Z bcu $
*/

require_once dirname(__FILE__).'/../../SEI.php';

class SecaoModeloINT extends InfraINT {

  public static function montarSelectOrdem($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdModelo=''){
    $objSecaoModeloDTO = new SecaoModeloDTO();
    $objSecaoModeloDTO->retNumIdSecaoModelo();
    $objSecaoModeloDTO->retNumOrdem();

    if ($numIdModelo!==''){
      $objSecaoModeloDTO->setNumIdModelo($numIdModelo);
    }

    $objSecaoModeloDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objSecaoModeloRN = new SecaoModeloRN();
    $arrObjSecaoModeloDTO = $objSecaoModeloRN->listar($objSecaoModeloDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjSecaoModeloDTO, 'IdSecaoModelo', 'Ordem');
  }
}
?>