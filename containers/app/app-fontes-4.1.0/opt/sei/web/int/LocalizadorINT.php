<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/05/2008 - criado por fbv
*
* Versão do Gerador de Código: 1.16.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class LocalizadorINT extends InfraINT {

  public static function montarSelectSeqLocalizador($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdUnidade='', $numIdTipoLocalizador='', $numIdTipoSuporte='', $numIdLugarLocalizador=''){
    $objLocalizadorDTO = new LocalizadorDTO();
    $objLocalizadorDTO->retNumIdLocalizador();
    $objLocalizadorDTO->retNumSeqLocalizador();
    $objLocalizadorDTO->setOrdNumSeqLocalizador(InfraDTO::$TIPO_ORDENACAO_ASC);


    if ($numIdUnidade!==''){
      $objLocalizadorDTO->setNumIdUnidade($numIdUnidade);
    }

    if ($numIdTipoLocalizador!==''){
      $objLocalizadorDTO->setNumIdTipoLocalizador($numIdTipoLocalizador);
    }

    if ($numIdTipoSuporte!==''){
      $objLocalizadorDTO->setNumIdTipoSuporte($numIdTipoSuporte);
    }

    if ($numIdLugarLocalizador!==''){
      $objLocalizadorDTO->setNumIdLugarLocalizador($numIdLugarLocalizador);
    }

    $objLocalizadorRN = new LocalizadorRN();
    $arrObjLocalizadorDTO = $objLocalizadorRN->listar($objLocalizadorDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjLocalizadorDTO, 'IdLocalizador', 'SeqLocalizador');
  }

  public static function montarSelectStaEstadoRI0681($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objLocalizadorRN = new LocalizadorRN();
    $arrObjEstadoLocalizadorDTO = $objLocalizadorRN->listarEstadosLocalizadorRN0680();
    
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjEstadoLocalizadorDTO, 'StaEstado', 'Descricao');
  }
 
  public static function sugestaodelocalizadorRI0683($numIdTipoLocalizador){
    if (($numIdTipoLocalizador=='null') || ($numIdTipoLocalizador=='')){
      $objLocalizadorDTO = new LocalizadorDTO();
      $objLocalizadorDTO->setStrSiglaTipoLocalizador(' ');
      $objLocalizadorDTO->setNumSeqLocalizador(' ');
      return $objLocalizadorDTO;
    }
    
  	$objTipoLocalizadorRN = new TipoLocalizadorRN();
  	$objTipoLocalizadorDTO = new TipoLocalizadorDTO();
  	$objTipoLocalizadorDTO->retStrSigla();
  	$objTipoLocalizadorDTO->setNumIdTipoLocalizador($numIdTipoLocalizador);
  	$objTipoLocalizadorDTO = $objTipoLocalizadorRN->consultarRN0607($objTipoLocalizadorDTO);
  	
  	if ($objTipoLocalizadorDTO===null){
  	  throw new InfraException('Sigla para o tipo de localizador não encontrada.');
  	}
  	
 		$strSigla = $objTipoLocalizadorDTO->getStrSigla();
  	
    $objLocalizadorRN = new LocalizadorRN();
    $objLocalizadorDTO = new LocalizadorDTO();
    $objLocalizadorDTO->retNumSeqLocalizador();
    $objLocalizadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    $objLocalizadorDTO->setNumIdTipoLocalizador($numIdTipoLocalizador);
    
    $objLocalizadorDTO->setOrdNumSeqLocalizador(InfraDTO::$TIPO_ORDENACAO_DESC);
    $arr = $objLocalizadorRN->listarRN0622($objLocalizadorDTO);
    
    $numSeqLocalizador = 1;
  	if (count($arr) > 0) {
  		$numSeqLocalizador = $arr[0]->getNumSeqLocalizador() + 1;
  	} 
    
    
    $objLocalizadorDTO = new LocalizadorDTO();
    $objLocalizadorDTO->setStrSiglaTipoLocalizador($strSigla);
    $objLocalizadorDTO->setNumSeqLocalizador($numSeqLocalizador);
    
    return $objLocalizadorDTO;
  }
    
  public static function buscarEtiquetasRI1127($arrNumIdLocalizador){

  	$arrObjLocalizadorDTO = array();
    $obj = new LocalizadorDTO();
    $obj->retNumIdLocalizador();
    $obj->retStrSiglaTipoLocalizador();
    $obj->retNumSeqLocalizador();
        
    $obj->setOrdStrSiglaTipoLocalizador(InfraDTO::$TIPO_ORDENACAO_ASC);
    $obj->setOrdNumSeqLocalizador(InfraDTO::$TIPO_ORDENACAO_ASC);
    $obj->setNumIdLocalizador($arrNumIdLocalizador,InfraDTO::$OPER_IN);
    
    $objLocalizadorRN = new LocalizadorRN();
    $arrObjLocalizadorDTO = $objLocalizadorRN->listarRN0622($obj);
    
    $arrEtiquetas = array();
    $arrLinhas = array();
   
    for ($i=0;$i<count($arrObjLocalizadorDTO);$i++){  	    
	    $sigla = $arrObjLocalizadorDTO[$i]->getStrSiglaTipoLocalizador().'<br /><br />';
	    $sequencia = $arrObjLocalizadorDTO[$i]->getNumSeqLocalizador();
   
      $arrColunas = array();
      $arrColunas[] = $arrObjLocalizadorDTO[$i]->getNumIdLocalizador();
      $arrColunas[] = $sigla.$sequencia;
      $arrLinhas[] = $arrColunas;
    }

     return PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrLinhas);     
  }  
  
  public static function conjuntoPorIdentificacaoRI1132($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdTipoLocalizador='', $numIdLocalizador=''){
    $objLocalizadorDTO = new LocalizadorDTO();
    $objLocalizadorDTO->retNumIdLocalizador();
    $objLocalizadorDTO->retStrSiglaTipoLocalizador();
    $objLocalizadorDTO->retNumSeqLocalizador();
    $objLocalizadorDTO->retStrIdentificacao();
    
    if ($numIdTipoLocalizador==''){
      $objLocalizadorDTO->setNumIdTipoLocalizador(null);
    }else{
      $objLocalizadorDTO->setNumIdTipoLocalizador($numIdTipoLocalizador);
    }
    
    if ($numIdLocalizador!=''){
    	$objLocalizadorDTO->setNumIdLocalizador($numIdLocalizador, InfraDTO::$OPER_DIFERENTE);
    }
    
    $objLocalizadorDTO->setStrStaEstado(LocalizadorRN::$EA_ABERTO);

    //$objLocalizadorDTO->setOrdStrIdentificacao(InfraDTO::$TIPO_ORDENACAO_DESC);
    $objLocalizadorDTO->setOrdStrSiglaTipoLocalizador(InfraDTO::$TIPO_ORDENACAO_ASC);
    $objLocalizadorDTO->setOrdNumSeqLocalizador(InfraDTO::$TIPO_ORDENACAO_DESC);
    
    
    $objLocalizadorRN = new LocalizadorRN();
    $arrObjLocalizadorDTO = $objLocalizadorRN->listarRN0622($objLocalizadorDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjLocalizadorDTO, 'IdLocalizador', 'Identificacao');
  }
  
  public static function montarIdentificacaoRI1132($strSiglaLocalizador, $strSeqLocalizador){
  	return PaginaSEI::tratarHTML(($strSiglaLocalizador!='' && $strSeqLocalizador!='')?$strSiglaLocalizador.'-'.$strSeqLocalizador:'');
  }
   
}
?>