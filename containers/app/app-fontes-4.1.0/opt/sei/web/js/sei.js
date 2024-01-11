
$( document ).ready(function() {
  if(verificarVisualizacaoArvore()){
    if ($("#ancVoltarArvoreSuperior")!=null || $("#ancVoltarArvoreInferior")!=null) {
      infraAdicionarEvento(window, 'resize', function () {
        processarExibicaoVoltarArvore();
      });
      processarExibicaoVoltarArvore();
    }
  }
});

if ( INFRA_FF>0 ) { // Correção para problema do Enter no confirm
  (function(window){
    var _confirm = window.confirm;
    window.confirm = function(msg){
      var keyupCanceler = function(ev){
        ev.stopPropagation();
        return false;
      };
      document.addEventListener("keyup", keyupCanceler, true);
      var retVal = _confirm(msg);
      setTimeout(function(){
        document.removeEventListener("keyup", keyupCanceler, true);
      }, 150); // Giving enough time to fire event
      return retVal;
    };
  })(window);
}

function seiExibirOcultarGrafico(id) {
  if (document.getElementById('div' + id).style.display != "block") {

    document.getElementById('div' + id).style.display = "block";
    document.getElementById('btnVer' + id).style.display = "none";
    document.getElementById('btnOcultar' + id).style.display = "block";

  } else {
    document.getElementById('div' + id).style.display = "none";
    document.getElementById('btnVer' + id).style.display = "block";
    document.getElementById('btnOcultar' + id).style.display = "none";
  }
  seiRedimensionarGraficos();
}

function seiRedimensionarGraficos(){
  var i=1;
  var divTela = document.getElementById('divInfraAreaTelaD');
  if (divTela!=null){
    var w = divTela.offsetWidth - 10;
    if (w < 0) w = 0;
    while (true) {
      var grf=document.getElementById('divGrf'+i);
      if (grf==null) break;
      grf.style.width = w + 'px';
      i++;
    }

    var arrDiv = document.getElementsByTagName('div');
    for (i = 0; i < arrDiv.length; i++) {
      if (arrDiv[i].className == 'divAreaGrafico') {
        arrDiv[i].style.width = w + 'px';
      }
    }
  }
}

function seiCadastroContato(idContato, idObject, idFrm, link) {
  var frm = document.getElementById(idFrm);

  document.getElementById('hdnContatoObject').value = idObject;
  document.getElementById('hdnContatoIdentificador').value = idContato;

  infraAbrirJanelaModal('', 800, 700);
  var actionAnterior = frm.action;
  frm.target = 'modal-frame';
  frm.action = link;
  frm.submit();
  frm.target = '_self';
  frm.action = actionAnterior;
}

function seiCadastroAssunto(idAssunto, idObject, idFrm, link) {

  if (infraTrim(idAssunto)!='') {
    var frm = document.getElementById(idFrm);

    document.getElementById('hdnAssuntoIdentificador').value = idAssunto;

    var actionAnterior = frm.action;
    infraAbrirJanelaModal('', 700, 600);
    frm.target = 'modal-frame';
    frm.action = link;
    frm.submit();
    frm.target = '_self';
    frm.action = actionAnterior;
  }
}

function seiFiltrarTabela(event){
  var tbl= $(event.data).find('tbody');
  var filtro=$(this).val();

  if (filtro.length>0){
    $('.infraTrSelecionada:hidden').removeClass('infraTrSelecionada');
    filtro=infraRetirarAcentos(filtro).toLowerCase();
    tbl.find('tr').each(function(){
      var ancora=$(this).find('.ancoraOpcao');
      var descricao=$(this).attr('data-desc');
      var i=descricao.indexOf(filtro);
      if(i==-1)
        $(this).hide();
      else {
        $(this).show();
        $(this).val();
        var text=ancora.text();
        var html='';
        var ini=0;
        while (i!=-1) {
          html+=text.substring(ini,i);
          html+='<span class="spanRealce">';
          html+=text.substr(i,filtro.length);
          html+='</span>';
          ini=i+filtro.length;
          i=descricao.indexOf(filtro,ini);
        }
        html+=text.substr(ini);
        ancora.html(html);
      }
    });
  } else {
    tbl.find('tr').show();
    tbl.find('.ancoraOpcao').each(function(){$(this).html($(this).text());});
  }
}

