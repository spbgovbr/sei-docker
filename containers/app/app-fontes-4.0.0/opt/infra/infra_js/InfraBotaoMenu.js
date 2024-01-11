var infraArrBotaoMenu = null;

function infraApagarBotaoMenu(ref){
  
  if (infraArrBotaoMenu == null){
    return;
  }
    
  if (ref != null && ref.className == 'infraLinkBotaoMenu'){
    return;
  }

  var tam = infraArrBotaoMenu.length;
  for(var i=0;i<tam;i++){
    infraArrBotaoMenu[i].apagar();
  }
}

function infraApagarMenu(numIdItemAtual){
  
  var n = '';
  var obj = null;
  while((obj = document.getElementById('divInfraMenu'+n))!=null){
  	if (typeof(obj)=='object'){
    	var itens = obj.getElementsByTagName("ul");
    	for(i=0;i<itens.length;i++){
    	  //Nao apaga raizes
    	  if (itens[i].id!='infraMenuRaizes'+n){
    	    //Nao apaga o item que esta sendo exibido pois pode afetar a barra de rolagem
    	    if (numIdItemAtual==undefined || itens[i].id!=numIdItemAtual){
    	      itens[i].style.display = '';
    	    }
    	  }
    	}  
  	}
    if (n==''){
      n=1;
    }else{
      n++;
    }
	}
}


function infraBotaoMenuPosicionarTodos(){
  var tam = infraArrBotaoMenu.length;
  for(var i=0;i<tam;i++){
    infraArrBotaoMenu[i].posicionar();
  }
}

