<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/08/2009 - criado por mga
*
* Versão do Gerador de Código: 1.27.1
*
* Versão no CVS: $Id$
*/

//require_once 'Infra.php';

class InfraParametroINT extends InfraINT {

  public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objInfraParametroDTO = new InfraParametroDTO();
    $objInfraParametroDTO->retStrNome();
    $objInfraParametroDTO->retStrNome();

    $objInfraParametroDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objInfraParametroRN = new InfraParametroRN();
    $arrObjInfraParametroDTO = $objInfraParametroRN->listar($objInfraParametroDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjInfraParametroDTO, 'Nome', 'Nome');
  }
}
?>