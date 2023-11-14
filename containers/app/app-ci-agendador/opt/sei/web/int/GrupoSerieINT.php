<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 01/07/2008 - criado por fbv
*
* Versão do Gerador de Código: 1.19.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoSerieINT extends InfraINT {

  public static function montarSelectNomeRI0801($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objGrupoSerieDTO = new GrupoSerieDTO();
    $objGrupoSerieDTO->retNumIdGrupoSerie();
    $objGrupoSerieDTO->retStrNome();
    $objGrupoSerieDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    
    if ($strValorItemSelecionado!=null){
      
      $objGrupoSerieDTO->setBolExclusaoLogica(false);
      $objGrupoSerieDTO->adicionarCriterio(array('SinAtivo','IdGrupoSerie'),
                                      array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),
                                      array('S',$strValorItemSelecionado),
                                      InfraDTO::$OPER_LOGICO_OR);
    }
    

    $objGrupoSerieRN = new GrupoSerieRN();
    $arrObjGrupoSerieDTO = $objGrupoSerieRN->listarRN0778($objGrupoSerieDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjGrupoSerieDTO, 'IdGrupoSerie', 'Nome');
  }
  
  public static function obterDadosRI0953($numIdGrupoSerie){
    $objGrupoSerieDTO = new GrupoSerieDTO();
    $objGrupoSerieDTO->retStrDescricao();
    $objGrupoSerieDTO->setNumIdGrupoSerie($numIdGrupoSerie);

    $objGrupoSerieRN = new GrupoSerieRN();
    return $objGrupoSerieRN->consultarRN0777($objGrupoSerieDTO);
  }
  
}
?>