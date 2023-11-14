
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

function seiAlterarContato(idContato, idObject, idFrm, link) {
  var frm = document.getElementById(idFrm);

  document.getElementById('hdnContatoObject').value = idObject;
  document.getElementById('hdnContatoIdentificador').value = idContato;

  var actionAnterior = frm.action;

  infraAbrirJanela('', 'janelaAlterarContato', 800, 700, 'location=0,status=1,resizable=1,scrollbars=1');

  frm.target = 'janelaAlterarContato';
  frm.action = link;
  frm.submit();

  frm.target = '_self';
  frm.action = actionAnterior;
}

function seiConsultarAssunto(idAssunto, idObject, idFrm, link) {

  if (infraTrim(idAssunto)!='') {

    var frm = document.getElementById(idFrm);

    document.getElementById('hdnAssuntoIdentificador').value = idAssunto;

    var actionAnterior = frm.action;

    infraAbrirJanela('', 'janelaConsultarAssunto', 700, 600, 'location=0,status=1,resizable=1,scrollbars=1');

    frm.target = 'janelaConsultarAssunto';
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
  if (window.parent!=null) {

    var arvore = window.parent.document.getElementById('divIframeArvore');

    if (arvore!=null && arvore.style.display != 'none') {

      if ($("#ancVoltarArvoreSuperior")!=null) {
        $("#ancVoltarArvoreSuperior").css("display", "none");
      }

      if ($("#ancVoltarArvoreInferior")!=null) {
        $("#ancVoltarArvoreInferior").css("display", "none");
      }

    } else {
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
    return window.self !== window.top && window.name == "ifrVisualizacao";
  } catch (e) {
    return true;
  }
}

function seiVoltarArvoreProcesso(){
  var p = window.parent;
  if (p!=null) {
    if (p.document.getElementById('divIframeArvore')!=null) {
      p.document.getElementById('divIframeArvore').style.cssText = "display:block !important;";
    }

    if (p.document.getElementById('divIframeVisualizacao')) {
      p.document.getElementById('divIframeVisualizacao').style.cssText = "display:none !important;";
    }
  }
}

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


function copiarParaClipboard(obj) {
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

  $("#divMsgClipboard").fadeIn(300).delay(1500).fadeOut(400);
  setTimeout(function(){
    $(trigger.parentNode.parentNode.parentNode).fadeOut(400,function() {
      $("#"+trigger.getAttribute("popoverId")).popover("hide");
      $(trigger.parentNode.parentNode.parentNode).show();
    });
  },1800);

};

function fecharClipboard(obj){
  var trigger = obj;
  $(trigger.parentNode.parentNode.parentNode).fadeOut(400,function() {
    $("#"+trigger.getAttribute("popoverId")).popover("hide");
    $(trigger.parentNode.parentNode.parentNode).show();
  });
}
