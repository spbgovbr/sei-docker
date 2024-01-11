<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 21/09/2022 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class AtividadeUnidadeINT extends InfraINT {

  public static function montarSelectUnidadesPermissaoUsuario($numIdSelecionado=null){

    $objInfraSip = new InfraSip(SessaoSEI::getInstance());

    $ret = array_values($objInfraSip->carregarUnidades(SessaoSEI::getInstance()->getNumIdSistema(),SessaoSEI::getInstance()->getNumIdUsuario()));

    InfraArray::ordenarArray($ret,InfraSip::$WS_UNIDADE_SIGLA,InfraArray::$TIPO_ORDENACAO_ASC);

    $arrUnidades = array();

    foreach($ret as $uni){
      //somente unidades ativas
      if ($uni[InfraSip::$WS_UNIDADE_SIN_ATIVO]=='S'){
        $arrUnidades[$uni[InfraSip::$WS_UNIDADE_ID]] = $uni[InfraSip::$WS_UNIDADE_SIGLA] . ' - ' . $uni[InfraSip::$WS_UNIDADE_DESCRICAO];
      }
    }

    if (InfraArray::contar($arrUnidades)==1){
      $strPrimeiroItemValor = null;
      $strPrimeiroItemDescricao = null;
    }else{
      $strPrimeiroItemValor = 'null';
      $strPrimeiroItemDescricao = ' ';
    }

    return parent::montarSelectArray($strPrimeiroItemValor, $strPrimeiroItemDescricao, $numIdSelecionado, $arrUnidades);
  }

  public static function montarSelectTipo($strValorItemSelecionado=null){

    $objAtividadeUnidadeRN = new AtividadeUnidadeRN();
    $arrObjInfraValorStaDTO = $objAtividadeUnidadeRN->listarValoresTipo();

    return parent::montarSelectArrInfraDTO(null, null, $strValorItemSelecionado, $arrObjInfraValorStaDTO, 'StaValor', 'Descricao');
  }
}
