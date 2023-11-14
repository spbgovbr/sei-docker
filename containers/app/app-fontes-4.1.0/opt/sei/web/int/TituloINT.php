<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 02/08/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class TituloINT extends InfraINT {

  public static function montarSelectExpressao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objTituloDTO = new TituloDTO();
    $objTituloDTO->retNumIdTitulo();
    $objTituloDTO->retStrExpressao();

    if ($strValorItemSelecionado!=null){
      $objTituloDTO->setBolExclusaoLogica(false);
      $objTituloDTO->adicionarCriterio(array('SinAtivo','IdTitulo'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objTituloDTO->setOrdStrExpressao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objTituloRN = new TituloRN();
    $arrObjTituloDTO = $objTituloRN->listar($objTituloDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTituloDTO, 'IdTitulo', 'Expressao');
  }

  public static function montarSelectExpressaoAbreviatura($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objTituloDTO = new TituloDTO();
    $objTituloDTO->retNumIdTitulo();
    $objTituloDTO->retStrExpressao();
    $objTituloDTO->retStrAbreviatura();

    if ($strValorItemSelecionado!=null){
      $objTituloDTO->setBolExclusaoLogica(false);
      $objTituloDTO->adicionarCriterio(array('SinAtivo','IdTitulo'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objTituloDTO->setOrdStrExpressao(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objTituloRN = new TituloRN();
    $arrObjTituloDTO = $objTituloRN->listar($objTituloDTO);

    foreach($arrObjTituloDTO as $objTituloDTO){
       $objTituloDTO->setStrExpressao(self::formatarExpressaoAbreviatura($objTituloDTO->getStrExpressao(), $objTituloDTO->getStrAbreviatura()));
    }

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTituloDTO, 'IdTitulo', 'Expressao');
  }

  public static function formatarExpressaoAbreviatura($strExpressao, $strAbreviatura){
    if(!InfraString::isBolVazia($strAbreviatura)) {
      return $strExpressao . ' (' . $strAbreviatura . ')';
    }else{
      return $strExpressao;
    }
  }
}
