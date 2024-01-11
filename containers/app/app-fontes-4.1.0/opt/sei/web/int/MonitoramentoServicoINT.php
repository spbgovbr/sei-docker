<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 05/10/2015 - criado por mga
 *
 */

require_once dirname(__FILE__).'/../SEI.php';

class MonitoramentoServicoINT extends InfraINT {

  public static function montarSelectStaTipo($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objMonitoramentoServicoRN = new MonitoramentoServicoRN();
    $arrObjTipoDTO = $objMonitoramentoServicoRN->listarTiposMonitoramento();
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTipoDTO, 'StaTipo', 'Descricao');
  }
}
?>