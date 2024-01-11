<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/02/2012 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ArquivoExtensaoINT extends InfraINT {

  public static function montarSelectExtensao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objArquivoExtensaoDTO = new ArquivoExtensaoDTO();
    $objArquivoExtensaoDTO->retNumIdArquivoExtensao();
    $objArquivoExtensaoDTO->retStrExtensao();
    $objArquivoExtensaoDTO->setStrSinInterface('S');

    if ($strValorItemSelecionado!=null){
      $objArquivoExtensaoDTO->setBolExclusaoLogica(false);
      $objArquivoExtensaoDTO->adicionarCriterio(array('SinAtivo','IdArquivoExtensao'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objArquivoExtensaoDTO->setOrdStrExtensao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objArquivoExtensaoRN = new ArquivoExtensaoRN();
    $arrObjArquivoExtensaoDTO = $objArquivoExtensaoRN->listar($objArquivoExtensaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjArquivoExtensaoDTO, 'IdArquivoExtensao', 'Extensao');
  }
}
?>