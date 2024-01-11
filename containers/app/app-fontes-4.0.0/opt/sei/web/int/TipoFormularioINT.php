<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/07/2015 - criado por mga
*
* Versão do Gerador de Código: 1.35.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class TipoFormularioINT extends InfraINT {

  public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objTipoFormularioDTO = new TipoFormularioDTO();
    $objTipoFormularioDTO->retNumIdTipoFormulario();
    $objTipoFormularioDTO->retStrNome();

    if ($strValorItemSelecionado!=null){
      $objTipoFormularioDTO->setBolExclusaoLogica(false);
      $objTipoFormularioDTO->adicionarCriterio(array('SinAtivo','IdTipoFormulario'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objTipoFormularioDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objTipoFormularioRN = new TipoFormularioRN();
    $arrObjTipoFormularioDTO = $objTipoFormularioRN->listar($objTipoFormularioDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTipoFormularioDTO, 'IdTipoFormulario', 'Nome');
  }
}
?>