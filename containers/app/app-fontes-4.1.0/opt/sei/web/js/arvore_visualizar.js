function excluirProcesso(){
  if (confirm('Confirma exclusão do processo?')){
    location.href = linkExcluirProcesso;
  }
}

function removerSobrestamentoProcesso(){
  if (confirm('Confirma remoção de sobrestamento do processo?')){
    location.href = linkRemoverSobrestamentoProcesso;
  }
}

function reabrirProcesso(){
  //if (confirm('Confirma reabetura do processo?')){
  location.href = linkReabrirProcesso;
  //}
}

function excluirDocumento(){
  if (confirm('Confirma exclusão do documento?')){
    location.href = linkExcluirDocumento;
  }
}

function cienciaProcesso(){
  //if (confirm('Confirma ciência no processo?')){
  document.getElementById('ifrVisualizacao').src = linkCienciaProcesso;
  //}
}

function cienciaDocumento(){
  //if (confirm('Confirma ciência no documento?')){
  document.getElementById('ifrVisualizacao').src = linkCienciaDocumento;
  //}
}

function cienciaProcessoAnexado(){
  //if (confirm('Confirma ciência no processo anexado?')){
  document.getElementById('ifrVisualizacao').src = linkCienciaProcessoAnexado;
  //}
}

function assinarDocumento(){
  parent.infraAbrirJanelaModal(linkAssinarDocumento,600,450);
}

function enviarEmailProcedimento(){
  abrirJanela('janelaEmail_' + nomeJanelaProcesso, linkProcedimentoEnviarEmail);
}

function enviarEmailDocumento(){
  abrirJanela('janelaEmailDocumento_' + nomeJanelaDocumento, linkDocumentoEnviarEmail);
}

function encaminharEmail(){
  abrirJanela('janelaEncaminharEmail_' + nomeJanelaDocumento, linkEncaminharEmail);
}

function responderFormulario(){
  abrirJanela('janelaResponderFormulario_' + nomeJanelaDocumento, linkResponderFormulario);
}

function renunciarCredencial(){
  if (confirm("ATENÇÃO: Confirma renúncia de credenciais do processo nesta unidade?")){
    location.href = linkRenunciarCredencial;
  }
}

function iniciarEditor(link){
  parent.infraAbrirJanela(link, 'janelaEditor_' + nomeJanelaDocumento, infraClientWidth(),infraClientHeight(),'location=0,status=0,resizable=1,scrollbars=1',false);
}

function editarConteudo(assinado){

  if (INFRA_FF > 0 && INFRA_FF < 4){
    alert('Para realizar a edição de documentos no Firefox é recomendado atualizar o navegador para a versão 4 ou posterior.\n\nPara iniciar a atualização automática acesse o menu "Ajuda / Verificar atualizações..." ou "Ajuda / Sobre o Firefox" do navegador.');
    //return;
  }

  if (assinado == 'S') {
    objAjaxVerificacaoAssinatura.bolAssinado = true;
  }else{
    objAjaxVerificacaoAssinatura.executar();
  }

  if (objAjaxVerificacaoAssinatura.bolAssinado){

    if (!confirm('Este documento já foi assinado. Se for editado perderá a assinatura e deverá ser assinado novamente.\n\n Deseja editar o documento?')){

      if (assinado == 'N') {
        atualizarArvore(linkMontarArvoreProcessoDocumento);
      }

      return;
    }
  }

  var janelaEditor = infraAbrirJanela('', 'janelaEditor_' + nomeJanelaDocumento, infraClientWidth(), infraClientHeight(), 'location=0,status=0,resizable=1,scrollbars=1', false);
  if (janelaEditor.location=='about:blank') {
    janelaEditor.location.href = linkEditarConteudo;
  }
  janelaEditor.focus();
}

