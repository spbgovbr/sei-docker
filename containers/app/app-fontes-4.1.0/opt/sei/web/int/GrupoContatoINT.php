<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.12.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoContatoINT extends InfraINT {

  public static function ConjuntoPorUnidadeRI0515($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objGrupoContatoDTO = new GrupoContatoDTO();
    $objGrupoContatoDTO->retNumIdGrupoContato();
    $objGrupoContatoDTO->retStrNome();
    
    $objGrupoContatoDTO->adicionarCriterio(array('IdUnidade','StaTipo'),
                                           array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),
                                           array(SessaoSEI::getInstance()->getNumIdUnidadeAtual(),GrupoContatoRN::$TGC_INSTITUCIONAL),
                                            InfraDTO::$OPER_LOGICO_OR);        


    $objGrupoContatoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
                                                
    $objGrupoContatoRN = new GrupoContatoRN();
    $arrObjGrupoContatoDTO = $objGrupoContatoRN->listarRN0477($objGrupoContatoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjGrupoContatoDTO, 'IdGrupoContato', 'Nome');
  }

  public static function montarSelectNomeContato($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objGrupoContatoDTO = new GrupoContatoDTO();
    $objGrupoContatoDTO->retNumIdGrupoContato();
    $objGrupoContatoDTO->retStrNome();
    $objGrupoContatoDTO->setStrStaTipo(GrupoContatoRN::$TGC_UNIDADE);
    $objGrupoContatoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

    if ($strValorItemSelecionado!=null){
      $objGrupoContatoDTO->setBolExclusaoLogica(false);
      $objGrupoContatoDTO->adicionarCriterio(array('SinAtivo','IdGrupoContato'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objGrupoContatoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objGrupoContatoRN = new GrupoContatoRN();
    $arrObjGrupoContatoDTO = $objGrupoContatoRN->listarRN0477($objGrupoContatoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjGrupoContatoDTO, 'IdGrupoContato', 'Nome');
  }

  public static function montarSelectNomeInstitucional($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdContato=''){
    $objGrupoContatoDTO = new GrupoContatoDTO();
    $objGrupoContatoDTO->retNumIdGrupoContato();
    $objGrupoContatoDTO->retStrNome();
    $objGrupoContatoDTO->setStrStaTipo(GrupoContatoRN::$TGC_INSTITUCIONAL);

    if ($numIdContato!==''){
      $objGrupoContatoDTO->setNumIdContato($numIdContato);
    }

    if ($strValorItemSelecionado!=null){
      $objGrupoContatoDTO->setBolExclusaoLogica(false);
      $objGrupoContatoDTO->adicionarCriterio(array('SinAtivo','IdGrupoContato'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objGrupoContatoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objGrupoContatoRN = new GrupoContatoRN();
    $arrObjGrupoContatoDTO = $objGrupoContatoRN->listarRN0477($objGrupoContatoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjGrupoContatoDTO, 'IdGrupoContato', 'Nome');
  }

}
?>