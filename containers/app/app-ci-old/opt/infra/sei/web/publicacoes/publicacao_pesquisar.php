<?

try {
	
	require_once dirname(__FILE__).'/../SEI.php';

	session_start();
	
	//////////////////////////////////////////////////////////////////////////////
	InfraDebug::getInstance()->setBolLigado(false);
	InfraDebug::getInstance()->setBolDebugInfra(true);
	InfraDebug::getInstance()->limpar();
	//////////////////////////////////////////////////////////////////////////////

	SessaoPublicacoes::getInstance()->validarLink();
	
	SessaoPublicacoes::getInstance()->validarPermissao($_GET['acao']);

  //$strParametros = '';
  $arrObjOrgaoDTO = array();
  $objResultadoPesquisaSolrDTO = null;

	switch($_GET['acao']){

	  case 'publicacao_pesquisar':

 	    $strTitulo = 'Publicações Eletrônicas';
	    	    
	    $strResultado = '';

	    $arrComandos = array();
	    $arrComandos[] = '<button type="submit" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton">Pesquisar</button>';

      $bolParametros = false;

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->retNumIdOrgao();
      $objOrgaoDTO->retStrSigla();
      $objOrgaoDTO->retStrDescricao();
      $objOrgaoDTO->setStrSinPublicacao('S');
      $objOrgaoDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objOrgaoRN = new OrgaoRN();
      $arrObjOrgaoDTO = $objOrgaoRN->listarRN1353($objOrgaoDTO);

      $strVisibilityOrgao = '';
      if (count($arrObjOrgaoDTO)==0) {
        throw new InfraException('Nenhum órgão configurado para publicação de documentos.');
      }

      $objPesquisaPublicacaoSolrDTO = new PesquisaPublicacaoSolrDTO();

      if (isset($_POST['selOrgao'])) {
        $arrNumIdOrgao = $_POST['selOrgao'];
        if (!is_array($arrNumIdOrgao)) {
          $arrNumIdOrgao = array($arrNumIdOrgao);
        }
      }else if (isset($_GET['id_orgao']) && trim($_GET['id_orgao'])!=''){
        $arrNumIdOrgao = explode(',',$_GET['id_orgao']);
      }else{
        $arrNumIdOrgao = array();
      }

      $objPesquisaPublicacaoSolrDTO->setArrNumIdOrgao($arrNumIdOrgao);

      $objPesquisaPublicacaoSolrDTO->setStrPalavrasChave($_POST['txtInteiroTeor']);
      $objPesquisaPublicacaoSolrDTO->setStrResumo($_POST['txtResumo']);

      if (isset($_POST['selUnidadeResponsavel'])) {
        $objPesquisaPublicacaoSolrDTO->setNumIdUnidadeResponsavel($_POST['selUnidadeResponsavel']);
      }else if (isset($_GET['id_unidade_responsavel'])){
        $objPesquisaPublicacaoSolrDTO->setNumIdUnidadeResponsavel($_GET['id_unidade_responsavel']);
        $bolParametros = true;
      }else{
        $objPesquisaPublicacaoSolrDTO->setNumIdUnidadeResponsavel(null);
      }

      if (isset($_POST['selSerie'])) {
        $objPesquisaPublicacaoSolrDTO->setNumIdSerie($_POST['selSerie']);
      }else if (isset($_GET['id_serie'])){
        $objPesquisaPublicacaoSolrDTO->setNumIdSerie($_GET['id_serie']);
        $bolParametros = true;
      }else{
        $objPesquisaPublicacaoSolrDTO->setNumIdSerie(null);
      }

      $objPesquisaPublicacaoSolrDTO->setStrNumero($_POST['txtNumero']);
      $objPesquisaPublicacaoSolrDTO->setStrProtocoloPesquisa($_POST['txtProtocoloPesquisa']);

      if (isset($_POST['selVeiculoPublicacao'])) {
        $objPesquisaPublicacaoSolrDTO->setNumIdVeiculoPublicacao($_POST['selVeiculoPublicacao']);
      }else if (isset($_GET['id_veiculo'])){
        $objPesquisaPublicacaoSolrDTO->setNumIdVeiculoPublicacao($_GET['id_veiculo']);
        $bolParametros = true;
      }else{
        $objPesquisaPublicacaoSolrDTO->setNumIdVeiculoPublicacao(null);
      }

      if (isset($_POST['txtDataDocumento'])) {
        $objPesquisaPublicacaoSolrDTO->setDtaGeracao($_POST['txtDataDocumento']);
      }else if (isset($_GET['dta_geracao'])){
        $objPesquisaPublicacaoSolrDTO->setDtaGeracao($_GET['dta_geracao']);
        $bolParametros = true;
      }else{
        $objPesquisaPublicacaoSolrDTO->setDtaGeracao(null);
      }

      if (isset($_POST['rdoDataPublicacao'])) {
        $objPesquisaPublicacaoSolrDTO->setStrStaPeriodoData($_POST['rdoDataPublicacao']);
      }else if (isset($_GET['rdo_data_publicacao'])){
        $objPesquisaPublicacaoSolrDTO->setStrStaPeriodoData($_GET['rdo_data_publicacao']);
        $bolParametros = true;
      }else{
        $objPesquisaPublicacaoSolrDTO->setStrStaPeriodoData('I');
      }

      if (isset($_POST['txtDataFim'])) {
        $objPesquisaPublicacaoSolrDTO->setDtaFim($_POST['txtDataFim']);
      }else if (isset($_GET['dta_fim'])){
        $objPesquisaPublicacaoSolrDTO->setDtaFim($_GET['dta_fim']);
        $bolParametros = true;
      }else{
        $objPesquisaPublicacaoSolrDTO->setDtaFim(null);
      }

      if (isset($_POST['txtDataInicio'])) {
        $objPesquisaPublicacaoSolrDTO->setDtaInicio($_POST['txtDataInicio']);
      }else if (isset($_GET['dta_inicio'])){
        $objPesquisaPublicacaoSolrDTO->setDtaInicio($_GET['dta_inicio']);
        $bolParametros = true;
      }else{
        $objPesquisaPublicacaoSolrDTO->setDtaInicio(null);
      }


      $objPesquisaPublicacaoSolrDTO->setNumInicioPaginacao($_POST['hdnInicio']);

      $numRegistros = 0;

	    if (count($_POST) || $bolParametros){
				try{

          $strResultado = SolrPublicacao::executar($objPesquisaPublicacaoSolrDTO, $numRegistros);

				}catch(Exception $e){
          SeiSolrUtil::tratarErroPesquisa(PaginaPublicacoes::getInstance(), $e);
				}
	    }

      break;

	  default:
	    throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
	}


  $strVisibilityOrgao = '';
  if (count($arrObjOrgaoDTO)==1){
    $strVisibilityOrgao = 'visibility:hidden;';
  }

  $strOptionsOrgaos='';
  foreach($arrObjOrgaoDTO as $objOrgaoDTO){
    $strOptionsOrgaos.='<option value="'.$objOrgaoDTO->getNumIdOrgao().'"';
    if (count($objPesquisaPublicacaoSolrDTO->getArrNumIdOrgao())){
      if (in_array($objOrgaoDTO->getNumIdOrgao(), $objPesquisaPublicacaoSolrDTO->getArrNumIdOrgao())) {
        $strOptionsOrgaos .= ' selected="selected"';
      }
    }else{
      $strOptionsOrgaos .= ' selected="selected"';
    }
    $strOptionsOrgaos.='>'.PaginaPublicacoes::tratarHTML($objOrgaoDTO->getStrSigla()).'</option>'."\n";
  }

	$strLinkAjaxUnidade = SessaoPublicacoes::getInstance()->assinarLink('controlador_ajax_publicacoes.php?acao_ajax=montar_unidades_pesquisa');
	$strLinkAjaxSerie = SessaoPublicacoes::getInstance()->assinarLink('controlador_ajax_publicacoes.php?acao_ajax=montar_series_pesquisa');

	$strItensSelUnidades = UnidadeINT::montarSelectSiglaDescricaoPesquisaPublicacao('null','&nbsp',$objPesquisaPublicacaoSolrDTO->getNumIdUnidadeResponsavel(), $arrIdOrgaosSelecionados);
	$strItensSelSeries = SerieINT::montarSelectNomeDescricaoPesquisaPublicacao('null','&nbsp',$objPesquisaPublicacaoSolrDTO->getNumIdSerie(), $arrIdOrgaosSelecionados);
	$strItensSelVeiculoPublicacao = VeiculoPublicacaoINT::montarSelectNomePesquisa('null','&nbsp;',$objPesquisaPublicacaoSolrDTO->getNumIdVeiculoPublicacao());

  $strLinkAjuda = ConfiguracaoSEI::getInstance()->getValor('SEI','URL').'/ajuda/ajuda_solr.html';

} catch(Exception $e) { 
	PaginaPublicacoes::getInstance()->processarExcecao($e);
}

