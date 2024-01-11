<?
  /**
  * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
  * 03/07/2019 - criado por cle@trf4.jus.br
  * Versão do Gerador de Código: 1.42.0
  */

  require_once dirname(__FILE__).'/../Infra.php';

  class InfraSessaoRestINT extends InfraINT {

    public static function montarSelectIdUsuario($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
      $objInfraSessaoRestDTO = new InfraSessaoRestDTO();
      $objInfraSessaoRestDTO->retStrIdInfraSessaoRest();
      $objInfraSessaoRestDTO->retNumIdUsuario();

      $objInfraSessaoRestDTO->setOrdNumIdUsuario(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objInfraSessaoRestRN = new InfraSessaoRestRN();
      $arrObjInfraSessaoRestDTO = $objInfraSessaoRestRN->listar($objInfraSessaoRestDTO);

      return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjInfraSessaoRestDTO, 'IdInfraSessaoRest', 'IdUsuario');
    }
  }
