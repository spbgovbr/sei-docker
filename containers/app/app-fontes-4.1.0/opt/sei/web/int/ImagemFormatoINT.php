<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/04/2014 - criado por mga
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ImagemFormatoINT extends InfraINT {

  public static function montarSelectFormato($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objImagemFormatoDTO = new ImagemFormatoDTO();
    $objImagemFormatoDTO->retNumIdImagemFormato();
    $objImagemFormatoDTO->retStrFormato();

    if ($strValorItemSelecionado!=null){
      $objImagemFormatoDTO->setBolExclusaoLogica(false);
      $objImagemFormatoDTO->adicionarCriterio(array('SinAtivo','IdImagemFormato'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objImagemFormatoDTO->setOrdStrFormato(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objImagemFormatoRN = new ImagemFormatoRN();
    $arrObjImagemFormatoDTO = $objImagemFormatoRN->listar($objImagemFormatoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjImagemFormatoDTO, 'IdImagemFormato', 'Formato');
  }
}
?>