//MONTAGEM DA PÁGINA
PaginaPublicacoes::getInstance()->montarDocType();
PaginaPublicacoes::getInstance()->abrirHtml();
PaginaPublicacoes::getInstance()->abrirHead();
PaginaPublicacoes::getInstance()->montarMeta();
PaginaPublicacoes::getInstance()->montarTitle('SEI - Publicações Eletrônicas');
PaginaPublicacoes::getInstance()->montarStyle();
PaginaPublicacoes::getInstance()->abrirStyle();
?>

div.barra {
  font-size: 1em;
  padding: 0 0 .5em 0;
  text-align: right;
}

#tblPublicacoes {
  border:0 !important;
	border-spacing: 0px !important;
}

#tblPublicacoes tr {
}

#tblPublicacoes td {
border-right:1px solid #ccc; 
border-bottom:1px solid #ccc;
padding:.4em !important;
}

td.tdCheck {
border-top:1px solid #ccc;
border-left:1px solid #ccc;
}

td.tdDados {
border-top:1px solid #ccc;
}

tr.trEspacoPublicacao{
 background-color: white;
}

tr.trEspacoPublicacao td{
 border:0 !important;  
}


#lblOrgao {position:absolute;left:0%;top:0%;width:9%;<?=$strVisibilityOrgao?>}
#selOrgao, .multipleSelect {position:absolute;left:20%;top:0%;width:35%;<?=$strVisibilityOrgao?>}

