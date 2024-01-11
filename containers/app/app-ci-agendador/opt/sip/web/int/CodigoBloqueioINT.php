<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/10/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../Sip.php';

class CodigoBloqueioINT extends InfraINT {

  public static function montarSelectEnvio($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $strIdCodigoAcesso=''){
    $objCodigoBloqueioDTO = new CodigoBloqueioDTO();
    $objCodigoBloqueioDTO->retStrIdCodigoBloqueio();
    $objCodigoBloqueioDTO->retDthEnvio();

    if ($strIdCodigoAcesso!==''){
      $objCodigoBloqueioDTO->setStrIdCodigoAcesso($strIdCodigoAcesso);
    }

    if ($strValorItemSelecionado!=null){
      $objCodigoBloqueioDTO->setBolExclusaoLogica(false);
      $objCodigoBloqueioDTO->adicionarCriterio(array('SinAtivo','IdCodigoBloqueio'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objCodigoBloqueioDTO->setOrdDthEnvio(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objCodigoBloqueioRN = new CodigoBloqueioRN();
    $arrObjCodigoBloqueioDTO = $objCodigoBloqueioRN->listar($objCodigoBloqueioDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjCodigoBloqueioDTO, 'IdCodigoBloqueio', 'Envio');
  }
}
