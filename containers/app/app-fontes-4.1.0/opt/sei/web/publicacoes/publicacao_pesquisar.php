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
      $objOrgaoDTO->setNumIdOrgao($_GET['id_orgao_publicacao']);

      $objOrgaoRN = new OrgaoRN();
      $arrObjOrgaoDTO = $objOrgaoRN->listarPesquisa($objOrgaoDTO);

      $numOrgaos = count($arrObjOrgaoDTO);

      $strVisibilityOrgao = '';
      if ($numOrgaos==0) {
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

      if (count($arrNumIdOrgao) != $numOrgaos){
        $objPesquisaPublicacaoSolrDTO->setArrNumIdOrgao($arrNumIdOrgao);
      }else{
        $objPesquisaPublicacaoSolrDTO->setArrNumIdOrgao(array());
      }

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

	$strItensSelUnidades = UnidadeINT::montarSelectSiglaDescricaoPesquisaPublicacao('null','&nbsp',$objPesquisaPublicacaoSolrDTO->getNumIdUnidadeResponsavel(), $arrNumIdOrgao);
	$strItensSelSeries = SerieINT::montarSelectNomeDescricaoPesquisaPublicacao('null','&nbsp',$objPesquisaPublicacaoSolrDTO->getNumIdSerie(), $arrNumIdOrgao);
	$strItensSelVeiculoPublicacao = VeiculoPublicacaoINT::montarSelectNomePesquisa('null','&nbsp;',$objPesquisaPublicacaoSolrDTO->getNumIdVeiculoPublicacao());

  $strLinkAjuda = SessaoPublicacoes::getInstance()->assinarLink('controlador_publicacoes.php?acao=publicacao_ajuda&acao_origem='.$_GET['acao']);

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

#tblPublicacoes {
  width:100%;
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


#lblOrgao {<?=$strVisibilityOrgao?>}
#selOrgao, .multipleSelect {<?=$strVisibilityOrgao?>}



a.ancoraSigla{
font-size:1em;
}

a.ancoraSigla:hover{
text-decoration:underline !important;
}

#divPrincipal, #frmPublicacaoPesquisa{
  max-width: 1200px;
}

#ancAjuda{
  padding-top:2px;
  height: 26px;
}

<?
PaginaPublicacoes::getInstance()->fecharStyle();
PaginaPublicacoes::getInstance()->montarJavaScript();
PaginaPublicacoes::getInstance()->abrirJavaScript();
?>
//<script>

var objAjaxUnidade = null;
var btnVerCriterios = null;

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

  btnVerCriterios = document.getElementById('btnVerCriteriosPesquisa');

  if (btnVerCriterios!=null && btnVerCriterios.style.visibility!='hidden'){
    location.href = "#ancoraBarraPesquisa";
    btnVerCriterios.focus();
  }else {
    document.getElementById('txtInteiroTeor').focus();
  }
}

function tratarPeriodo(){

  if (document.getElementById('optPeriodoExplicito').checked){
    $("#divPeriodoExplicito").css("visibility", "visible");
  }else{
    $("#divPeriodoExplicito").css("visibility", "hidden");
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
    infraAbrirJanelaModal('<?=SessaoPublicacoes::getInstance()->assinarLink('controlador_publicacoes.php?acao=publicacao_visualizar')?>&id_documento='+publicacoes_sei+'&id_publicacao_legado='+publicacoes_legado,800,600,false);
  }
}