#lblInteiroTeor {position:absolute;left:0%;top:10%;width:29%;visibility:hidden;}
#txtInteiroTeor {position:absolute;left:20%;top:10%;width:50%;}
#ancAjuda {position:absolute;left:72%;top:10%;}

#lblResumo {position:absolute;left:0%;top:19%;width:29%;}
#txtResumo {position:absolute;left:20%;top:19%;width:50%;}

#lblUnidadeResponsavel {position:absolute;left:0%;top:28%;width:29%;}
#selUnidadeResponsavel {position:absolute;left:20%;top:28%;width:65%;}

#lblSerie {position:absolute;left:0%;top:37%;width:29%;}
#selSerie {position:absolute;left:20%;top:37%;width:35%;}

#lblNumero {position:absolute;left:0%;top:46%;width:29%;}
#txtNumero {position:absolute;left:20%;top:46%;width:10%;}

#lblProtocoloPesquisa {position:absolute;left:0%;top:55%;width:29%;}
#txtProtocoloPesquisa {position:absolute;left:20%;top:55%;width:15%;}

#lblVeiculoPublicacao {position:absolute;left:0%;top:64%;width:29%;}
#selVeiculoPublicacao {position:absolute;left:20%;top:64%;width:35%;}

#lblDataDocumento {position:absolute;left:0%;top:73%;width:20%;}
#txtDataDocumento {position:absolute;left:20%;top:73%;width:10%;}
#imgDataDocumento {position:absolute;left:31%;top:75%;}

#lblDataPublicacao {position:absolute;left:0%;top:82%;width:20%;}
#divOptHoje {position:absolute;left:20%;top:81%;}
#divOptIndeterminada {position:absolute;left:20%;top:87%;}
#divOptPeriodoExplicito {position:absolute;left:20%;top:93%;}

#txtDataInicio {position:absolute;left:20%;top:0%;width:10%;}
#imgDataInicio {position:absolute;left:31%;top:10%;}
#lblDataAte {position:absolute;left:34%;top:10%;width:1%;}
#txtDataFim {position:absolute;left:38%;top:0%;width:10%;}
#imgDataFim {position:absolute;left:49%;top:10%;}


