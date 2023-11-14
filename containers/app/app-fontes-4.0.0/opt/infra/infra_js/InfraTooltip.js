var infraTooltip = null;

function infraTooltipMostrar(displaytext, title, width) {

  infraTooltip = false;

  if (infraTrim(displaytext)=='') {
    if (title==undefined || infraTrim(title)=='') {
      return;
    }
  }

  //cria div
  var div = document.getElementById('divInfraTooltip');
  if (div==null) {
    div = document.createElement('div');
    div.id = 'divInfraTooltip';
    div.className = 'infraTooltip';
    document.body.appendChild(div);
  }

  if (!div) return false;

  //reseta tamanho

  div.style.visibility = 'hidden';
  div.style.left = '0px';
  div.style.top = '0px';

  if (width != undefined && width != '') {
    div.style.width = width + 'px';
  }else{
    div.style.width = 'auto';
  }
	
	
  //cria iframe
  if (INFRA_IE>0 && INFRA_IE<7){
    var ifr = document.getElementById('ifrInfraTooltip');
    if (ifr==null){
      ifr = document.createElement('iframe');
      ifr.id = 'ifrInfraTooltip';
      ifr.style.position = 'absolute';
      ifr.scroll = 'no';    
      document.body.appendChild(ifr);		
      ifr.style.zIndex = 1;
      div.style.zIndex = 2;
    }
    ifr.style.visibility = 'hidden';
	  ifr.style.left = '0px';
	  ifr.style.top = '0px';
  }

	var html = '';

  if (width != undefined && width != '') {
    html += '<table id="tabInfraTooltip" border="0" cellspacing="0" style="width:' + width + 'px;">';
  }else{
    html += '<table id="tabInfraTooltip" border="0" cellspacing="0" style="width:auto;">';
	}

	
	
	if(title!=undefined && title!=null && title.length>0){
	  title = title.infraReplaceAll('\\r\\n',"<br />");
	  title = title.infraReplaceAll('\\n',"<br />");
	  html += '<tr><td><div class="infraTooltipTitulo">'+title+'</div></td></tr>';
	}

  /*
	displaytext = displaytext.infraReplaceAll('<BR>','<br />');
  displaytext = displaytext.infraReplaceAll('<','&lt;');
  displaytext = displaytext.infraReplaceAll('>','&gt;');
  displaytext = displaytext.infraReplaceAll('"','&quot;');
	displaytext = displaytext.infraReplaceAll('&lt;br /&gt;','<br />');
	*/

	displaytext = displaytext.infraReplaceAll("\r\n",'<br />');
	displaytext = displaytext.infraReplaceAll("\n",'<br />');

	if (infraTrim(displaytext)!='') {
    html += '<tr><td><div class="infraTooltipTexto">' + displaytext + '</div></td></tr>';
  }

	html += '</table>';

  //alert(html);

	if (typeof div.innerHTML != 'undefined') {
		div.innerHTML = html;
	}

  if (width != undefined && width != '') {
    if (div.offsetWidth > width) {
      div.style.width = width + 'px';
    }
  }else{
    div.style.width = 'auto';
	}
	
	infraTooltip = true;
	document.onmousemove = infraTooltipMouseMoveHandler;
  
  //infraAdicionarEvento(document,'mousemove',infraTooltipMouseMoveHandler);
}

// Clears popups if appropriate
function infraTooltipOcultar(){
  
  var div = document.getElementById('divInfraTooltip');
  if (div!=null){
		div.style.visibility = 'hidden';
    
    if (INFRA_IE>0 && INFRA_IE<7){
      var ifr = document.getElementById('ifrInfraTooltip');
      if (ifr!=null){
		    ifr.style.visibility = 'hidden';
      }
    }		
	}
	document.onmousemove = null;
}

//called when the mouse moves
//sets mouse related variables
function infraTooltipMouseMoveHandler(e) {
  
  
	div = document.getElementById('divInfraTooltip');
	if (div!=null){
	  
	  if (div.style.visibility!='hidden' || infraTooltip == true){

      var xcoordinate = 0;
      var ycoordinate = 0;

    	if(!e){
    		e=event;
    	}
	    
   	  if (e.clientX){
   	    xcoordinate = e.clientX;
   	    ycoordinate = e.clientY;
   	  }
   	  
	    tab = document.getElementById('tabInfraTooltip');
	    
    	var placeX;
    	var placeY;
  	
    	//if (xcoordinate > screen.availWidth/2){
    	if (xcoordinate > infraClientWidth()/2){
      	placeX = xcoordinate - tab.offsetWidth - 13;
    	}else{
      	placeX = xcoordinate+13;
    	}
    	
    	//if (ycoordinate > screen.availHeight/2){
    	if (ycoordinate > infraClientHeight()/2){
    	  placeY = ycoordinate - tab.offsetHeight - 13;
    	}else{
    	  placeY = ycoordinate+13;
    	}
    	
    	placeX += infraScrollLeft();
    	placeY += infraScrollTop();
    	
    	//Move the object
    	div.style.left = placeX+'px';
    	div.style.top = placeY+'px';
    	
     if (INFRA_IE>0 && INFRA_IE<7){
        var ifr = document.getElementById('ifrInfraTooltip');
        if (ifr!=null){
      	  ifr.style.left = placeX+'px';
      	  ifr.style.top = placeY+'px';
      	  ifr.style.width = div.offsetWidth +  'px';
      	  ifr.style.height = div.offsetHeight + 'px';
        }
      }    	
      
  	  if (infraTooltip){
        div.style.visibility = 'visible';
      	if (INFRA_IE>0 && INFRA_IE<7){
      	  ifr.style.visibility = 'visible';
      	}	
      	infraTooltip = false;
  	  }
    }
	}
  return true;
}