<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/05/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class AcessoFederacaoINT extends InfraINT {

  public static function montarSelectStaTipo($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objAcessoFederacaoRN = new AcessoFederacaoRN();

    $arrObjTipoAcessoFederacaoDTO = $objAcessoFederacaoRN->listarValoresTipo();

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTipoAcessoFederacaoDTO, 'StaTipo', 'Descricao');

  }

  public static function montarSelectStaSentido($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $arr = array(AcessoFederacaoRN::$TST_ENVIADO => 'Enviado',
                 AcessoFederacaoRN::$TST_RECEBIDO => 'Recebido');
    return parent::montarSelectArray($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arr);
  }

}
