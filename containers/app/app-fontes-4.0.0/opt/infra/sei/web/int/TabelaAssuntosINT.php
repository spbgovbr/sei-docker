<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/11/2015 - criado por mga
*
* Versão do Gerador de Código: 1.36.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class TabelaAssuntosINT extends InfraINT {

  public static function montarSelectNomeMapeamento($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdTabelaOrigem){
    $objTabelaAssuntosDTO = new TabelaAssuntosDTO();
    $objTabelaAssuntosDTO->retNumIdTabelaAssuntos();
    $objTabelaAssuntosDTO->retStrNome();

    $objTabelaAssuntosDTO->setNumIdTabelaAssuntos($numIdTabelaOrigem,InfraDTO::$OPER_DIFERENTE);

    $objTabelaAssuntosDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objTabelaAssuntosRN = new TabelaAssuntosRN();
    $arrObjTabelaAssuntosDTO = $objTabelaAssuntosRN->listar($objTabelaAssuntosDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTabelaAssuntosDTO, 'IdTabelaAssuntos', 'Nome');
  }
}
?>