function infraBotaoMenu(idButton,arrLinks, arrowUp){
    var me = this;
    this.btn = infraGetElementById(idButton);
    this.div = null;
    this.ifr = null;
    this.validar = null;
    this.numItens = 0;
    this.idFocus = null;
    this.arrowUp = arrowUp;

    this.inicializar = function (){
      var img = document.createElement('img');
      img.src =  arrowUp ? INFRA_PATH_IMAGENS + '/seta_botao_menu_acima.gif' : INFRA_PATH_IMAGENS + '/seta_botao_menu_abaixo.gif';
      img.className = 'infraImgBotaoMenu';
      me.btn.appendChild(img);


      me.div = document.createElement('div');
      me.div.style.visibility = 'hidden';
      document.body.appendChild(me.div);
      me.div.className = 'infraBotaoMenu';
      var ul = document.createElement('ul');
      me.div.appendChild(ul);

      if (INFRA_IE>0 && INFRA_IE<7){
        me.ifr = document.createElement('iframe');
        me.ifr.style.visibility = 'hidden';
        document.body.appendChild(me.ifr);
        me.ifr.style.position = 'absolute';
        me.ifr.scroll = 'no';
        me.ifr.style.zIndex = 1;
        me.div.style.zIndex = 2;
      }

      if (infraArrBotaoMenu==null){
        infraArrBotaoMenu = Array();
      }

      var tam = infraArrBotaoMenu.length;
      infraArrBotaoMenu[tam] = me;
      me.posicionar();
      infraAdicionarEvento(window,'resize',infraBotaoMenuPosicionarTodos);

      me.btn.onkeydown = function(evt){
        var key = infraGetCodigoTecla(evt);
        if (key==40){ //abaixo
          me.mostrar();
          me.selecionar(null);
        }else if (key==27){ //ESC
          me.apagar();
        }else if (key==9){ //TAB
          return true;
        }
        return false;
      };

    }

    this.adicionarSeparador =  function(){
      var lis = me.div.getElementsByTagName('li');
      if (lis.length>0){
        lis[lis.length-1].className = 'infraSeparadorBotaoMenu';
      }
    }

    this.adicionar=function(rotulo, title, href){
      var ul = me.div.getElementsByTagName('ul');

      if (ul.length!=1){
        alert('Lista de itens não encontrada para o botão '+me.btn.id+'.');
        return;
      }

      var li = document.createElement("li");
      var a = document.createElement("a");
      a.href='#'; //necessário para setar o foco
      a.innerHTML = rotulo;

      a.onclick = function(){
        var ret = true;

        if(typeof(this.validar)=='function'){
          ret = this.validar();
        }

        if (ret){
          if(typeof(me.btn.form.onsubmit)=='function'){
            ret = me.btn.form.onsubmit();
          }
        }

        me.apagar();

        if (ret){
          var hrefOriginal = me.btn.form.action;
          me.btn.form.action = href;
          me.btn.form.submit();
          me.btn.form.action = hrefOriginal;
        }

        //necessário para não executar o link da âncora no IE
        return false;
      }

      a.title = title;
      a.id = 'lnk'+me.btn.id+me.numItens;
      me.numItens++;
      a.className = 'infraLinkBotaoMenu';
      a.onkeydown = function(evt){
        var key = infraGetCodigoTecla(evt);
        //alert(key);
        if (key==38){ //acima
          me.selecionar(this.id, 0);
        }else if (key==40){ //abaixo
          me.selecionar(this.id, 1);
        }else if (key==27){ //ESC
          me.apagar();
          me.btn.focus();
        }else if (key==13){
          return true;
        }
        return false;
      };

      a.onmouseover=function(evt){
        try{
          this.focus();
        }catch(exc){}
      }

      if (INFRA_IE > 0){
        a.onfocus=function(evt){
          this.style.backgroundColor = '#e0e0e0';
          return true;
        }
      }

      if (INFRA_IE>0){
        a.onblur=function(evt){
          this.style.backgroundColor = 'white';
          return true;
        }
      }

      li.appendChild(a);
      ul[0].appendChild(li);

      return a;
    }


    this.selecionar=function(idAtual, direcao){
      var id = null;

      if (idAtual==null){
        id = '0';
      }else{
        id = idAtual.replace('lnk'+me.btn.id,'');
        if (direcao==0){
          id--;
        }else{
          id++;
        }

        if (id < 0){
          id = me.numItens-1;
        }else if (id == me.numItens){
          id = '0';
        }
      }

      try{
        var a = document.getElementById('lnk'+me.btn.id+id);

        if (a!=null){
          a.focus();
        }

      }catch(exc){}
    }

    this.btn.onclick = function (){
      me.mostrar();
    }

    this.posicionar = function(){
      var el = me.btn;
      var x = 0;
      var y = el.offsetHeight;
      var w = el.offsetWidth;
      var yOriginal = y;
      var divSize = me.div.offsetHeight;

      //Walk up the DOM and add up all of the offset positions.
      while (el.offsetParent && el.tagName.toUpperCase() != 'BODY')
      {
        x += el.offsetLeft;
        y += el.offsetTop;
        el = el.offsetParent;
      }

      x += el.offsetLeft;
      y += el.offsetTop;

      if (INFRA_IE > 0){
        x += 5;
        y += 8;
      }else{
        x += 2;
      }

      w -= 2;

      me.div.style.left = x + 'px';
      me.div.style.top = arrowUp ? (y-yOriginal-divSize) + 'px' : y + 'px';
      me.div.style.width = w+'px';

      if (me.ifr!=null){
        me.ifr.style.left = x + 'px';
        me.ifr.style.top  = arrowUp ? (y-yOriginal-divSize) + 'px' : y + 'px';
        me.ifr.style.width = w + 2 + 'px';
        me.ifr.style.height  = me.div.offsetHeight + 'px';
      }

      el = null;
    };

    this.mostrar = function(){
      me.posicionar();

      if (me.ifr!=null){
        me.ifr.style.visibility = 'visible';
      }

      me.div.style.visibility = 'visible';
    }

    this.apagar = function(){
      if (me.ifr!=null){
        me.ifr.style.visibility = 'hidden';
      }
      me.div.style.visibility = 'hidden';
    }

    me.inicializar();
  }