#divRodape {
	margin-top:.5em;
	width:99%;
}

#divRodape div{
 text-align:center;
 font-size: 1.2em;
}

#divRodape b {
	font-weight: bold;
}

#divRodape a {
	border-bottom: 1px solid transparent;
	color: #000080;
	text-decoration: none;
}

#divRodape a:hover {
	border-bottom: 1px solid #000000;
	color: #800000;
}

a.ancoraSigla{
font-size:1em;
}

a.ancoraSigla:hover{
text-decoration:underline !important;
}


<?
PaginaPublicacoes::getInstance()->fecharStyle();
PaginaPublicacoes::getInstance()->montarJavaScript();
PaginaPublicacoes::getInstance()->abrirJavaScript();
?>
//<script>

var objAjaxUnidade = null;

$( document ).ready(function() {
  $("#selOrgao").multipleSelect({
    filter: false,
    minimumCountSelected: 1,
    selectAll: true,
  });
  tratarSelecaoOrgao(false);
});

function inicializar(){

  document.getElementById('frmPublicacaoPesquisa').action = '';



	objAjaxUnidade = new infraAjaxMontarSelect('selUnidadeResponsavel','<?=$strLinkAjaxUnidade?>');
  objAjaxUnidade.limparSelect = true;
  objAjaxUnidade.prepararExecucao = function(){
     return infraAjaxMontarPostPadraoSelect('null','','<?=$_POST['selUnidadeResponsavel']?>')+'&idOrgao=' + obterOrgaosSelecionados();
  };
  objAjaxUnidade.processarResultado = function(){};
  //objAjaxUnidade.executar();
  
  objAjaxSerie = new infraAjaxMontarSelect('selSerie','<?=$strLinkAjaxSerie?>');
  objAjaxSerie.limparSelect = true;
  objAjaxSerie.prepararExecucao = function(){
     return infraAjaxMontarPostPadraoSelect('null','','<?=$_POST['selSerie']?>')+'&idOrgao=' + obterOrgaosSelecionados();
  };
  objAjaxSerie.processarResultado = function(){};
  //objAjaxSerie.executar();

	tratarPeriodo();
	
	infraProcessarResize();

  prepararTrs();

  infraExibirMenuSistemaEsquema();
}

function tratarPeriodo(){
  
  if (document.getElementById('optPeriodoExplicito').checked){  
    document.getElementById('divPeriodoExplicito').style.display='block';    
  }else{
  	document.getElementById('divPeriodoExplicito').style.display='none';
  }
}

function visualizarPublicacoes() {

  var publicacoes_sei = '';
  var publicacoes_legado = '';
  
  var publicacoes = document.getElementById('hdnInfraItensSelecionados').value;
  
  if (publicacoes == ''){ 
    alert('Nenhum registro selecionado.');
  }else{    
      
    arrPublicacao = publicacoes.split(',');
     
    for (var i = 0; i < arrPublicacao.length; i++) {         
      if (arrPublicacao[i].indexOf("sei-") != -1){      
        if (publicacoes_sei != ''){
          publicacoes_sei += ',';
        }        
        publicacoes_sei += arrPublicacao[i].substr(4);
                     
      }else{        
        if (arrPublicacao[i].indexOf("legado-") != -1){        
          if (publicacoes_legado != ''){
            publicacoes_legado += ',';
          }          
          publicacoes_legado += arrPublicacao[i].substr(7);
        }
      }
    }
    infraAbrirJanela('<?=SessaoPublicacoes::getInstance()->assinarLink('controlador_publicacoes.php?acao=publicacao_visualizar')?>&id_documento='+publicacoes_sei+'&id_publicacao_legado='+publicacoes_legado,'janelaVisualizarPublicacoes',1024,768,'location=0,status=0,resizable=1,scrollbars=0');        
  }  
}

function visualizarPublicacoesRelacionadas(link) {
  infraAbrirJanela(link,'janelaVisualizarPublicacoesRelacionadas',740,400,'location=0,status=0,resizable=1,scrollbars=0');           
}

