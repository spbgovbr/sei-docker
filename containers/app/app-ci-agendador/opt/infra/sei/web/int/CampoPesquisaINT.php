<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/03/2020 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class CampoPesquisaINT extends InfraINT {

  public static function montarSelectIdCampoPesquisa($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdPesquisa=''){
    $objCampoPesquisaDTO = new CampoPesquisaDTO();
    $objCampoPesquisaDTO->retNumIdCampoPesquisa();
    $objCampoPesquisaDTO->retNumIdCampoPesquisa();

    if ($numIdPesquisa!==''){
      $objCampoPesquisaDTO->setNumIdPesquisa($numIdPesquisa);
    }

    $objCampoPesquisaDTO->setOrdNumIdCampoPesquisa(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objCampoPesquisaRN = new CampoPesquisaRN();
    $arrObjCampoPesquisaDTO = $objCampoPesquisaRN->listar($objCampoPesquisaDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjCampoPesquisaDTO, 'IdCampoPesquisa', 'IdCampoPesquisa');
  }

  public static function montarInput(int $numChave,  string $strCampo, array $arrCampoPesquisa){
      return '<input type="hidden" name="'.$strCampo.'" value="'.($arrCampoPesquisa[$numChave] != null && InfraArray::contar($arrCampoPesquisa[$numChave]) > 0 ? PaginaSEI::tratarHTML($arrCampoPesquisa[$numChave][0]->getStrValor()) : '').'"/>';
  }

  public static function montarArrayPesquisa(int $numChave,  string $strCampo, array &$arrCampoPesquisa){
    $strCampoValor = PaginaSEI::getInstance()->recuperarCampo($strCampo);
    if(!InfraString::isBolVazia($strCampoValor)){
      $objCampoPesquisaDTO = new CampoPesquisaDTO();
      $objCampoPesquisaDTO->setNumChave($numChave);
      $objCampoPesquisaDTO->setStrValor($strCampoValor);
      $arrCampoPesquisa[] = $objCampoPesquisaDTO;
    }
  }
}