function seiPrepararFiltroTabela(objTabela,objInput){
  $(objInput).on('keyup',objTabela,seiFiltrarTabela);
  $(objInput).focus();
  var tbody=$(objTabela).find('tbody');
  tbody.find('tr').each(function(){
    $(this).removeAttr('onmouseover').removeAttr('onmouseout');
  });
  tbody.on('mouseenter','tr',function(e){
    $('.infraTrSelecionada').removeClass('infraTrSelecionada');
    $(e.currentTarget).addClass('infraTrSelecionada').find('.ancoraOpcao').focus();
  });
  $(document).on('keydown',function(e){
    if(e.which!=40 && e.which!=38) return;
    var sel=$('.infraTrSelecionada');
    if(sel.length==0) {
      sel=tbody.find('tr:visible:first').addClass('infraTrSelecionada');
    } else if(e.which==40) {
      if (sel.nextAll('tr:visible').length != 0) {
        sel.removeClass('infraTrSelecionada');
        sel=sel.nextAll('tr:visible:first').addClass('infraTrSelecionada');
      }
    } else {
      if (sel.prevAll('tr:visible').length != 0) {
        sel.removeClass('infraTrSelecionada');
        sel=sel.prevAll('tr:visible:first').addClass('infraTrSelecionada');
      }
    }
    sel.find('.ancoraOpcao').focus();
    e.preventDefault();
  })
}

function processarExibicaoVoltarArvore(){
  if (parent!=null) {

    var arvore = parent.parent.document.getElementById('divIframeArvore');

    if (arvore!=null && arvore.style.display != 'none') {

      if ($("#ancVoltarArvoreSuperior")!=null) {
        $("#ancVoltarArvoreSuperior").css("display", "none");
      }

      if ($("#ancVoltarArvoreInferior")!=null) {
        $("#ancVoltarArvoreInferior").css("display", "none");
      }

    } else if(!parent.parent.infraIsBreakpointBootstrap("lg")) {
      if ($("#ancVoltarArvoreSuperior")!=null) {
        $("#ancVoltarArvoreSuperior").css("display", "block");
      }

      if ($("#ancVoltarArvoreInferior")!=null) {
        $("#ancVoltarArvoreInferior").css("display", "block");
      }
    }
  }
}

function verificarVisualizacaoArvore() {
  try {
    return window.self !== window.top && window.name == "ifrConteudoVisualizacao";
  } catch (e) {
    return true;
  }
}

function seiVoltarArvoreProcesso(){
  var p = window.parent.parent;
  if (p!=null) {
    if (p.document.getElementById('divIframeArvore')!=null) {
      p.document.getElementById('divIframeArvore').style.cssText = "display:flex !important;";
    }

    if (p.document.getElementById('divIframeVisualizacao')) {
      p.document.getElementById('divIframeVisualizacao').style.cssText = "display:none !important;";
    }
  }
}

function copiarParaClipboard(obj) {

  event.preventDefault();

  var str = $(obj).attr("data-clipboard-text");
  function listener(e) {
    e.clipboardData.setData("text/plain", str);
    e.preventDefault();
  }

  if(window.clipboardData){
    window.clipboardData.setData("Text", str);
  } else if(navigator.clipboard) {
    navigator.clipboard.writeText(str);
  }else{
    document.addEventListener("copy", listener);
    document.execCommand("copy");
    document.removeEventListener("copy", listener);
  }

  var trigger = obj;

  p = infraObterPosicao(trigger)

  var div = document.getElementById('divMsgClipboard');
  var criou = false;

  if (div==null) {
    var div = document.createElement("div");
    div.id = 'divMsgClipboard';
    criou = true;
  }
  div.style.zIndex = 1000;
  div.className = 'msgGeral msgSucesso';

  var texto = "Texto copiado.";
  if(trigger.getAttribute("tipo") == "link"){
    texto = "Link para o editor copiado.";
  }else if(trigger.getAttribute("tipo") == "url") {
    texto = "Link para acesso direto copiado.";
  }
  div.innerHTML = texto;
  div.style.position = "absolute";  // Prevent scrolling to bottom of page in MS Edge.

  var liAntes = $(trigger).prevAll();

  div.style.top = (liAntes.length*25) + 'px';
  if (criou) {
    trigger.parentNode.appendChild(div);
  }

  div.setAttribute('role','alert');

  $("#divMsgClipboard").fadeIn(300).delay(1500).fadeOut(400);
  setTimeout(function(){
    $(trigger.parentNode.parentNode.parentNode).fadeOut(400,function() {
      $("#"+trigger.getAttribute("popoverId")).popover("hide");
      $(trigger.parentNode.parentNode.parentNode).show();
      $("#"+trigger.getAttribute("popoverId")).focus();
    });
  },1800);

};

