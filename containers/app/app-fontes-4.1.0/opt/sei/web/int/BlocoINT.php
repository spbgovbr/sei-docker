<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/09/2009 - criado por fbv@trf4.gov.br
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class BlocoINT extends InfraINT {

  public static function montarSelectAssinatura($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objBlocoDTO = new BlocoDTO();
    $objBlocoDTO->retNumIdBloco();
    $objBlocoDTO->retStrDescricao();
    $objBlocoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    $objBlocoDTO->setStrStaTipo(BlocoRN::$TB_ASSINATURA);
    $objBlocoDTO->setStrStaEstado(array(BlocoRN::$TE_ABERTO,BlocoRN::$TE_RETORNADO),InfraDTO::$OPER_IN);
    $objBlocoDTO->setOrdNumIdBloco(InfraDTO::$TIPO_ORDENACAO_DESC);

    $objBlocoRN = new BlocoRN();
    $arrObjBlocoDTO = $objBlocoRN->listarRN1277($objBlocoDTO);
    
    foreach($arrObjBlocoDTO as $objBlocoDTO){
    	$objBlocoDTO->setStrDescricao($objBlocoDTO->getNumIdBloco().' - '.$objBlocoDTO->getStrDescricao());
    }

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjBlocoDTO, 'IdBloco', 'Descricao');
  }

  public static function montarSelectStaEstadoRI1283($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $strStaEstado=''){
    $objBlocoRN = new BlocoRN();
    $arrObjEstadoBlocoDTO = $objBlocoRN->listarValoresEstadoRN1265();
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjEstadoBlocoDTO, 'StaEstado', 'Descricao');
  }
  
  public static function montarSelectMultiploFiltroBlocos(BlocoDTO $objBlocoDTO){

    $strOptions = '';

    $objBlocoRN = new BlocoRN();
    $arrObjEstadoBlocoDTO = $objBlocoRN->listarValoresEstadoRN1265();

    foreach ($arrObjEstadoBlocoDTO as $objEstadoBlocoDTO) {

      if ($objBlocoDTO->getStrStaTipo()==BlocoRN::$TB_INTERNO && ($objEstadoBlocoDTO->getStrStaEstado()==BlocoRN::$TE_DISPONIBILIZADO || $objEstadoBlocoDTO->getStrStaEstado()==BlocoRN::$TE_RETORNADO || $objEstadoBlocoDTO->getStrStaEstado()==BlocoRN::$TE_RECEBIDO)){
        continue;
      }

      $strOptions .= '<option value="'.$objEstadoBlocoDTO->getStrStaEstado().'"';
      if (in_array($objEstadoBlocoDTO->getStrStaEstado(), $objBlocoDTO->getStrStaEstado())) {
        $strOptions .= ' selected="selected"';
      }

      $strOptions .= '>'.PaginaSEI::tratarHTML($objEstadoBlocoDTO->getStrDescricao()).'</option>'."\n";
    }

    return $strOptions;
  }

  public static function montarSelectGeradora($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $varStaTipo){

    $objRelBlocoUnidadeDTO = new RelBlocoUnidadeDTO();
    $objRelBlocoUnidadeDTO->setDistinct(true);
    $objRelBlocoUnidadeDTO->retNumIdUnidadeBloco();
    $objRelBlocoUnidadeDTO->retStrSiglaUnidadeBloco();
    $objRelBlocoUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    $objRelBlocoUnidadeDTO->setOrdStrSiglaUnidadeBloco(InfraDTO::$TIPO_ORDENACAO_ASC);

    if (!is_array($varStaTipo)) {
      $varStaTipo = array($varStaTipo);
    }

    $objRelBlocoUnidadeDTO->setStrStaTipoBloco($varStaTipo, InfraDTO::$OPER_IN);
    $objRelBlocoUnidadeDTO->setStrStaEstadoBloco(BlocoRN::$TE_CONCLUIDO, InfraDTO::$OPER_DIFERENTE);

    $objRelBlocoUnidadeRN = new RelBlocoUnidadeRN();
    $arrObjRelBlocoUnidadeDTO = $objRelBlocoUnidadeRN->listarRN1304($objRelBlocoUnidadeDTO);

    if (count($arrObjRelBlocoUnidadeDTO) == 0){
      $objRelBlocoUnidadeDTO = new RelBlocoUnidadeDTO();
      $objRelBlocoUnidadeDTO->setNumIdUnidadeBloco(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objRelBlocoUnidadeDTO->setStrSiglaUnidadeBloco(SessaoSEI::getInstance()->getStrSiglaUnidadeAtual());
      $arrObjRelBlocoUnidadeDTO[] = $objRelBlocoUnidadeDTO;
    }

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjRelBlocoUnidadeDTO, 'IdUnidadeBloco', 'SiglaUnidadeBloco');
  }

  public static function pesquisarLinkEditor($strNumero)
  {

    $objInfraException = new InfraException();

    if (InfraString::isBolVazia($strNumero)) {
      $objInfraException->lancarValidacao('Número do Bloco de Assinatura para pesquisa não informado.');
    }

    $strNumBloco = InfraUtil::retirarFormatacao(trim($strNumero), false);

    $objBlocoDTOPesquisa = new BlocoDTO();
    $objBlocoDTOPesquisa->retNumIdUnidade();
    $objBlocoDTOPesquisa->retNumIdBloco();
    $objBlocoDTOPesquisa->retStrStaEstado();
    $objBlocoDTOPesquisa->setNumIdBloco($strNumBloco);
    $objBlocoDTOPesquisa->setStrStaTipo(BlocoRN::$TB_ASSINATURA);

    $objBlocoRN = new BlocoRN();
    $arrObjBlocoDTOPesquisa = $objBlocoRN->pesquisar($objBlocoDTOPesquisa);

    if(count($arrObjBlocoDTOPesquisa)==0){
      $objInfraException->lancarValidacao('Bloco de Assinatura não encontrado.');
    }else{
      $objBlocoDTOPesquisa = $arrObjBlocoDTOPesquisa[0];

      if ($objBlocoDTOPesquisa->getNumIdUnidade()!=SessaoSEI::getInstance()->getNumIdUnidadeAtual() && $objBlocoDTOPesquisa->getStrStaEstado()!=BlocoRN::$TE_RECEBIDO){
        $objInfraException->lancarValidacao('Unidade ' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . ' não têm acesso ao Bloco ' . $strNumBloco . '.');
      }

    }

    return array('id_bloco' => $strNumBloco);
  }
}
?>