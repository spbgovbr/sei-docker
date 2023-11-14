var infraArrSelectEditavel = null;

function infraSelectEditavelRedimensionarTodos(){
  var tam = infraArrSelectEditavel.length;
  for(var i=0;i<tam;i++){
    infraArrSelectEditavel[i].posicionar();
  }
}

function infraSelectEditavel(idSelect,idText){
	var me = this;
	this.txt = infraGetElementById(idText);
	this.sel = infraGetElementById(idSelect);
	this.ifr = null;
	this.ie = false;
	
  this.inicializar = function(){

    me.txt.style.position = 'absolute';
    me.txt.style.borderRight = '0px'; 
    
    if (INFRA_IE>0 && INFRA_IE<7){
      me.sel.style.zIndex = 1;
  		me.ifr = document.createElement('iframe');
      me.sel.parentNode.appendChild(me.ifr);	
  		me.ifr.style.position = 'absolute';
      me.ifr.scroll = 'no';
      me.ifr.style.zIndex = 2;
      me.txt.style.zIndex = 3;
    }

    if (infraArrSelectEditavel==null){
      infraArrSelectEditavel = Array();
    }
    
    var tam = infraArrSelectEditavel.length;
    infraArrSelectEditavel[tam] = me;

    me.posicionar();

    infraAdicionarEvento(window,"resize",infraSelectEditavelRedimensionarTodos);
    
    me.txt.style.visibility = 'visible';
    
    infraDesabilitarAutoCompleteTxt(me.txt);
	}
	
	
  this.posicionar=function(){
    var x,y,w,h;
   
    x = me.sel.offsetLeft;
    y = me.sel.offsetTop;
    
    if (INFRA_IE==0){
      w = me.sel.offsetWidth - 19;
    } else if (INFRA_IE < 7){
      w = me.sel.offsetWidth - 18;
    } else {
      w = me.sel.offsetWidth - 21;
    }
    
    h = me.sel.offsetHeight - 2;

    if (me.ifr!=null){
      me.ifr.style.left = x + 'px';
      me.ifr.style.top  = y + 'px';
      me.ifr.style.width = w + 'px';
      me.ifr.style.height  = h + 'px';
    }
      
    me.txt.style.left = x + 'px';
    me.txt.style.top = y + 'px';
    me.txt.style.width = w-2 + 'px';
    me.txt.style.height = h-4 + 'px';
  }	
  
  /*
	this.sel.onchange = function(){
	  me.atualizar();
	}
  */
  
  /*
  this.setBolSomenteLeitura = function(bolSomenteLeitura){
    if (bolSomenteLeitura){
      me.txt.style.visibility='hidden';
    }else{
      me.txt.style.visibility='';
    }
  }
  */
  
	
  this.atualizar = function(){
    if (me.sel.selectedIndex!=-1){
      me.txt.value = me.sel.options[me.sel.selectedIndex].text;
    }else{
      me.txt.value = '';
    }
    //alert(me.txt.value);
  }
  
  this.resetar = function(){
    me.sel.selectedIndex = -1;
  }
  
  infraAdicionarEvento(this.sel,"change",this.atualizar);
  infraAdicionarEvento(this.txt,"keydown",this.resetar);
  
	me.inicializar(); 
}