function fecharClipboard(obj){

  event.preventDefault();

  var trigger = obj;
  $(trigger.parentNode.parentNode.parentNode).fadeOut(400,function() {
    $("#"+trigger.getAttribute("popoverId")).popover("hide");
    $(trigger.parentNode.parentNode.parentNode).show();
    $("#"+trigger.getAttribute("popoverId")).focus();
  });
}

function seiGerarEfeitoTabelasRowSpan(idTabela) {

  var i;
  var tab = document.getElementById(idTabela);

  infraEfeitoTabelas();

  if (tab!=null) {

    var trs = tab.getElementsByTagName("tr");

    for (i = 0; i<trs.length; i++) {

      if (trs[i].getAttribute('name')!=null) {

        trs[i].onmarcada = function () {
          var arrTr = document.getElementsByName(this.getAttribute('name'));
          for (var j = 0; j<arrTr.length; j++) {
            $(arrTr[j]).removeClass("infraTrSelecionada");
            $(arrTr[j]).addClass("infraTrMarcada");
          }
        };

        trs[i].ondesmarcada = function () {
          var arrTr = document.getElementsByName(this.getAttribute('name'));
          for (var j = 0; j<arrTr.length; j++) {
            $(arrTr[j]).removeClass("infraTrMarcada");
            $(arrTr[j]).removeClass("infraTrAcessada");
            $(arrTr[j]).removeClass("infraTrSelecionada");
          }
        };

        trs[i].onacessada = function () {
          var arrTr = document.getElementsByName(this.getAttribute('name'));
          for (var j = 0; j<arrTr.length; j++) {
            $(arrTr[j]).removeClass("infraTrSelecionada");
            $(arrTr[j]).removeClass("infraTrMarcada");
            $(arrTr[j]).addClass("infraTrAcessada");
          }
        };

        trs[i].onmouseover = function () {
          var arrTr = document.getElementsByName(this.getAttribute('name'));
          for (var j = 0; j<arrTr.length; j++) {
            $(arrTr[j]).addClass("infraTrSelecionada");
          }
        };

        trs[i].onmouseout = function () {
          var arrTr = document.getElementsByName(this.getAttribute('name'));
          for (var j = 0; j<arrTr.length; j++) {
            $(arrTr[j]).removeClass("infraTrSelecionada");
          }
        };

        if (trs[i].className.indexOf('infraTrAcessada')>0) {
          var arrTr = document.getElementsByName(trs[i].getAttribute('name'));
          for (var j = 0; j<arrTr.length; j++) {
            $(arrTr[j]).removeClass("infraTrSelecionada");
            $(arrTr[j]).removeClass("infraTrMarcada");
            $(arrTr[j]).addClass("infraTrAcessada");
          }
        }
      }
    }
  }
}

function seiConfigurarTabIndexSinalizacoes(tabela, tabindex){
  $('#' + tabela + ' tr td:nth-child(2) > *').attr('tabindex',tabindex);
}

