<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/10/2013 - criado por mga
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

  PaginaSEI::getInstance()->salvarCamposPost(array('hdnTipoVisualizacaoDesempenho'));
  
  $strParametros = '';
  if (isset($_GET['id_estatisticas'])){
  	$strParametros .= '&id_estatisticas='.$_GET['id_estatisticas'];
  }

  if (isset($_GET['tabela_estatisticas'])){
  	$strParametros .= '&tabela_estatisticas='.$_GET['tabela_estatisticas'];
  }

  if (isset($_GET['id_unidade'])){
    $strParametros .= '&id_unidade='.$_GET['id_unidade'];
  }
  
  if (isset($_GET['id_tipo_procedimento'])){
  	$strParametros .= '&id_tipo_procedimento='.$_GET['id_tipo_procedimento'];
  }

  if (isset($_GET['id_procedimento'])){
    $strParametros .= '&id_procedimento='.$_GET['id_procedimento'];
  }
  
  $objEstatisticasDTO = new EstatisticasDTO();
  
  switch($_GET['acao']){

    case 'estatisticas_detalhar_desempenho':  
    case 'estatisticas_detalhar_desempenho_procedimento':
    	
    	PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);
    	
    	if (InfraString::isBolVazia($_GET['id_estatisticas'])){
    		throw new InfraException('Detalhe da estatística não informado.');
    	}
    	
    	$objEstatisticasDTO->setDblIdEstatisticas($_GET['id_estatisticas']);
    	
    	switch($_GET['tabela_estatisticas']){
    			
    	  case EstatisticasRN::$TIPO_DESEMPENHO:
    	    $strTitulo = EstatisticasRN::$TITULO_DESEMPENHO;
    	    break;

        case EstatisticasRN::$TIPO_DESEMPENHO_PROCESSO:
          $strTitulo = EstatisticasRN::$TITULO_DESEMPENHO_PROCESSO;
          break;

    		default:
    		  throw new InfraException('Tipo do detalhe da estatística não informado.');
    	}
      
    	break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  if ($_GET['acao_origem']=='gerar_estatisticas_desempenho_processos'){
    $strTipoVisualizacao = 'P';
  }else{
    $strTipoVisualizacao = PaginaSEI::getInstance()->recuperarCampo('hdnTipoVisualizacaoDesempenho','P');
  }
  
  $arrComandos = array();

  $objEstatisticasDTO->retDblIdEstatisticas();
  $objEstatisticasDTO->retDblIdProcedimento();
  $objEstatisticasDTO->retStrProtocoloFormatadoProcedimento();
  $objEstatisticasDTO->retStrSiglaOrgaoUnidade();
  $objEstatisticasDTO->retStrDescricaoOrgaoUnidade();
  $objEstatisticasDTO->retNumIdUnidade();
  $objEstatisticasDTO->retStrSiglaUnidade();
  $objEstatisticasDTO->retStrDescricaoUnidade();
  $objEstatisticasDTO->retNumIdTipoProcedimento();
  $objEstatisticasDTO->retStrNomeTipoProcedimento();
  $objEstatisticasDTO->retDthAbertura();
  $objEstatisticasDTO->retDthConclusao();
  $objEstatisticasDTO->retDblTempoAberto();
  $objEstatisticasDTO->retDblQuantidade();
  
  $objEstatisticasDTO->setOrdDblIdProcedimento(InfraDTO::$TIPO_ORDENACAO_ASC);
  
  if ($_GET['tabela_estatisticas']==EstatisticasRN::$TIPO_DESEMPENHO){
    
    if ($strTipoVisualizacao=='P'){
      $strTitulo = 'Desempenho por Processo';
      
      $objEstatisticasDTO->setDblIdProcedimento(null,InfraDTO::$OPER_DIFERENTE);
      $objEstatisticasDTO->setNumIdUnidade(null);
      
      if (isset($_GET['id_tipo_procedimento']) && $_GET['id_tipo_procedimento']!=''){
        $objEstatisticasDTO->setNumTipoFkTipoProcedimento(InfraDTO::$TIPO_FK_OBRIGATORIA);
        $objEstatisticasDTO->setNumIdTipoProcedimento($_GET['id_tipo_procedimento']);
      }
      
    }else{
      $strTitulo = 'Desempenho por Unidade';
      
      $objEstatisticasDTO->setDblIdProcedimento(null);
      $objEstatisticasDTO->setNumIdUnidade(null,InfraDTO::$OPER_DIFERENTE);
      
      if (isset($_GET['id_tipo_procedimento']) && $_GET['id_tipo_procedimento']!=''){
        $objEstatisticasDTO->setNumTipoFkTipoProcedimento(InfraDTO::$TIPO_FK_OBRIGATORIA);
        $objEstatisticasDTO->setNumIdTipoProcedimento($_GET['id_tipo_procedimento']);
      }else{
        $objEstatisticasDTO->setNumIdTipoProcedimento(null);
      }
      
    }
  }else{
    
    if (isset($_GET['id_procedimento']) && $_GET['id_procedimento']!=''){
      $strTitulo = 'Desempenho do Processo';
      $objEstatisticasDTO->setNumTipoFkProcedimento(InfraDTO::$TIPO_FK_OBRIGATORIA);
      $objEstatisticasDTO->setDblIdProcedimento($_GET['id_procedimento']);
    }else{
      $objEstatisticasDTO->setDblIdProcedimento(null, InfraDTO::$OPER_DIFERENTE);
    }
    
    if (isset($_GET['id_tipo_procedimento']) && $_GET['id_tipo_procedimento']!=''){
      $objEstatisticasDTO->setNumTipoFkTipoProcedimento(InfraDTO::$TIPO_FK_OBRIGATORIA);
      $objEstatisticasDTO->setNumIdTipoProcedimento($_GET['id_tipo_procedimento']);
    }

    if (isset($_GET['id_unidade']) && $_GET['id_unidade']!=''){
      $strTitulo = 'Desempenho dos Processos na Unidade';
      $objEstatisticasDTO->setNumIdUnidade($_GET['id_unidade']);
    }else{
      $objEstatisticasDTO->setNumIdUnidade(null, InfraDTO::$OPER_DIFERENTE);
    }
    
  }  
  
  //$objEstatisticasDTO->setOrdDblQuantidade(InfraDTO::$TIPO_ORDENACAO_ASC);
  $objEstatisticasDTO->setOrdDblTempoAberto(InfraDTO::$TIPO_ORDENACAO_ASC);
  
  PaginaSEI::getInstance()->prepararPaginacao($objEstatisticasDTO);

  $objEstatisticasRN = new EstatisticasRN();
  $arrObjEstatisticasDTO = $objEstatisticasRN->listar($objEstatisticasDTO);
  
  PaginaSEI::getInstance()->processarPaginacao($objEstatisticasDTO);
  $numRegistros = count($arrObjEstatisticasDTO);

  if ($numRegistros > 0){

    $bolCheck = true;
    $bolAcaoImprimir = true;
    $bolAcaoProcedimentoTrabalhar = SessaoSEI::getInstance()->verificarPermissao('procedimento_trabalhar');

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

    if ($_GET['tabela_estatisticas']==EstatisticasRN::$TIPO_DESEMPENHO){
      if ($strTipoVisualizacao=='P'){
        $strResultado .= '<th class="infraTh" width="25%">Processo</th>'."\n";
        //$strResultado .= '<th class="infraTh">Tipo</th>'."\n";
        $strResultado .= '<th class="infraTh" width="20%">Abertura</th>'."\n";
        $strResultado .= '<th class="infraTh" width="20%">Conclusão</th>'."\n";
        $strResultado .= '<th class="infraTh">Tempo</th>'."\n";
      }else{
        $strResultado .= '<th class="infraTh" width="20%">Órgão</th>'."\n";
        $strResultado .= '<th class="infraTh" width="20%">Unidade</th>'."\n";
        $strResultado .= '<th class="infraTh" width="20%">Quantidade</th>'."\n";
        $strResultado .= '<th class="infraTh">Tempo Médio na Unidade</th>'."\n";
      }
    }else{
      
      if (!(isset($_GET['id_procedimento']) && $_GET['id_procedimento']!='')){
        $strResultado .= '<th class="infraTh" width="25%">Processo</th>'."\n";
      }
      
      if (!(isset($_GET['id_tipo_procedimento']) && $_GET['id_tipo_procedimento']!='')){
        $strResultado .= '<th class="infraTh" width="20%">Tipo</th>'."\n";
      }
        
      $strResultado .= '<th class="infraTh" width="">Órgão</th>'."\n";
      $strResultado .= '<th class="infraTh" width="">Unidade</th>'."\n";
      $strResultado .= '<th class="infraTh">Tempo</th>'."\n";
    }
    
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjEstatisticasDTO[$i]->getDblIdEstatisticas(),$arrObjEstatisticasDTO[$i]->getDblIdEstatisticas()).'</td>';
      }

      if ($_GET['tabela_estatisticas']==EstatisticasRN::$TIPO_DESEMPENHO){
        if ($strTipoVisualizacao=='P'){
          
          $strResultado .= '<td valign="top" align="center">';
          if ($bolAcaoProcedimentoTrabalhar){
            $strLinkProcedimento = 'controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjEstatisticasDTO[$i]->getDblIdProcedimento();
            $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink($strLinkProcedimento).'" target="_blank" class="protocoloNormal" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrNomeTipoProcedimento()).'">'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrProtocoloFormatadoProcedimento()).'</a>';
          }else{
            $strResultado .= PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrProtocoloFormatadoProcedimento());
          }
          $strResultado .= '</td>';
          
          $strResultado .= '<td valign="top" align="center">'.$arrObjEstatisticasDTO[$i]->getDthAbertura().'</td>';
          $strResultado .= '<td valign="top" align="center">'.$arrObjEstatisticasDTO[$i]->getDthConclusao().'</td>';
          
          $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=estatisticas_detalhar_desempenho_procedimento&id_estatisticas='.$_GET['id_estatisticas'].'&tabela_estatisticas='.EstatisticasRN::$TIPO_DESEMPENHO_PROCESSO.'&id_procedimento='.$arrObjEstatisticasDTO[$i]->getDblIdProcedimento().'&id_tipo_procedimento='.$arrObjEstatisticasDTO[$i]->getNumIdTipoProcedimento());
          $strResultado .= '<td valign="top" align="right"><a href="javascript:void(0);" onclick="abrirDesempenhoDetalhe(\''.$strLink.'\');" class="ancoraPadraoAzul">'.InfraData::formatarTimestamp($arrObjEstatisticasDTO[$i]->getDblTempoAberto()).'</a></td>';
          
        }else{
        
          $strResultado .= '<td valign="top" align="center">';
          $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrDescricaoOrgaoUnidade()).'" title="'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrDescricaoOrgaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrSiglaOrgaoUnidade()).'</a>';
          $strResultado .= '</td>';
    
          $strResultado .= '<td valign="top" align="center">';
          $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrSiglaUnidade()).'</a>';
          $strResultado .= '</td>';
          
          $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=estatisticas_detalhar_desempenho_procedimento&id_estatisticas='.$_GET['id_estatisticas'].'&tabela_estatisticas='.EstatisticasRN::$TIPO_DESEMPENHO_PROCESSO.'&id_unidade='.$arrObjEstatisticasDTO[$i]->getNumIdUnidade().'&id_tipo_procedimento='.$_GET['id_tipo_procedimento']);
          
          $strResultado .= '<td valign="top" align="center"><a href="javascript:void(0);" onclick="abrirDesempenhoDetalhe(\''.$strLink.'\');" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($arrObjEstatisticasDTO[$i]->getDblQuantidade()).'</a></td>';
          $strResultado .= '<td valign="top" align="right"><a href="javascript:void(0);" onclick="abrirDesempenhoDetalhe(\''.$strLink.'\');" class="ancoraPadraoAzul">'.InfraData::formatarTimestamp($arrObjEstatisticasDTO[$i]->getDblTempoAberto()).'</a></td>';
          
        }
      }else{
        
        if (!(isset($_GET['id_procedimento']) && $_GET['id_procedimento']!='')){
          $strResultado .= '<td valign="top" align="center">';
          if ($bolAcaoProcedimentoTrabalhar){
            $strLinkProcedimento = 'controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjEstatisticasDTO[$i]->getDblIdProcedimento();
            $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink($strLinkProcedimento).'" target="_blank" class="protocoloNormal" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrNomeTipoProcedimento()).'">'.$arrObjEstatisticasDTO[$i]->getStrProtocoloFormatadoProcedimento().'</a>';
          }else{
            $strResultado .= $arrObjEstatisticasDTO[$i]->getStrProtocoloFormatadoProcedimento();
          }
          $strResultado .= '</td>';
        }

        if (!(isset($_GET['id_tipo_procedimento']) && $_GET['id_tipo_procedimento']!='')){
          $strResultado .= '<td valign="top" align="center">'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrNomeTipoProcedimento()).'</td>';
        }
        
        $strResultado .= '<td valign="top" align="center">';
        $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrDescricaoOrgaoUnidade()).'" title="'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrDescricaoOrgaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrSiglaOrgaoUnidade()).'</a>';
        $strResultado .= '</td>';
        
        $strResultado .= '<td valign="top" align="center">';
        $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjEstatisticasDTO[$i]->getStrSiglaUnidade()).'</a>';
        $strResultado .= '</td>';
          
        $strResultado .= '<td valign="top" align="center">'.InfraData::formatarTimestamp($arrObjEstatisticasDTO[$i]->getDblTempoAberto()).'</td>';
      }      
      
      $strResultado .= '</tr>'."\n";
            
    }
    $strResultado .= '</table>';
  }
  
  $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

  $strDisplayTipoVisualizacao = 'display:none;';
  if ($_GET['tabela_estatisticas']==EstatisticasRN::$TIPO_DESEMPENHO){
    $strDisplayTipoVisualizacao = '';
  }
  
  if ($strTipoVisualizacao=='P'){
    $strLinkTipoVisualizacao = '<a id="ancTipoVisualizacaoDesempenho" href="javascript:void(0);" onclick="mudarVisualizacao(\'U\');" class="ancoraPadraoPreta" style="'.$strDisplayTipoVisualizacao.'">Detalhar por Unidade</a>';
  }else{
    $strLinkTipoVisualizacao = '<a id="ancTipoVisualizacaoDesempenho" href="javascript:void(0);" onclick="mudarVisualizacao(\'P\');" class="ancoraPadraoPreta" style="'.$strDisplayTipoVisualizacao.'">Detalhar por Processo</a>';
  }
  
  
  $strDisplayProcesso = 'display:none;';
  $strDisplayTipoProcesso = 'display:none;';

  if (isset($_GET['id_procedimento']) && $_GET['id_procedimento']!=''){
  
    $objProcedimentoDTO = new ProcedimentoDTO();
    $objProcedimentoDTO->retDblIdProcedimento();
    $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
    $objProcedimentoDTO->retStrNomeTipoProcedimento();
    $objProcedimentoDTO->setDblIdProcedimento($_GET['id_procedimento']);
  
    $objProcedimentoRN = new ProcedimentoRN();
    $objProcedimentoDTO = $objProcedimentoRN->consultarRN0201($objProcedimentoDTO);
  
    if ($objProcedimentoDTO!=null){
  
      $strDisplayProcesso = '';
      $strDisplayTipoProcesso = '';
  
      if ($bolAcaoProcedimentoTrabalhar){
        $strLinkProcedimento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$objProcedimentoDTO->getDblIdProcedimento());
        $strProcesso = '<a id="ancProcesso" href="'.$strLinkProcedimento.'" target="_blank" title="'.$objProcedimentoDTO->getStrProtocoloProcedimentoFormatado().'">'.$objProcedimentoDTO->getStrProtocoloProcedimentoFormatado().'</a>';
      }else{
        $strProcesso = '<input type="text" id="txtProcesso" name="txtProcesso" readonly="readonly" class="infraText infraReadOnly" value="'.$objProcedimentoDTO->getStrProtocoloProcedimentoFormatado().'" />';
      }
  
      $strTipoProcesso = $objProcedimentoDTO->getStrNomeTipoProcedimento();
    }
  }
  
  if (isset($_GET['id_tipo_procedimento']) && $_GET['id_tipo_procedimento']!=''){
    
    $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
    $objTipoProcedimentoDTO->retStrNome();
    $objTipoProcedimentoDTO->setNumIdTipoProcedimento($_GET['id_tipo_procedimento']);
    
    $objTipoProcedimentoRN = new TipoProcedimentoRN();
    $objTipoProcedimentoDTO = $objTipoProcedimentoRN->consultarRN0267($objTipoProcedimentoDTO);
    
    if ($objTipoProcedimentoDTO!=null){
      $strDisplayTipoProcesso = '';
      $strTipoProcesso = $objTipoProcedimentoDTO->getStrNome();
    }
  }
    
  
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
#divProcesso {<?=$strDisplayProcesso?>}
#lblProcesso {position:absolute;left:0%;top:0%;width:20%;}
#ancProcesso {position:absolute;left:21%;top:0%;width:75%;}
#txtProcesso {position:absolute;left:21%;top:0%;width:75%;border:0;}