function onSubmitForm(){

  if (obterOrgaosSelecionados()==''){
    alert('Selecione pelo menos um órgão para pesquisa.');
    document.getElementById('selOrgao').focus();
    return false;          
  }

  if (!infraValidarData(document.getElementById('txtDataDocumento'))) {
    return false;
  }

  if (document.getElementById('optPeriodoExplicito').checked){

    if ((infraTrim(document.getElementById('txtDataInicio').value)=='') && (infraTrim(document.getElementById('txtDataFim').value)=='')){
      alert('Período não informado.');
      document.getElementById('txtDataInicio').focus();
      return false;
    }

    if ((infraTrim(document.getElementById('txtDataInicio').value)=='') ^ (infraTrim(document.getElementById('txtDataFim').value)=='')){
      alert('Período incompleto.');
      document.getElementById('txtDataInicio').focus();
      return false;
    }

    if (infraTrim(document.getElementById('txtDataInicio').value)!='' && infraTrim(document.getElementById('txtDataFim').value)!='') {
      if (!infraValidarData(document.getElementById('txtDataInicio'))) {
        return false;
      }

      if (!infraValidarData(document.getElementById('txtDataFim'))) {
        return false;
      }

      if (infraCompararDatas(document.getElementById('txtDataInicio').value, document.getElementById('txtDataFim').value)<0) {
        alert('Período de datas inválido.');
        document.getElementById('txtDataInicio').focus();
        return false;
      }
    }
  }

  var parametros = '';

  if ($('#selOrgao option:selected').length != $("#selOrgao option").length && obterOrgaosSelecionados()!=''){
    parametros += '&id_orgao=' + obterOrgaosSelecionados();
  }

  if (infraSelectSelecionado(document.getElementById('selUnidadeResponsavel'))){
    parametros += '&id_unidade_responsavel=' + document.getElementById('selUnidadeResponsavel').value;
  }

  if (infraSelectSelecionado(document.getElementById('selSerie'))){
    parametros += '&id_serie=' + document.getElementById('selSerie').value;
  }

  if (infraSelectSelecionado(document.getElementById('selVeiculoPublicacao'))){
    parametros += '&id_veiculo=' + document.getElementById('selVeiculoPublicacao').value;
  }

  if (infraTrim(document.getElementById('txtDataDocumento').value)!=''){
    parametros += '&dta_geracao=' + document.getElementById('txtDataDocumento').value;
  }

  if (document.getElementById('optHoje').checked){
    parametros += '&rdo_data_publicacao=H';
  }else if (document.getElementById('optPeriodoExplicito').checked){
    parametros += '&rdo_data_publicacao=E';
  }else{
    parametros += '&rdo_data_publicacao=I';
  }

  if (infraTrim(document.getElementById('txtDataInicio').value)!=''){
    parametros += '&dta_inicio=' + document.getElementById('txtDataInicio').value;
  }

  if (infraTrim(document.getElementById('txtDataFim').value)!=''){
    parametros += '&dta_fim=' + document.getElementById('txtDataFim').value;
  }

  document.getElementById('frmPublicacaoPesquisa').action = '<?=SessaoPublicacoes::getInstance()->assinarLink('controlador_publicacoes.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>' + parametros;

  return true;
}

function tratarSelecaoOrgao(executar){

  if (obterOrgaosSelecionados()==''){
    document.getElementById('selUnidadeResponsavel').disabled = true;
    document.getElementById('selUnidadeResponsavel').options.length = 0;
    
    document.getElementById('selSerie').disabled = true;
    document.getElementById('selSerie').options.length = 0;
  }else{
    document.getElementById('selUnidadeResponsavel').disabled = false;
    if (executar){
      objAjaxUnidade.executar();
    }

    document.getElementById('selSerie').disabled = false;
    if (executar){
      objAjaxSerie.executar();
    }
  }
}

function obterOrgaosSelecionados(){
  return $("#selOrgao").multipleSelect("getSelects");
}

function prepararTrs(){
  
  var i;
  var tab = document.getElementById('tblPublicacoes');
  
  if (tab != null){
    
    //Adiciona eventos para modificar a linha com o passar do mouse
    var trs = tab.getElementsByTagName("tr");
      
    for(i=0;i < trs.length;i++){
      if (trs[i].id.search('trPublicacaoA')==0){
      
        trs[i].onmarcada=function(){
          var trDependente = document.getElementById(this.id.replace('A','B'));
          if (trDependente!=null){
            infraFormatarTrMarcada(trDependente);
          }
        };
        
        trs[i].ondesmarcada=function(){
          var trDependente = document.getElementById(this.id.replace('A','B'));
          if (trDependente!=null){
            infraFormatarTrDesmarcada(trDependente);
          }
        };
      }
    }
  }
}

