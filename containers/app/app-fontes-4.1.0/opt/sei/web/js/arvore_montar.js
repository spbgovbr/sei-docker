function consultarCiencias(link){
  parent.document.getElementById('ifrConteudoVisualizacao').contentWindow.document.getElementById("ifrVisualizacao").src = link + '#' + infraGetAnchor();
}

function consultarAndamento(link){
  var nos = objArvore.nodes;
    if (nos!=null && nos!=undefined) {
      objArvore.setNoSelecionado(nos[0]);
    }
  var no = objArvore.getNoSelecionado();
  var ifr = parent.document.getElementById('ifrConteudoVisualizacao')
  ifr.src = no.href;
  function consultarAndamentoListener(event) {
    ifr.contentWindow.document.getElementById("ifrVisualizacao").src = link;
    visualizarDocumento();
    ifr.removeEventListener("load",consultarAndamentoListener);
  }
  ifr.addEventListener("load",consultarAndamentoListener);
}

function associarNosClipboard(nos,nosAcoes,seiUrl,idProcedimento){
  var icone = null;
  for(var i=0;i<nos.length;i++){
    var no = nos[i];
    if (no.tipo != 'PASTA' && no.tipo != 'AGUARDE' && no.tipo.indexOf('FEDERACAO') == -1) {
      icone = document.getElementById('anchorImg' + no.id);

      var id = 'popover-content' + icone.id;
      var divConteudoPopover = null;
      if (no.tipo.indexOf('PROCESSO') != -1) {
        divConteudoPopover = $('<div id="' + id + '" style="display: none;position:relative;">\n' +
            '  <div class="list-group custom-popover" tipo="' + no.tipo + '">\n' +
            '     <a popoverId="' + icone.id + '" tipo="texto" onclick="copiarParaClipboard(this)" data-clipboard-text="' + no.aux + '" class="list-group-item d-flex flex-row clipboard clipboard-icon-focus" href="#" ><img class="align-self-center clipboard-icon-img" src="imagens/arvore_copiar_texto.svg" title="Copiar texto" />&nbsp;<span class="align-self-center">' + no.aux + '</span></a>\n' +
            '    <a popoverId="' + icone.id + '" tipo="texto" onclick="copiarParaClipboard(this)" data-clipboard-text="' + no.aux +' (' + no.title +')" class="list-group-item d-flex flex-row clipboard clipboard-icon-focus" href="#" ><img class="align-self-center clipboard-icon-img" src="imagens/arvore_copiar_texto.svg" title="Copiar texto" />&nbsp;<span class="align-self-center">'  + no.aux +' (' + no.title + ')</span></a>\n' +
            '    <a popoverId="' + icone.id + '" tipo="link" onclick="copiarParaClipboard(this)" data-clipboard-text="#{'+ no.id +'|'  + no.aux +'}#" class="list-group-item d-flex flex-row clipboard clipboard-icon-focus" href="#" ><img class="align-self-center clipboard-icon-img" src="imagens/arvore_copiar_editor.svg" title="Copiar link editor" />&nbsp;<span class="align-self-center">' + no.aux + '</span></a>\n' +
            '    <a popoverId="' + icone.id + '" tipo="link" onclick="copiarParaClipboard(this)" data-clipboard-text="#{'+ no.id +'|'  + no.aux +'} (' + no.title +  ')#" class="list-group-item d-flex flex-row clipboard clipboard-icon-focus" href="#" ><img class="align-self-center clipboard-icon-img" src="imagens/arvore_copiar_editor.svg" title="Copiar link editor" />&nbsp;<span class="align-self-center">' + no.aux +' (' + no.title +  ')</span></a>\n' +
            '    <a popoverId="' + icone.id + '" tipo="url" onclick="copiarParaClipboard(this)" data-clipboard-text="' + seiUrl + '/controlador.php?acao=procedimento_trabalhar&id_procedimento='+no.id+'" class="list-group-item d-flex flex-row clipboard clipboard-icon-focus" href="#" ><img class="align-self-center clipboard-icon-img" src="imagens/arvore_copiar_link_direto.svg" /><span class="align-self-center">&nbsp;Link para Acesso Direto</span></a>\n' +
            '    <a popoverId="' + icone.id + '"  onclick="fecharClipboard(this)" class="list-group-item d-flex flex-row li-fechar clipboard-icon-focus" href="#" ><span class="align-self-center">Fechar</span></a>\n' +
            '  </div>\n' +
            '</div>');
      }else{

        if (!no.bolHabilitado || no.tipo == 'DOCUMENTO_MOVIDO'){
          divConteudoPopover = $('<div id="' + id + '" style="display: none;position:relative;">\n' +
              '  <div class="list-group custom-popover" tipo="' + no.tipo + '">\n' +
              '    <a popoverId="' + icone.id + '" tipo="texto" onclick="copiarParaClipboard(this)" data-clipboard-text="' + no.aux + '" class="list-group-item d-flex flex-row clipboard clipboard-icon-focus" href="#" ><img class="align-self-center clipboard-icon-img" src="imagens/arvore_copiar_texto.svg" title="Copiar texto"/>&nbsp;<span class="align-self-center">' + no.aux + '</span></a>\n' +
              '    <a popoverId="' + icone.id + '" tipo="texto" onclick="copiarParaClipboard(this)"  data-clipboard-text="' + no.label +'" class="list-group-item d-flex flex-row clipboard clipboard-icon-focus" href="#" ><img class="align-self-center clipboard-icon-img" src="imagens/arvore_copiar_texto.svg" title="Copiar texto"/>&nbsp;<span class="align-self-center">'   + no.label + '</span></a>\n' +
              '    <a popoverId="' + icone.id + '"  onclick="fecharClipboard(this)" class="list-group-item d-flex flex-row li-fechar clipboard-icon-focus" href="#" ><span class="align-self-center">Fechar</span></a>\n' +
              '  </div>\n' +
              '</div>');
        }else{
          divConteudoPopover = $('<div id="' + id + '" style="display: none;position:relative;">\n' +
              '  <div class="list-group custom-popover" tipo="' + no.tipo + '">\n' +
              '    <a popoverId="' + icone.id + '" tipo="texto" onclick="copiarParaClipboard(this)" data-clipboard-text="' + no.aux + '" class="list-group-item d-flex flex-row clipboard clipboard-icon-focus" href="#"><img class="align-self-center clipboard-icon-img" src="imagens/arvore_copiar_texto.svg"  title="Copiar texto" />&nbsp;<span class="align-self-center">' + no.aux + '</span></a>\n' +
              '    <a popoverId="' + icone.id + '" tipo="texto" onclick="copiarParaClipboard(this)"  data-clipboard-text="' + no.label +'" class="list-group-item d-flex flex-row clipboard clipboard-icon-focus" href="#"><img class="align-self-center clipboard-icon-img" src="imagens/arvore_copiar_texto.svg"  title="Copiar texto" />&nbsp;<span class="align-self-center">'   + no.label + '</span></a>\n' +
              '    <a popoverId="' + icone.id + '" tipo="link" onclick="copiarParaClipboard(this)" data-clipboard-text="#{'+ no.id +'|'  + no.aux +'}#" class="list-group-item d-flex flex-row clipboard clipboard-icon-focus" href="#"><img class="align-self-center clipboard-icon-img" src="imagens/arvore_copiar_editor.svg" title="Copiar link editor"/>&nbsp;<span class="align-self-center">' + no.aux + '</span></a>\n' +
              '    <a popoverId="' + icone.id + '" tipo="link" onclick="copiarParaClipboard(this)" data-clipboard-text="#'+no.label.replace(no.aux,"{"+ no.id +"|"  + no.aux +"}")+'#" class="list-group-item d-flex flex-row clipboard clipboard-icon-focus" href="#"><img class="align-self-center clipboard-icon-img" src="imagens/arvore_copiar_editor.svg" title="Copiar link editor"/>&nbsp;<span class="align-self-center">' + no.label +'</span></a>\n' +
              '    <a popoverId="' + icone.id + '" tipo="url" onclick="copiarParaClipboard(this)" data-clipboard-text="' + seiUrl + '/controlador.php?acao=procedimento_trabalhar&id_procedimento=' + idProcedimento + '&id_documento='+no.id+'" class="list-group-item d-flex flex-row clipboard clipboard-icon-focus" href="#" ><img class="align-self-center clipboard-icon-img" src="imagens/arvore_copiar_link_direto.svg" /><span class="align-self-center">&nbsp;Link para Acesso Direto</span></a>\n' +
          '    <a popoverId="' + icone.id + '"  onclick="fecharClipboard(this)" class="list-group-item d-flex flex-row li-fechar clipboard-icon-focus" href="#" ><span class="align-self-center">Fechar</span></a>\n' +
          '  </div>\n' +
          '</div>');
        }
      }
      $("body").append(divConteudoPopover);
      $(icone).attr("data-toggle","popover");
      $(icone).attr("data-placement","bottom");
      $(icone).attr("href","#");

      img = document.getElementById('icon' + no.id);
      img.title = 'Menu cópia protocolo';


      $(icone).click(function(e) {
        e.preventDefault();
      })   .popover({
        html: true,
        sanitize: false,
        content: function() {
          return $("#"+'popover-content'+ this.id) .html();
        },

      });

      $(icone).on('show.bs.popover', function () {
        $("a[data-toggle=popover]").not($(this)).popover("hide");

      })
      $(icone).on('shown.bs.popover', function () {
        var idPopover = $("#"+this.id).attr("aria-describedby");
        $( "#" +idPopover ).find(".clipboard-icon-focus").first().focus();
      })
    }
  }

  for(var i=0;i<nosAcoes.length;i++){

    var no = nosAcoes[i];
    var divConteudoPopover = null;
    var id = null;

    if (no.tipo.indexOf('UNIDADE_GERADORA') != -1) {

      icone = document.getElementById('anchor' + no.id);
      icone.title = 'Menu cópia unidade';

      id = 'popover-content' + icone.id;

      divConteudoPopover = $('<div id="' + id + '" style="display: none;position:relative;" >\n' +
          '  <div class="list-group custom-popover" tipo="' + no.tipo + '">\n' +
          '    <a popoverId="' + icone.id + '" tipo="texto" onclick="copiarParaClipboard(this)" data-clipboard-text="' + no.informacao + '" class="list-group-item d-flex flex-row clipboard clipboard-icon-focus" href="#" ><img class="align-self-center clipboard-icon-img" src="imagens/arvore_copiar_texto.svg" title="Copiar texto" />&nbsp;<span class="align-self-center">' + no.informacao + '</span></a>\n' +
          '    <a popoverId="' + icone.id + '" tipo="texto" onclick="copiarParaClipboard(this)" data-clipboard-text="' + no.title + '" class="list-group-item d-flex flex-row clipboard clipboard-icon-focus" href="#" ><img class="align-self-center clipboard-icon-img" src="imagens/arvore_copiar_texto.svg" title="Copiar texto" />&nbsp;<span class="align-self-center">' + no.title + '</span></a>\n' +
          '    <a popoverId="' + icone.id + '" tipo="texto" onclick="copiarParaClipboard(this)" data-clipboard-text="' + no.title + ' (' + no.informacao + ')" class="list-group-item d-flex flex-row clipboard clipboard-icon-focus" href="#" ><img class="align-self-center clipboard-icon-img" src="imagens/arvore_copiar_texto.svg" title="Copiar texto" />&nbsp;<span class="align-self-center">' + no.title + ' (' + no.informacao + ')</span></a>\n' +
          '    <a popoverId="' + icone.id + '" tipo="texto" onclick="copiarParaClipboard(this)" data-clipboard-text="' + no.informacao + ' - ' + no.title + '" class="list-group-item d-flex flex-row clipboard clipboard-icon-focus" href="#" ><img class="align-self-center clipboard-icon-img" src="imagens/arvore_copiar_texto.svg" title="Copiar texto" />&nbsp;<span class="align-self-center">' + no.informacao + ' - ' + no.title + '</span></a>\n' +
          '    <a popoverId="' + icone.id + '"  onclick="fecharClipboard(this)" class="list-group-item d-flex flex-row li-fechar clipboard-icon-focus" href="#" ><span class="align-self-center">Fechar</span></a>\n' +
          '  </div>\n' +
          '</div>');

      $("body").append(divConteudoPopover);
      $(icone).attr("data-toggle", "popover");
      $(icone).attr("data-placement", "bottom");

      icone.onfocus = function(){this.title='Menu cópia unidade'};
      icone.onmouseover = function(){this.title='Menu cópia unidade'};

      $(icone).click(function (e) {
        e.preventDefault();
      }).popover({
        html: true,
        sanitize: false,
        content: function () {
          return $("#" + 'popover-content' + this.id).html();
        }
      });

      //$(icone).tooltip({
      //  placement : "bottom"
      //});

      $(icone).on('show.bs.popover', function () {
        $("a[data-toggle=popover]").not($(this)).popover("hide");
      })

      $(icone).on('shown.bs.popover', function () {
        var idPopover = $("#" + this.id).attr("aria-describedby");
        $("#" + idPopover).find(".clipboard-icon-focus").first().focus();
      })
    }
  }
}

