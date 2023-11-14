<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/08/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoBlocoINT extends InfraINT {

  public static function montarSelectUnidade($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){

    $objGrupoBlocoDTO = new GrupoBlocoDTO();
    $objGrupoBlocoDTO->retNumIdGrupoBloco();
    $objGrupoBlocoDTO->retStrNome();
    $objGrupoBlocoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    $objGrupoBlocoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objGrupoBlocoRN = new GrupoBlocoRN();
    $arrObjGrupoBlocoDTO = $objGrupoBlocoRN->listar($objGrupoBlocoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjGrupoBlocoDTO, 'IdGrupoBloco', 'Nome');
  }
}
