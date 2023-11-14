<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 01/03/2012 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class PaisINT extends InfraINT {

  private static $numIdPaisBrasil = null;

  public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objPaisDTO = new PaisDTO();
    $objPaisDTO->retNumIdPais();
    $objPaisDTO->retStrNome();

    $objPaisDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objPaisRN = new PaisRN();
    $arrObjPaisDTO = $objPaisRN->listar($objPaisDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjPaisDTO, 'IdPais', 'Nome');
  }

  public static function buscarIdPaisBrasil(){
    if (self::$numIdPaisBrasil === null) {
        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        self::$numIdPaisBrasil = $objInfraParametro->getValor('ID_PAIS_BRASIL', false, ID_BRASIL);
    }
    return self::$numIdPaisBrasil;
  }
}
?>