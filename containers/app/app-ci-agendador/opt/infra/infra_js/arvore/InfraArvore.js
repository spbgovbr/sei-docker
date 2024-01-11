function infraArvore(divId,arrNos,arrNosAcao,idHidden,divRaiz,tamIcones){

  var me = this;

  this.nodes = arrNos;
  this.nodesAction = arrNosAcao;
  this.nosAbertos	= new Array();
  this.icons	= new Array(6);
  this.div = document.getElementById(divId);
  this.noAtual = null;
  this.hdn = infraGetElementById(idHidden);

  if (divRaiz != undefined){
    this.divRaiz = document.getElementById(divRaiz);
  }else{
    this.divRaiz = null;
  }

  if (tamIcones == undefined){
    tamIcones = 16;
  }

  if (tamIcones!=16 && tamIcones!=24){
    alert('Tamanho ' + tamIcones + 'inválido para ícones da árvore.');
    return;
  }

  this.pathToImages = INFRA_PATH_JS + '/arvore/' + tamIcones + '/';


  this.carregarIcones = function() {
    me.icons[0] = new Image();
    me.icons[0].src = me.pathToImages + 'plus.gif';
    me.icons[1] = new Image();
    me.icons[1].src = me.pathToImages + 'plusbottom.gif';
    me.icons[2] = new Image();
    me.icons[2].src = me.pathToImages + 'minus.gif';
    me.icons[3] = new Image();
    me.icons[3].src = me.pathToImages + 'minusbottom.gif';
    me.icons[4] = new Image();
    me.icons[4].src = me.pathToImages + 'folder.gif';
    me.icons[5] = new Image();
    me.icons[5].src = me.pathToImages + 'folderopen.gif';
  }

  this.removerNoHidden = function(no) {
    var arrDados = me.hdn.value.split('¥');
    var tmp = '';
    for ( var i = 0; i < arrDados.length; i++) {
      if (arrDados[i] != no.id) {
        if (tmp != '') {
          tmp += '¥';
        }
        tmp += arrDados[i];
      }
    }
    me.hdn.value = tmp;
  }

  this.adicionarNoHidden = function(no) {
    var arrDados = me.hdn.value.split('¥');

    for ( var i = 0; i < arrDados.length; i++) {
      if (arrDados[i] == no.id) {
        return;
      }
    }

    if (me.hdn.value != '') {
      me.hdn.value = me.hdn.value.concat('¥');
    }

    me.hdn.value = me.hdn.value.concat(no.id);
  }

  this.inicializar = function() {

    if (me.nodes.length > 0) {

      var k = 0;
      if (me.hdn.value != ''){
        var arrNosAbertosHidden = me.hdn.value.split('¥');
        for ( var j = 0; j < arrNosAbertosHidden.length; j++) {
          for( var i = 0; i < me.nodes.length; i++){
            if (arrNosAbertosHidden[j] == me.nodes[i].id) {
              me.nosAbertos[k++] = me.nodes[i].id;
            }
          }
        }
      }else{
        for(var i=0;i < me.nodes.length;i++){
          if (me.nodes[i].bolAberto){
            me.nosAbertos[k++] = me.nodes[i].id;
            me.adicionarNoHidden(me.nodes[i]);
          }
        }
      }

      me.div.className = 'infraArvore';

      me.carregarIcones();

      var no = me.nodes[0];

      var divRaiz = me.div;
      if (me.divRaiz != null){
        me.divRaiz.className = 'infraArvore';
        divRaiz = me.divRaiz;
      }

      var a = me.criarA(divRaiz, no, false, false);
      divRaiz.appendChild(a);

      var imgEspaco = null;
      for(var k=0;k < me.nodesAction.length;k++){
        if (me.nodesAction[k].idPai == no.id){
          imgEspaco = document.createElement('img');
          imgEspaco.src = me.pathToImages + 'espaco.gif';
          divRaiz.appendChild(imgEspaco);

          a = me.criarA(null, me.nodesAction[k],false,false);
          divRaiz.appendChild(a);
        }
      }

      var quebra = document.createElement('span');
      quebra.innerHTML = '<br />';
      divRaiz.appendChild(quebra);

      var divFilhos = document.createElement('div');
      divFilhos.id = 'div' + no.id;
      divFilhos.className = 'infraArvore';
      me.div.appendChild(divFilhos);

      var recursedNodes = new Array();
      me.adicionarNo(no.id, recursedNodes);
    }
  }

  this.criarImg = function(src,title){
    var img = document.createElement('img');
    img.src =  src;
    img.align = 'absbottom';
    if (title!=undefined){
      img.title = title;
    }
    return img;
  }

  this.criarSpan = function(no){
    var span = document.createElement('span');
    span.innerHTML = no.label;
    span.align = 'absbottom';
    span.title = no.title;
    return span;
  }

  this.getNo = function(nodeId){
    var i;

    for (i=0; i < me.nodes.length; i++) {
      if (me.nodes[i].id == nodeId){
        return me.nodes[i];
      }
    }

    for (i=0; i < me.nodesAction.length; i++) {
      if (me.nodesAction[i].id == nodeId){
        return me.nodesAction[i];
      }
    }
    return null;
  }

  this.isNoAberto = function(node) {
    for (var i=0; i < me.nosAbertos.length; i++)
      if (me.nosAbertos[i]==node) return true;
    return false;
  }

  this.temFilhos = function(parentNode) {
    for (var i=0; i < me.nodes.length; i++) {
      if (me.nodes[i].idPai == parentNode) return true;
    }
    return false;
  }

  this.adicionarFilhos = function(noPai, nosFilhos, nosAcoesFilhos) {
    var a = null;
    var img = null;
    var span = null;
    var div = document.getElementById('div' + noPai.id);

    for (var i = 0; i < nosFilhos.length; i++) {

      me.nodes.push(nosFilhos[i]);

      if (noPai.bottom==0){
        div.appendChild(me.criarImg(me.pathToImages + 'line.gif'));
      }else{
        div.appendChild(me.criarImg(me.pathToImages + 'empty.gif'));
      }

      if (i==(nosFilhos.length-1)){
        div.appendChild(me.criarImg(me.pathToImages + 'joinbottom.gif'));
      }else{
        div.appendChild(me.criarImg(me.pathToImages + 'join.gif'));
      }

      a = me.criarA(div, nosFilhos[i], false, false);

      div.appendChild(a);

      var imgEspaco = null;
      for(var k=0;k < nosAcoesFilhos.length;k++){

        if (nosAcoesFilhos[k].idPai == nosFilhos[i].id){

          me.nodesAction.push(nosAcoesFilhos[k]);

          imgEspaco = document.createElement('img');
          imgEspaco.src = me.pathToImages + 'espaco.gif';
          div.appendChild(imgEspaco);

          a = me.criarA(null, nosAcoesFilhos[k],false,false);
          div.appendChild(a);
        }
      }

      var quebra = document.createElement('span');
      quebra.innerHTML = '<br />';
      div.appendChild(quebra);
    }
  }

  this.ultimoRamo = function(node, parentNode) {
    var lastChild = 0;
    for (var i=0; i < me.nodes.length; i++) {
      if (me.nodes[i].idPai == parentNode){
        lastChild = me.nodes[i].id;
      }
    }
    if (lastChild==node) return true;
    return false;
  }

  this.criarA = function(div, no, hcn, ino){
    a = document.createElement('a');
    a.id = 'anchor' + no.id;
    a.align = 'absbottom';

    if (no.target != null){
      a.target = no.target;
    }

    if (no.href != null){
      a.href = no.href;
    }

    a.disabled = !no.bolHabilitado;

    img = null;

    if (hcn) {
      if (ino){
        if (no.iconeAberto==null){
          img = me.criarImg(me.icons[5].src);
        }else{
          img = me.criarImg(no.iconeAberto);
        }
      }else{
        if (no.iconeFechado==null){
          img = me.criarImg(me.icons[4].src);
        }else{
          img = me.criarImg(no.iconeFechado);
        }
      }
    } else if (no.icone!=null){
      img = me.criarImg(no.icone);
    }

    if (img != null) {

      img.id = 'icon' + no.id;

      if (div!=null) {
        aImg = document.createElement('a');
        aImg.id = 'anchorImg' + no.id;
        aImg.appendChild(img);
        div.appendChild(aImg);
      } else {
        a.appendChild(img);
      }
    }

    if (no.label != undefined){
      span = me.criarSpan(no);
      span.id = 'span' + no.id;

      if (a.disabled){
        span.style.color = '#cc9e80';
      }

      if (no.classNameNormal != null){
        span.className = no.classNameNormal;
      }

      a.appendChild(span);

    }else if (img != null){
      img.title = no.title;
    }else if (no.informacao!=null){

      var span = document.createElement('span');
      span.innerHTML = no.informacao;
      span.align = 'absbottom';
      span.title = no.title;

      if (a.disabled){
        span.style.color = '#cc9e80';
      }

      if (no.classInformacao == null) {
        a.className = 'infraArvoreInformacao';
      }else{
        a.className = no.classInformacao;
      }
      a.appendChild(span);
    }

    a.onclick = function(){

      ret = true;

      var no = me.getNo(this.id.substr(6));

      if (!no.bolHabilitado){
        ret = false;
      }else{

        if (typeof(no.processar)=='function'){
          ret = no.processar(no);
        }else if (typeof(me.processar)=='function'){
          ret = me.processar(no);
        }

        if (ret){
          me.setNoSelecionado(no);
        }
      }

      return ret;
    }

    /*
    a.onblur = function(){
      var no = me.getNo(this.id.substr(6));
      var span = document.getElementById('span'+this.id.substr(6));
      if (span != null && (span.className == no.classNameSelecionado || span.className == 'infraArvoreNoSelecionado')){
    	  if (no.classNameVisitado!=null){
    	    span.className = no.classNameVisitado;
    	  }else{
    	    span.className = 'infraArvoreNoVisitado';
    	  }
      }
      return true;
    }
    */

    return a;
  }

  this.adicionarNo = function(parentNode, recursedNodes) {
    var a = null;
    var img = null;
    var span = null;
    var div = document.getElementById('div' + parentNode);

    for (var i = 0; i < me.nodes.length; i++) {

      var no = me.nodes[i];

      if (no.idPai == parentNode) {

        var ls	= me.ultimoRamo(no.id, no.idPai);
        var hcn	= me.temFilhos(no.id);
        var ino = me.isNoAberto(no.id);

        for (var g=0; g < recursedNodes.length; g++) {
          if (recursedNodes[g] == 1) {
            div.appendChild(me.criarImg(me.pathToImages + 'line.gif'));
          }else {
            div.appendChild(me.criarImg(me.pathToImages + 'empty.gif'));
          }
        }

        if (ls) {
          recursedNodes.push(0);
        }
        else {
          recursedNodes.push(1);
        }

        if (hcn) {

          a = document.createElement('a');
          a.id = 'ancjoin' + no.id;
          a.align = 'absbottom';
          //a.href = '#';
          a.href = 'javascript:void(0);';
          img = null;

          if (ls) {
            no.bottom = 1;

            if (ino){

              img = me.criarImg(me.pathToImages + 'minusbottom.gif');
              img.status = 1;

              a.onclick = function(){
                return me.processarNoJuncao(this.id.substr(7));
              }

            }else{

              img = me.criarImg(me.pathToImages + 'plusbottom.gif');
              img.status = 0;

              a.onclick = function(){
                return me.processarNoJuncao(this.id.substr(7));
              }

            }

          } else {

            no.bottom = 0;

            if (ino){

              img = me.criarImg(me.pathToImages + 'minus.gif');
              img.status = 1;

              a.onclick = function(){
                return me.processarNoJuncao(this.id.substr(7));
              }

            }else{

              img = me.criarImg(me.pathToImages + 'plus.gif');
              img.status = 0;

              a.onclick = function(){
                return me.processarNoJuncao(this.id.substr(7));
              }

            }
          }
          img.id = 'join' + no.id;
          a.appendChild(img);
          div.appendChild(a);

        } else {
          if (ls){
            div.appendChild(me.criarImg(me.pathToImages + 'joinbottom.gif'));
          }else{
            div.appendChild(me.criarImg(me.pathToImages + 'join.gif'));
          }
        }

        a = me.criarA(div, no,hcn,ino);

        div.appendChild(a);

        var imgEspaco = null;
        for(var k=0;k < me.nodesAction.length;k++){

          if (me.nodesAction[k].idPai == no.id){

            imgEspaco = document.createElement('img');
            imgEspaco.src = me.pathToImages + 'espaco.gif';
            div.appendChild(imgEspaco);

            a = me.criarA(null, me.nodesAction[k],false,false);
            div.appendChild(a);
          }
        }

        var quebra = document.createElement('span');
        quebra.innerHTML = '<br />';
        div.appendChild(quebra);

        if (hcn) {
          var divFilhos = document.createElement('div');
          divFilhos.id = 'div' + no.id;
          divFilhos.className = 'infraArvore';
          if (!ino){
            divFilhos.style.display = 'none';
          }
          div.appendChild(divFilhos);
          me.adicionarNo(no.id, recursedNodes);
        }
        recursedNodes.pop();
      }
    }
  }

  this.processarNoJuncao = function(id){

    ret = true;

    var theJoin = document.getElementById("join" + id);

    var no = me.getNo(id);

    if (!no.bolHabilitado){
      return false;
    }else{
      if (theJoin.status==0){
        if (typeof(no.processarAbertura)=='function'){
          ret = no.processarAbertura(no);
        }else if (typeof(me.processarAbertura)=='function'){
          ret = me.processarAbertura(no);
        }
      }else{
        if (typeof(no.processarFechamento)=='function'){
          ret = no.processarFechamento(no);
        }else if (typeof(me.processarFechamento)=='function'){
          ret = me.processarFechamento(no);
        }
      }

      if (ret){
        me.processarNo(no);
      }
    }

    return ret;
  }

  // Opens or closes a node
  this.processarNo = function (no) {

    var theDiv = document.getElementById("div" + no.id);
    var theJoin	= document.getElementById("join" + no.id);
    var theIcon = document.getElementById("icon" + no.id);

    if (theDiv.style.display == 'none') {

      theJoin.status = 1;

      if (no.bottom==1) {
        theJoin.src = me.icons[3].src;
      }else{
        theJoin.src = me.icons[2].src;
      }

      if (no.iconeAberto==null){
        theIcon.src = me.icons[5].src;
      }else{
        theIcon.src = no.iconeAberto;
      }

      theDiv.style.display = '';

      me.adicionarNoHidden(no);

    } else {

      theJoin.status = 0;

      if (no.bottom==1) {
        theJoin.src = me.icons[1].src;
      }else{
        theJoin.src = me.icons[0].src;
      }

      if (no.iconeFechado==null){
        theIcon.src = me.icons[4].src;
      }else{
        theIcon.src = no.iconeFechado;
      }

      theDiv.style.display = 'none';

      me.removerNoHidden(no);
    }

    me.setNoSelecionado(no);

  }

  this.setNoSelecionado = function(novoNo){
    var span = null;

    if (me.noAtual != null){
      span = document.getElementById('span'+me.noAtual.id);

      if (span != null){
        if (me.noAtual.classNameVisitado==null) {
          if (me.noAtual.classNameNormal!=null) {
            span.className = me.noAtual.classNameNormal;
          } else {
            span.className = '';
          }
        }else{
          span.className = me.noAtual.classNameVisitado;
        }
      }
    }

    me.noAtual = novoNo;

    span = document.getElementById('span'+me.noAtual.id);

    if (span != null){
      if (me.noAtual.classNameSelecionado!=null){
        span.className = me.noAtual.classNameSelecionado;
      }else{
        span.className = 'infraArvoreNoSelecionado';
      }
    }

    if (novoNo.bolHabilitado) {
      var a = document.getElementById('anchor' + novoNo.id);
      if (a!=null) {
        a.focus();
      }
    }

  }

  this.getNoSelecionado = function(){
    return me.noAtual;
  }

  this.getAncoraNo = function(id){
    return (document.getElementById('anchor' + id));
  }

  if(!Array.prototype.push) {
    function array_push() {
      for(var i=0;i < arguments.length;i++)
        this[this.length]=arguments[i];
      return this.length;
    }
    Array.prototype.push = array_push;
  }

  if(!Array.prototype.pop) {
    function array_pop(){
      lastElement = this[this.length-1];
      this.length = Math.max(this.length-1,0);
      return lastElement;
    }
    Array.prototype.pop = array_pop;
  }

  me.inicializar();
}

function infraArvoreNo(tipo, id, idPai, href, target, label, title, icone, iconeAberto, iconeFechado, aberto, habilitado, classNameNormal, classNameSelecionado, classNameVisitado, aux){
  this.tipo = tipo;
  this.id = id;
  this.idPai = idPai;
  this.href = href;
  this.target = target;
  this.label = label;
  this.title = title;
  this.icone = icone;
  this.iconeAberto = iconeAberto;
  this.iconeFechado = iconeFechado;
  this.bolAberto = aberto;
  this.bolHabilitado = habilitado;
  this.classNameNormal = classNameNormal;
  this.classNameSelecionado = classNameSelecionado;
  this.classNameVisitado = classNameVisitado;
  this.aux = aux;
}

function infraArvoreAcao(tipo, id, idPai, href, target, title, icone, habilitado, informacao, classInformacao){
  this.tipo = tipo;
  this.id = id;
  this.idPai = idPai;
  this.href = href;
  this.target = target;
  this.title = title;
  this.icone = icone;
  this.bolHabilitado = habilitado;
  this.informacao = informacao;
  this.classInformacao = classInformacao;
}