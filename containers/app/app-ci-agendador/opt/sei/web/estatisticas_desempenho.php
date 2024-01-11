<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 21/10/2013 - criado por mga
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  
	//PaginaSEI::getInstance()->setBolAutoRedimensionar(false);
  //////////////////////////////////////////////////////////////////////////////
  
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////
  
  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
  
  $arrComandos = array();  
  $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';          	
  
  switch($_GET['acao']){

  	case 'gerar_estatisticas_desempenho_processos':

      $strTitulo = 'Estatísticas de Desempenho de Processos';
      
      $objEstatisticasAtividadeDTO = new EstatisticasAtividadeDTO();
      
    	$numIdOrgaoEscolha = $_POST['selOrgao'];
      if ($numIdOrgaoEscolha!=''){
		    $objEstatisticasAtividadeDTO->setNumIdOrgaoUnidadeGeradoraProtocolo($numIdOrgaoEscolha);
      }

      $dtaPeriodoDe 	= $_POST['txtPeriodoDe'];
      $objEstatisticasAtividadeDTO->setDtaInicio($dtaPeriodoDe);
      
      $dtaPeriodoA		= $_POST['txtPeriodoA'];
		  $objEstatisticasAtividadeDTO->setDtaFim($dtaPeriodoA);

      $arrTiposProcedimento = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTiposProcedimento']);
		  if (InfraArray::contar($arrTiposProcedimento)){
		    $objEstatisticasAtividadeDTO->setNumIdTipoProcedimentoProcedimento($arrTiposProcedimento,InfraDTO::$OPER_IN);
		  }

		  $objEstatisticasAtividadeDTO->setStrSinConcluidos(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinConcluidos']));
			  
		  $objEstatisticasDTORet = null;

			 
			if (isset($_POST['sbmPesquisar'])){
  		  try{
  			  $objEstatisticasRN = new EstatisticasRN();
  	      $objEstatisticasDTORet	= $objEstatisticasRN->gerarDesempenhoProcessos($objEstatisticasAtividadeDTO);
  			}catch(Exception $e){
  			  PaginaSEI::getInstance()->processarExcecao($e);
  			}
		  }
			 			  
			if ($objEstatisticasDTORet != null){
			  $arrObjEstatisticasTabelaDESEMPENHO	= $objEstatisticasDTORet->getArrEstatisticasDESEMPENHO();
        $arrCores = $objEstatisticasRN->getArrCores();
  			$arrCoresTipoProcedimento = $arrCores[0];
		  }  
      
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  
  if ($objEstatisticasDTORet != null){
  	
  	//Tipos de Procedimento
  	$objTipoProcedimentoDTO = new TipoProcedimentoDTO();
  	$objTipoProcedimentoDTO->setBolExclusaoLogica(false);
  	$objTipoProcedimentoDTO->retNumIdTipoProcedimento();
  	$objTipoProcedimentoDTO->retStrNome();
  	
  	$objTipoProcedimentoRN = new TipoProcedimentoRN();
  	$arrObjTipoProcedimentoDTO = InfraArray::indexarArrInfraDTO($objTipoProcedimentoRN->listarRN0244($objTipoProcedimentoDTO),'IdTipoProcedimento');

    $bolAcaoImprimir = true;
    
    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirDiv(\'divTabelas\');" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
    }
    
    // DESEMPENHO
    $strResultadoTabelaDesempenho = '';
    $strCssTr='';
    $contadorTabelaDesempenho = 0;
    $numTotalTempoMedio = 0; 
    $numTotalQuantidade = 0;
    foreach ($arrObjEstatisticasTabelaDESEMPENHO as $keyTipo => $arrTipo){

      $numTempoMedioTipo = $arrTipo[0];
      $numQuantidadeTipo = $arrTipo[1];

      $numTotalTempoMedio += $numTempoMedioTipo;
      $numTotalQuantidade += $numQuantidadeTipo;
      
		  if ($arrObjTipoProcedimentoDTO[$keyTipo]!=null){
      	$strNomeTipoProcedimento = $arrObjTipoProcedimentoDTO[$keyTipo]->getStrNome();
      }else{
      	$strNomeTipoProcedimento = '';
      }

			$strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=estatisticas_detalhar_desempenho&acao_origem='.$_GET['acao'].'&id_estatisticas='.$objEstatisticasDTORet->getDblIdEstatisticasDesempenho().'&tabela_estatisticas='.EstatisticasRN::$TIPO_DESEMPENHO.'&id_tipo_procedimento='.$keyTipo);
      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultadoTabelaDesempenho .= $strCssTr;
	    $strResultadoTabelaDesempenho .= '<td align="left">'.PaginaSEI::tratarHTML($strNomeTipoProcedimento).'</td>';
      $strResultadoTabelaDesempenho .= '<td align="center"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.$strLink.'\');" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($numQuantidadeTipo).'</a></td>';
	    $strResultadoTabelaDesempenho .= '<td align="right"><a href="javascript:void(0);" onclick="abrirDetalhe(\''.$strLink.'\');" class="ancoraPadraoAzul">'.InfraData::formatarTimestamp($numTempoMedioTipo).'</a></td>';
      $strResultadoTabelaDesempenho .= '</tr>'."\n";
      
      $strTituloGraficoDesempenho = EstatisticasRN::$TITULO_DESEMPENHO;
      $arrayGraficoDESEMPENHO[] = array($strNomeTipoProcedimento, InfraData::formatarTimestamp($numTempoMedioTipo), $numTempoMedioTipo, $strLink);
      $contadorTabelaDesempenho++;
    }

		$strResultadoDesempenho = '';
    $strResultadoDesempenho .= '<table width="70%" class="infraTable" summary="Tabela de '.EstatisticasRN::$TITULO_DESEMPENHO.'">'."\n";
    $strResultadoDesempenho .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela(InfraString::transformarCaixaBaixa(EstatisticasRN::$TITULO_DESEMPENHO),InfraArray::contar($arrObjEstatisticasTabelaDESEMPENHO)).'</caption>';
    $strResultadoDesempenho .= '<tr>';
    $strResultadoDesempenho .= '<th class="infraTh" width="">Tipo</th>'."\n";
    $strResultadoDesempenho .= '<th class="infraTh" width="15%">Quantidade</th>'."\n";
    $strResultadoDesempenho .= '<th class="infraTh" width="25%">Tempo Médio</th>'."\n";
    $strResultadoDesempenho .= '</tr>'."\n";    
    $strResultadoDesempenho .= $strResultadoTabelaDesempenho;
    $strResultadoDesempenho .= '</table>';
    
    
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $numGrafico=0;
    $objEstatisticasRN = new EstatisticasRN();
		
		if ($contadorTabelaDesempenho > 0){
			$strResultadoGraficoDESEMPENHO = ''; 
  	  $strResultadoGraficoDESEMPENHO .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
  	  $strResultadoGraficoDESEMPENHO .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,EstatisticasRN::$TITULO_DESEMPENHO,null, $arrayGraficoDESEMPENHO, 150, $arrCoresTipoProcedimento);
  	  $strResultadoGraficoDESEMPENHO .= '</div><br />';
		}

  }
  
  $strItensSelOrgaos = OrgaoINT::montarSelectSiglaRI1358('','Todos',$numIdOrgaoEscolha);
  $strItensSelTipoProcedimento = TipoProcedimentoINT::montarSelectNome('null', '&nbsp;', $numIdTipoProcedimentoEscolha);
  $strLinkAjaxTipoProcedimento = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=tipo_procedimento_auto_completar');
  $strLinkTipoProcedimentoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_procedimento_selecionar&tipo_selecao=2&id_object=objLupaTipoProcedimento');


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
.divAreaGrafico DIV { margin:0px; }

#lblOrgao	{position:absolute;left:0%;top:1%;}
#selOrgao	{position:absolute;left:14%;top:0%;width:11%;}	

#lblTipoProcedimento {position:absolute;left:0%;top:14%;}

  #txtTipoProcedimento {position:absolute;left:14%;top:14%;width:49%;}
  #selTipoProcedimento {position:absolute;left:14%;top:26%;width:65%;}
  #imgLupaTipoProcedimento {position:absolute;left:79.5%;top:27%;}
  #imgExcluirTipoProcedimento {position:absolute;left:79.5%;top:38%;}

#lblPeriodoDe {position:absolute;left:0%;top:64%;width:9%;}
#txtPeriodoDe {position:absolute;left:14%;top:64%;width:9%;}
#imgCalPeriodoD {position:absolute;left:23.5%;top:65%;}

#lblPeriodoA 	{position:absolute;left:26%;top:66%;width:9%;}
#txtPeriodoA 	{position:absolute;left:27.5%;top:64%;width:9%;}
#imgCalPeriodoA {position:absolute;left:37%;top:65%;}
 
#divSinConcluidos {position:absolute;left:13.7%;top:80%;width:70%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
?>

<script type="text/javascript" src="/infra_js/raphaeljs/raphael-min.js"></script>
<script type="text/javascript" src="/infra_js/raphaeljs/g.raphael-min.js"></script>
<script type="text/javascript" src="/infra_js/raphaeljs/g.bar-min.js"></script>
<? 
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>
var objAutoCompletarTipoProcedimento = null;
var objLupaTipoProcedimento = null;

function labelBarChart(r, bc, labels, attrs) {
    // Label a bar chart bc that is part of a Raphael object r
    // Labels is an array of strings. Attrs is a dictionary
    // that provides attributes such as fill (text color)
    // and font (text font, font-size, font-weight, etc) for the
    // label text.

    for (var i = 0; i< bc.bars[0].length; i++) {
        var bar = bc.bars[0][i];
        var gutter_y = bar.w * 0.4;
        var label_x = bar.x;
        var label_y = bar.y  - gutter_y;
        var label_text = bar.value;
        var label_attr = { fill:  "#2f69bf", font: "11px sans-serif" };

        r.text(label_x, label_y, label_text).attr(label_attr);
    }

}

function inicializar() {

  if ('<?=$_GET['acao_origem']?>' != 'gerar_estatisticas_unidade' && '<?=$_GET['acao_origem']?>' != 'gerar_estatisticas_ouvidoria') {
    infraOcultarMenuSistemaEsquema();
  }

  infraAdicionarEvento(window, 'resize', seiRedimensionarGraficos);
  infraProcessarResize();
  infraAviso();
  infraEfeitoTabelas();
  seiRedimensionarGraficos();

  objLupaTipoProcedimento = new infraLupaSelect('selTipoProcedimento', 'hdnTiposProcedimento', '<?=$strLinkTipoProcedimentoSelecao?>');
  objAutoCompletarTipoProcedimento = new infraAjaxAutoCompletar('hdnIdTipoProcedimento', 'txtTipoProcedimento', '<?=$strLinkAjaxTipoProcedimento?>');
  objAutoCompletarTipoProcedimento.limparCampo = true;

  objAutoCompletarTipoProcedimento.prepararExecucao = function () {
    return 'palavras_pesquisa=' + document.getElementById('txtTipoProcedimento').value;
  };

  objAutoCompletarTipoProcedimento.processarResultado = function (id, descricao, complemento) {
    if (id != '') {
      objLupaTipoProcedimento.adicionar(id,descricao,document.getElementById('txtTipoProcedimento'));
    }
  };
}

function abrirDetalhe(link){
 infraAbrirJanela(link,'janelaEstatisticasDetalhe',750,550,'location=0,status=1,resizable=1,scrollbars=1');
}

function validarFormulario() {

  if (!infraSelectSelecionado(document.getElementById('selTipoProcedimento'))) {
    alert('Escolha pelo menos um Tipo de Processo.');
    document.getElementById('selTipoProcedimento').focus();
    return false;
  }

  if ((infraTrim(document.getElementById('txtPeriodoDe').value) == "" || infraTrim(document.getElementById('txtPeriodoA').value) == "")) {

    alert("Informe o período de datas.");

    if (infraTrim(document.getElementById('txtPeriodoDe').value) == "") {
      document.getElementById('txtPeriodoDe').focus();
    } else {
      document.getElementById('txtPeriodoA').focus();
    }

    return false;
  }

  infraExibirAviso();
  return true;
}
//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmEstatisticasDesempenho" onsubmit="return validarFormulario();" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('22em');
  ?>

		<label id="lblOrgao" for="selOrgao" accesskey="" class="infraLabelOpcional">Órgão:</label>
		<select id="selOrgao" name="selOrgao" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
		<?=$strItensSelOrgaos?>
		</select>    
    
    <label id="lblTipoProcedimento" for="selTipoProcedimento" accesskey="" class="infraLabelObrigatorio">Tipo de Processo:</label>

  <input type="text" id="txtTipoProcedimento" name="txtTipoProcedimento" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <input type="hidden" id="hdnIdTipoProcedimento" name="hdnIdTipoProcedimento" class="infraText" value="<?=$_POST['hdnIdTipoProcedimento']?>" />
  <select id="selTipoProcedimento" name="selTipoProcedimento" size="4" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">

  </select>
  <img id="imgLupaTipoProcedimento" onclick="objLupaTipoProcedimento.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Tipo de Processo" title="Selecionar Tipo de Processo" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgExcluirTipoProcedimento" onclick="objLupaTipoProcedimento.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Tipos de Processo Selecionados" title="Remover Tipos de Processo Selecionados" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnTiposProcedimento" name="hdnTiposProcedimento" value="<?=$_POST['hdnTiposProcedimento']?>"/>


  <label id="lblPeriodoDe" for="txtPeriodoDe" accesskey="" class="infraLabelObrigatorio">Período:</label>
    <input type="text" id="txtPeriodoDe" name="txtPeriodoDe" class="infraText" value="<?=PaginaSEI::tratarHTML($dtaPeriodoDe)?>" onkeypress="return infraMascaraData(this, event)" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  
		<img id="imgCalPeriodoD" title="Selecionar Data Inicial" alt="Selecionar Data Inicial" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtPeriodoDe',this);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    
    <label id="lblPeriodoA" for="txtPeriodoA" accesskey="" class="infraLabelOpcional">a</label>
    <input type="text" id="txtPeriodoA" name="txtPeriodoA" class="infraText" value="<?=PaginaSEI::tratarHTML($dtaPeriodoA)?>" onkeypress="return infraMascaraData(this, event)" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    
    <img id="imgCalPeriodoA" title="Selecionar Data Final" alt="Selecionar Data Final" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtPeriodoA',this);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    
    <div id="divSinConcluidos" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinConcluidos" name="chkSinConcluidos" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objEstatisticasAtividadeDTO->getStrSinConcluidos())?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  	  <label id="lblSinConcluidos" for="chkSinConcluidos" accesskey="" class="infraLabelCheckbox" >Considerar apenas processos concluídos</label>
  	</div>      
    
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  
  ?>
  <div id="divTabelas">
  <?

  echo '<div id="divSeparador" style="float:left;padding:1em"></div>';
 	echo '<br /><br />';
	PaginaSEI::getInstance()->montarAreaTabela($strResultadoDesempenho,$contadorTabelaDesempenho);
	
  if ($contadorTabelaDesempenho > 0) {
		EstatisticasINT::montarGrafico('Desempenho',$strResultadoGraficoDESEMPENHO);
  }

  ?>
  </div>
  <?
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);

  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>