function navegarArvore(sentido) {

  ultimoSentido = sentido;

  if (objArvore!=null) {

    var noSelecionado = objArvore.getNoSelecionado();

    if (noSelecionado!=null) {
      var no = null;
      while(true){
        if (sentido == 'P'){
          no = objArvore.getNoProximo(noSelecionado.id);
        }else if (sentido == 'A'){
          no = objArvore.getNoAnterior(noSelecionado.id);
        }

        if (no!=null) {

          if(no.tipo == 'AGUARDE' || !no.bolHabilitado){
            noSelecionado = no;
          }else{
            objArvore.setNoSelecionado(no);
            self.setTimeout('atualizarVisualizacao(\'' + no.id + '\')', 300);
            break;
          }
        }else{
          alert("Não existem mais itens disponíveis para exibição.");
          break;
        }
      }
    }else{
      alert("Não existem mais itens disponíveis para exibição.");
    }
  }
}


function atualizarMensagemPasta(tipo){

  var pastaAtual = document.getElementById('hdnPastaAtual');

  if (pastaAtual != null){

    var idAguarde = pastaAtual.value.replace('PASTA','AGUARDE');

    var spanAguarde = document.getElementById('span' + idAguarde);
    var imgAguarde = document.getElementById('icon' + idAguarde);

    if (spanAguarde != null && imgAguarde != null){
      if (tipo == 'AVISO'){
        spanAguarde.innerHTML = spanAguarde.title = 'Não foi possível carregar os protocolos.';
        imgAguarde.src = imgIconeRemover;
      }else if (tipo == 'ERRO'){
        spanAguarde.innerHTML = spanAguarde.title = 'Erro carregando protocolos.';
        imgAguarde.src = imgIconeRemover;
      }else if (tipo == 'NAO ENCONTRADO'){
        spanAguarde.innerHTML = spanAguarde.title = 'Nenhum protocolo encontrado.';
        imgAguarde.src = imgIconeRemover;
      }else if (tipo == 'AGUARDE'){
        spanAguarde.innerHTML = spanAguarde.title = 'Aguarde...';
        imgAguarde.src = imgIconeAguardar;
      }
    }
  }
}

