<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.12.0
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

  PaginaSEI::getInstance()->salvarCamposPost(array('selOrgao', 'txtUnidade'));
  
  switch($_GET['acao']){

    case 'acompanhamento_listar_ouvidoria':
    case 'acompanhamento_gerar_grafico_ouvidoria':
      $strTitulo = 'Acompanhamento da Ouvidoria';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
    
  if (SessaoSEI::getInstance()->verificarPermissao('acompanhamento_listar_ouvidoria')){
    $arrComandos[] = '<button type="button" accesskey="P" id="sbmPesquisar" name="sbmPesquisar" onclick="processar(\'P\',\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acompanhamento_listar_ouvidoria&acao_origem='.$_GET['acao']).'\');" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar Processos</button>';
  }
  
  if (SessaoSEI::getInstance()->verificarPermissao('acompanhamento_gerar_grafico_ouvidoria')){
    $arrComandos[] = '<button type="button" accesskey="G" id="sbmGerarGrafico" name="sbmGerarGrafico" onclick="processar(\'G\',\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acompanhamento_gerar_grafico_ouvidoria&acao_origem='.$_GET['acao']).'\');" value="Gerar Gráficos" class="infraButton"><span class="infraTeclaAtalho">G</span>erar Gráficos</button>';
  }
  
  $arrComandos[] = '<button type="button" accesskey="L" id="btnLimpar" name="btnLimpar" onclick="limpar();" value="Limpar" class="infraButton"><span class="infraTeclaAtalho">L</span>impar Critérios</button>';          	

  $objAcompanhamentoOuvidoriaDTO = new AcompanhamentoOuvidoriaDTO();
  
  $objAcompanhamentoOuvidoriaDTO->setNumIdOrgaoUnidadeOrigem(PaginaSEI::getInstance()->recuperarCampo('selOrgao',SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual()));
  $objAcompanhamentoOuvidoriaDTO->setDtaInicio($_POST['txtPeriodoDe']);
  $objAcompanhamentoOuvidoriaDTO->setDtaFim($_POST['txtPeriodoA']);

  if(isset($_POST['selTipoProcedimento'])){
    $arrNumIdTiposProcedimento = $_POST['selTipoProcedimento'];
    if (!is_array($arrNumIdTiposProcedimento)){
      $arrNumIdTiposProcedimento = array($arrNumIdTiposProcedimento);
    }
  }else{
    $arrNumIdTiposProcedimento = array();
  }

  if (InfraArray::contar($arrNumIdTiposProcedimento)) {
    $objAcompanhamentoOuvidoriaDTO->setArrObjTipoProcedimentoDTO(InfraArray::gerarArrInfraDTO('TipoProcedimentoDTO', 'IdTipoProcedimento', $arrNumIdTiposProcedimento));
  }

  $objAcompanhamentoOuvidoriaDTO->setStrStaOuvidoriaProcedimento($_POST['selStaOuvidoria']);
  
  $strIdUnidade = trim($_POST['hdnIdUnidade']);
  $strNomeUnidade = $_POST['txtUnidade'];
  $objAcompanhamentoOuvidoriaDTO->setNumIdUnidade($strIdUnidade);

  $objAcompanhamentoOuvidoriaDTO->setStrSinTramitacaoOuvidoria(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinTramitacaoOuvidoria']));
  
  $numRegistros = 0;
  
  if (isset($_POST['hdnTipo'])){
    
    if ($_POST['hdnTipo']=='P'){
      
      PaginaSEI::getInstance()->prepararOrdenacao($objAcompanhamentoOuvidoriaDTO, 'IdProtocolo', InfraDTO::$TIPO_ORDENACAO_DESC);
      
      PaginaSEI::getInstance()->prepararPaginacao($objAcompanhamentoOuvidoriaDTO);
      
      $bolErro = false;
      try{
        $objOuvidoriaRN = new OuvidoriaRN();
        $arrObjAcompanhamentoOuvidoriaDTO = $objOuvidoriaRN->listarAcompanhamento($objAcompanhamentoOuvidoriaDTO);
      }catch(Exception $e){
        $bolErro = true;
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      
      PaginaSEI::getInstance()->processarPaginacao($objAcompanhamentoOuvidoriaDTO);
    
      $numRegistros = InfraArray::contar($arrObjAcompanhamentoOuvidoriaDTO);
    
      if ($numRegistros >0){
    
        
        $bolAcaoImprimir = true;
    
        if ($bolAcaoImprimir){
          $bolCheck = true;
          $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
        }
    
        $strResultado = '';
        
        $strSumarioTabela = 'Tabela de Solicitações.';
        $strCaptionTabela = 'Solicitações';
    
        $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
        $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
        $strResultado .= '<tr>';
        if ($bolCheck) {
          $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
        }
        
        $strResultado .= '<th class="infraTh" width="17%">'.PaginaSEI::getInstance()->getThOrdenacao($objAcompanhamentoOuvidoriaDTO,'Processo','IdProtocolo',$arrObjAcompanhamentoOuvidoriaDTO).'</th>'."\n";
        $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAcompanhamentoOuvidoriaDTO,'Tipo','NomeTipoProcedimento',$arrObjAcompanhamentoOuvidoriaDTO).'</th>'."\n";  
        $strResultado .= '<th class="infraTh" width="15%">Data do Envio</th>'."\n";
        $strResultado .= '<th class="infraTh" width="15%">Destino</th>'."\n";
        $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objAcompanhamentoOuvidoriaDTO,'Atendida?','StaOuvidoriaProcedimento',$arrObjAcompanhamentoOuvidoriaDTO).'</th>'."\n";
        
        /*
        $strResultado .= '<th class="infraTh" width="17%">Processo</th>'."\n";
        $strResultado .= '<th class="infraTh">Tipo</th>'."\n";
        $strResultado .= '<th class="infraTh" width="15%">Data do Envio</th>'."\n";
        $strResultado .= '<th class="infraTh" width="15%">Destino</th>'."\n";
        $strResultado .= '<th class="infraTh" width="10%">Atendida?</th>'."\n";
        */
        
        $strResultado .= '</tr>'."\n";
        $strCssTr='';
        for($i = 0;$i < $numRegistros; $i++){
    
          $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
          $strResultado .= $strCssTr;
    
          if ($bolCheck){
            $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjAcompanhamentoOuvidoriaDTO[$i]->getDblIdProtocolo(),$arrObjAcompanhamentoOuvidoriaDTO[$i]->getStrProtocoloFormatadoProtocolo()).'</td>';
          }
          
          $strResultado .= '<td align="center"><a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjAcompanhamentoOuvidoriaDTO[$i]->getDblIdProtocolo()).'" target="_blank" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" alt="'.PaginaSEI::tratarHTML($arrObjAcompanhamentoOuvidoriaDTO[$i]->getStrNomeTipoProcedimento()).'" title="'.PaginaSEI::tratarHTML($arrObjAcompanhamentoOuvidoriaDTO[$i]->getStrNomeTipoProcedimento()).'" class="protocoloNormal">'.PaginaSEI::tratarHTML($arrObjAcompanhamentoOuvidoriaDTO[$i]->getStrProtocoloFormatadoProtocolo()).'</a></td>';
          $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjAcompanhamentoOuvidoriaDTO[$i]->getStrNomeTipoProcedimento()).'</td>';
          $strResultado .= '<td align="center">'.$arrObjAcompanhamentoOuvidoriaDTO[$i]->getDthAbertura().'</td>';
          $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjAcompanhamentoOuvidoriaDTO[$i]->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($arrObjAcompanhamentoOuvidoriaDTO[$i]->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjAcompanhamentoOuvidoriaDTO[$i]->getStrSiglaUnidade()).'</a></td>';
          $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML(OuvidoriaINT::obterDescricaoStaOuvidoriaTabela($arrObjAcompanhamentoOuvidoriaDTO[$i]->getStrStaOuvidoriaProcedimento())).'</td>';
          
          
          $strResultado .= '</tr>'."\n";
        }
        $strResultado .= '</table>';
      }
    }else if ($_POST['hdnTipo']=='G'){
      
      try{
        $objOuvidoriaRN = new OuvidoriaRN();
        $objAcompanhamentoOuvidoriaDTORet = $objOuvidoriaRN->gerarGraficoAcompanhamento($objAcompanhamentoOuvidoriaDTO);
      }catch(Exception $e){
        $bolErro = true;
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      
      if (!$bolErro){
        $objEstatisticasRN = new EstatisticasRN();
        $arrCoresGlobal=$objEstatisticasRN->getArrCores();
  
        $arrTipos = array('S' => 'Sim', 'N' => 'Não', '-' => 'Sem registro de atendimento');
        $arrFatias = array('Sim', 'Não', 'Sem registro de atendimento');
        $arrCores = array('Sim' => $arrCoresGlobal[1], 'Não' => $arrCoresGlobal[3], 'Sem registro de atendimento' => $arrCoresGlobal[2]);
        
        $arrGraficoGeral = array();
        $numTotalGraficoGeral = 0;
        foreach($objAcompanhamentoOuvidoriaDTORet->getArrGraficoGeral() as $staOuvidoria => $numTotal){
          $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acompanhamento_detalhar_ouvidoria&id_estatisticas='.$objAcompanhamentoOuvidoriaDTORet->getDblIdEstatisticas().'&sta_ouvidoria='.$staOuvidoria);
          $arrGraficoGeral[] = array($arrTipos[$staOuvidoria],$numTotal,$numTotal,$strLink);
          $numTotalGraficoGeral += $numTotal;
          //$arrGraficoGeral['Geral'][$arrTipos[$staOuvidoria]] = $numTotal;
        }
        
        $numGrafico = 0;
        $strResultadoGraficoGeral = '';
        $strResultadoGraficoGeral .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
        
        $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acompanhamento_detalhar_ouvidoria&id_estatisticas='.$objAcompanhamentoOuvidoriaDTORet->getDblIdEstatisticas());
        
        $strResultadoGraficoGeral .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,'Geral ('.$numTotalGraficoGeral.')',$strLink, $arrGraficoGeral, 150, $arrCores);
        $strResultadoGraficoGeral .= '</div>';
        
        $arrGraficoPorTipo = array();
        $arrTotalGraficoPorTipo = array();      
        foreach($objAcompanhamentoOuvidoriaDTORet->getArrGraficoPorTipo() as $strTipoProcedimento => $arrTotal){
          
          $arrTipoProcedimento = explode('#',$strTipoProcedimento);
          
          $arrTotalGraficoPorTipo[$strNomeTipoProcedimento] = 0;
          foreach($arrTotal as $staOuvidoria => $numTotal){
            
            
            $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acompanhamento_detalhar_ouvidoria&id_estatisticas='.$objAcompanhamentoOuvidoriaDTORet->getDblIdEstatisticas().'&sta_ouvidoria='.$staOuvidoria.'&id_tipo_procedimento='.$arrTipoProcedimento[0]);
            
            $arrGraficoPorTipo[$strTipoProcedimento][] = array($arrTipos[$staOuvidoria],$numTotal,$numTotal,$strLink);
            $arrTotalGraficoPorTipo[$strTipoProcedimento] += $numTotal;
            //$arrGraficoPorTipo[$strTipoProcedimento][$arrTipos[$staOuvidoria]] = $numTotal;
          }
        }
  
        $strResultadoGraficoPorTipo = '';
        foreach($arrGraficoPorTipo as $strTipoProcedimento => $arrGraficoTipo){
          
          $arrTipoProcedimento = explode('#',$strTipoProcedimento);
          
          $strResultadoGraficoPorTipo .= '<div id="divGrf'.(++$numGrafico).'" class="divAreaGrafico">';
          
          $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acompanhamento_detalhar_ouvidoria&id_estatisticas='.$objAcompanhamentoOuvidoriaDTORet->getDblIdEstatisticas().'&id_tipo_procedimento='.$arrTipoProcedimento[0]);
          
          $strResultadoGraficoPorTipo .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico,$arrTipoProcedimento[1].' ('.$arrTotalGraficoPorTipo[$strTipoProcedimento].')',$strLink, $arrGraficoTipo, 150, $arrCores);
          $strResultadoGraficoPorTipo .= '</div>';
        }
      }
    }
  }
    
  $strItensSelOrgao = OrgaoINT::montarSelectSiglaOuvidoria('null', 'Todos', $objAcompanhamentoOuvidoriaDTO->getNumIdOrgaoUnidadeOrigem());
  $strItensSelStaOuvidoria = OuvidoriaINT::montarSelectStaOuvidoriaAcompanhamento('null', 'Todas', $objAcompanhamentoOuvidoriaDTO->getStrStaOuvidoriaProcedimento());
  $strLinkAjaxUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_auto_completar_todas');
  
  
  $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
  $objTipoProcedimentoDTO->retNumIdTipoProcedimento();
  $objTipoProcedimentoDTO->retStrNome();
  $objTipoProcedimentoDTO->setStrSinOuvidoria('S');
  $objTipoProcedimentoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
  
  $objTipoProcedimentoRN = new TipoProcedimentoRN();
  $arrObjTipoProcedimentoDTO = $objTipoProcedimentoRN->listarRN0244($objTipoProcedimentoDTO);

  $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
  $numIdTipoEquivoco = $objInfraParametro->getValor('ID_TIPO_PROCEDIMENTO_OUVIDORIA_EQUIVOCO', false);

  $strOptionsTipoProcedimento='';
  $numProcedimentos = count($arrObjTipoProcedimentoDTO);
  for($i=0;$i<$numProcedimentos;$i++){
    $strOptionsTipoProcedimento.='<option value="'.$arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento().'"';
    if (isset($_POST['selTipoProcedimento'])){
      if (in_array($arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento(), $arrNumIdTiposProcedimento)) {
        $strOptionsTipoProcedimento .= ' selected="selected"';
      }
    }else{
      if ($arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento() != $numIdTipoEquivoco){
        $strOptionsTipoProcedimento .= ' selected="selected"';
      }
    }
    $strOptionsTipoProcedimento.='>'.PaginaSEI::tratarHTML($arrObjTipoProcedimentoDTO[$i]->getStrNome()).'</option>';
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
 
#lblOrgao {position:absolute;left:0%;top:2%;width:11%;}
#selOrgao {position:absolute;left:10%;top:0%;width:11%;}

#lblPeriodoDe {position:absolute;left:0%;top:23%;width:9%;}
#txtPeriodoDe {position:absolute;left:10%;top:21%;width:9%;}
#imgCalPeriodoD {position:absolute;left:19.7%;top:23%;}

#lblPeriodoA 	{position:relative;left:23%;top:23%;width:9%;}
#txtPeriodoA 	{position:absolute;left:25%;top:21%;width:9%;}
#imgCalPeriodoA {position:absolute;left:34.7%;top:23%;}

#lblStaOuvidoria {position:absolute;left:0%;top:44%;width:25%;}
#selStaOuvidoria {position:absolute;left:10%;top:42%;width:25%;}

#lblUnidade {position:absolute;left:0%;top:65%;width:55%;}
#txtUnidade {position:absolute;left:10%;top:63%;width:55%;}
#divSinTramitacaoOuvidoria {position:absolute;left:66%;top:63%;width:30%;}

#lblTipoProcedimento {position:absolute;left:0%;top:84%;width:9%;}
#selTipoProcedimento, .multipleSelect {position:absolute;left:10%;top:84%;width:40%;}

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

function inicializar(){

  objAutoCompletarUnidade = new infraAjaxAutoCompletar('hdnIdUnidade','txtUnidade','<?=$strLinkAjaxUnidade?>');
  //objAutoCompletarUnidade.maiusculas = true;
  //objAutoCompletarUnidade.mostrarAviso = true;
  //objAutoCompletarUnidade.tempoAviso = 1000;
  //objAutoCompletarUnidade.tamanhoMinimo = 3;
  objAutoCompletarUnidade.limparCampo = true;
  //objAutoCompletarUnidade.bolExecucaoAutomatica = false;

  objAutoCompletarUnidade.prepararExecucao = function(){
    return 'palavras_pesquisa='+document.getElementById('txtUnidade').value+'&id_orgao='+(document.getElementById('selOrgao').value=='null'?'':document.getElementById('selOrgao').value);
  };

  objAutoCompletarUnidade.processarResultado = function(id,descricao,complemento){
    if (id!=''){
      document.getElementById('hdnIdUnidade').value = id;
      document.getElementById('txtUnidade').value = descricao;
    }
  }

  objAutoCompletarUnidade.selecionar('<?=$strIdUnidade?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeUnidade,false)?>');



  sinalizarDestino();

  infraEfeitoTabelas();
}

function alterar(){

  objAutoCompletarUnidade.limpar();

};

function limpar() {
  document.getElementById('selOrgao').value='null';
  document.getElementById('txtPeriodoDe').value='';
  document.getElementById('txtPeriodoA').value='';
  document.getElementById('selStaOuvidoria').value='null';

  objInput = document.getElementsByTagName('input');

  for (var i = 0; i < objInput.length; i++) {
    if (objInput[i].type == 'checkbox' && objInput[i].id.search('chkTipoProcedimento') == 0) {
      objInput[i].checked = false;
    }
  }
  alterar();
}


function onSubmitForm(){

  if ($("#selTipoProcedimento").multipleSelect("getSelects").length==0) {
    alert('Nenhum Tipo selecionado.');
    return false;
  }

  infraExibirAviso();

  return true;
}

function processar(tipo, link){

  document.getElementById('hdnTipo').value = tipo;

  if (onSubmitForm()){
    document.getElementById('frmAcompanhamentoOuvidoria').action = link;
    document.getElementById('frmAcompanhamentoOuvidoria').submit();
  }
}

function abrirDetalhe(link){
 infraAbrirJanelaModal(link,850,550);
}

function sinalizarDestino(){
  if (document.getElementById('chkSinTramitacaoOuvidoria').checked){
    objAutoCompletarUnidade.limpar();
    document.getElementById('txtUnidade').disabled = true;
  }else{
    document.getElementById('txtUnidade').disabled = false;
  }
}
$( document ).ready(function() {
  $("#selTipoProcedimento").multipleSelect({
    filter: false,
    minimumCountSelected: 1,
    selectAll: true
  });
});
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAcompanhamentoOuvidoria"  method="post" onsubmit="return onSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('15em','style="overflow:visible"');
  ?>
    <label id="lblOrgao" for="selOrgao" accesskey="" class="infraLabelOpcional">Órgão:</label>
    <select id="selOrgao" name="selOrgao" onchange="alterar();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelOrgao?>
    </select>

    <label id="lblPeriodoDe" for="txtPeriodoDe" accesskey="" class="infraLabelOpcional">Período:</label>
    <input type="text" id="txtPeriodoDe" name="txtPeriodoDe" class="infraText" value="<?=$_POST['txtPeriodoDe']?>" onkeypress="return infraMascaraData(this, event)" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
		<img id="imgCalPeriodoD" title="Selecionar Data Inicial" alt="Selecionar Data Inicial" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtPeriodoDe',this);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblPeriodoA" for="txtPeriodoA" accesskey="" class="infraLabelObrigatorio">a</label>
    <input type="text" id="txtPeriodoA" name="txtPeriodoA" class="infraText" value="<?=$_POST['txtPeriodoA']?>" onkeypress="return infraMascaraData(this, event)" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <img id="imgCalPeriodoA" title="Selecionar Data Final" alt="Selecionar Data Final" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtPeriodoA',this);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblStaOuvidoria" for="selStaOuvidoria" class="infraLabelOpcional">Solicitações:</label>
    <select id="selStaOuvidoria" name="selStaOuvidoria" class="infraSelect"	tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  	<?=$strItensSelStaOuvidoria?>
    </select>

    <label id="lblUnidade" for="txtUnidade" accesskey="" class="infraLabelOpcional">Destino:</label>
    <input type="text" id="txtUnidade" name="txtUnidade" class="infraText" value="<?=$strNomeUnidade?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" value="<?=$strIdUnidade?>" />

    <div id="divSinTramitacaoOuvidoria" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinTramitacaoOuvidoria" name="chkSinTramitacaoOuvidoria" onchange="sinalizarDestino();" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objAcompanhamentoOuvidoriaDTO->getStrSinTramitacaoOuvidoria())?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinTramitacaoOuvidoria" for="chkSinTramitacaoOuvidoria" accesskey="" class="infraLabelCheckbox" >Com tramitação apenas na ouvidoria</label>
    </div>

    <label id="lblTipoProcedimento" for="selTipoProcedimento" accesskey="" class="infraLabelObrigatorio">Tipos:</label>
    <select style="display: none;" multiple id="selTipoProcedimento" name="selTipoProcedimento[]" class="infraSelect multipleSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strOptionsTipoProcedimento;?>
    </select>


    <input type="hidden" id="hdnTipo" name="hdnTipo" value="<?=$_POST['hdnTipo']?>" />
  <?
  PaginaSEI::getInstance()->fecharAreaDados();

  if (!$bolErro){
    if ($_GET['acao']=='acompanhamento_listar_ouvidoria'){

      PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
      
    }else if ($_GET['acao']=='acompanhamento_gerar_grafico_ouvidoria'){

      EstatisticasINT::montarGrafico('Geral',$strResultadoGraficoGeral,false);
      EstatisticasINT::montarGrafico('Tipos',$strResultadoGraficoPorTipo,false);

    }
  }
  
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);

  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>