function navegar(inicio) {
  document.getElementById('hdnInicio').value = inicio;
  if (typeof(window.onSubmitForm)=='function' && !window.onSubmitForm()) {
    return;
  }
  document.getElementById('frmPublicacaoPesquisa').submit();
}

//</script>
<?
PaginaPublicacoes::getInstance()->fecharJavaScript();
PaginaPublicacoes::getInstance()->fecharHead();
PaginaPublicacoes::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmPublicacaoPesquisa" name="frmPublicacaoPesquisa" method="post" onsubmit="return onSubmitForm();" action="<?=SessaoPublicacoes::getInstance()->assinarLink('controlador_publicacoes.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
if ($numRegistros > 0){
  $arrComandos[] = '<button type="button" accesskey="V" id="btnVisualizar" value="Visualizar Selecionados" onclick="visualizarPublicacoes();" class="infraButton"><span class="infraTeclaAtalho">V</span>isualizar Selecionados</button>';
  //$arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
}
PaginaPublicacoes::getInstance()->montarBarraComandosSuperior($arrComandos);
?>

<div id="divPrincipal" class="infraAreaDados" style="height:35em;">

  <label id="lblOrgao" for="selOrgao" accesskey="" class="infraLabelObrigatorio">Órgão:</label>
  <select style="display: none" multiple id="selOrgao" name="selOrgao[]" onchange="tratarSelecaoOrgao(true)" class="infraSelect multipleSelect" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>">
    <?=$strOptionsOrgaos;?>
  </select>

  <label id="lblInteiroTeor" for="txtInteiroTeor" accesskey="" class="infraLabelOpcional">Inteiro Teor:</label>
	<input type="text" id="txtInteiroTeor" name="txtInteiroTeor" class="infraText"  maxlength="250" onkeypress="return infraLimitarTexto(this,event,250);" value="<?=str_replace('\\','',str_replace('"','&quot;',$objPesquisaPublicacaoSolrDTO->getStrPalavrasChave()))?>" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />
  <a id="ancAjuda" href="<?=$strLinkAjuda?>" target="_blank" title="Ajuda para Pesquisa" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>"><img src="<?=PaginaPublicacoes::getInstance()->getIconeAjuda()?>" class="infraImg"/></a>

	<label id="lblResumo" for="txtResumo" accesskey="" class="infraLabelOpcional">Resumo:</label>
	<input type="text" id="txtResumo" name="txtResumo" class="infraText"  maxlength="250" onkeypress="return infraLimitarTexto(this,event,250);" value="<?=$objPesquisaPublicacaoSolrDTO->getStrResumo()?>" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />
	
  <label id="lblUnidadeResponsavel" for="selUnidadeResponsavel" accesskey="" class="infraLabelOpcional">Unidade Responsável:</label>
  <select id="selUnidadeResponsavel" name="selUnidadeResponsavel" class="infraSelect" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>">
  <?=$strItensSelUnidades?>
  </select>

  <label id="lblSerie" for="selSerie" accesskey="" class="infraLabelOpcional">Tipo do Documento:</label>
  <select id="selSerie" name="selSerie" class="infraSelect" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>">
  <?=$strItensSelSeries?>
  </select>

  <label id="lblNumero" for="txtNumero" accesskey="" class="infraLabelOpcional">Número:</label>
  <input type="text" id="txtNumero" name="txtNumero" class="infraText"  maxlength="50" onkeypress="return infraLimitarTexto(this,event,50);" value="<?=$objPesquisaPublicacaoSolrDTO->getStrNumero()?>" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />

  <label id="lblProtocoloPesquisa" for="txtProtocoloPesquisa" accesskey="" class="infraLabelOpcional">Protocolo:</label> 
  <input type="text" id="txtProtocoloPesquisa" name="txtProtocoloPesquisa" class="infraText"  maxlength="50" onkeypress="return infraLimitarTexto(this,event,50);" value="<?=$objPesquisaPublicacaoSolrDTO->getStrProtocoloPesquisa()?>" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />

  <label id="lblVeiculoPublicacao" for="selVeiculoPublicacao" accesskey="" class="infraLabelOpcional">Veículo:</label>
	<select id="selVeiculoPublicacao" name="selVeiculoPublicacao" class="infraSelect" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>">
	<?=$strItensSelVeiculoPublicacao?>
	</select>
  
  <label id="lblDataDocumento" for="txtDataDocumento" accesskey="" class="infraLabelOpcional">Data do Documento:</label>        
  <input type="text" id="txtDataDocumento" name="txtDataDocumento" onkeypress="return infraMascaraData(this, event)" class="infraText" value="<?=$objPesquisaPublicacaoSolrDTO->getDtaGeracao()?>" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />
  <img id="imgDataDocumento" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" onclick="infraCalendario('txtDataDocumento',this);" alt="Selecionar Data do Documento" title="Selecionar Data do Documento" class="infraImg" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />

  <label id="lblDataPublicacao" class="infraLabelObrigatorio">Data de Publicação:</label>

  <div id="divOptHoje" class="infraDivRadio">
    <input type="radio" id="optHoje" name="rdoDataPublicacao" value="H" onclick="tratarPeriodo();" <?=($objPesquisaPublicacaoSolrDTO->getStrStaPeriodoData()=='H'  ? 'checked="checked"':'')?> class="infraRadio"/>
    <label id="lblHoje" accesskey="" for="optHoje" class="infraLabelRadio" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>">Hoje</label>
  </div>

  <div id="divOptIndeterminada" class="infraDivRadio">
    <input type="radio" id="optIndeterminada" name="rdoDataPublicacao" value="I" onclick="tratarPeriodo();" <?=($objPesquisaPublicacaoSolrDTO->getStrStaPeriodoData()=='I' || $objPesquisaPublicacaoSolrDTO->getStrStaPeriodoData()==null ? 'checked="checked"':'')?> class="infraRadio"/>
    <label id="lblIndeterminada" accesskey="" for="optIndeterminada" class="infraLabelRadio" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>">Indeterminada</label>
  </div>

  <div id="divOptPeriodoExplicito" class="infraDivRadio">
    <input type="radio" id="optPeriodoExplicito" name="rdoDataPublicacao" value="E" onclick="tratarPeriodo();" <?=($objPesquisaPublicacaoSolrDTO->getStrStaPeriodoData()=='E' ? 'checked="checked"':'')?> class="infraRadio" />
    <label id="lblPeriodoExplicito" accesskey="" for="optPeriodoExplicito" class="infraLabelRadio" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>">Período explícito</label>
  </div>

</div>
  
<div id="divPeriodoExplicito" class="infraAreaDados" style="height:2.5em;">
  <input type="text" id="txtDataInicio" name="txtDataInicio" onkeypress="return infraMascaraData(this, event)" class="infraText" value="<?=$objPesquisaPublicacaoSolrDTO->getDtaInicio()?>" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />
  <img id="imgDataInicio" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" onclick="infraCalendario('txtDataInicio',this);" alt="Selecionar Data Inicial" title="Selecionar Data Inicial" class="infraImg" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />
  <label id="lblDataAte" class="infraLabelOpcional">&nbsp;até&nbsp;</label>
  <input type="text" id="txtDataFim" name="txtDataFim" onkeypress="return infraMascaraData(this, event)" class="infraText" value="<?=$objPesquisaPublicacaoSolrDTO->getDtaFim()?>" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />
  <img id="imgDataFim" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" onclick="infraCalendario('txtDataFim',this);" alt="Selecionar Data Final" title="Selecionar Data Final" class="infraImg" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />
</div>

<?
if ($numRegistros){
  PaginaPublicacoes::getInstance()->montarAreaTabela($strResultado,$numRegistros,false);
}else{
  echo $strResultado;
}
?>
  <input type="hidden" id="hdnInicio" name="hdnInicio" value="0" />
</form>
<?
PaginaPublicacoes::getInstance()->montarAreaDebug();
PaginaPublicacoes::getInstance()->fecharBody();
PaginaPublicacoes::getInstance()->fecharHtml();
?>