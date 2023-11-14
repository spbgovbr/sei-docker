<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/04/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class InstalacaoFederacaoINT extends InfraINT {

  public static function montarSelectSigla($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
    $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
    $objInstalacaoFederacaoDTO->retStrSigla();
    $objInstalacaoFederacaoDTO->setStrStaEstado(InstalacaoFederacaoRN::$EI_LIBERADA);
    $objInstalacaoFederacaoDTO->setStrStaTipo(InstalacaoFederacaoRN::$TI_LOCAL, InfraDTO::$OPER_DIFERENTE);

    if ($strValorItemSelecionado!=null){
      $objInstalacaoFederacaoDTO->setBolExclusaoLogica(false);
      $objInstalacaoFederacaoDTO->adicionarCriterio(array('SinAtivo','IdInstalacaoFederacao'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objInstalacaoFederacaoDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
    $arrObjInstalacaoFederacaoDTO = $objInstalacaoFederacaoRN->listar($objInstalacaoFederacaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjInstalacaoFederacaoDTO, 'IdInstalacaoFederacao', 'Sigla');
  }

  public static function formatarSiglaDescricao($strSigla, $strDescricao){
    return $strSigla.' - '.$strDescricao;
  }

}
