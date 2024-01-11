<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/10/2010 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class OuvidoriaINT extends InfraINT {
	
	public static function montarSelectOuvidoriaDestino($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdOrgao){

    $objUnidadeDTO = new UnidadeDTO();
    $objUnidadeDTO->setDistinct(true);
    $objUnidadeDTO->retNumIdOrgao();
    $objUnidadeDTO->retStrDescricaoOrgao();
    $objUnidadeDTO->setStrSinOuvidoria('S');
    $objUnidadeDTO->setNumIdOrgao($numIdOrgao,InfraDTO::$OPER_DIFERENTE);
    $objUnidadeDTO->setOrdStrDescricaoOrgao(InfraDTO::$TIPO_ORDENACAO_ASC);

		$objUnidadeRN = new UnidadeRN();
		$arrObjUnidadeDTO = $objUnidadeRN->listarRN0127($objUnidadeDTO);
		
		return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjUnidadeDTO, 'IdOrgao', 'DescricaoOrgao');
	}
	
	public static function montarSelectStaOuvidoriaFinalizacao($strPrimeiroItemValor, $strPrimeiroItemDescricao,$strValorItemSelecionado){
	  $arr = array(ProcedimentoRN::$TFO_NENHUM => ' ', ProcedimentoRN::$TFO_SIM => 'Sim', ProcedimentoRN::$TFO_NAO => 'Não');
	  return InfraINT::montarSelectArray($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arr);
	}

	public static function montarSelectStaOuvidoriaAcompanhamento($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
	  $arr = array(ProcedimentoRN::$TFO_NENHUM => 'Sem atendimento registrado', ProcedimentoRN::$TFO_SIM => 'Atendidas', ProcedimentoRN::$TFO_NAO => 'Não atendidas'); 
	  return InfraINT::montarSelectArray($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arr);
	}
	
	public static function obterDescricaoStaOuvidoriaTabela($strStaOuvidoria){
	  $arr = array(ProcedimentoRN::$TFO_NENHUM => ' ', ProcedimentoRN::$TFO_SIM => 'Sim', ProcedimentoRN::$TFO_NAO => 'Não');
	  if (isset($arr[$strStaOuvidoria])){
	    return $arr[$strStaOuvidoria];
	  }
	  return null;
	}
}
?>