function resizeIframe(){
  document.getElementById("ifrPasta").style.height = (infraClientHeight()-30) + 'px';
}

function abrirFecharPasta(id){
  objArvore.processarNoJuncao(id);
}

function visualizacaoRelacionados(n){

  var div = document.getElementById('divRelacionadosParcial'+n);
  if (div != null){
    if (div.style.display=='block'){
      div.style.display = 'none';
    }else{
      div.style.display = 'block';
    }
  }
}

function processarAcoes(NosAcoes){
  for(var a=0;a < NosAcoes.length;a++){
    NosAcoes[a].processar = function(noAcao) {
      var ifrInterno = parent.document.getElementById('ifrConteudoVisualizacao').contentWindow.document.getElementById("ifrVisualizacao");
      objArvore.setNoSelecionado(objArvore.getNo(noAcao.idPai));
      ifrInterno.addEventListener("load", visualizarDocumento);
    }
  }
}

function navegarAgrupador(nodeId) {

  no = objArvore.getNo(nodeId);
  if (no!=null) {
    no.navegar = !no.bolAberto;
    abrirFecharPasta(no.id);

    if (no.carregado) {
      parent.document.getElementById('ifrConteudoVisualizacao').src = linkArvoreNavegar;
    }
  }
}

function finalizarNavegacaoAgrupador(nodeId){

  no = objArvore.getNo(nodeId);

  if (no!=null && no.bolAberto) {
    var nos = objArvore.nodes;
    var noProximo = null;
    n = nos.length;

    for (i = 0; i<n; i++) {
      if (nos[i].id==nodeId) {
        break;
      }
    }

    if (ultimoSentido=='P') {
      for (j = i + 1; j<n; j++) {
        if (nos[j].idPai==nodeId && nos[j].tipo!='AGUARDE' && nos[j].bolHabilitado) {
          noProximo = nos[j];
          break;
        }
      }
    } else {
      for (j = n - 1; j>i; j--) {
        if (nos[j].idPai==nodeId && nos[j].tipo!='AGUARDE' && nos[j].bolHabilitado) {
          noProximo = nos[j];
          break;
        }
      }
    }

    if (noProximo!=null) {
      objArvore.setNoSelecionado(noProximo);
      self.setTimeout('atualizarVisualizacao(\'' + noProximo.id + '\')', 300);
    }else{
      parent.document.getElementById('ifrConteudoVisualizacao').src = linkArvoreNavegar;
    }
  }
}

