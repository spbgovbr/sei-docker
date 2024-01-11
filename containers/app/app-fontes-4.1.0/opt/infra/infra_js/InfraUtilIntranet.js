function abrirJanela(arquivo, largura, altura) {
    novaJanela = window.open(arquivo, 'novaJanela', 'location=0,status=0,resizable=1,scrollbars=1,width=' + largura + ',height=' + altura);
    novaJanela.focus();
    centralizarJanela(largura, altura);
}

function abrirJanelaNavegativa(arquivo, largura, altura) {
    novaJanela = window.open(arquivo, 'novaJanela', 'location=0,status=0,toolbar=yes,resizable=1,scrollbars=1,width=' + largura + ',height=' + altura);
    novaJanela.focus();
    centralizarJanela(largura, altura);
}

function abrirJanelaMenu(arquivo, largura, altura) {
    novaJanela = window.open(arquivo, 'novaJanela', 'location=0,status=0,toolbar=0,menubar=1,resizable=1,scrollbars=1,width=' + largura + ',height=' + altura);
    novaJanela.focus();
    centralizarJanela(largura, altura);
}

function abrirJanelaMenuBotoes(arquivo, largura, altura) {
    novaJanela = window.open(arquivo, 'novaJanela', 'location=0,status=0,toolbar=1,menubar=1,resizable=1,scrollbars=1,width=' + largura + ',height=' + altura);
    novaJanela.focus();
    centralizarJanela(largura, altura);
}

function centralizarJanela(largura, altura) {
    esquerda = (screen.availWidth / 2) - (largura / 2);
    topo = (screen.availHeight / 2) - (altura / 2);
    if (esquerda <= 9) {
        esquerda = 10;
    }
    if (topo <= 9) {
        topo = 10;
    }
    novaJanela.moveTo(esquerda - 10, topo - 10);
}

function mostrarAviso(aviso) {
    alert(aviso);
}

function ajustarEventListener(eventListener) {
    tirarBordasCheckboxes();
    document.onmouseover = eventListener;
}

function esconderMostrarSelect(acao) {
    var sel = document.getElementsByTagName("SELECT");
    for (i = 0; i < sel.length; i++) {
        sel[i].style.visibility = acao;
    }
}

function avaliarObjeto(event) {
    objeto = window.event.srcElement;
    strObjeto = objeto.toString();
    if (strObjeto.substr(0, 4) == "http") {
        esconderMostrarSelect("hidden");
    } else {
        esconderMostrarSelect("visible");
    }
}

function tirarBordasCheckboxes() {
    var sel = document.getElementsByTagName("INPUT");
    for (i = 0; i < sel.length; i++) {
        if ((sel[i].type == "checkbox") || (sel[i].type == "radio")) {
            sel[i].style.border = "none";
        }
    }
}

function imprimirDIV(strIdDiv) {
    abrirJanela('imprimir_div.php?div=' + strIdDiv, 750, 500);
}

var tamanhos = new Array('10px', '11px', '12px', '13px', '14px', '15px', '16px', '17px');
if (isNaN(contador)) {
    var contador = 0;
}

function alterarTamanhoFonte(zoom) {
    var bolAlterar = 0;
    if (zoom == "+") {
        if (contador < tamanhos.length - 1) {
            contador++;
            bolAlterar = 1;
        }
    }
    if (zoom == "-") {
        if (contador > 0) {
            contador--;
            bolAlterar = 1;
        }
    }
    if (zoom == "p") {
        contador = 0;
        bolAlterar = 1;
    }
    if (bolAlterar == 1) {
        document.getElementById("divInfraAreaGlobal").style.fontSize = tamanhos[contador];
        ajustarSessaoTamanhoFonte(contador);
    }
}

var xmlHttp;

function ajustarSessaoTamanhoFonte(intTamanho) {
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Este browser não suporta HTTPRequest");
        return;
    }
    xmlHttp.onreadystatechange = stateChanged;
    xmlHttp.open("GET", "infra/InfraAJAX.php?acao=alterarTamanhoFonte&tamanho=" + intTamanho, true);
    xmlHttp.send(null);
}

function stateChanged() {
    if ((xmlHttp.readyState == 4) || (xmlHttp.readyState == "complete")) {
        //alert(xmlHttp.responseText);
        //alert(xmlHttp.responseText);
        /*try {
          if(xmlHttp.status == 200) {
            alert(xmlHttp.responseText);
          }
        } catch(componentfailure) {}*/
    }
}

function GetXmlHttpObject() {
    var objXMLHttp = null;
    if (window.XMLHttpRequest) {
        objXMLHttp = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        objXMLHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    return objXMLHttp;
}

function validarData(data) {
    var strExReg = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/\d{4}$/;
    if (!strExReg.test(data)) {
        return false;
    } else {
        return true;
    }
}