function infraSistemaTeclasAtalho(tecla, e) {
  if (!infraPadraoTeclasAtalho(tecla,e)){

    if (e.ctrlKey && e.altKey){

      switch(tecla){
        case 82: //R
        case 114: //r

          var ifrArvore = parent.parent.document.getElementById('ifrArvore');
          if (ifrArvore!=null){
            var objArvore = ifrArvore.contentWindow['objArvore'];
            objArvore.setNoSelecionado(objArvore.getNo(objArvore.nodes[0].id));
            ifrArvore.contentWindow.atualizarVisualizacao();
            return true;
          }
          break;

        case 83: //S
        case 115: //s

          var ifrArvore = parent.parent.document.getElementById('ifrArvore');
          if (ifrArvore!=null){
            var objArvore = ifrArvore.contentWindow['objArvore'];
            objArvore.setNoSelecionado(objArvore.getNoSelecionado());
            return true;
          }
          break;

        case 70: //F
        case 102: //f

          var ifrConteudoVisualizacao = parent.parent.document.getElementById('ifrConteudoVisualizacao');
          if (ifrConteudoVisualizacao!=null){
            var divAcoes = ifrConteudoVisualizacao.contentWindow['divArvoreAcoes'];
            if (divAcoes!=null) {
              var arrBotoes = divAcoes.getElementsByTagName('a');
              if (arrBotoes.length) {
                arrBotoes[0].focus();
                return true;
              }
            }
          }
          break;

        case 86: //V
        case 118: //v

          var ifrConteudoVisualizacao = parent.parent.document.getElementById('ifrConteudoVisualizacao');
          if (ifrConteudoVisualizacao!=null){
            var ifrVisualizacao = ifrConteudoVisualizacao.contentWindow['ifrVisualizacao'];
            if (ifrVisualizacao!=null) {
              var divArvoreConteudo = ifrConteudoVisualizacao.contentWindow.document.getElementById('divArvoreConteudoIfr');
              if (divArvoreConteudo!=null) {
                var divInfraBarraLocalizacao = ifrVisualizacao.document.getElementById('divInfraBarraLocalizacao');
                if (divInfraBarraLocalizacao!=null) {
                  divInfraBarraLocalizacao.focus();
                  return true;
                }else{
                  divArvoreConteudo.focus();
                  return true;
                }
              }
            }
          }
          break;

        case 85: //U
        case 117: //u

          var ifrArvore = parent.parent.document.getElementById('ifrArvore');
          if (ifrArvore!=null){
            var objArvore = ifrArvore.contentWindow['objArvore'];
            objArvore.setNoSelecionado(objArvore.getNo(objArvore.nodes[objArvore.nodes.length-1].id));
            ifrArvore.contentWindow.atualizarVisualizacao();
            return true;
          }
          break;
      }

    }else if (e.altKey){

      switch(tecla){

        case 38: //SETA ACIMA

          if (document.activeElement!=null && document.activeElement.tagName.toLowerCase()=='select'){
            return true;
          }

          var ifrArvore = parent.parent.document.getElementById('ifrArvore');
          if (ifrArvore!=null){
            var objArvore = ifrArvore.contentWindow['objArvore'];
            ifrArvore.contentWindow.navegarArvore('A');
            return true
          }
          break;

        case 40: //SETA ABAIXO

          if (document.activeElement!=null && document.activeElement.tagName.toLowerCase()=='select'){
            return true;
          }

          var ifrArvore = parent.parent.document.getElementById('ifrArvore');
          if (ifrArvore!=null){
            var objArvore = ifrArvore.contentWindow['objArvore'];
            ifrArvore.contentWindow.navegarArvore('P');
            return true;
          }
          break;

        //case 67: //C = Controle de Processos
        case 113: //F2 = Controle de Processos
          var lnk = parent.parent.document.getElementById('lnkControleProcessos');
          if (lnk != null){
            $(lnk).click();
            return true;
          }
          break;

        //case 80: //P = Painel de Controle
        case 114: //F3 = Painel de Controle
          var lnk = parent.parent.document.getElementById('lnkPainelControle');
          if (lnk != null){
            $(lnk).click();
            return true;
          }
          break;

        //case 81: //Q = Pesquisa Rapida
        case 121: //F10 = Pesquisa Rapida
          var txtPesquisaRapida = parent.parent.document.getElementById('txtPesquisaRapida');
          if (txtPesquisaRapida!=null){
            txtPesquisaRapida.focus();
            return true;
          }
          break;

        case 66: //B = Barra de comandos controle de processos
          var divBotoesControle = document.getElementById('divBotoesControleProcessos');
          if (divBotoesControle!=null){
            var arrBotoes = divBotoesControle.getElementsByTagName('a');
            if (arrBotoes.length) {
              arrBotoes[0].focus();
              return true;
            }
          }
          break;

        case 82: //R = Recebidos
          var tblRecebidos = document.getElementById('tblProcessosRecebidos');
          if (tblRecebidos!=null){
            tblRecebidos.focus();
            return true;
          }
          break;

        case 71: //G = Gerados
          var tblGerados = document.getElementById('tblProcessosGerados');
          if (tblGerados!=null){
            tblGerados.focus();
            return true;
          }
          break;
      }
    }
  }
  return false;
}

