<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/10/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class CategoriaINT extends InfraINT {

  public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objCategoriaDTO = new CategoriaDTO();
    $objCategoriaDTO->retNumIdCategoria();
    $objCategoriaDTO->retStrNome();

    if ($strValorItemSelecionado!=null){
      $objCategoriaDTO->setBolExclusaoLogica(false);
      $objCategoriaDTO->adicionarCriterio(array('SinAtivo','IdCategoria'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objCategoriaDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objCategoriaRN = new CategoriaRN();
    $arrObjCategoriaDTO = $objCategoriaRN->listar($objCategoriaDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjCategoriaDTO, 'IdCategoria', 'Nome');
  }
}
