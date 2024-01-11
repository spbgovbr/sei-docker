<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/03/2023 - criado por mgb29
*
* Versão do Gerador de Código: 1.43.2
*/

//require_once dirname(__FILE__).'/../Infra.php';

class InfraErroPhpINT extends InfraINT {

  public static function montarSelectIdInfraErroPhp($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objInfraErroPhpDTO = new InfraErroPhpDTO();
    $objInfraErroPhpDTO->retStrIdInfraErroPhp();
    $objInfraErroPhpDTO->retStrIdInfraErroPhp();

    $objInfraErroPhpDTO->setOrdStrIdInfraErroPhp(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objInfraErroPhpRN = new InfraErroPhpRN();
    $arrObjInfraErroPhpDTO = $objInfraErroPhpRN->listar($objInfraErroPhpDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjInfraErroPhpDTO, 'IdInfraErroPhp', 'IdInfraErroPhp');
  }

  public static function montarSelectStaTipo($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objInfraErroPhpRN = new InfraErroPhpRN();

    $arrObjInfraErroPhpTipoDTO = $objInfraErroPhpRN->listarValoresTipo();

      foreach($arrObjInfraErroPhpTipoDTO as $objInfraErroPhpTipoDTO){
          $objInfraErroPhpTipoDTO->setStrErro(ucfirst($objInfraErroPhpTipoDTO->getStrErro()).' ('.InfraString::transformarCaixaBaixa(substr($objInfraErroPhpTipoDTO->getStrDescricao(),0,1)).substr($objInfraErroPhpTipoDTO->getStrDescricao(),1).')');
      }

    InfraArray::ordenarArrInfraDTO($arrObjInfraErroPhpTipoDTO, 'Erro', InfraArray::$TIPO_ORDENACAO_ASC);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjInfraErroPhpTipoDTO, 'StaTipo', 'Erro');
  }
}
