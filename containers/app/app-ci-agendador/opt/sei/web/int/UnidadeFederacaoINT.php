<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/06/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class UnidadeFederacaoINT extends InfraINT {

  public static function montarSelectSigla($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $strIdInstalacaoFederacao=''){
    $objUnidadeFederacaoDTO = new UnidadeFederacaoDTO();
    $objUnidadeFederacaoDTO->retStrIdUnidadeFederacao();
    $objUnidadeFederacaoDTO->retStrSigla();

    if ($strIdInstalacaoFederacao!==''){
      $objUnidadeFederacaoDTO->setStrIdInstalacaoFederacao($strIdInstalacaoFederacao);
    }

    if ($strValorItemSelecionado!=null){
      $objUnidadeFederacaoDTO->setBolExclusaoLogica(false);
      $objUnidadeFederacaoDTO->adicionarCriterio(array('SinAtivo','IdUnidadeFederacao'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objUnidadeFederacaoDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objUnidadeFederacaoRN = new UnidadeFederacaoRN();
    $arrObjUnidadeFederacaoDTO = $objUnidadeFederacaoRN->listar($objUnidadeFederacaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjUnidadeFederacaoDTO, 'IdUnidadeFederacao', 'Sigla');
  }
}