function processarPasta(seiUrl, idProcedimento){

  if (processarIframe){

    var ie = infraVersaoIE();

    try{
      if (!ie){
        docIframe = document.getElementById('ifrPasta').contentWindow.document;
      }else{
        docIframe = window.frames['ifrPasta'].document;
      }
    }catch(e){
      alert('Não foi possível recuperar os protocolos.');
      return;
    }

    ret = docIframe.body.innerHTML;

    if (ret != ''){

      if (ret.substring(0,2) != 'OK'){

        var prefixoValidacao = 'INFRA_VALIDACAO';

        if (ret.substr(0,15) == prefixoValidacao){

          atualizarMensagemPasta('AVISO');

          var msg = ret.substr(prefixoValidacao.length+1);
          msg = msg.infraReplaceAll("\\n", "\n");
          msg = decodeURIComponent(msg);
          alert(msg);

        }else{

          try{

            atualizarMensagemPasta('ERRO');

            if (docIframe.getElementById('divInfraExcecao')!=null){
              document.getElementById("ifrPasta").style.display = 'block';
              document.getElementById('frmArvore').style.display = 'none';
              resizeIframe();
            }

          }catch(e){alert(e);}
        }
      }else{

        if (objArvore != null){

          var Nos = [];
          var NosAcoes = [];

          var arrComandos = ret.substr(3).split("\n");
          for(var i=0; i < arrComandos.length; i++){
            if (arrComandos[i].substr(0,3)=='Nos'){
              eval(arrComandos[i]);
            }
          }

          if (Nos.length==0){
            atualizarMensagemPasta('NAO ENCONTRADO');
          }else{
            processandoPasta = true;
            try{
              var noPasta = objArvore.getNo(document.getElementById('hdnPastaAtual').value);

              var div = document.getElementById('div' + noPasta.id);
              div.innerHTML = '';

              for (var i=0;i<Nos.length;i++){
                Nos[i].bolAgrupador = false;
              }

              objArvore.adicionarFilhos(noPasta, Nos, NosAcoes);
              processarAcoes(NosAcoes);

              associarNosClipboard(Nos, NosAcoes, seiUrl, idProcedimento);

              noPasta.carregado = true;

              if (noPasta.navegar){
                var funcaoNavegar = 'finalizarNavegacaoAgrupador(\'' + noPasta.id + '\')';
                noPasta.navegar = false;
                setTimeout(funcaoNavegar, 200);
              }

            }catch(e){
              alert(e);
            }
            processandoPasta = false;
          }
        }

        if (INFRA_IE){
          window.status='Finalizado.';
        }
      }
    }
  }
}

