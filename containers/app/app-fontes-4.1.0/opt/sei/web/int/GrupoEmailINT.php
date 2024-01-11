<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/09/2010 - criado por alexandre_db
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoEmailINT extends InfraINT {

  public static function montarSelectNomeUnidade($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objGrupoEmailDTO = new GrupoEmailDTO();
    $objGrupoEmailDTO->retNumIdGrupoEmail();
    $objGrupoEmailDTO->retStrNome();

    $objGrupoEmailDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    $objGrupoEmailDTO->setStrStaTipo(GrupoEmailRN::$TGE_UNIDADE);
    
    $objGrupoEmailDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objGrupoEmailRN = new GrupoEmailRN();
    $arrObjGrupoEmailDTO = $objGrupoEmailRN->listar($objGrupoEmailDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjGrupoEmailDTO, 'IdGrupoEmail','Nome');
  }
  
  public static function montarSelectNomeInstitucional($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){

    $objGrupoEmailDTO = new GrupoEmailDTO();
    $objGrupoEmailDTO->retNumIdGrupoEmail();
    $objGrupoEmailDTO->retStrNome();
    $objGrupoEmailDTO->setStrStaTipo(GrupoEmailRN::$TGE_INSTITUCIONAL);
    $objGrupoEmailDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objGrupoEmailRN = new GrupoEmailRN();
    $arrObjGrupoEmailDTO = $objGrupoEmailRN->listarInstitucionais($objGrupoEmailDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjGrupoEmailDTO, 'IdGrupoEmail','Nome');
  }
  
}
?>