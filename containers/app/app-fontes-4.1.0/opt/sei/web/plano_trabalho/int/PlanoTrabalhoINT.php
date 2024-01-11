<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/09/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class PlanoTrabalhoINT extends InfraINT {

  public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado) {
    $objPlanoTrabalhoDTO = new PlanoTrabalhoDTO();
    $objPlanoTrabalhoDTO->retNumIdPlanoTrabalho();
    $objPlanoTrabalhoDTO->retStrNome();

    if ($strValorItemSelecionado != null) {
      $objPlanoTrabalhoDTO->setBolExclusaoLogica(false);
      $objPlanoTrabalhoDTO->adicionarCriterio(array('SinAtivo', 'IdPlanoTrabalho'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array('S', $strValorItemSelecionado), InfraDTO::$OPER_LOGICO_OR);
    }

    $objPlanoTrabalhoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objPlanoTrabalhoRN = new PlanoTrabalhoRN();
    $arrObjPlanoTrabalhoDTO = $objPlanoTrabalhoRN->listar($objPlanoTrabalhoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjPlanoTrabalhoDTO, 'IdPlanoTrabalho', 'Nome');
  }
}