function atualizarVisualizacao(id){

  if (objArvore != null) {
    var no = objArvore.getNoSelecionado();
    if (no != null) {

      if (id == undefined || id == no.id) {
        if (!no.bolAgrupador) {
          parent.document.getElementById('ifrConteudoVisualizacao').src = no.href;
        }else{
          if(!parent.infraIsBreakpointBootstrap("lg")){
            parent.document.getElementById('ifrConteudoVisualizacao').src = linkArvoreNavegar;
          }
        }
      }
    }
  }
  redimensionar();
  infraAdicionarEvento(window,'resize',redimensionar);
}

function redimensionar() {
  if(parent.infraIsBreakpointBootstrap("lg")) {
    $("#aVisualizarDocumento").css("display","none");
  }else{
    $("#aVisualizarDocumento").css("display","inline-block");
  }
}

function visualizarDocumento(){
  if(!parent.infraIsBreakpointBootstrap("lg")) {
    $(parent.document.getElementById('ifrConteudoVisualizacao').contentWindow.document.getElementById('collapseControle')).addClass("hide");
    $(parent.document.getElementById('ifrConteudoVisualizacao').contentWindow.document.getElementById('collapseControle')).removeClass("show");
    parent.document.getElementById('divIframeVisualizacao').style.cssText = "display:flex !important;";
    parent.document.getElementById('divIframeArvore').style.cssText = "display:none !important;";
  }
}