#divTipoProcesso {<?=$strDisplayTipoProcesso?>}
#lblTipoProcesso {position:absolute;left:0%;top:20%;width:20%;}
#txtTipoProcesso {position:absolute;left:21%;top:0%;width:75%;border:0;}

#ancTipoVisualizacaoDesempenho {}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  document.getElementById('btnFecharSelecao').focus();
  infraEfeitoTabelas();
}

function abrirDesempenhoDetalhe(link){
 infraAbrirJanelaModal(link,750,550);
}

function mudarVisualizacao(valor){
  document.getElementById('hdnTipoVisualizacaoDesempenho').value = valor;
  document.getElementById('frmEstatisticasDesempenhoDetalhe').submit();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmEstatisticasDesempenhoDetalhe" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  //echo $strLinkTipoVisualizacao;
  ?>
  <div id="divProcesso" class="infraAreaDados" style="height:2em;">
    <label id="lblProcesso" for="ancProcesso" accesskey="" class="infraLabelObrigatorio">Processo:</label>
    <?=$strProcesso?>
  </div>
  
  <div id="divTipoProcesso" class="infraAreaDados" style="height:2em;">
    <label id="lblTipoProcesso" accesskey="" class="infraLabelObrigatorio">Tipo do Processo:</label>
    <input type="text" id="txtTipoProcesso" name="txtTipoProcesso" readonly="readonly" class="infraText infraReadOnly" value="<?=PaginaSEI::tratarHTML($strTipoProcesso)?>" />
  </div>  
  <?
  echo '<br />'.$strLinkTipoVisualizacao;
  //PaginaSEI::getInstance()->abrirAreaDados('5em');
  //PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
  <input type="hidden" id="hdnTipoVisualizacaoDesempenho" name="hdnTipoVisualizacaoDesempenho" value="<?=$strTipoVisualizacao?>" />
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>