function visualizarPublicacoesRelacionadas(link) {
  infraAbrirJanelaModal(link,800,500,false);
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

  document.getElementById('frmPublicacaoPesquisa').action = '<?=SessaoPublicacoes::getInstance()->assinarLink('controlador_publicacoes.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>' + parametros + '#ancoraBarraPesquisa';

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

function infraExibirMoverScroll(){
  if (btnVerCriterios!=null) {
    btnVerCriterios.style.visibility = 'visible';
  }
}

function infraOcultarMoverScroll(){
  if (btnVerCriterios!=null) {
    btnVerCriterios.style.visibility = 'hidden';
  }
}

//</script>
<?
PaginaPublicacoes::getInstance()->fecharJavaScript();
PaginaPublicacoes::getInstance()->fecharHead();
PaginaPublicacoes::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmPublicacaoPesquisa" name="frmPublicacaoPesquisa"   method="post" onsubmit="return onSubmitForm();" action="<?=SessaoPublicacoes::getInstance()->assinarLink('controlador_publicacoes.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'#ancoraBarraPesquisa')?>">
<?
if ($numRegistros > 0){
  $arrComandos[] = '<button type="button" accesskey="V" id="btnVisualizar" value="Visualizar Selecionados" onclick="visualizarPublicacoes();" class="infraButton"><span class="infraTeclaAtalho">V</span>isualizar Selecionados</button>';
  //$arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
}
PaginaPublicacoes::getInstance()->montarBarraComandosSuperior($arrComandos);
?>

<div id="divPrincipal" class="infraAreaDados" style="height:35em;">

  <div  class="infraAreaDados d-flex flex-column flex-md-row mb-1" >
    <div class="col-12 col-md-2 mx-0 px-0 pt-2">
      <label id="lblOrgao" for="selOrgao" accesskey="" class="infraLabelObrigatorio">Órgão:</label>
    </div>
    <div class="col-12 col-md-9 pl-0 pl-md-1 pt-1 media">
      <select style="display: none" multiple id="selOrgao" name="selOrgao[]" onchange="tratarSelecaoOrgao(true)" class="w-75 w-md-50 infraSelect multipleSelect" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>">
        <?=$strOptionsOrgaos;?>
      </select>
    </div>
  </div>

  <div  class="infraAreaDados d-flex flex-column flex-md-row mb-1" >
    <div class="col-12 col-md-2 mx-0 px-0 pt-2">
      <label id="lblInteiroTeor" for="txtInteiroTeor" accesskey="" class="infraLabelOpcional">Texto para Pesquisa:</label>
    </div>
    <div class="col-12 col-md-9 pl-0 pl-md-1 pt-1 media">
      <input type="text" id="txtInteiroTeor" name="txtInteiroTeor" class="w-100 w-md-75 infraText"  maxlength="250" onkeypress="return infraLimitarTexto(this,event,250);" value="<?=str_replace('\\','',str_replace('"','&quot;',$objPesquisaPublicacaoSolrDTO->getStrPalavrasChave()))?>" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />
      <a id="ancAjuda" class="ml-1" href="<?=$strLinkAjuda?>" target="_blank" title="Ajuda para Pesquisa" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>"><img src="<?=PaginaPublicacoes::getInstance()->getIconeAjuda()?>" class="infraImg"/></a>
    </div>
  </div>

  <div  class="infraAreaDados d-flex flex-column flex-md-row mb-1" >
    <div class="col-12 col-md-2 mx-0 px-0 pt-2">
    	<label id="lblResumo" for="txtResumo" accesskey="" class="infraLabelOpcional">Resumo:</label>
    </div>
    <div class="col-12 col-md-9 pl-0 pl-md-1 pt-1 media">
      <input type="text" id="txtResumo" name="txtResumo" class="w-100 w-md-75 infraText"  maxlength="250" onkeypress="return infraLimitarTexto(this,event,250);" value="<?=$objPesquisaPublicacaoSolrDTO->getStrResumo()?>" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />
    </div>
  </div>

  <div  class="infraAreaDados d-flex flex-column flex-md-row mb-1" >
    <div class="col-12 col-md-2 mx-0 px-0 pt-2">
      <label id="lblUnidadeResponsavel" for="selUnidadeResponsavel" accesskey="" class="infraLabelOpcional">Unidade Responsável:</label>
    </div>
    <div class="col-12 col-md-9 pl-0 pl-md-1 pt-1 media">
    <select id="selUnidadeResponsavel" name="selUnidadeResponsavel" class="w-100 w-md-75 infraSelect" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>">
      <?=$strItensSelUnidades?>
      </select>
    </div>
  </div>

  <div  class="infraAreaDados d-flex flex-column flex-md-row mb-1" >
    <div class="col-12 col-md-2 mx-0 px-0 pt-2">
      <label id="lblSerie" for="selSerie" accesskey="" class="infraLabelOpcional">Tipo do Documento:</label>
    </div>
    <div class="col-12 col-md-9 pl-0 pl-md-1 pt-1 media">
      <select id="selSerie" name="selSerie" class="w-75 w-md-50 infraSelect" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>">
      <?=$strItensSelSeries?>
      </select>
    </div>
  </div>

  <div  class="infraAreaDados d-flex flex-column flex-md-row mb-1" >
    <div class="col-12 col-md-2 mx-0 px-0 pt-2">
  <label id="lblNumero" for="txtNumero" accesskey="" class="infraLabelOpcional">Número:</label>
    </div>
    <div class="col-12 col-md-9 pl-0 pl-md-1 pt-1 media">
  <input type="text" id="txtNumero" name="txtNumero" class="w-50 w-md-25 infraText"  maxlength="50" onkeypress="return infraLimitarTexto(this,event,50);" value="<?=$objPesquisaPublicacaoSolrDTO->getStrNumero()?>" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />
    </div>
  </div>

  <div  class="infraAreaDados d-flex flex-column flex-md-row mb-1" >
    <div class="col-12 col-md-2 mx-0 px-0 pt-2">
      <label id="lblProtocoloPesquisa" for="txtProtocoloPesquisa" accesskey="" class="infraLabelOpcional">Protocolo:</label>
    </div>
    <div class="col-12 col-md-9 pl-0 pl-md-1 pt-1 media">
      <input type="text" id="txtProtocoloPesquisa" name="txtProtocoloPesquisa" class="w-50 w-md-25 infraText"  maxlength="50" onkeypress="return infraLimitarTexto(this,event,50);" value="<?=$objPesquisaPublicacaoSolrDTO->getStrProtocoloPesquisa()?>" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />
    </div>
  </div>

  <div  class="infraAreaDados d-flex flex-column flex-md-row mb-1" >
    <div class="col-12 col-md-2 mx-0 px-0 pt-2">
      <label id="lblVeiculoPublicacao" for="selVeiculoPublicacao" accesskey="" class="infraLabelOpcional">Veículo:</label>
    </div>
    <div class="col-12 col-md-9 pl-0 pl-md-1 pt-1 media">
      <select id="selVeiculoPublicacao" name="selVeiculoPublicacao" class="infraSelect w-75 w-md-50" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>">
      <?=$strItensSelVeiculoPublicacao?>
      </select>
    </div>
  </div>

  <div  class="infraAreaDados d-flex flex-column flex-md-row mb-1" >
    <div class="col-12 col-md-2 mx-0 px-0 pt-2">
      <label id="lblDataDocumento" for="txtDataDocumento" accesskey="" class="infraLabelOpcional">Data do Documento:</label>
    </div>
    <div class="col-12 col-md-9 pl-0 pl-md-1 pt-1  d-flex flex-wrap align-items-center">
      <input type="text" id="txtDataDocumento" name="txtDataDocumento" onkeypress="return infraMascaraData(this, event)" class="w-50 w-md-25 infraText" value="<?=$objPesquisaPublicacaoSolrDTO->getDtaGeracao()?>" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />
      <img id="imgDataDocumento" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" onclick="infraCalendario('txtDataDocumento',this);" alt="Selecionar Data do Documento" title="Selecionar Data do Documento" class="infraImg" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />
    </div>
  </div>

  <div  class="infraAreaDados d-flex flex-column flex-md-row mb-1" >
    <div class="col-12 col-md-2 mx-0 px-0 pt-2">
      <label id="lblDataPublicacao" class="infraLabelObrigatorio">Data de Publicação:</label>
    </div>
    <div class="d-flex flex-column mx-0 px-0 pt-2">
      <div id="divOptHoje" class="my-1 infraDivRadio">
        <input type="radio" id="optHoje" name="rdoDataPublicacao" value="H" onclick="tratarPeriodo();" <?=($objPesquisaPublicacaoSolrDTO->getStrStaPeriodoData()=='H'  ? 'checked="checked"':'')?> class="infraRadio" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>"/>
        <label id="lblHoje" accesskey="" for="optHoje" class="infraLabelRadio">Hoje</label>
      </div>

      <div id="divOptIndeterminada" class="my-1 infraDivRadio">
        <input type="radio" id="optIndeterminada" name="rdoDataPublicacao" value="I" onclick="tratarPeriodo();" <?=($objPesquisaPublicacaoSolrDTO->getStrStaPeriodoData()=='I' || $objPesquisaPublicacaoSolrDTO->getStrStaPeriodoData()==null ? 'checked="checked"':'')?> class="infraRadio" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>"/>
        <label id="lblIndeterminada" accesskey="" for="optIndeterminada" class="infraLabelRadio">Indeterminada</label>
      </div>
      <div id="divOptPeriodoExplicito" class="my-1 infraDivRadio">
        <input type="radio" id="optPeriodoExplicito" name="rdoDataPublicacao" value="E" onclick="tratarPeriodo();" <?=($objPesquisaPublicacaoSolrDTO->getStrStaPeriodoData()=='E' ? 'checked="checked"':'')?> class="infraRadio" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />
        <label id="lblPeriodoExplicito" accesskey="" for="optPeriodoExplicito" class="infraLabelRadio">Período explícito</label>
      </div>
      <div id="divPeriodoExplicito"  class="col-12 col-md-8 media pl-0 pt-1 d-flex">
        <div class="col-5  px-0  d-flex flex-wrap align-items-center">
          <input type="text" id="txtDataInicio" name="txtDataInicio" onkeypress="return infraMascaraData(this, event)" class=" w-75 infraText" value="<?=$objPesquisaPublicacaoSolrDTO->getDtaInicio()?>" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />
          <img id="imgDataInicio" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" onclick="infraCalendario('txtDataInicio',this);" alt="Selecionar Data Inicial" title="Selecionar Data Inicial" class="infraImg mr-1" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />
        </div>
        <div class="col-1 col-md-2  px-0">
          <label id="lblDataAte" class="infraLabelOpcional mx-0 pt-1 pl-md-3">até</label>
        </div>
        <div class="col-5    px-0  d-flex flex-wrap align-items-center">
        <input type="text" id="txtDataFim" name="txtDataFim" onkeypress="return infraMascaraData(this, event)" class="w-75 infraText" value="<?=$objPesquisaPublicacaoSolrDTO->getDtaFim()?>" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />
          <img id="imgDataFim" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" onclick="infraCalendario('txtDataFim',this);" alt="Selecionar Data Final" title="Selecionar Data Final" class="infraImg mr-1" tabindex="<?=PaginaPublicacoes::getInstance()->getProxTabDados()?>" />
        </div>
      </div>
    </div>
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