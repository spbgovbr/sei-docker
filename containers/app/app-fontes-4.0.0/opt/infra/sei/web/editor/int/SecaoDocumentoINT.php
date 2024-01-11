<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 01/12/2011 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id: SecaoDocumentoINT.php 7875 2013-08-20 14:59:02Z bcu $
*/

require_once dirname(__FILE__).'/../../SEI.php';

class SecaoDocumentoINT extends InfraINT {

  public static function montarSelectOrdem($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdSecaoModelo='', $dblIdDocumento=''){
    $objSecaoDocumentoDTO = new SecaoDocumentoDTO();
    $objSecaoDocumentoDTO->retNumIdSecaoDocumento();
    $objSecaoDocumentoDTO->retNumOrdem();

    if ($numIdSecaoModelo!==''){
      $objSecaoDocumentoDTO->setNumIdSecaoModelo($numIdSecaoModelo);
    }

    if ($dblIdDocumento!==''){
      $objSecaoDocumentoDTO->setDblIdDocumento($dblIdDocumento);
    }

    $objSecaoDocumentoDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objSecaoDocumentoRN = new SecaoDocumentoRN();
    $arrObjSecaoDocumentoDTO = $objSecaoDocumentoRN->listar($objSecaoDocumentoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjSecaoDocumentoDTO, 'IdSecaoDocumento', 'Ordem');
  }
}
?>