<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 18/11/2010 - criado por mga
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  
  $strParametros = '';
  if (isset($_GET['id_estatisticas'])){
  	$strParametros .= '&id_estatisticas='.$_GET['id_estatisticas'];
  }

  if (isset($_GET['id_unidade'])){
  	$strParametros .= '&id_unidade='.$_GET['id_unidade'];
  }
  
  if (isset($_GET['tabela_estatisticas'])){
  	$strParametros .= '&tabela_estatisticas='.$_GET['tabela_estatisticas'];
  }

  if (isset($_GET['id_orgao'])){
  	$strParametros .= '&id_orgao='.$_GET['id_orgao'];
  }
  
  if (isset($_GET['id_tipo_procedimento'])){
  	$strParametros .= '&id_tipo_procedimento='.$_GET['id_tipo_procedimento'];
  }

  if (isset($_GET['id_serie'])){
  	$strParametros .= '&id_serie='.$_GET['id_serie'];
  }

  if (isset($_GET['sta_protocolo'])){
  	$strParametros .= '&sta_protocolo='.$_GET['sta_protocolo'];
  }
  
  if (isset($_GET['mes'])){
  	$strParametros .= '&mes='.$_GET['mes'];
  }
  
  if (isset($_GET['ano'])){
  	$strParametros .= '&ano='.$_GET['ano'];
  }
  
  $objEstatisticasDTO = new EstatisticasDTO();
  
  switch($_GET['acao']){

    case 'estatisticas_detalhar_unidade':
    case 'estatisticas_detalhar_ouvidoria':	
    	
    	PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);
    	
    	if (InfraString::isBolVazia($_GET['id_estatisticas'])){
    		throw new InfraException('Detalhe da estatística não informado.');
    	}
    	
    	$objEstatisticasDTO->setDblIdEstatisticas($_GET['id_estatisticas']);
    	
    	$bolDetalhamentoDocumentos = false;
    	if ($_GET['tabela_estatisticas']==EstatisticasRN::$TIPO_ESTATISTICAS_DOCUMENTOS_GERADOS || $_GET['tabela_estatisticas']==EstatisticasRN::$TIPO_ESTATISTICAS_DOCUMENTOS_RECEBIDOS){
    	  $bolDetalhamentoDocumentos = true;
    	}
    	
    	if ($bolDetalhamentoDocumentos){
    	  $objEstatisticasDTO->setNumTipoFkDocumento(InfraDTO::$TIPO_FK_OBRIGATORIA);
    	}else{
    		$objEstatisticasDTO->setNumTipoFkProcedimento(InfraDTO::$TIPO_FK_OBRIGATORIA);
    	}
    	
   	  $objEstatisticasDTO->setNumTipoFkUnidade(InfraDTO::$TIPO_FK_OBRIGATORIA);
    	
    	switch($_GET['tabela_estatisticas']){
    			
    	  //estatíticas unidade e ouvidoria
    		case EstatisticasRN::$TIPO_ESTATISTICAS_GERADOS:
    	    $strTitulo = EstatisticasRN::$TITULO_ESTATISTICAS_GERADOS;
    	    break;

    		case EstatisticasRN::$TIPO_ESTATISTICAS_TRAMITACAO:
    	    $strTitulo = EstatisticasRN::$TITULO_ESTATISTICAS_TRAMITACAO;
    	    break;
    			
    		case EstatisticasRN::$TIPO_ESTATISTICAS_FECHADOS:
    	    $strTitulo = EstatisticasRN::$TITULO_ESTATISTICAS_FECHADOS;
    	    break;
    			
    		case EstatisticasRN::$TIPO_ESTATISTICAS_ABERTOS:
    	    $strTitulo = EstatisticasRN::$TITULO_ESTATISTICAS_ABERTOS;
    	    break;
    			
    		case EstatisticasRN::$TIPO_ESTATISTICAS_TEMPO:
    	    $strTitulo = EstatisticasRN::$TITULO_ESTATISTICAS_TEMPO;
    	    break;
    	    
    		case EstatisticasRN::$TIPO_ESTATISTICAS_DOCUMENTOS_GERADOS:
    	    $strTitulo = EstatisticasRN::$TITULO_ESTATISTICAS_DOCUMENTOS_GERADOS;
    	    break;

  	    case EstatisticasRN::$TIPO_ESTATISTICAS_DOCUMENTOS_RECEBIDOS:
  	      $strTitulo = EstatisticasRN::$TITULO_ESTATISTICAS_DOCUMENTOS_RECEBIDOS;
  	      break;
    	      	
    		default:
    		  throw new InfraException('Tipo do detalhe da estatística não informado.');
    	}

      if (isset($_GET['id_orgao'])){
        $objEstatisticasDTO->setNumIdOrgaoUnidade($_GET['id_orgao']);
      }
    	
      if (isset($_GET['id_unidade'])){
        $objEstatisticasDTO->setNumIdUnidade($_GET['id_unidade']);
      }
        
      if (isset($_GET['id_tipo_procedimento'])){
        $objEstatisticasDTO->setNumIdTipoProcedimento($_GET['id_tipo_procedimento']);
      }

      if (isset($_GET['id_serie'])){
        $objEstatisticasDTO->setNumIdSerieDocumento($_GET['id_serie']);
      }
      
      if (isset($_GET['sta_protocolo'])){
        $objEstatisticasDTO->setStrStaProtocoloDocumento($_GET['sta_protocolo']);
      }
      
    	if (isset($_GET['mes'])){
    	  $objEstatisticasDTO->setNumMes($_GET['mes']);
    	}
      
      if (isset($_GET['ano'])){
        $objEstatisticasDTO->setNumAno($_GET['ano']);
      }
    	
      if (isset($_GET['sta_ouvidoria'])){
        $objEstatisticasDTO->setStrStaOuvidoriaProcedimento($_GET['sta_ouvidoria']);
      }     

      
    	break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $objEstatisticasDTO->retDblIdEstatisticas();
  $objEstatisticasDTO->retDblIdProcedimento();
  $objEstatisticasDTO->retDblIdDocumento();
  $objEstatisticasDTO->retStrProtocoloFormatadoProcedimento();
  $objEstatisticasDTO->retStrSiglaOrgaoUnidade();
  $objEstatisticasDTO->retStrDescricaoOrgaoUnidade();
  $objEstatisticasDTO->retStrSiglaUnidade();
  $objEstatisticasDTO->retStrDescricaoUnidade();
  $objEstatisticasDTO->retStrNomeTipoProcedimento();
  $objEstatisticasDTO->retStrSiglaUsuario();
  $objEstatisticasDTO->retStrNomeUsuario();
  $objEstatisticasDTO->retNumAno();
  $objEstatisticasDTO->retNumMes();
  $objEstatisticasDTO->retDblTempoAberto();
  $objEstatisticasDTO->retStrProtocoloFormatadoDocumento();
  $objEstatisticasDTO->retStrNomeSerie();
  $objEstatisticasDTO->retNumIdOrgaoUnidade();
  $objEstatisticasDTO->retStrStaOuvidoriaProcedimento();
  
  $objEstatisticasDTO->setOrdStrSiglaOrgaoUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);
  $objEstatisticasDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);
  $objEstatisticasDTO->setOrdNumAno(InfraDTO::$TIPO_ORDENACAO_ASC);
  $objEstatisticasDTO->setOrdNumMes(InfraDTO::$TIPO_ORDENACAO_ASC);
  $objEstatisticasDTO->setOrdDblIdProcedimento(InfraDTO::$TIPO_ORDENACAO_ASC);
  
  //$objEstatisticasBD = new EstatisticasBD(BancoSEI::getInstance());
  //die($objEstatisticasBD->listar($objEstatisticasDTO,true));
  
  PaginaSEI::getInstance()->prepararPaginacao($objEstatisticasDTO);

  $objEstatisticasRN = new EstatisticasRN();
  $arrObjEstatisticasDTO = $objEstatisticasRN->listar($objEstatisticasDTO);
  
  PaginaSEI::getInstance()->processarPaginacao($objEstatisticasDTO);
  $numRegistros = count($arrObjEstatisticasDTO);

  if ($numRegistros > 0){

    $bolCheck = true;
    $bolAcaoImprimir = true;
    $bolAcaoProcedimentoTrabalhar = SessaoSEI::getInstance()->verificarPermissao('procedimento_trabalhar');
    $bolAcaoDocumentoVisualizar = SessaoSEI::getInstance()->verificarPermissao('documento_visualizar');

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Registros de Detalhamento.';
    $strCaptionTabela = 'Registros de Detalhamento';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    
    $strResultado .= '<th class="infraTh" width="10%">Órgão</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Unidade</th>'."\n";
    $strResultado .= '<th class="infraTh" width="30%">Processo</th>'."\n";
    
    if (!$bolDetalhamentoDocumentos){
      $strResultado .= '<th class="infraTh">Tipo</th>'."\n";
    }else{
      $strResultado .= '<th class="infraTh">Documento</th>'."\n";
      $strResultado .= '<th class="infraTh">Tipo</th>'."\n";
    }
    
    $strResultado .= '<th class="infraTh" width="6%">Mês</th>'."\n";
    $strResultado .= '<th class="infraTh" width="6%">Ano</th>'."\n";
    
    if ($_GET['tabela_estatisticas']==EstatisticasRN::$TIPO_ESTATISTICAS_TEMPO){
      $strResultado .= '<th class="infraTh" width="17%">Tempo Aberto</th>'."\n";
    }
    
    
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjEstatisticasDTO[$i]->getDblIdEstatisticas(),$arrObjEstatisticasDTO[$i]->getDblIdEstatisticas()).'</td>';
      }
      
        
      $strResultado .= '<td valign="top" align="center">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrDescricaoOrgaoUnidade()).'" title="'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrDescricaoOrgaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrSiglaOrgaoUnidade()).'</a>';
      $strResultado .= '</td>';

      $strResultado .= '<td valign="top" align="center">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrSiglaUnidade()).'</a>';
      $strResultado .= '</td>';
      
      $strResultado .= '<td valign="top" align="center">';
      if ($bolAcaoProcedimentoTrabalhar){              

      	$strLinkProcedimento = 'controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjEstatisticasDTO[$i]->getDblIdProcedimento();
      	
      	if ($arrObjEstatisticasDTO[$i]->getDblIdDocumento()!=null){
      		$strLinkProcedimento .= '&id_documento='.$arrObjEstatisticasDTO[$i]->getDblIdDocumento();
      	}
      	
      	$strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink($strLinkProcedimento).'" target="_blank" class="protocoloNormal" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrNomeTipoProcedimento()).'">'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrProtocoloFormatadoProcedimento()).'</a>';
      }else{
      	$strResultado .= PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrProtocoloFormatadoProcedimento());
      }  
      $strResultado .= '</td>';
      
      if (!$bolDetalhamentoDocumentos){
        $strResultado .= '<td valign="top" align="center">'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrNomeTipoProcedimento()).'</td>';
      }else{

	      $strResultado .= '<td valign="top" align="center">';
	      if ($bolAcaoDocumentoVisualizar){              
	      	$strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_documento='.$arrObjEstatisticasDTO[$i]->getDblIdDocumento()).'" target="_blank" class="protocoloNormal" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrNomeSerie()).'">'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrProtocoloFormatadoDocumento()).'</a>';
	      }else{
	      	$strResultado .= PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrProtocoloFormatadoDocumento());
	      }  
	      $strResultado .= '</td>';
      	
      
	      $strResultado .= '<td valign="top" align="center">';
      	$strResultado .=  PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrNomeSerie());
	      $strResultado .= '</td>';
      }
      
      $strResultado .= '<td valign="top" align="center">'.$arrObjEstatisticasDTO[$i]->getNumMes().'</td>';
      $strResultado .= '<td valign="top" align="center">'.$arrObjEstatisticasDTO[$i]->getNumAno().'</td>';
      
      if ($_GET['tabela_estatisticas']==EstatisticasRN::$TIPO_ESTATISTICAS_TEMPO){
        $strResultado .= '<td valign="top"  align="right">'.InfraData::formatarTimestamp($arrObjEstatisticasDTO[$i]->getDblTempoAberto()).'</td>';
      }
      
      $strResultado .= '</tr>'."\n";
            
    }
    $strResultado .= '</table>';
  }
  
  
  $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
} 

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  document.getElementById('btnFecharSelecao').focus();
  infraEfeitoTabelas();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmEstatisticasLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  //PaginaSEI::getInstance()->abrirAreaDados('5em');
  //PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>