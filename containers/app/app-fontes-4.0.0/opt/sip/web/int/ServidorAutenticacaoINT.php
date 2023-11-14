<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/06/2014 - criado por mga
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../Sip.php';

class ServidorAutenticacaoINT extends InfraINT {

  public static function montarSelectEndereco($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objServidorAutenticacaoDTO = new ServidorAutenticacaoDTO();
    $objServidorAutenticacaoDTO->retNumIdServidorAutenticacao();
    $objServidorAutenticacaoDTO->retStrEndereco();

    $objServidorAutenticacaoDTO->setOrdStrEndereco(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objServidorAutenticacaoRN = new ServidorAutenticacaoRN();
    $arrObjServidorAutenticacaoDTO = $objServidorAutenticacaoRN->listar($objServidorAutenticacaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjServidorAutenticacaoDTO, 'IdServidorAutenticacao', 'Endereco');
  }

  public static function montarSelectStaTipo($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objServidorAutenticacaoRN = new ServidorAutenticacaoRN();

    $arrObjTipoServidorAutenticacaoDTO = $objServidorAutenticacaoRN->listarValoresTipo();

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTipoServidorAutenticacaoDTO, 'StaTipo', 'Descricao');

  }
  
  public static function montarSelectVersao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    return parent::montarSelectArray($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, array('2' => '2', '3' => '3'));
  }
  
  public static function formatarIdentificacao($strNome, $strEndereco){
    return $strNome .' ('.$strEndereco.')';
  }
  
}
?>