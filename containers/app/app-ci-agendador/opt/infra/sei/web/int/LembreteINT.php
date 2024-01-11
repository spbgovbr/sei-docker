<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/08/2014 - criado por bcu
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class LembreteINT extends InfraINT {

  public static function montarSelectConteudo($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdUsuario=''){
    $objLembreteDTO = new LembreteDTO();
    $objLembreteDTO->retNumIdLembrete();
    $objLembreteDTO->retStrConteudo();

    if ($numIdUsuario!==''){
      $objLembreteDTO->setNumIdUsuario($numIdUsuario);
    }

    if ($strValorItemSelecionado!=null){
      $objLembreteDTO->setBolExclusaoLogica(false);
      $objLembreteDTO->adicionarCriterio(array('SinAtivo','IdLembrete'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objLembreteDTO->setOrdStrConteudo(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objLembreteRN = new LembreteRN();
    $arrObjLembreteDTO = $objLembreteRN->listar($objLembreteDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjLembreteDTO, 'IdLembrete', 'Conteudo');
  }

  public static function atualizarLembrete($operacao, $objLembreteDTO){
    //try {

      $objLembreteRN=new LembreteRN();

      switch ($operacao){
        case 'N':
          $ret=$objLembreteRN->cadastrar($objLembreteDTO);
          return $ret->getNumIdLembrete();
        case 'A':
          $objLembreteRN->alterar($objLembreteDTO);
          return "true";
        case 'D':
          $objLembreteRN->desativar(array($objLembreteDTO));
          return "true";
        default:
          return "false";
      }


    //} catch (Exception $e) {
    //  return $e.toString();
    //}
  }
}
?>