
//aponta para a ação origem do menu exibido no momento
var infraObjMenuAcoes = null;

function infraAcoesMenu(){
  
  var imgs = document.getElementsByTagName('img');
  
  //procura ações que devem exibir um menu
  for(var i=0;i<imgs.length;i++){
    
    if (imgs[i].className=='infraAcaoMenu'){
      
      //mostrar ao teclar ENTER
      imgs[i].onkeypress = function(evt){

        if (INFRA_IE){
          evt = event;
        }
                
        if (infraGetCodigoTecla(evt) != 13){ //ENTER
          return true;
        }

        infraAcaoMenuMostrar(this,evt);
        
        return false;
      };
      
      //mostrar ao passar o mouse
      imgs[i].onmouseover = function(event){
        infraAcaoMenuMostrar(this,event);
      };
      
    }
  }
}

function infraAcaoMenuMostrar(obj,evt) {
  
  var me = this;
  
  if (infraObjMenuAcoes != null){
    infraApagarMenuAcoes(obj);
  }
  
	//se ainda não existe na página então cria div para conter o menu
	var div = self.document.getElementById('divInfraMenuAcoes');
	
	if (div==null){
   	div = document.createElement('div');	
    div.id = 'divInfraMenuAcoes';
    div.className = 'infraMenuAcoes';
    div.style.visibility = 'hidden';
   	self.document.body.appendChild(div);		
   	
   	infraAdicionarEvento(window,'resize',infraPosicionarMenuAcoes);
	}
	
	if(!div) return false;

	
  //cria iframe
  if (INFRA_IE>0 && INFRA_IE<7){
    var ifr = self.document.getElementById('ifrInfraMenuAcoes');
    if (ifr==null){
      ifr = document.createElement('iframe');
      ifr.id = 'ifrInfraMenuAcoes';
      ifr.style.position = 'absolute';
      ifr.scroll = 'no';    
      self.document.body.appendChild(ifr);		
      ifr.style.zIndex = 1;
      div.style.zIndex = 2;
    }
  }
  
  //apaga conteúdo do menu anterior
  div.innerHTML = '';
  
  //pega div que contém a ação menu clicada
  arr = obj.parentNode.getElementsByTagName('div');
  
  var links = null;
  
  //deve existir apenas uma div dentro do TD
  if (arr.length==1){
    
  	if (typeof div.innerHTML != 'undefined') {
  	  
  	  //copia para div do menu o conteúdo da div existente no TD
  	  div.innerHTML = arr[0].innerHTML;

  	  var links = div.getElementsByTagName('a');

	    if (links.length > 0){
	      
	      for(j=0;j<links.length;j++){

	        links[j].className = 'infraLinkMenuAcoes';
	        
	        //para poder executar no ENTER deve ter href
	        if (links[j].href==''){
	          links[j].href = '#';
	        }
	        
	        //rolagem da tela automática
	        links[j].onmouseover = function(){
            	var el = this;
            	var y = 0;
            	while (el.offsetParent && el.tagName.toUpperCase() != 'BODY'){
            		y += el.offsetTop;
            		el = el.offsetParent;
            	}
            	y += el.offsetTop;
              
              var deslocamento = (y+40) - (infraClientHeight()+infraScrollTop())
              if ( deslocamento > 0 ){
            	    window.scrollBy(0,10);
             	}
             	return true;
	        };
	        
	        //tratamento de teclas acima, abaixo, esc e enter
          links[j].onkeydown = function(evt){
            
              var links = this.parentNode.getElementsByTagName('a');
              
              var key = infraGetCodigoTecla(evt);
              
              if (key==38){ //acima
                if (links.length > 1){
                  for(var i=1;i<links.length;i++){
                    if (links[i]==this){
                      links[i-1].focus();
                      return false;
                    }
                  }
                  links[links.length-1].focus();
                }
              }else if (key==40){ //abaixo
                if (links.length > 1){
                  for(var i=0;i<links.length-1;i++){
                    if (links[i]==this){
                      links[i+1].focus();
                      return false;
                    }
                  }
                  links[0].focus();
                }
              }else if (key==27){ //ESC
                if (infraObjMenuAcoes!=null){
                  infraObjMenuAcoes.focus();
                  infraApagarMenuAcoes(null);
                }
              }else if (key==13){
                return true;
              }
              return false;
           };
           
           //no IE faz alternar as cores quando navegando por teclas
           if (INFRA_IE > 0){
             links[j].onfocus=function(evt){
                this.style.backgroundColor = '#e0e0e0';
                return true;
             }
           }
      
           if (INFRA_IE>0){
             links[j].onblur=function(evt){
                this.style.backgroundColor = 'white';
                return true;
              }
           }
	      }
	    }
  	}
  }
	
  infraObjMenuAcoes = obj;
  
  infraPosicionarMenuAcoes();
  
  if (evt!=null && evt.type=='keypress' && links != null && links.length > 0){
    links[0].focus();
  }
}

//posiciona o menu quando ocorre mudança no tamanho ou posição da tela
function infraPosicionarMenuAcoes(){
  
  if (infraObjMenuAcoes==null){
    return;
  }
  
  var div = self.document.getElementById('divInfraMenuAcoes');
  
  
  if (div != null){
  
  	var w = (infraClientWidth()/5);
  
  	div.style.width = w + 'px';
  
  
    var el = infraObjMenuAcoes;
  	var x = 0;
  	var y = el.offsetHeight/2;
  	
    	
  	//Walk up the DOM and add up all of the offset positions.
  	while (el.offsetParent && el.tagName.toUpperCase() != 'BODY')
  	{
  		x += el.offsetLeft;
  		y += el.offsetTop;
  		el = el.offsetParent;
  	}
  
  	x += el.offsetLeft;
  	y += el.offsetTop;
  
  	if (INFRA_IE >0){
  	  y += 4;
  	  x += 4;
  	}
  	
  	div.style.left = (x-w) + 'px';
  	div.style.top = y + 'px'; 
  	div.style.visibility = 'visible';
    
  	if (INFRA_IE>0 && INFRA_IE<7){
  	  var ifr = self.document.getElementById('ifrInfraMenuAcoes');
      
  	  if (ifr!=null){
        ifr.style.left = (x-w) + 'px';
        ifr.style.top  = y + 'px';
        ifr.style.width = w + 2 + 'px';
        ifr.style.height  = div.offsetHeight + 'px';
     	  ifr.style.visibility = 'visible';
      }
  	}
  	 
  	el = null;
  	
  	infraObjMenuAcoes.className = 'infraImgMenuAcaoSelecionada';
  }
}

//apaga menu exibido no momento
function infraApagarMenuAcoes(ref){
	
  if (infraObjMenuAcoes == null){
    return;
  }
  
  //ativada pela infraMouseDown
  //se clicou no link ou um item dentro do link (img por exemplo) nao apaga porque senao perde o evento onclick
  if (ref != null && (ref.className == 'infraLinkMenuAcoes' || (ref.parentNode != null && ref.parentNode.className=='infraLinkMenuAcoes'))){
    return;
  }

  var div = self.document.getElementById('divInfraMenuAcoes');
  
  if (div != null){
  
    div.style.visibility = 'hidden';
  
    if (INFRA_IE>0 && INFRA_IE<7){
      var ifr = self.document.getElementById('ifrInfraMenuAcoes');
      
      if (ifr!=null){
  	    ifr.style.visibility = 'hidden';
      }
    }	

    infraObjMenuAcoes.className = 'infraImgMenuAcaoNormal';
    
    infraObjMenuAcoes = null;
  }  
}

