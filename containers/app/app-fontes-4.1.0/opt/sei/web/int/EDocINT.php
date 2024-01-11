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

class EDocINT extends InfraINT {

	public static function montarVisualizacaoDocumento($dblIdDocumentoEdoc,$bolPublicacao=false){
  	$objEDocRN = new EDocRN();
    $dto = new DocumentoDTO();
    $dto->setDblIdDocumentoEdoc($dblIdDocumentoEdoc);
    
		$strConteudo = $objEDocRN->consultarHTMLDocumentoRN1204($dto);

    $posBody = strpos($strConteudo,'<body>');
    if ($posBody !== false){
        
      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->retStrSinBloqueado();
			$objDocumentoDTO->retStrStaProtocoloProtocolo();
			$objDocumentoDTO->retStrStaDocumento();
      $objDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
      $objDocumentoDTO->setDblIdDocumentoEdoc($dblIdDocumentoEdoc);
        
      $objDocumentoRN = new DocumentoRN();
      $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);
        
      if ($objDocumentoDTO != null){
        
        $objDocumentoRN->bloquearConsultado($objDocumentoDTO);
        
        $strConteudo = substr($strConteudo,0,$posBody).'<body>Processo Nº '.$objDocumentoDTO->getStrProtocoloProcedimentoFormatado().'<br /><br /><div>'.substr($strConteudo,$posBody+6);
      }
    }
    return $strConteudo;
	}
	

  
	public static function converterParaEditorInterno($conteudo) {
	  
    $objEstiloDTO = new EstiloDTO();
    $objEstiloDTO->retTodos();
    
    $objEstiloRN = new EstiloRN();
    $arrObjEstiloDTO = $objEstiloRN->listar($objEstiloDTO);
    $arrEstilos = array();
    foreach ($arrObjEstiloDTO as $objEstiloDTO) {
      $txtEstilo=str_replace("'","",$objEstiloDTO->getStrFormatacao());
      $txtEstilo=str_replace(",",";",$txtEstilo);
      $arrEstilos[$objEstiloDTO->getStrNome()]=$txtEstilo;
    }
	  
	  $arrEstilos["Texto_justificado_Recuo_Primeria_Linha"]=$arrEstilos["Texto_Justificado_Recuo_Primeira_Linha"];
	
	  $corpo=substr($conteudo,stripos($conteudo,"<body>")+6);
	  $corpo=substr($corpo,0,stripos($corpo,"</body>"));
	  $saida=array();
	  //separa todas as DIV, BR e Table
	  preg_match_all('%<br />|<(\w*)[^>]*>(.*?)</\1>%si', $corpo, $result, PREG_SET_ORDER);
	  $tagsNaoProcessadas="";
	  for ($i = 0; $i < InfraArray::contar($result)-1; $i++) {
	    switch (strtolower($result[$i][1])){
	      case 'div':	        
	      case 'table':
	      case 'b':
	      case 'tr':
	      case 'td':
	      case null:
	        break;
	      default:
	        $tagsNaoProcessadas.=$result[$i][1].' ';
	    }
	    
	  }  
	  if ($tagsNaoProcessadas!="") {
	    InfraDebug::getInstance()->gravar('Tags não processadas:'.$tagsNaoProcessadas);
	  }
	  preg_match_all('%<div[^>]*>(.*?)</div>|<br />|<table[^>]*>(.*?)</table>%si', $corpo, $result, PREG_SET_ORDER);
    
	  for ($i = 3; $i < InfraArray::contar($result)-1; $i++) {
	    $tag=strtolower(substr($result[$i][0],1,3));
	    //se for BR só retorna 1 elemento
	    //InfraDebug::getInstance()->gravar('Tag:'.$tag);
	    switch ($tag) {
	      case 'br ':	        
	        //seta segundo elemento como BR para colocar dentro do P
	        $result[$i][1]="<br />";
	        $classe = "Texto_Justificado_Recuo_Primeira_Linha";	 
	        $texto='<p style="'.$arrEstilos[$classe].'">&nbsp;</p>';
	        break;
	      case 'div':
	        //sendo DIV o segundo elemento é o conteúdo da DIV
	        if (preg_match('/<div class="([\wÃãáÁàÀâÂéÉêÊíÍóÓõÕôÔúÚüÜçÇ]*)">/si', $result[$i][0], $regs)) {
	          $classe = $regs[1];	          
	        } else {
	          $classe ="";// não possui classe de formatacao
	          InfraDebug::getInstance()->gravar('classe do paragrafo:'.$result[$i][0]);
	        }
	        //se for classe micron, divide o numero de BR por 4 e utiliza a classe padrão
	        if ($classe=="Micron") {
	          $classe = "Texto_Justificado_Recuo_Primeira_Linha";
	          preg_match_all('%<br />%si', $result[$i][0], $micron, PREG_PATTERN_ORDER);
	          $cntMicron=InfraArray::contar($micron[0]);
	          $txtBr="";
	          //ao inves de contar, substitui os microns por 1 paragrafo, independente de quantidade.
	          //for ($j=0;$j<$cntMicron;$j+=6){
	          //  $txtBr.="<br />";
	          //}
	          $result[$i][1]=$txtBr.'&nbsp;';
	        }	        
	        $texto='<p style="'.$arrEstilos[$classe].'">'.$result[$i][1].'</p>';
	        break;
	      case 'tab':
	        $x=0;
	        $texto=$result[$i][0];  //coloca a table no formato original
	        while (preg_match('/<div class="([\wÃãáÁàÀâÂéÉêÊíÍóÓõÕôÔúÚüÜçÇ]*)">/si',$texto,$resultDiv)==1) {
	          //InfraDebug::getInstance()->gravar('Iteração:'.$x++);
	          //InfraDebug::getInstance()->gravar('ResultDiv:'.$resultDiv[1]);
	          $pattern='/<div class="'.$resultDiv[1].'">/si';
	          $replace='<div style="'.$arrEstilos[$resultDiv[1]].'">';
	          if ($x>20) break;
	          $texto=preg_replace($pattern, $replace, $texto);	          
	        }	        
	        break;
	    }
	    
  	  $saida[]=$texto;
	  }
	
	  $ret="";
	  for ($i=0;$i<InfraArray::contar($saida);$i++)
	    $ret.=$saida[$i]."\n";
	  //print_r($ret);
	 
	  $ret = str_replace(array('<o:p>','</o:p>'),'', $ret);
	  
	  return $ret;
	
	}
  
  
}
?>