<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 01/07/2008 - criado por fbv
*
* Versão do Gerador de Código: 1.19.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class SerieINT extends InfraINT {

  public static function montarSelectAcessoExterno($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrNumIdSerie){

    $objSerieDTO = new SerieDTO();
    $objSerieDTO->retNumIdSerie();
    $objSerieDTO->retStrNome();
    $objSerieDTO->setStrSinUsuarioExterno("S");
    $objSerieDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
    $objSerieDTO->setNumIdSerie($arrNumIdSerie,InfraDTO::$OPER_IN);

    $objSerieRN = new SerieRN();
    $arrObjSerieDTO = $objSerieRN->listarRN0646($objSerieDTO);
        
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjSerieDTO, 'IdSerie', 'Nome');
  }
  
  public static function montarSelectNomeDescricaoPesquisaPublicacao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrNumIdOrgao=''){

    $objSeriePublicacaoDTO = new SeriePublicacaoDTO();
    $objSeriePublicacaoDTO->setDistinct(true);
    $objSeriePublicacaoDTO->retNumIdSerie();
    $objSeriePublicacaoDTO->retStrNomeSerie();

    if ($arrNumIdOrgao!=''){
      $objSeriePublicacaoDTO->setNumIdOrgao($arrNumIdOrgao,InfraDTO::$OPER_IN);
    }

    $objSeriePublicacaoDTO->setOrdStrNomeSerie(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objSeriePublicacaoRN = new SeriePublicacaoRN();
    $arrObjSeriePublicacaoDTO = $objSeriePublicacaoRN->listar($objSeriePublicacaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjSeriePublicacaoDTO, 'IdSerie', 'NomeSerie');
  }

  public static function montarSelectNomeRI0802($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdGrupoSerie=''){
    $objSerieDTO = new SerieDTO();
    $objSerieDTO->retNumIdSerie();
    $objSerieDTO->retStrNome();
    $objSerieDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);


    if ($numIdGrupoSerie!==''){
      $objSerieDTO->setNumIdGrupoSerie($numIdGrupoSerie);
    }

    if ($strValorItemSelecionado!=null){
      
      $objSerieDTO->setBolExclusaoLogica(false);
      $objSerieDTO->adicionarCriterio(array('SinAtivo','IdSerie'),
                                            array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),
                                            array('S',$strValorItemSelecionado),
                                            InfraDTO::$OPER_LOGICO_OR);
    }

    $objSerieRN = new SerieRN();
    $arrObjSerieDTO = $objSerieRN->listarRN0646($objSerieDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjSerieDTO, 'IdSerie', 'Nome');
  }

  public static function montarSelectNomeExternos($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdGrupoSerie=''){

    $objSerieRN = new SerieRN();

  	$objSerieDTO = new SerieDTO();
  	$objSerieDTO->retNumIdSerie();
  	$objSerieDTO->retStrNome();
  
  	if ($numIdGrupoSerie!==''){
  		$objSerieDTO->setNumIdGrupoSerie($numIdGrupoSerie);
  	}
  
  	if ($strValorItemSelecionado!=null){
  
  		$objSerieDTO->setBolExclusaoLogica(false);

      $objSerieDTO->adicionarCriterio(array('SinAtivo', 'SinInterno', 'StaAplicabilidade'),
          array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IN),
          array('S', 'N', array(SerieRN::$TA_INTERNO_EXTERNO, SerieRN::$TA_EXTERNO)),
          array(InfraDTO::$OPER_LOGICO_AND, InfraDTO::$OPER_LOGICO_AND),
          'cTipoSelecionavel');

  		$objSerieDTO->adicionarCriterio(array('IdSerie'),
  				array(InfraDTO::$OPER_IGUAL),
  				array($strValorItemSelecionado),
  				null,
          'cTipoUtilizado');

  		$objSerieDTO->agruparCriterios(array('cTipoSelecionavel','cTipoUtilizado'), InfraDTO::$OPER_LOGICO_OR);

  	}else{
  	  $objSerieDTO->setStrStaAplicabilidade(array(SerieRN::$TA_INTERNO_EXTERNO, SerieRN::$TA_EXTERNO),InfraDTO::$OPER_IN);
      $objSerieDTO->setStrSinInterno('N');
  	}

  	$objSerieDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

  	$arrObjSerieDTO = $objSerieRN->listarRN0646($objSerieDTO);

  	return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjSerieDTO, 'IdSerie', 'Nome');
  }

  public static function montarSelectUsuarioExterno($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdAcessoExterno){

    $objRelAcessoExtSerieDTO = new RelAcessoExtSerieDTO();
    $objRelAcessoExtSerieDTO->retNumIdAcessoExterno();
    $objRelAcessoExtSerieDTO->retNumIdSerie();
    $objRelAcessoExtSerieDTO->setNumIdAcessoExterno($numIdAcessoExterno);

    $objRelAcessoExtSerieRN = new RelAcessoExtSerieRN();
    $arrIdSerie = InfraArray::converterArrInfraDTO($objRelAcessoExtSerieRN->listar($objRelAcessoExtSerieDTO),'IdSerie');

    $arrObjSerieDTO = array();

    if (count($arrIdSerie)) {
      $objSerieDTO = new SerieDTO();
      $objSerieDTO->retNumIdSerie();
      $objSerieDTO->retStrNome();
      $objSerieDTO->setNumIdSerie($arrIdSerie, InfraDTO::$OPER_IN);
      $objSerieDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objSerieRN = new SerieRN();
      $arrObjSerieDTO = $objSerieRN->listarRN0646($objSerieDTO);
    }

  	return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjSerieDTO, 'IdSerie', 'Nome');
  }

  public static function montarSelectNomeGerados($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdGrupoSerie=''){
    $objSerieDTO = new SerieDTO();
    $objSerieDTO->retNumIdSerie();
    $objSerieDTO->retStrNome();

    if ($numIdGrupoSerie!==''){
      $objSerieDTO->setNumIdGrupoSerie($numIdGrupoSerie);
    }

    if ($strValorItemSelecionado!=null){

      $objSerieDTO->setBolExclusaoLogica(false);
      $objSerieDTO->adicionarCriterio(array('SinAtivo','IdSerie'),
          array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),
          array('S',$strValorItemSelecionado),
          InfraDTO::$OPER_LOGICO_OR);

      $objSerieDTO->adicionarCriterio(array('StaAplicabilidade', 'IdSerie'),
          array(InfraDTO::$OPER_IN, InfraDTO::$OPER_IGUAL),
          array(array(SerieRN::$TA_INTERNO_EXTERNO, SerieRN::$TA_INTERNO),$strValorItemSelecionado),
          InfraDTO::$OPER_LOGICO_OR);
    }else{
      $objSerieDTO->setStrStaAplicabilidade(array(SerieRN::$TA_INTERNO_EXTERNO, SerieRN::$TA_INTERNO),InfraDTO::$OPER_IN);
    }

    $objSerieDTO->setStrSinInterno('N');
    $objSerieDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objSerieRN = new SerieRN();
    $arrObjSerieDTO = $objSerieRN->listarRN0646($objSerieDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjSerieDTO, 'IdSerie', 'Nome');
  }

  public static function obterDadosRI0954($numIdSerie){
    $objSerieDTO = new SerieDTO();
    $objSerieDTO->retStrDescricao();
    $objSerieDTO->setNumIdSerie($numIdSerie);

    $objSerieRN = new SerieRN();
    return $objSerieRN->consultarRN0644($objSerieDTO);
  }

  public static function montarSelectStaNumeracaoRI0797($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objSerieRN = new SerieRN();
    $arrObjTipoDTO = $objSerieRN->listarTiposNumeracaoRN0795();
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTipoDTO, 'StaTipo', 'Descricao');
  }

  public static function montarSelectStaAplicabilidade($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
  	$objSerieRN = new SerieRN();
  	$arrObjTipoDTO = $objSerieRN->listarTiposAplicabilidade();
  	return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjTipoDTO, 'StaTipo', 'Descricao');
  }

  public static function montarSelectSugestaoAssuntos($numIdSerie){
    
    $objRelSerieAssuntoDTO = new RelSerieAssuntoDTO();
    $objRelSerieAssuntoDTO->retNumIdAssunto();
    $objRelSerieAssuntoDTO->retStrCodigoEstruturadoAssunto();
    $objRelSerieAssuntoDTO->retStrDescricaoAssunto();
    $objRelSerieAssuntoDTO->retNumSequencia();
    $objRelSerieAssuntoDTO->setNumIdSerie($numIdSerie);
    $objRelSerieAssuntoDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);
  
    $objRelSerieAssuntoRN = new RelSerieAssuntoRN();
    $arrObjRelSerieAssuntoDTO = $objRelSerieAssuntoRN->listar($objRelSerieAssuntoDTO);
  
    foreach($arrObjRelSerieAssuntoDTO as $dto){
      $dto->setStrDescricaoAssunto(AssuntoINT::formatarCodigoDescricaoRI0568($dto->getStrCodigoEstruturadoAssunto(),$dto->getStrDescricaoAssunto()));
    }
  
    return parent::montarSelectArrInfraDTO(null, null, null, $arrObjRelSerieAssuntoDTO, 'IdAssunto', 'DescricaoAssunto');
  }

  public static function autoCompletarSerie($strPalavrasPesquisa,$strStaAplicabilidade=null){

    $objSerieDTO = new SerieDTO();
    $objSerieDTO->retNumIdSerie();
    $objSerieDTO->retStrNome();
    if ($strStaAplicabilidade!=null){
      if (strpos($strStaAplicabilidade,',')!==false){
        $objSerieDTO->setStrStaAplicabilidade(explode(',',$strStaAplicabilidade),InfraDTO::$OPER_IN);
      } else {
        $objSerieDTO->setStrStaAplicabilidade($strStaAplicabilidade);
      }
    }

    $objSerieDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objSerieRN = new SerieRN();

    $arrObjSerieDTO = $objSerieRN->listarRN0646($objSerieDTO);

    $strPalavrasPesquisa = trim($strPalavrasPesquisa);
    if ($strPalavrasPesquisa != ''){
      $ret = array();
      $strPalavrasPesquisa = strtolower($strPalavrasPesquisa);
      foreach($arrObjSerieDTO as $objSerieDTO){
        if (strpos(strtolower($objSerieDTO->getStrNome()),$strPalavrasPesquisa)!==false){
          $ret[] = $objSerieDTO;
        }
      }
    }else{
      $ret = $arrObjSerieDTO;
    }
    return $ret;
  }

  public static function montarSelectMultiploProcedimento($dblIdProcedimento, $arrIdSeriesSelecionadas){

    $strOptions = '';

    $objDocumentoDTO = new DocumentoDTO();
    $objDocumentoDTO->setDistinct(true);
    $objDocumentoDTO->retNumIdSerie();
    $objDocumentoDTO->retStrNomeSerie();
    $objDocumentoDTO->setDblIdProcedimento($dblIdProcedimento);
    $objDocumentoDTO->setOrdStrNomeSerie(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objDocumentoRN = new DocumentoRN();
    $arrObjDocumentoDTO = $objDocumentoRN->listarRN0008($objDocumentoDTO);

    foreach($arrObjDocumentoDTO as $objDocumentoDTO){
      $strOptions .= '<option value="'.$objDocumentoDTO->getNumIdSerie().'"';
      if (isset($_POST['selSerie'])){
        if (in_array($objDocumentoDTO->getNumIdSerie(), $arrIdSeriesSelecionadas)) {
          $strOptions .= ' selected="selected"';
        }
      }
      $strOptions .= '>'.PaginaSEI::tratarHTML($objDocumentoDTO->getStrNomeSerie()).'</option>'."\n";
    }
    return $strOptions;
  }

  public static function montarSelectSinalizacao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $arrObjSinalizacaoDTO = SerieRN::listarValoresSinalizacao();
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjSinalizacaoDTO, 'StaSinalizacao', 'Descricao');
  }

}
?>