function configurarArvore(objArvore, arrPastas, idProcedimento, numPastas){

  objArvore.processarAbertura = function(no){

    if (no.tipo.indexOf('FEDERACAO') != -1) {
      return true;
    }

    processarIframe = true;

    if (!processandoPasta){
      if (!no.carregado){
        document.getElementById('hdnPastaAtual').value = no.id;
        document.getElementById('hdnProtocolos').value = arrPastas[no.id.substr(5)]['protocolos'];
        document.getElementById('frmArvore').action = arrPastas[no.id.substr(5)]['link'];
        document.getElementById('frmArvore').submit();
      }

      document.getElementById('anchorFP' + idProcedimento).style.display='';

      objArvore.numPastasAbertas = objArvore.numPastasAbertas + 1;
      if (objArvore.numPastasAbertas == numPastas){
        document.getElementById('anchorAP' + idProcedimento).style.display='none';
      }

      return true;
    }

    return false;
  }

  objArvore.processarFechamento = function(no){

    if (no.tipo.indexOf('FEDERACAO') != -1) {
      return true;
    }

    document.getElementById('hdnPastaAtual').value = no.id;
    atualizarMensagemPasta('AGUARDE');
    document.getElementById('anchorAP' + idProcedimento).style.display='';
    objArvore.numPastasAbertas = objArvore.numPastasAbertas - 1;
    if (objArvore.numPastasAbertas == 0){
      document.getElementById('anchorFP' + idProcedimento).style.display='none';
    }

    return true;
  }

  objArvore.processar = function(no){
    if(!parent.infraIsBreakpointBootstrap('lg') && (no instanceof infraArvoreNo || (no instanceof infraArvoreAcao && no.target == "ifrConteudoVisualizacao" && no.href != "javascript:void"))) {
      parent.document.getElementById('ifrConteudoVisualizacao').onload = function() {
        if(!parent.infraIsBreakpointBootstrap('lg')) {
          visualizarDocumento();
        }
      }
    }
    return no;
  }

  objArvore.getNoAnterior = function(nodeId){

    var i,j,n,f,k;
    n = this.nodes.length;

    if (n > 1) {

      for (i = 0; i < n; i++) {
        if (this.nodes[i].id==nodeId) {
          break;
        }
      }

      if (i > 0){

        if (this.nodes[i].tipo == 'FEDERACAO') {
          return this.nodes[0]
        }

        if (this.nodes[i].tipo.indexOf('FEDERACAO')!=-1 || this.nodes[i-1].tipo.indexOf('FEDERACAO')!=-1) {
          var f = 1;
          for (j = 1; j<i; j++) {
            if (this.nodes[j].tipo=='FEDERACAO'){
              f = j;
              if (!this.nodes[j].bolAberto){
                break;
              }
            }else if (this.nodes[j].tipo=='INSTALACAO_FEDERACAO' && this.nodes[1].bolAberto) {
              f = j;
            } else if (this.nodes[j].tipo=='ORGAO_FEDERACAO' && this.nodes[j].bolHabilitado) {

              if (this.nodes[j].idPai=='FEDERACAO' && this.nodes[1].bolAberto) {
                f = j;
              }else{
                for (k = 1; k<n; k++) {
                  if (this.nodes[k].id==this.nodes[j].idPai && this.nodes[k].bolAberto) {
                    f = j;
                  }
                }
              }
            }
          }
          return this.nodes[f];
        }

        if (this.nodes[i].tipo == 'PASTA'){
          for(j=i-1;j>0;j--){
            if (this.nodes[j].tipo == 'PASTA'){

              if (this.nodes[j].bolAberto) {
                for (k = n - 1; k>i; k--) {
                  if (this.nodes[k].idPai==this.nodes[j].id && this.nodes[k].tipo!='AGUARDE') {
                    return this.nodes[k];
                  }
                }
              }
              return this.nodes[j];
            }
          }

          return this.nodes[0];

        }else{
          if (this.nodes[i-1].idPai == this.nodes[i].idPai || this.nodes[i-1].tipo == 'PROCESSO' || this.nodes[i-1].tipo == 'PASTA'){
            return this.nodes[i-1];
          }else{

            for (j=1;j<n;j++){
              if (this.nodes[j].tipo == 'PASTA' && this.nodes[j].id==this.nodes[i].idPai) {
                return this.nodes[j];
                break;
              }
            }
          }
        }
      }
    }
    return null;
  }

  objArvore.getNoProximo = function(nodeId){

    var i,j,k,n;
    n = this.nodes.length;

    if (n > 1) {

      for (i = 0; i < n; i++) {
        if (this.nodes[i].id==nodeId) {
          break;
        }
      }

      if (i < n){

        if (i==0){
          return this.nodes[i + 1];
        }

        if (this.nodes[i].tipo == 'FEDERACAO'){

          if (this.nodes[i].bolAberto){
            return this.nodes[i + 1];
          }

          for (j = i + 1; j<n; j++) {
            if (this.nodes[j].tipo!='INSTALACAO_FEDERACAO' && this.nodes[j].tipo!='ORGAO_FEDERACAO') {
              return this.nodes[j];
            }
          }
        }

        if (this.nodes[i].tipo == 'INSTALACAO_FEDERACAO'){

          if (this.nodes[i].bolAberto){
            return this.nodes[i + 1];
          }

          for (j = i + 1; j<n; j++) {
            if (this.nodes[j].idPai!=this.nodes[i].id) {
              return this.nodes[j];
            }
          }
        }

        if (this.nodes[i].tipo=='ORGAO_FEDERACAO'){
          return this.nodes[i + 1];
        }

        if (this.nodes[i].tipo == 'PASTA'){

          if (this.nodes[i].bolAberto) {
            for (j = i + 1; j<n; j++) {
              if (this.nodes[j].idPai==this.nodes[i].id && this.nodes[j].tipo!='AGUARDE') {
                return this.nodes[j];
              }
            }
          }

          for(j=i+1;j<n;j++){
            if (this.nodes[j].tipo == 'PASTA'){
              return this.nodes[j];
            }
          }

        }else{
          if ((i < n - 1) && this.nodes[i+1].idPai == this.nodes[i].idPai){
            return this.nodes[i+1];
          }else{
            for (j=1;j<n;j++){
              if (this.nodes[j].tipo == 'PASTA' && this.nodes[j].id==this.nodes[i].idPai){
                for(k=j+1;k<n;k++){
                  if (this.nodes[k].tipo == 'PASTA'){
                    return this.nodes[k];
                  }
                }
                break;
              }
            }
          }
        }
      }
    }
    return null;
  }

}