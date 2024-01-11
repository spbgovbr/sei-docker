<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class ReplicacaoFederacaoINT extends InfraINT {

  public static function montarSelectCadastro($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $strIdInstalacaoFederacao='', $strIdProtocoloFederacao=''){
    $objReplicacaoFederacaoDTO = new ReplicacaoFederacaoDTO();
    $objReplicacaoFederacaoDTO->retStrIdReplicacaoFederacao();
    $objReplicacaoFederacaoDTO->retDthCadastro();

    if ($strIdInstalacaoFederacao!==''){
      $objReplicacaoFederacaoDTO->setStrIdInstalacaoFederacao($strIdInstalacaoFederacao);
    }

    if ($strIdProtocoloFederacao!==''){
      $objReplicacaoFederacaoDTO->setStrIdProtocoloFederacao($strIdProtocoloFederacao);
    }

    $objReplicacaoFederacaoDTO->setOrdDthCadastro(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objReplicacaoFederacaoRN = new ReplicacaoFederacaoRN();
    $arrObjReplicacaoFederacaoDTO = $objReplicacaoFederacaoRN->listar($objReplicacaoFederacaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjReplicacaoFederacaoDTO, 'IdReplicacaoFederacao', 'Cadastro');
  }

  public static function montarSelectStaTipo($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objReplicacaoFederacaoRN = new ReplicacaoFederacaoRN();

    $arrObjTipoReplicacaoFederacaoDTO = $objReplicacaoFederacaoRN->listarValoresTipo();

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTipoReplicacaoFederacaoDTO, 'StaTipo', 'Descricao');

  }
}
