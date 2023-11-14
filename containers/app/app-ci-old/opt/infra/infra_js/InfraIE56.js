function infraProcessarMouseOver() {
  document.onmouseover=infraMouseOverIE;
}

function infraMouseOverIE(){
  try{
  	objeto = window.event.srcElement;
  	if (typeof(objeto)=='object'){

  	  if (objeto.className=="infraRotuloMenu" ||
  	      objeto.className=="infraSetaMenu" ||
  	      objeto.className=="infraMenuRaiz" ||
  	      objeto.className=="infraMenuFilho" ||
  	      objeto.className=="infraItemMenu"){
    		infraEsconderMostrarSelect("hidden");
    	}else{
    	  //alert(objeto.className);
    	  infraEsconderMostrarSelect("visible");
    	}
  	}
  }catch(failed){}
}

function infraEsconderMostrarSelect(acao) {
	var sel=document.getElementsByTagName("SELECT");
	for(i=0; i<sel.length; i++) {
	  if (sel[i].className!='infraSelectOculto'){
		  sel[i].style.visibility = acao;
	  }
	}
}
