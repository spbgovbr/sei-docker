<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/09/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class EtapaTrabalhoINT extends InfraINT {

  public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdPlanoTrabalho = '') {
    $objEtapaTrabalhoDTO = new EtapaTrabalhoDTO();
    $objEtapaTrabalhoDTO->retNumIdEtapaTrabalho();
    $objEtapaTrabalhoDTO->retStrNome();
    $objEtapaTrabalhoDTO->retNumOrdem();

    if ($numIdPlanoTrabalho !== '') {
      $objEtapaTrabalhoDTO->setNumIdPlanoTrabalho($numIdPlanoTrabalho);
    }

    if ($strValorItemSelecionado != null) {
      $objEtapaTrabalhoDTO->setBolExclusaoLogica(false);
      $objEtapaTrabalhoDTO->adicionarCriterio(array('SinAtivo', 'IdEtapaTrabalho'), array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL), array('S', $strValorItemSelecionado), InfraDTO::$OPER_LOGICO_OR);
    }

    $objEtapaTrabalhoDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objEtapaTrabalhoRN = new EtapaTrabalhoRN();
    $arrObjEtapaTrabalhoDTO = $objEtapaTrabalhoRN->listar($objEtapaTrabalhoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjEtapaTrabalhoDTO, 'IdEtapaTrabalho', 'Nome');
  }
}
