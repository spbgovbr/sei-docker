<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class PerfilINT extends InfraINT {

  public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdSistema){
    $objPerfilDTO = new PerfilDTO();
    $objPerfilDTO->retNumIdPerfil();

    if (InfraString::isBolVazia($numIdSistema)){
			$objPerfilDTO->setNumIdSistema(null);
		}else{
		  $objPerfilDTO->setNumIdSistema($numIdSistema);
		}		
		
    $objPerfilDTO->retStrNome();
    $objPerfilDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objPerfilRN = new PerfilRN();
    $arrObjPerfilDTO = $objPerfilRN->listar($objPerfilDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado,$arrObjPerfilDTO, 'IdPerfil', 'Nome');
  }
	
  public static function montarSelectSiglaAutorizados($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdSistema, $numIdUnidade = ''){

		//Busca ID da hierarquia associada com o sistema
		$objSistemaDTO = new SistemaDTO();
		$objSistemaDTO->setNumIdSistema($numIdSistema);
		$objSistemaDTO->setNumIdUnidade($numIdUnidade);

		$objPerfilRN = new PerfilRN();
		$arrObjPerfilDTO = $objPerfilRN->obterAutorizados($objSistemaDTO);
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjPerfilDTO, 'IdPerfil', 'Nome');
  }
}
?>