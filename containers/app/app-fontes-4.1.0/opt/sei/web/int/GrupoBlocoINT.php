<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/08/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoBlocoINT extends InfraINT {

  public static function montarSelectUnidade($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){

    $objGrupoBlocoDTO = new GrupoBlocoDTO();
    $objGrupoBlocoDTO->retNumIdGrupoBloco();
    $objGrupoBlocoDTO->retStrNome();
    $objGrupoBlocoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    $objGrupoBlocoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    if ($strValorItemSelecionado!=null){
      $objGrupoBlocoDTO->setBolExclusaoLogica(false);
      $objGrupoBlocoDTO->adicionarCriterio(array('SinAtivo','IdGrupoBloco'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objGrupoBlocoRN = new GrupoBlocoRN();
    $arrObjGrupoBlocoDTO = $objGrupoBlocoRN->listar($objGrupoBlocoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjGrupoBlocoDTO, 'IdGrupoBloco', 'Nome');
  }

  public static function montarLinkBlocos($numIdGrupoBloco, $numRegistros, $strAcao){
    $ret = '';
    if ($numRegistros) {
      $ret .= '<a onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" target="_blank" '.
              ' href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcao.'&acao_origem='.$_GET['acao'].'&acao_retorno='.$strAcao.'&id_grupo_bloco='.$numIdGrupoBloco.'&sta_estado='.implode(',', array(BlocoRN::$TE_ABERTO, BlocoRN::$TE_DISPONIBILIZADO, BlocoRN::$TE_RECEBIDO, BlocoRN::$TE_RETORNADO, BlocoRN::$TE_CONCLUIDO))).'"'.
              ' class="ancoraPadraoAzul" style="padding:0 1em;" style="padding:0 1em;" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'">'.InfraUtil::formatarMilhares($numRegistros).'</a>';
    }else{
      $ret .= '0';
    }
    return $ret;
  }
}