function alterarFormulario(assinado){

  if (assinado == 'S') {
    objAjaxVerificacaoAssinatura.bolAssinado = true;
  }else{
    objAjaxVerificacaoAssinatura.executar();
  }

  if (objAjaxVerificacaoAssinatura.bolAssinado){

    if (!confirm('Este formulário já foi assinado. Se for editado perderá a assinatura e deverá ser assinado novamente.\n\n Deseja editar o formulário?')){

      if (assinado == 'N') {
        atualizarArvore(linkMontarArvoreProcessoDocumento);
      }

      return;
    }
  }
  document.getElementById('ifrVisualizacao').src = linkAlterarFormulario;
}

function abrirJanela(nome, link){
  var janela = infraAbrirJanela('',nome,850,550,'location=0,status=1,resizable=1,scrollbars=1',false);
  if (janela.location == 'about:blank'){
    janela.location.href = link;
  }
  janela.focus();
}

function testarMudancaHrefIframeInterno(){
  var src = $("#ifrVisualizacao").attr("src");
  if(!src.includes("controlador.php?acao=documento_visualizar&")){
    $("#ifrVisualizacaoInterno").removeClass("ifrVisualizacaoInternoContraste")
  }else{
    $("#ifrVisualizacaoInterno").addClass("ifrVisualizacaoInternoContraste")
  }
}

function exibirAguarde(ifr){
  if($("#"+ifr) && $("#"+ifr).attr("src") && $("#"+ifr).attr("src") != "about:blank" && $("#"+ifr).attr("src") != ""){
    $("#divArvoreAguarde").removeClass("d-none");
    $("#divArvoreAguarde").addClass("d-flex");
  }
}

function ocultarAguarde() {
  if ($("#divArvoreAguarde").hasClass("d-flex")) {

    if (document.getElementById('divArvoreAguarde') != null) {
      $("#divArvoreAguarde").removeClass("d-flex");
      $("#divArvoreAguarde").addClass("d-none");
    }

    //corrige problema do IE onde a barra de status de vez em quando fica como se estivesse carregando (mesmo após o término)
    if (INFRA_IE > 0) {
      window.status = 'Finalizado.';
    }

    redimensionar();
  }
}

function redimensionar(){
  if ( document.getElementById('ifrVisualizacao')!=null){
    exibirVoltarAcoes(false);
  }
}

function atualizarArvore(linkArvore){
  parent.parent.infraOcultarAviso();
  parent.parent.document.getElementById('ifrArvore').src = linkArvore;
}

function detectarExcecao(){
  var idIFrame = "ifrArvoreHtml";
  var ret = false;
  try{
    var doc = null;
    if (document.getElementById("ifrVisualizacao") != null && document.getElementById("ifrVisualizacao").contentWindow != null &&
        document.getElementById("ifrVisualizacao").contentWindow.document.getElementById(idIFrame) != null && document.getElementById("ifrVisualizacao").contentWindow.document.getElementById(idIFrame).contentWindow!=null){
      doc = document.getElementById("ifrVisualizacao").contentWindow.document.getElementById(idIFrame).contentWindow;
    }

    ret = (doc!=null && doc.document.getElementById('divInfraExcecao')!=null);

    if(ret){
      if (document.getElementById("ifrVisualizacao").contentWindow.document.getElementById('divArvoreInformacao') != null) {
        document.getElementById("ifrVisualizacao").contentWindow.document.getElementById('divArvoreInformacao').style.display = 'none';
      }
    }
  }catch(exc){}

}

function incluirEmBloco(tipo){
  document.getElementById('txtBloco').value = '';
  document.getElementById('hdnIdBloco').value = '';
  objLupaBloco.selecionar(700,500,true);
}

function redirecionarBlocos(){
  parent.parent.document.location.href = linkProtocolosBloco + '#' + infraGetAnchor();
}

