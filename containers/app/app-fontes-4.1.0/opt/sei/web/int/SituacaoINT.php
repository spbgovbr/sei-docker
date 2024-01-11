<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/09/2014 - criado por bcu
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class SituacaoINT extends InfraINT {

  public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdUnidade){
    $objSituacaoDTO = new SituacaoDTO();
    $objSituacaoDTO->retNumIdSituacao();
    $objSituacaoDTO->retStrSinAtivo();
    $objSituacaoDTO->retStrNome();

    $objRelSituacaoUnidadeDTO = new RelSituacaoUnidadeDTO();
    $objRelSituacaoUnidadeDTO->retNumIdSituacao();
    $objRelSituacaoUnidadeDTO->setNumIdUnidade($numIdUnidade);

    $objRelSituacaoUnidadeRN = new RelSituacaoUnidadeRN();
    $arrIdSituacao = InfraArray::converterArrInfraDTO($objRelSituacaoUnidadeRN->listar($objRelSituacaoUnidadeDTO),'IdSituacao');

    if ($strValorItemSelecionado!=null && !in_array($strValorItemSelecionado,$arrIdSituacao)){
      $arrIdSituacao[] = $strValorItemSelecionado;
    }

    if (count($arrIdSituacao)){
      $objSituacaoDTO->setNumIdSituacao($arrIdSituacao,InfraDTO::$OPER_IN);
    }else{
      $objSituacaoDTO->setNumIdSituacao(null);
    }

    if ($strValorItemSelecionado!=null){
      $objSituacaoDTO->setBolExclusaoLogica(false);
      $objSituacaoDTO->adicionarCriterio(array('SinAtivo','IdSituacao'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objSituacaoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objSituacaoRN = new SituacaoRN();
    $arrObjSituacaoDTO = $objSituacaoRN->listar($objSituacaoDTO);
    foreach ($arrObjSituacaoDTO as $dto) {
      $dto->setStrNome(self::formatarSituacaoDesativada($dto->getStrNome(),$dto->getStrSinAtivo()));
    }

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjSituacaoDTO, 'IdSituacao', 'Nome');
  }

  public static function montarSelectNomeCompleto($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $sinInativos){

    $arrObjSituacaoDTO = array();

    $objAndamentoSituacaoDTO = new AndamentoSituacaoDTO();
    $objAndamentoSituacaoDTO->setDistinct(true);
    $objAndamentoSituacaoDTO->retNumIdSituacao();
    $objAndamentoSituacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

    $objAndamentoSituacaoRN = new AndamentoSituacaoRN();
    $arrIdSituacao = InfraArray::converterArrInfraDTO($objAndamentoSituacaoRN->listar($objAndamentoSituacaoDTO),'IdSituacao');

    if (count($arrIdSituacao)){

      $objSituacaoDTO = new SituacaoDTO();

      if ($sinInativos == 'S') {
        $objSituacaoDTO->setBolExclusaoLogica(false);
      }

      $objSituacaoDTO->retStrNome();
      $objSituacaoDTO->retNumIdSituacao();
      $objSituacaoDTO->retStrSinAtivo();
      $objSituacaoDTO->setNumIdSituacao($arrIdSituacao,InfraDTO::$OPER_IN);
      $objSituacaoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objSituacaoRN = new SituacaoRN();
      $arrObjSituacaoDTO = $objSituacaoRN->listar($objSituacaoDTO);

      if ($sinInativos == 'S') {
        foreach ($arrObjSituacaoDTO as $dto) {
          $dto->setStrNome(self::formatarSituacaoDesativada($dto->getStrNome(),$dto->getStrSinAtivo()));
        }
      }
    }

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjSituacaoDTO, 'IdSituacao', 'Nome');
  }

  public static function formatarSituacaoDesativada($strNomeSituacao, $strSinAtivoSituacao){
    return $strNomeSituacao.(($strSinAtivoSituacao == 'N')?' - DESATIVADO':'');
  }
}
?>