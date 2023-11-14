<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/05/2008 - criado por fbv
*
* Versão do Gerador de Código: 1.16.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class TipoLocalizadorINT extends InfraINT {

/*
  public static function montarSelectSigla($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdUnidade=''){
    $objTipoLocalizadorDTO = new TipoLocalizadorDTO();
    $objTipoLocalizadorDTO->retNumIdTipoLocalizador();
    $objTipoLocalizadorDTO->retStrSigla();
    $objTipoLocalizadorDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);


    if ($numIdUnidade!==''){
      $objTipoLocalizadorDTO->setNumIdUnidade($numIdUnidade);
    }

    $objTipoLocalizadorRN = new TipoLocalizadorRN();
    $arrObjTipoLocalizadorDTO = $objTipoLocalizadorRN->listar($objTipoLocalizadorDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTipoLocalizadorDTO, 'IdTipoLocalizador', 'Sigla');
  }
*/
  
  public static function montarSelectNomeRI0676($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objTipoLocalizadorDTO = new TipoLocalizadorDTO();
    $objTipoLocalizadorDTO->retNumIdTipoLocalizador();
    $objTipoLocalizadorDTO->retStrNome();
    $objTipoLocalizadorDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
    $objTipoLocalizadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

    $objTipoLocalizadorRN = new TipoLocalizadorRN();
    $arrObjTipoLocalizadorDTO = $objTipoLocalizadorRN->listarRN0610($objTipoLocalizadorDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTipoLocalizadorDTO, 'IdTipoLocalizador', 'Nome');
  }
  
}
?>