function visualizarAssinaturas(){
  var doc = document.getElementById("ifrVisualizacao").contentWindow.document;
  if (doc.getElementById('ifrTarjasAssinatura').style.display == 'none'){
    doc.getElementById('btnVisualizarAssinaturas').innerHTML = 'Ocultar Autenticações';
    doc.getElementById('btnVisualizarAssinaturas').value = 'Ocultar Autenticações';

    if (doc.getElementById('ifrArvoreHtml')!=null){
      doc.getElementById('ifrArvoreHtml').style.display = 'none';
    }

    doc.getElementById('ifrTarjasAssinatura').style.display = 'flex';
  }else{
    doc.getElementById('btnVisualizarAssinaturas').innerHTML = 'Visualizar Autenticações';
    doc.getElementById('btnVisualizarAssinaturas').value = 'Visualizar Autenticações';
    doc.getElementById('ifrTarjasAssinatura').style.display = 'none';

    if (doc.getElementById('ifrArvoreHtml')!=null){
      doc.getElementById('ifrArvoreHtml').style.display = 'flex';
    }
  }
}

function alterarTargetAcoes(){
  $(document.getElementById('divArvoreAcoes')).children('a[href!="#"]').attr("target", "ifrVisualizacao");
}


function exibirVoltarAcoes(bolInicio){
  if(parent.infraIsBreakpointBootstrap("lg") || window.name == "ifrVisualizacao"){
    $("#ancVoltarArvore").css("display","none");
    $("#ancIcones").css("display","none");
    $("#ancAnteriorArvore").css("display","none");
    $("#ancProximoArvore").css("display","none");
    $("#divArvoreNavegacao").css("display","none");

  }else {
    $("#ancVoltarArvore").css("display", "inline");
    if( (noSelecionado != null && noSelecionado.acoes != undefined && noSelecionado.acoes.trim() != "")) {
      $("#ancIcones").css("display", "inline");
    }
    $("#ancAnteriorArvore").css("display","inline");
    $("#ancProximoArvore").css("display","inline");
    if (noSelecionado!=null) {
      if (noSelecionado.bolAgrupador) {
        if (noSelecionado.bolAberto) {
          $("#ancFecharArvore").css("display", "inline");
          $("#ancAbrirArvore").css("display", "none");
        } else {
          $("#ancFecharArvore").css("display", "none");
          $("#ancAbrirArvore").css("display", "inline");
        }
      }
    }
    $("#divArvoreNavegacao").css("display","contents");

  }

  if(window.name == "ifrVisualizacao"){
    $("#collapseControle").addClass("d-none");
  }else if(!parent.infraIsBreakpointBootstrap("lg")){
    if(!bolInicio){
      $("#collapseControle").addClass("hide");
      $("#collapseControle").removeClass("show");
    }

  }else{
    if(!bolInicio){
      $("#collapseControle").addClass("show");
      $("#collapseControle").removeClass("hide");
    }
  }
}

function testarDocumento(bolArvore){
  if (bolArvore != '1') {
    var objArvore = parent.parent.document.getElementById('ifrArvore').contentWindow['objArvore'];
    if (objArvore!=null) {
      var noSelecionado = objArvore.getNoSelecionado();
      if (noSelecionado!=null && noSelecionado.acoes!=undefined) {
        if ($("#hdnHashAcoes").val()==undefined) {
          $("#hdnHashAcoes").val(infraMd5(noSelecionado.acoes))
        } else
          if (infraMd5(noSelecionado.acoes)!=$("#hdnHashAcoes").val()) {
            $("#hdnHashAcoes").val(infraMd5(noSelecionado.acoes))
            if (document.getElementById('divArvoreAcoes')) {
              document.getElementById('divArvoreAcoes').innerHTML = noSelecionado.acoes;
              if (noSelecionado.bolAgrupador) {
                document.getElementById('divArvoreNavegacao').innerHTML = '<span>' + noSelecionado.title + '</span>';
              } else {
                document.getElementById('divArvoreNavegacao').innerHTML = '<span>' + noSelecionado.label + '</span>';
              }
              exibirVoltarAcoes(false);
              alterarTargetAcoes();
            }
          }
      }
    }
  }
}
